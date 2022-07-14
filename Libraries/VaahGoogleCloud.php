<?php
namespace WebReinvent\VaahExtend\Libraries;



use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Google;

class VaahGoogleCloud{



    //-----------------------------------------------------
    function __construct()
    {


    }

    //----------------------------------------------------------
    public static function getGmailApiInputs()
    {

        $inputs = [
            'clientId' => env('GMAIL_API_CLIENT_ID'),
            'clientSecret' => env('GMAIL_API_CLIENT_SECRET'),
            'redirectUri' => env('GMAIL_API_REDIRECT_URL'),
            'accessType' => 'offline',
        ];

        return $inputs;
    }
    //----------------------------------------------------------
    public static function getGmailApiValidation($inputs)
    {

        $rules = array(
            'clientId' => 'required',
            'clientSecret' => 'required',
            'redirectUri' => 'required',
        );

        $messages = [
            'clientId.required' => 'Set ENV variable GMAIL_API_CLIENT_ID',
            'clientSecret.required' => 'Set ENV variable GMAIL_API_CLIENT_SECRET',
            'redirectUri.required' => 'Set ENV variable GMAIL_API_REDIRECT_URL',
        ];

        $validator = \Validator::make( $inputs, $rules, $messages);
        if ( $validator->fails() ) {
            $errors             = $validator->errors();
            $response['success'] = false;
            $response['errors'] = $errors;
            return $response;
        }

        $response['success'] = true;
        return $response;
    }
    //----------------------------------------------------------
    public static function getGmailApiAuthUrl()
    {
        $inputs = self::getGmailApiInputs();
        $validate = self::getGmailApiValidation($inputs);

        if(!$validate['success'])
        {
            return $validate;
        }

        $google_options = [
            'scope' => [
                'https://mail.google.com/'
            ]
        ];

        $provider = new Google($inputs);

        $response['success'] = true;
        $response['data']['authorization_url'] = $provider->getAuthorizationUrl($google_options);

        return $response;
    }
    //----------------------------------------------------------
    public static function getGmailApiToken(Request $request)
    {
        $inputs = self::getGmailApiInputs();

        $validate = self::getGmailApiValidation($inputs);

        if(!$validate['success'])
        {
            return $validate;
        }

        if(!$request->has('code'))
        {
            $response['success'] = false;
            $response['errors'][] = 'Code variable not received from Authorization Url.';
            return $response;
        }

        $code = $request->get('code');

        $provider = new Google($inputs);

        try{

            $token_obj = $provider->getAccessToken('authorization_code', ['code' => $code]);
            $token = $token_obj->getToken();
            $refresh_token = $token_obj->getRefreshToken();

            $response['success'] = true;
            $response['data']['token'] = $token;
            $response['data']['refresh_token'] = $refresh_token;
            return $response;

        }catch(IdentityProviderException $e)
        {
            $response['failed'] = true;
            $response['errors'][] = $e->getMessage();
            return $response;
        }catch(\Exception $e)
        {
            $response['failed'] = true;
            $response['errors'][] = $e->getMessage();
            return $response;
        }

    }
    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------


}
