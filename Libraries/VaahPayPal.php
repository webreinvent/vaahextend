<?php
namespace WebReinvent\VaahExtend\Libraries;

use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Support\Facades\Log;
use PayPal\Api\OpenIdTokeninfo;
use PayPal\Api\OpenIdUserinfo;
use Srmklive\PayPal\Services\ExpressCheckout;


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

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class VaahPayPal{

    //----------------------------------------------------------
    public function pay($inputs){
//        $user['email'] = $inputs['stripe']['email'];
        $user['username'] = $inputs['user']['username'];
//      $user['address'] = $inputs['stripe']['address'];


        $payment = $inputs['stripe']['payment'];
        //set user data
//        $payerInfo = new \PayPal\Api\PayerInfo();
//        $address = new \PayPal\Api\Address();
//        $address->setLine1($user['address']['line1'])
//            ->setCity($user['address']['city'])
//            ->setState($user['address']['state'])
//            ->setPostalCode($user['address']['postal_code'])
//            ->setCountryCode($user['address']['country']);

//        $payerInfo->setEmail($user['email'])
//            ->setFirstName($user['name'])
//            ->setBillingAddress($address);

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod("paypal");
//            ->setPayerInfo($payerInfo);

        $item = new \PayPal\Api\Item();
        $item->setName($payment['description'])
            ->setCurrency($payment['currency'])
            ->setPrice($payment['amount'])
            ->setQuantity(1);

        $itemList = new \PayPal\Api\ItemList();
        $itemList->setItems(array($item));

//        $details = new \PayPal\Api\Details();
//        $details->setTax(0)->setSubtotal($payment['amount']);

        $amount = new \PayPal\Api\Amount();
        $total = $payment['amount'] * $item->getQuantity();
        $amount->setCurrency($payment['currency'])
            ->setTotal($total);


        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)->setItemList($itemList)
            ->setDescription($payment['description'])
            ->setInvoiceNumber(uniqid());

        $returnUrl = url('/').'#/paypal/complete';
        $cancelUrl = url('/').'#/paypal/cancel';
        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl)
            ->setCancelUrl($cancelUrl);

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));


        $apiContext = $this->getApiContext(
            env('PAYPAL_SANDBOX_CLIENT_ID'),
            env('PAYPAL_SANDBOX_CLIENT_SECRET')
        );
        try {
            $resp = $payment->create($apiContext);
            $approvalUrl = $resp->getApprovalLink();
            $response = [];
            $response['status'] = 'success';
            $response['data']['approval_url'] = $approvalUrl;
            $response['data']['payment_id'] = $resp->getId();
            $response['data']['token'] = $resp->getToken();
            return $response;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $response = [];
            $response['status'] = 'failed';
            $response['error'] = $ex->getData();
            return $response;
        } catch (\Exception $ex) {
            $response = [];
            $response['status'] = 'failed';
            $response['error'] = $ex->getMessage();
            return $response;
        }
    }
     public function getUserInfo(){
        $apiContext = $this->getApiContext(
            env('PAYPAL_SANDBOX_CLIENT_ID'),
            env('PAYPAL_SANDBOX_CLIENT_SECRET')
        );
         try {
             $token = $apiContext->getCredential()->getAccessToken($apiContext);
             $user = OpenIdUserinfo::getUserinfo(['access_token' => $token], $apiContext);
             $response = [];
                $response['status'] = 'success';
                $response['data'] = $user->toArray();
                return $response;
         } catch (\PayPal\Exception\PayPalConnectionException $ex) {
             $response = [];
             $response['status'] = 'failed';
             $response['error'] = json_decode($ex->getData())->message;
             return $response;
         } catch (\Exception $ex) {
             $response = [];
             $response['status'] = 'failed';
             $response['error'] = $ex->getMessage();
             return $response;
         }
    }
    //----------------------------------------------------------
    public function executePayment($paymentId, $payerId)
    {
        $apiContext = $this->getApiContext(
            env('PAYPAL_SANDBOX_CLIENT_ID'),
            env('PAYPAL_SANDBOX_CLIENT_SECRET')
        );
      try {
        $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
        $execution = new \PayPal\Api\PaymentExecution();
        $execution->setPayerId($payerId);
        $result = $payment->execute($execution, $apiContext);
        $response = [];
        $response['status'] = 'success';
        $response['data'] = $result->toArray();
        return $response;
      } catch (\PayPal\Exception\PayPalConnectionException $ex) {
        $response = [];
        $response['status'] = 'failed';
        $response['error'] = json_decode($ex->getData())->message;
        return $response;
      } catch (\Exception $ex) {
        $response = [];
        $response['status'] = 'failed';
        $response['error'] = $ex->getMessage();
        return $response;
      }
    }
    //----------------------------------------------------------
   public function getPaymentDetailByPaymentID($paymentId)
    {
        $apiContext = $this->getApiContext(
            env('PAYPAL_SANDBOX_CLIENT_ID'),
            env('PAYPAL_SANDBOX_CLIENT_SECRET')
        );
        try {
            $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
            $response = [];
            $response['status'] = 'success';
            $response['data'] = $payment->toArray();
            return $response;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $response = [];
            $response['status'] = 'failed';
            $response['error'] = json_decode($ex->getData())->message;
            return $response;
        } catch (\Exception $ex) {
            $response = [];
            $response['status'] = 'failed';
            $response['error'] = $ex->getMessage();
            return $response;
        }
    }
    //----------------------------------------------------
    public function payPalOnetime( $inputs, $shippingAddress = null ) {

        $inputs  = [
            'price'             => 20.00,
            'tax'               => 10.00,
            'success_url'       => 'http://localhost/vikram/morapp/public',
            //route for redirection after payment success.
            'failed_url'        => 'http://localhost/vikram/morapp/public',
            //route for redirection after payment failure.
            'item_name'         => "Item one",
            'item_sku'          => "item-one",
            'item_desc'         => "This is the product",
            'currency'          => "USD",
            'item_quantity'     => 5,
            'invoice_number'    => uniqid(),
            'payment_frequency' => 'Month',
            // can be  `WEEK`, `DAY`, `YEAR`, `MONTH`.
            'payment_interval'  => 1,
            // in how many months, days, or week you to execute the same payment.
            'cycles'            => 12,
            // in how many months, days, or week you to execute the same payment. 0 if plan type is infinite
            'plan_type'         => 12,
            // Allowed values: `FIXED`, `INFINITE` infinite is for subscription or fixed for EMIs.
        ];

        $rules     = array(
            'price'          => 'required|numeric',
//			'tax'            => 'required|numeric',
            'success_url'    => 'required',
            'failed_url'     => 'required',
            'item_name'      => 'required',
            'item_sku'       => 'required',
            'currency'       => 'required',
            'item_quantity'  => 'required|integer',
            'invoice_number' => 'required',
        );
        $messages  = [
            'price.required'          => 'Price is required.',
            'item_quantity.required'  => 'Item quantity is required.',
            'tax.required'            => 'Tax is required.',
            'price.numeric'           => 'Price should be in Numbers.',
            'item_quantity.numeric'   => 'Item Quantity should be in Numbers.',
            'tax.numeric'             => 'Tax should be in Numbers.',
            'success_url.required'    => 'Success url is required.',
            'failed_url.required'     => 'Failure url is required.',
            'item_name.required'      => 'Item name is required.',
            'item_sku.required'       => 'Item sku is required.',
            'currency.required'       => 'Currency is required.',
            'invoice_number.required' => 'Invoice number is required.',
        ];
        $validator = \Validator::make( $inputs, $rules, $messages );
        if ( $validator->fails() ) {

            $errors             = $validator->errors();
            $response['status'] = 'failed';
            $response['errors'] = $errors;

            return $response;
        }
        $response = [];
        $address  = null;
        if ( $shippingAddress && is_array( $shippingAddress ) ) {
            $res = $this->setShippingAddress( $shippingAddress );
            if ( $res['status'] === 'failed' ) {
                return $res;
            }
            if ( $res['status'] === 'success' ) {
                $address = $res['data'];
            }
        }

        $tax = 0;

        if (isset($inputs['tax']) && $inputs['tax']>0){
            if (!is_numeric($inputs['tax'])){
                $response['status'] = 'failed';
                $response['errors'][] = 'Tax should be in numbers';

                return $response;
            }
            $tax = $inputs['tax'];
        }

        $payer = new Payer();
        $payer->setPaymentMethod( "paypal" );
        $item = new Item();
        $item->setName( $inputs['item_name'] )
            ->setCurrency( $inputs['currency'] )
            ->setSku( $inputs['item_sku'] )
            ->setQuantity( $inputs['item_quantity'] )
            ->setPrice( $inputs['price'] );
        $details = new \PayPal\Api\Details();
        $details->setTax( $tax )->setSubtotal( $inputs['price'] );
        $itemList = new ItemList();
        $itemList->setItems( [ $item ] );
        if ( $address ) {
            $itemList->setShippingAddress( $address );
        }
        $_amount = ($inputs['price'] * $inputs['item_quantity']) + $tax;
        //Payment Amount
        $amount = new Amount();
        $amount->setCurrency( $inputs['currency'] )
            // the total is $17.8 = (16 + 0.6) * 1 ( of quantity) + 1.2 ( of Shipping).
            ->setTotal( ( $_amount ) )->setDetails( $details );
        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        //die;
        $transaction = new Transaction();
        $transaction->setAmount( $amount )
            ->setItemList( $itemList )
//		            ->setDescription("Assignable.io Cart ID: ".$cart->uid)
            ->setInvoiceNumber( $inputs['invoice_number'] );
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'
        //die;
//        return $transaction;
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl( $inputs['success_url'] )
            ->setCancelUrl( $inputs['failed_url'] );
        $payment = new Payment();
        $payment->setIntent( "sale" )
            ->setPayer( $payer )
            ->setRedirectUrls( $redirectUrls )
            ->setTransactions( [ $transaction ] );
        try {
            // ### Create Payment
            // Create a payment by posting to the APIService
            // using a valid ApiContext
            // The return object contains the status;
            $apiContext = $this->getApiContext( env( 'PAYPAL_SANDBOX_CLIENT_ID', '' ), env( 'PAYPAL_SANDBOX_CLIENT_SECRET', '' ) );
            $payment->create( $apiContext );
            $approved_url = $payment->getApprovalLink();
            $approved_url = parse_url( $approved_url );
            parse_str( $approved_url['query'], $approved_query_params );
        } catch ( \Exception $ex ) {

            $response['status']   = 'failed';
            $response['errors'][] = $ex->getMessage();

            return $response;
        }
        $response['status']               = 'success';
        $response['data']['redirect_url'] = $payment->getApprovalLink();
        $response['messages'][]           = 'Redirecting you to PayPal';

        return $response;
    }	public function payPalSubscription( $inputs ) {

    $rules     = array(
        'price'             => 'required|numeric',
//			'tax'               => 'required|numeric',
        'success_url'       => 'required',
        'plan_type'         => 'required',
        'failed_url'        => 'required',
        'item_name'         => 'required',
        'item_sku'          => 'required',
        'item_desc'         => 'required',
        'currency'          => 'required',
        'item_quantity'     => 'required|integer',
        'invoice_number'    => 'required',
        'payment_frequency' => 'required',
        'payment_interval'  => 'required|integer',
        'payment_cycles'    => 'required|integer',
    );
    $messages  = [
        'price.required'             => 'Price is required.',
        'item_quantity.required'     => 'Item quantity is required.',
        'tax.required'               => 'Tax is required.',
        'price.numeric'              => 'Price should be in Numbers.',
        'item_quantity.numeric'      => 'Item Quantity should be in Numbers.',
        'tax.numeric'                => 'Tax should be in Numbers.',
        'success_url.required'       => 'Success url is required.',
        'failed_url.required'        => 'Failure url is required.',
        'item_name.required'         => 'Item name is required.',
        'item_desc.required'         => 'Item description is required.',
        'item_sku.required'          => 'Item sku is required.',
        'currency.required'          => 'Currency is required.',
        'invoice_number.required'    => 'Invoice number is required.',
        'plan_type.required'         => 'Plan Type is required this should be infinite or fixed.',
        'payment_interval.required'  => 'Payment interval is required.',
        'payment_frequency.required' => 'Payment frequency is required.',
        'payment_cycles.integer'     => 'Payment cycles should be in integer.',
        'payment_interval.integer'   => 'Payment interval should be in integer.',
        'payment_cycles.required'    => 'Payment Cycle is required.',
    ];
    $validator = \Validator::make( $inputs, $rules, $messages );
    if ( $validator->fails() ) {

        $errors             = $validator->errors();
        $response['status'] = 'failed';
        $response['errors'] = $errors;

        return $response;
    }
    $plan = new Plan();
    $plan->setName( $inputs['item_name'] )
        ->setDescription( $inputs['item_desc'] )
        ->setType( $inputs['plan_type'] );  //infinite or fixed
    // Set billing plan definitions
    $paymentDefinition = new PaymentDefinition();
    $paymentDefinition->setName( 'Regular Payments' )
        ->setType( 'REGULAR' )
        ->setFrequency( $inputs['payment_frequency'] )
        ->setFrequencyInterval( $inputs['payment_interval'] )
        ->setCycles( $inputs['payment_cycles'] )//cycle 0
        ->setAmount( new Currency( array(
            'value'    => $inputs['price'],
            'currency' => $inputs['currency']
        ) ) );
    // Set merchant preferences
    $merchantPreferences = new MerchantPreferences();
    $merchantPreferences->setReturnUrl( $inputs['success_url'] )
        ->setCancelUrl( $inputs['failed_url'] )
        ->setAutoBillAmount( 'yes' )
        ->setInitialFailAmountAction( 'CONTINUE' )
        ->setMaxFailAttempts( '0' );
//		                    ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));
    $plan->setPaymentDefinitions( array( $paymentDefinition ) );
    $plan->setMerchantPreferences( $merchantPreferences );
    $apiContext = $this->getApiContext( env( 'PAYPAL_CLIENT_ID', '' ), env( 'PAYPAL_CLIENT_SECRET', '' ) );
    //create plan
    try {
        $createdPlan = $plan->create( $apiContext );
        try {
            $patch = new Patch();
            $value = new PayPalModel( '{"state":"ACTIVE"}' );
            $patch->setOp( 'replace' )
                ->setPath( '/' )
                ->setValue( $value );
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch( $patch );
            $createdPlan->update( $patchRequest, $apiContext );
            $plan = Plan::get( $createdPlan->getId(), $apiContext );
            // Create new agreement
            $agreement = new Agreement();
            $agreement->setName( 'Base Agreement' )
                ->setDescription( 'Basic Agreement' )
//				          ->setShippingAddress( $shippingAddress )
                ->setStartDate( date( "Y-m-d\TH:i:s\Z", strtotime( 'tomorrow' ) ) );
            $_plan = new Plan();
            $_plan->setId( $plan->getId() );
            $agreement->setPlan( $_plan );
            // Add payer type
            $payer = new Payer();
            $payer->setPaymentMethod( 'paypal' );
            $agreement->setPayer( $payer );
// Adding shipping details
            // Create agreement
            $agreement = $agreement->create( $apiContext );
            // Extract approval URL to redirect user
            $approvalUrl            = $agreement->getApprovalLink();
            $response['status']     = 'success';
            $response['data']       = $approvalUrl;
            $response['messages'][] = 'Redirecting you to Paypal';

            return $response;
            // Output plan id
//				echo $plan->getId();
        } catch ( PayPal\Exception\PayPalConnectionException $ex ) {
            $response['status']   = 'failed';
            $response['errors'][] = $ex->getData();

            return $response;
//				die($ex);
        } catch ( \Exception $ex ) {
            $response['status']   = 'failed';
            $response['errors'][] = $ex;

            return $response;
        }
    } catch ( PayPal\Exception\PayPalConnectionException $ex ) {
        $response['status']   = 'failed';
        $response['errors'][] = $ex->getData();

        return $response;
    } catch ( \Exception $ex ) {
        $response['status']   = 'failed';
        $response['errors'][] = $ex;

        return $response;
    }
//		$payment_defination =
    $response['status'] = 'success';
//		$response['data']['redirect_url'] = $payment->getApprovalLink();
    $response['messages'][] = 'Redirecting you to Paypal';

    return $response;
}

    //----------------------------------------------------
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
                'mode'           => env( 'PAYPAL_MODE' ),
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
    //-----------------------------------------------------------------
