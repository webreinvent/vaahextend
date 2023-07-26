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


}
