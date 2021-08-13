<?php
namespace WebReinvent\VaahExtend\Libraries;

use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;

class VaahStripe{

    //----------------------------------------------------------
    //----------------------------------------------------------

    public static function pay(Request $request)
    {

        $inputs = $request->all();

        $validate = self::validation($inputs);

        if(isset($validate['status']) && $validate['status'] == 'failed')
        {
            return $validate;
        }

        try{
            $customer_inputs = [
                'email' => $inputs['customer']['email'],
                'name' => $inputs['customer']['name'],
                'address' => [
                    'line1' => 'New York',
                    'country' => 'US'
                ]
            ];

            if(isset($inputs['address'])){
                $customer_inputs['address'] = $inputs['address'];
            }

            // Set Stripe Secret Key

            $customer = Stripe::customers()
                ->create($customer_inputs);

            $pay_inputs = $inputs['payment'];
            $pay_inputs['customer'] = $customer['id'];


            $pay_intent = Stripe::paymentIntents()
                ->create($pay_inputs);


            $inputs['card']['number'] = str_replace(
                '-', '', $inputs['card']['number']
            );

            $data = [
                "type" => 'card',
                "card" => $inputs['card']
            ];


            $payment_method = Stripe::paymentMethods()
                ->create($data);

            Stripe::paymentIntents()->update($pay_intent['id'], [
                'payment_method' =>  $payment_method['id']
            ]);

            $pay_confirm = Stripe::paymentIntents()->confirm(
                $pay_intent['id'],
                [
                    "return_url" => $inputs['return_url']
                ]
            );

            $response['status'] = 'success';
            $response['data'] = $pay_confirm;

        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
        }


        return $response;

    }
    //----------------------------------------------------------
    public function subscribe(Request $request)
    {

        $inputs = $request->all();

        $validate = self::validation($inputs,'subscription');

        if(isset($validate['status']) && $validate['status'] == 'failed')
        {
            return $validate;
        }

        $pay_inputs = $inputs['payment'];

        $package_price_id = $this->getPriceId($pay_inputs);

        if(is_null($package_price_id) || empty($package_price_id))
        {
            $response = [
                "status" => 'failed',
                "errors" => ['Plan or plan price for '.$pay_inputs['package']." does not exist"],
            ];
            return $response;
        }

        try{
            $customer_inputs = [
                'email' => $inputs['customer']['email'],
                'name' => $inputs['customer']['name'],
                'address' => [
                    'line1' => 'New York',
                    'country' => 'US'
                ]
            ];

            if($inputs['address']){
                $customer_inputs['address'] = $inputs['address'];
            }

            $token = Stripe::tokens()->create([
                'card' =>  $inputs['card'],
            ]);

            $customer_inputs['source'] = $token['id'];


            $customer = Stripe::customers()->create($customer_inputs);

            $method_inputs = [
                "type" => 'card',
                "card" => $inputs['card']
            ];

            $paymentMethod = Stripe::paymentMethods()->create($method_inputs);

            $customerID = $customer['id'];
            $paymentMethodID = $paymentMethod['id'];

            Stripe::paymentMethods()->attach($paymentMethodID, $customerID);

            $subscription = Stripe::subscriptions()->create(
                $customerID,
                [
                    'plan' => $package_price_id,
                    'payment_behavior' => 'default_incomplete',
                    'expand' => ['latest_invoice.payment_intent'],
                ]
            );

            $invoice = Stripe::invoices()->find($subscription['latest_invoice']['id']);


            $payIntent = Stripe::paymentIntents()->confirm(
                $invoice['payment_intent'],
                [
                    "return_url" => $inputs['return_url']
                ]
            );

            $customer = Stripe::customers()->find($payIntent['customer']);

            $response['status'] = 'success';
            $response['data'] = $payIntent;
            $response['customer'] = $customer;

        }catch(\Exception $e)
        {

            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();
        }

        return $response;

    }
    //----------------------------------------------------------

    public function getPriceId($package)
    {
        $data = [
            'active' => true
        ];

        $plans = Stripe::plans()->all($data);

        foreach ($plans['data'] as $plan){

            if($plan['name'] == $package['package']
            && $plan['interval'] == $package['interval']
            && $plan['currency'] == strtolower($package['currency'])){
                return $plan['id'];
            }
        }

        $product = Stripe::products()->create([
            'name' => $package['package'],
            'description' => $package['description'],
            'type' => 'service'
        ]);

        $package['product'] = $product['id'];

        unset($package['package']);
        unset($package['description']);

        $plan = Stripe::plans()->create($package);

        return $plan['id'];
    }
    //----------------------------------------------------------
    public static function validation($inputs,$type = null){

        $rules = array(
            'customer.name' => 'required',
            'customer.email' => 'required|email:rfc,dns',
            'payment.currency' => 'required',
            'payment.amount' => 'required',
            'payment.description' => 'required',
            'card.number' => 'required',
            'card.exp_month' => 'required',
            'card.exp_year' => 'required',
            'card.cvc' => 'required',
            'return_url' => 'required'   // URL to redirect your customer back to after they authenticate or cancel their payment
        );

        if($type === 'subscription'){
            $rules['payment.package'] = 'required';
            $rules['payment.interval'] = 'required';
        }

        $validator = \Validator::make( $inputs, $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

    }
    //----------------------------------------------------------


}
