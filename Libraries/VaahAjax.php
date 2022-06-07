<?php
namespace WebReinvent\VaahExtend\Libraries;


use GuzzleHttp\Client;

class VaahAjax{

    public $ajax;

    //----------------------------------------------------------
    public function __construct()
    {
        $this->ajax = new Client();
    }
    //------------------------------------------------
    public function post($url, $params=null, $headers = null)
    {
        $data = null;

        if(!is_null($params))
        {
            $data['form_params'] = $params;
        }
        if(!is_null($headers))
        {
            $data['headers'] = $headers;
        }
        try{
            $res = $this->ajax->request('POST', $url, $data);
            $response = [
                'status' => 'success',
                'data' => [
                    'status_code' => $res->getStatusCode(),
                    'body' => $res->getBody(),
                    'content' => $res->getBody()->getContents(),
                ]
            ];
        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'] = [$e->getMessage()];

        }
        return $response;
    }

    //------------------------------------------------
    //----------------------------------------------------------

    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------
}
