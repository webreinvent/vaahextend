<?php
namespace WebReinvent\VaahExtend\Libraries;

use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;

class VaahStripe{

    //----------------------------------------------------------
    //----------------------------------------------------------

    public static function pay($customer,
                               $card,
                               $package,
                               $address,
                               $return_url)
    {

        $validate = self::validation($customer,
            $card, $address,null, $return_url, $package);

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

            // Set Stripe Secret Key

            $customer = Stripe::customers()
                ->create($customer_inputs);

            $pay_inputs = $package;
            $pay_inputs['customer'] = $customer['id'];

            $pay_intent = Stripe::paymentIntents()
                ->create($pay_inputs);


            $card['number'] = str_replace(
                '-', '', $card['number']
            );

            $data = [
                "type" => 'card',
                "card" => $card
            ];


            $payment_method = Stripe::paymentMethods()
                ->create($data);

            Stripe::paymentIntents()->update($pay_intent['id'], [
                'payment_method' =>  $payment_method['id']
            ]);

            $pay_confirm = Stripe::paymentIntents()->confirm(
                $pay_intent['id'],
                [
                    "return_url" => $return_url
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
