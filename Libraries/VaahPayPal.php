<?php
namespace WebReinvent\VaahExtend\Libraries;

use PayPal\Api\OpenIdUserinfo;


use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Plan;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;

class VaahPayPal{

    private $apiContext;
    private $mode;
    private $client_id;
    private $client_secret;
    private $return_url;
    private $cancel_url;

    public function __construct(
        $client_id,
        $client_secret,
        $return_url = '/api/vaah/paypal/execute',
        $cancel_url = '/api/vaah/paypal/cancel',
        $mode = 'sandbox'
    )
    {
        $this->mode = $mode;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->return_url = $return_url;
        $this->cancel_url = $cancel_url;
        $this->apiContext = $this->getApiContext($this->client_id, $this->client_secret);
    }

    //----------------------------------------------------------
    public function pay($inputs){
        $rules = [
            'name' => 'required',
            'amount' => 'required',
            'currency' => 'required',
            'description' => 'required',
            'quantity' => 'required',
        ];
        $validator = \Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod("paypal");
        //set item info
        $item = new \PayPal\Api\Item();
        $item->setName($inputs['name'])
            ->setDescription($inputs['description'])
            ->setCurrency($inputs['currency'])
            ->setPrice($inputs['amount'])
            ->setQuantity($inputs['quantity']);
        //set item list
        $itemList = new \PayPal\Api\ItemList();
        $itemList->setItems(array($item));
        //details
        $details = new \PayPal\Api\Details();
        $shipping = $inputs['shipping'] ?? 0;
        $tax = $inputs['tax'] ?? 0;

        $subtotal = ($inputs['amount'] * $inputs['quantity']) + $shipping + $tax;
        $details->setShipping($shipping)
            ->setTax($tax)
            ->setSubtotal($subtotal);

        //set amount
        $amount = new \PayPal\Api\Amount();
        $total = $inputs['amount'] * $item->getQuantity();
        $amount->setCurrency($inputs['currency'])
            ->setTotal($total)
            ->setDetails($details);

        //set transaction
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)->setItemList($itemList)
            ->setDescription($inputs['description'])
            ->setInvoiceNumber(uniqid());
        //set redirect urls
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl($this->return_url)
            ->setCancelUrl($this->cancel_url);
        //set payment
        $payment = new \PayPal\Api\Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        //create payment with valid api context
        try {
            $resp = $payment->create($this->apiContext);
            $approvalUrl = $resp->getApprovalLink(); //approval url
            $response = [];
            $response['status'] = 'success';
            $response['data']['approval_url'] = $approvalUrl;
            $response['data']['payment_id'] = $resp->getId();
            $response['data']['token'] = $resp->getToken();
            return $response;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $response = [];
            $response['status'] = 'failed';
            $response['errors'] = $ex->getData();
            return $response;
        } catch (\Exception $ex) {
            $response = [];
            $response['status'] = 'failed';
            $response['errors'] = $ex->getMessage();
            return $response;
        }
    }
    //----------------------------------------------------------
    public function getUserInfo(){
        //getting user details
        try {
            $token = $this->apiContext->getCredential()->getAccessToken($this->apiContext); //get access token
            $user = OpenIdUserinfo::getUserinfo(['access_token' => $token], $this->apiContext);
            $response = [];
            $response['status'] = 'success';
            $response['data'] = $user->toArray();
            return $response;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $response = [];
            $response['status'] = 'failed';
           $response['errors'] = $ex->getData();
            return $response;
        } catch (\Exception $ex) {
            $response = [];
            $response['status'] = 'failed';
           $response['errors'] = $ex->getMessage();
            return $response;
        }
    }
    //----------------------------------------------------------
    public function executePayment($paymentId, $payerId)
    {
        try {
            $payment = \PayPal\Api\Payment::get($paymentId, $this->apiContext);
            $execution = new \PayPal\Api\PaymentExecution();
            $execution->setPayerId($payerId);
            $result = $payment->execute($execution, $this->apiContext);
            $response = [];
            $response['status'] = 'success';
            $response['data'] = $result->toArray();
            return $response;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $response = [];
            $response['status'] = 'failed';
           $response['errors'] = $ex->getData();
            return $response;
        } catch (\Exception $ex) {
            $response = [];
            $response['status'] = 'failed';
           $response['errors'] = $ex->getMessage();
            return $response;
        }
    }
    //----------------------------------------------------------
    public function getApiContext( $clientId, $clientSecret ) {
        // #### SDK configuration
        // Register the sdk_config.ini file in current directory
        // as the configuration source.
        /*
        if(!defined("PP_CONFIG_PATH")) {
            define("PP_CONFIG_PATH", __DIR__);
        }
        */
        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            ),

        );
        // Comment this line out and uncomment the PP_CONFIG_PATH
        // 'define' block if you want to use static file
        // based configuration
        $apiContext->setConfig(
            array(
                'mode'           => $this->mode,
                'log.LogEnabled' => true,
                'log.FileName'   => '../PayPal.log',
                'log.LogLevel'   => 'DEBUG',
                // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled'  => true,
                //'cache.FileName' => '/PaypalCache' // for determining paypal cache directory
                // 'http.CURLOPT_CONNECTTIMEOUT' => 30
                // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
                //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
            )
        );
        // Partner Attribution Id
        // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
        // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
        // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');
        return $apiContext;
    }
    //----------------------------------------------------
}
