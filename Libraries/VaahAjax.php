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
    public function get($url, $query=null, $headers = null): array
    {
        $data = null;

        if(!is_null($query))
        {
            $data['query'] = $query;
        }
        if(!is_null($headers))
        {
            $data['headers'] = $headers;
        }
        try{
            $res = $this->ajax->request('GET', $url, $data);
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
    public function post($url, $params=null, $headers = null): array
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
