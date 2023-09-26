<?php
namespace WebReinvent\VaahExtend\Libraries;

use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;

class VaahApollo{

    //----------------------------------------------------------
    public $api_base_url;
    public $api_key;

    //-----------------------------------------------------
    function __construct($api_key, $api_base_url="https://api.apollo.io/v1/")
    {
        $this->api_base_url = $api_base_url;
        $this->api_key = $api_key;
    }
    //----------------------------------------------------------

    public function getOrganizations($inputs )
    {
        $url = $this->api_base_url."organizations/enrich";
        $ajax = new VaahAjax();
        $headers = [];
        $inputs['api_key'] = $this->api_key;

        $res = $ajax->post($url, $inputs, $headers);

        unset($inputs['api_key']);

        $response['request'] = $inputs;
        $response['response'] = $res;

        return $response;
    }
    //----------------------------------------------------------
    public function getPeople($inputs)
    {

        $url = $this->api_base_url."mixed_people/search";

        $ajax = new VaahAjax();
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $inputs['api_key'] = $this->api_key;

        if(isset($inputs['domains'])){
            if(is_array($inputs['domains']) && count($inputs['domains']) > 0){
                $inputs['q_organization_domains'] = implode("\n", $inputs['domains']);
            }else if(is_string($inputs['domains'])){
                $inputs['q_organization_domains'] = preg_replace('/^www\./', '', $inputs['domains']);
            }
        }
        

        /*
         * person_titles variable accept query string like
         * person_titles[]=ceo&person_titles[]=cto
         * which is not equal to php array, hence we have to convert it
         */

        $apollo_inputs = http_build_query($inputs);
        $apollo_inputs = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $apollo_inputs);

        $res = $ajax->get($url, $apollo_inputs, $headers);

        unset($inputs['api_key']);
        $response['request'] = $inputs;
        $response['response'] = $res;

        return $response;
    }
    //----------------------------------------------------------


}
