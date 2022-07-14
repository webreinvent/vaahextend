<?php
namespace WebReinvent\VaahExtend\Libraries;




use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class VaahSmtpOAuth{

    private $client_id;
    private $client_secret;
    private $username;
    private $refresh_token;
    private $provider;


    //-----------------------------------------------------
    function __construct($client_id, $client_secret, $username, $refresh_token, $provider='gmail')
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->username = $username;
        $this->refresh_token = $refresh_token;
        $this->provider = $provider;
    }

    //----------------------------------------------------------
    /*
     * $from_array = ['from@domain.org' => 'from name'];
     * $to_array = ['receiver@domain.org', 'other@domain.org' => 'A name'];
     */
    public function send(
        $from_array,
        $to_array, $subject, $message,
        $cc_array=null, $bcc_array=null,
        $attachments_array=null,
        $reply_to_array=null
    )
    {

        $response = null;

        switch ($this->provider)
        {
            case 'gmail':
                $response = $this->sendViaGmail($from_array,
                    $to_array, $subject, $message,
                    $cc_array, $bcc_array,
                    $attachments_array,
                    $reply_to_array);
                break;
        }



        return $response;
    }
    //----------------------------------------------------------

    public function sendViaGmail($from_array,
                                 $to_array, $subject, $message,
                                 $cc_array=null, $bcc_array=null,
                                 $attachments_array=null,
                                 $reply_to_array=null)
    {

        try{

            $inputs = array(
                'clientId' => $this->client_id,
                'clientSecret' => $this->client_secret,
                'userName' => $this->username,
                'refreshToken' => $this->refresh_token,
            );

            $inputs['provider'] = new Google($inputs);

            $oauth = new OAuth($inputs);
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPAuth = true;
            $mail->AuthType = 'XOAUTH2';
            $mail->setOAuth($oauth);

            $from_email = key($from_array);
            $from_name =  $from_array[$from_email];

            $mail->setFrom($from_email, $from_name);

            //--- add to
            if(is_array($to_array) && count($to_array) > 0)
            {
                foreach($to_array as $email => $name)
                {
                    $validator = \Validator::make(['email' => $email],[
                        'email' => 'required|email'
                    ]);
                    if($validator->passes()){
                        $mail->AddAddress($email, $name);
                    } else{
                        $mail->AddAddress($name);
                    }
                }
            }

            //--- add cc
            if(is_array($cc_array) && count($cc_array) > 0)
            {
                foreach($cc_array as $email => $name)
                {
                    $validator = \Validator::make(['email' => $email],[
                        'email' => 'required|email'
                    ]);
                    if($validator->passes()){
                        $mail->addCC($email, $name);
                    } else{
                        $mail->addCC($name);
                    }
                }
            }

            //--- add bcc
            if(is_array($bcc_array) && count($bcc_array) > 0)
            {
                foreach($bcc_array as $email => $name)
                {
                    $validator = \Validator::make(['email' => $email],[
                        'email' => 'required|email'
                    ]);
                    if($validator->passes()){
                        $mail->addBCC($email, $name);
                    } else{
                        $mail->addBCC($name);
                    }
                }
            }

            //--- add attachments
            if(is_array($attachments_array) && count($attachments_array) > 0)
            {
                foreach ($attachments_array as $attachment)
                {
                    if(isset($attachment['name']) && !empty($attachment['name']))
                    {
                        $mail->addAttachment($attachment['path'], $attachment['name']);
                    } else{
                        $mail->addAttachment($attachment['path']);
                    }
                }
            }

            $mail->Subject = $subject;
            $mail->msgHTML($message);
            $result = $mail->send();

            $response['success'] = true;
            $response['data']['smtp_response'] = $result;
            $response['messages'][]= 'Email has been sent';
            return $response;

        }catch(\Exception $e)
        {
            $response['failed'] = true;
            $response['errors'][] = $e->getMessage();
            return $response;
        }

    }

    //----------------------------------------------------------
    public static function validateGmailInputs($inputs)
    {

        $rules = array(
            'client_id' => 'required',
            'client_secret' => 'required',
            'username' => 'required',
            'refresh_token' => 'required',
        );

        $validator = \Validator::make( $inputs, $rules);
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
    //----------------------------------------------------------


}