//    public function executePayment( Request $request ) {
//        $response = [];
//        if ( $request->has( 'paymentId' ) && $request->has( 'PayerID' ) ) {
//
//            $paymentId  = $request->paymentId;
//            $payerId    = $request->PayerID;
//            $apiContext = $this->getApiContext( env( 'PAYPAL_CLIENT_ID', '' ),
//                env( 'PAYPAL_CLIENT_SECRET', '' ) );
//            $payment    = Payment::get( $paymentId, $apiContext );
//            $execution  = new PaymentExecution();
//            $execution->setPayerId( $payerId );
//            try {
//                $result             = $payment->execute( $execution, $apiContext );
//                $response['status'] = 'success';
//                $response['data']   = $paymentId;
//
//                return $response;
//            } catch ( \Exception $e ) {
//
//                $response['status']   = 'failed';
//                $response['errors'][] = $e->getMessage();
//
//                return $response;
//            }
//        }
//
//        $response['status']   = 'failed';
//        $response['errors'][] = 'Something went wrong. No Payment Id is received.';
//
//        return $response;
//    }

    //-----------------------------------------------------------------
    public function executePlan( Request $request ) {

        if ( isset( $_GET['token'] ) ) {
            $token     = $request['token'];
            $agreement = new \PayPal\Api\Agreement();
            try {
                $apiContext = $this->getApiContext( env( 'PAYPAL_CLIENT_ID', '' ),
                    env( 'PAYPAL_CLIENT_SECRET', '' ) );
                // Execute agreement
                $payment            = $agreement->execute( $token, $apiContext );
                $response['status'] = 'success';
                $response['data']   = $agreement->getId();

                return $response;
            } catch ( PayPal\Exception\PayPalConnectionException $ex ) {
                $response             = [];
                $response['status']   = 'failed';
                $response['errors'][] = $ex->getData();

                return $response;
//				die($ex);
            } catch ( \Exception $ex ) {
                $response             = [];
                $response['status']   = 'failed';
                $response['errors'][] = $ex->getData();

                return $response;
            }
        } else {
//			echo "user canceled agreement";
            $response             = [];
            $response['status']   = 'failed';
            $response['errors'][] = "User cancelled request";

            return $response;
        }
    }

    //----------------------------------------------------
    public function setShippingAddress( $shippingAddress ) {

        $_shippingAddress = new ShippingAddress();
        if ( isset( $shippingAddress['line_one'] ) ) {
            $_shippingAddress->setLine1( $shippingAddress['line_one'] );
        } else {
            $response['status']   = 'failed';
            $response['errors'][] = 'Address Line 1 is required.';

            return $response;
        }
        if ( isset( $shippingAddress['line_two'] ) ) {
            $_shippingAddress->setLine2( $shippingAddress['line_two'] );
        }
        if ( isset( $shippingAddress['city'] ) ) {
            $_shippingAddress->setCity( $shippingAddress['city'] );
        } else {
            $response['status']   = 'failed';
            $response['errors'][] = 'Address City is required.';

            return $response;
        }
        if ( isset( $shippingAddress['zip'] ) ) {
            $_shippingAddress->setPostalCode( $shippingAddress['zip'] );
        } else {
            $response['status']   = 'failed';
            $response['errors'][] = 'Address Zip is required.';

            return $response;
        }
        if ( isset( $shippingAddress['country'] ) ) {
            $_shippingAddress->setCountryCode( $shippingAddress['country'] );
        } else {
            $response['status']   = 'failed';
            $response['errors'][] = 'Address Country is required.';

            return $response;
        }
        if ( isset( $shippingAddress['state'] ) ) {
            $_shippingAddress->setCountryCode( $shippingAddress['state'] );
        } else {
            $response['status']   = 'failed';
            $response['errors'][] = 'Address state is required.';

            return $response;
        }
        if ( isset( $shippingAddress['mobile'] ) ) {
            $_shippingAddress->setPhone( $shippingAddress['mobile'] );
        }
        if ( isset( $shippingAddress['name'] ) ) {
            $_shippingAddress->setRecipientName( $shippingAddress['name'] );
        }
        $response['status'] = 'success';
        $response['data']   = $_shippingAddress;

        return $response;
//		return $_shippingAddress;
    }
    //----------------------------------------------------------

    public function subscription($customer,
                                 $card,
                                 $address,
                                 $price_id,
                                 $return_url)
    {

        $validate = self::validation($customer,
            $card, $address, $price_id, $return_url, null,true);

        if(isset($validate['status']) && $validate['status'] == 'failed')
        {
            return $validate;
        }

        try{

            $customer_inputs = [
                'email'     => $customer['email'],
                'name'      => $customer['name'],
                'address'   => $address
            ];

            $card['number'] = str_replace(
                '-', '', $card['number']
            );

            $token = Stripe::tokens()->create([
                'card' =>  $card,
            ]);

            $customer_inputs['source'] = $token['id'];

            $customer = Stripe::customers()->create($customer_inputs);

            $method_inputs = [
                "type" => 'card',
                "card" => $card
            ];

            $payment_method = Stripe::paymentMethods()->create($method_inputs);

            $customer_iD = $customer['id'];
            $payment_method_iD = $payment_method['id'];

            Stripe::paymentMethods()->attach($payment_method_iD, $customer_iD);

            $subscription = Stripe::subscriptions()->create(
                $customer_iD,
                [
                    'plan' => $price_id,
                    'payment_behavior' => 'default_incomplete',
                    'expand' => ['latest_invoice.payment_intent'],
                ]
            );

            $invoice = Stripe::invoices()->find($subscription['latest_invoice']['id']);


            $pa_intent = Stripe::paymentIntents()->confirm(
                $invoice['payment_intent'],
                [
                    "return_url" => $return_url
                ]
            );

            $response['status']     = 'success';
            $response['data']       = $pa_intent;


        }catch(\Exception $e)
        {
            $response['status']     = 'failed';
            $response['errors'][]   = $e->getMessage();
        }

        return $response;

    }
    //----------------------------------------------------------

    public function createProduct(Request $request)
    {
        $inputs = $request->all();

        $rules = array(
            'name'          => 'required',
            'description'   => 'required'
        );

        $validator = \Validator::make( $inputs, $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        try{

            $product = Stripe::products()->create([
                'name'          => $inputs['name'],
                'description'   => $inputs['description'],
                'type'          => 'service'
            ]);

            $response['status'] = 'success';
            $response['data']   = $product;

        }catch(\Exception $e)
        {
            $response['status']     = 'failed';
            $response['errors'][]   = $e->getMessage();
        }

        return $response;

    }
    //----------------------------------------------------------

    public function createPrice(Request $request)
    {
        $inputs = $request->all();

        $rules = array(
            'product_id'    => 'required',
            'amount'        => 'required',
            'currency'      => 'required',
            'interval'      => 'required'
        );

        $validator = \Validator::make( $inputs, $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        try{

            $product = Stripe::prices()->create([
                'unit_amount'   => $inputs['amount'],
                'currency'      => $inputs['currency'],
                'product'       => $inputs['product_id'],
                'recurring'     => [
                    'interval'  => $inputs['interval']
                ]
            ]);

            $response['status'] = 'success';
            $response['data']   = $product;

        }catch(\Exception $e)
        {
            $response['status']     = 'failed';
            $response['errors'][]   = $e->getMessage();
        }

        return $response;

    }
    //----------------------------------------------------------

    public function findProductByName($name)
    {

        if(!$name){
            $response['status'] = 'failed';
            $response['errors'] = 'The name field is required.';
            return $response;
        }

        try{

            $product_val  = null;

            $data = [
                'active' => true
            ];

            $products = Stripe::products()->all($data);

            foreach ($products['data'] as $product){

                if($product['name'] == $name){
                    $product_val =  $product;
                }

            }

            if(!$product_val){
                $response['status']     = 'failed';
                $response['errors'][]   = 'No Product Found';
                return $response;
            }

            $response['status'] = 'success';
            $response['data']   = $product_val;


        }catch(\Exception $e)
        {
            $response['status']     = 'failed';
            $response['errors'][]   = $e->getMessage();
        }

        return $response;

    }
    //----------------------------------------------------------

    public function getProductPrice($product_id, $value = null, $by = 'amount')
    {

        if(!$product_id){
            $response['status'] = 'failed';
            $response['errors'] = 'The product id field is required.';
            return $response;
        }

        try{

            $price_val  = null;

            $data = [
                'active' => true
            ];

            $prices = Stripe::prices()->all($data);

            foreach ($prices['data'] as $price){

                if($price['product'] == $product_id){
                    if($value && $by){

                        switch ($by)
                        {
                            //------------------------------------
                            case 'amount':

                                if($price['unit_amount'] == $value){
                                    $price_val =  $price;
                                }

                                break;

                            //------------------------------------
                            case 'currency':

                                if($price[$by] == $value){
                                    $price_val =  $price;
                                }

                                break;

                            //------------------------------------
                            case 'interval':

                                if($price['recurring'][$by] == $value){
                                    $price_val =  $price;
                                }

                                break;
                            //------------------------------------

                        }

                    }else{
                        $price_val =  $price;
                    }
                }

            }

            if(!$price_val){
                $response['status']     = 'failed';
                $response['errors'][]   = 'No Price Found';
                return $response;
            }

            $response['status'] = 'success';
            $response['data']   = $price_val;


        }catch(\Exception $e)
        {
            $response['status']     = 'failed';
            $response['errors'][]   = $e->getMessage();
        }

        return $response;

    }
    //----------------------------------------------------------

    //----------------------------------------------------------
    public static function validation($customer,
                                      $card,
                                      $address,
                                      $price_id,
                                      $return_url,
                                      $package,
                                      $is_subscription = false){

        $rules = array(
            'name'    => 'required',
            'email'   => 'required|email:rfc,dns'
        );

        $validator = \Validator::make( $customer, $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        $rules = array(
            'number'       => 'required',
            'exp_month'    => 'required',
            'exp_year'     => 'required',
            'cvc'          => 'required'
        );

        $validator = \Validator::make( $card, $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        if(!$is_subscription && $package){
            $rules = array(
                'currency'      => 'required',
                'amount'        => 'required',
                'description'   => 'required'
            );

            $validator = \Validator::make( $package, $rules);
            if ( $validator->fails() ) {

                $errors             = errorsToArray($validator->errors());
                $response['status'] = 'failed';
                $response['errors'] = $errors;
                return $response;
            }
        }

        $rules = array(
            'line1'         => 'required',
            'country'       => 'required',
        );

        $validator = \Validator::make( $address, $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        if($is_subscription && !$price_id){
            $response['status'] = 'failed';
            $response['errors'] = 'The price id field is required.';
            return $response;
        }

        if(!$return_url){
            $response['status'] = 'failed';
            $response['errors'] = 'The return url field is required.';
            return $response;
        }

    }
    //----------------------------------------------------------


}
