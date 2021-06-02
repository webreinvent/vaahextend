<?php
namespace WebReinvent\VaahExtend\Libraries;

use \Swift_Mailer;
use Swift_Message;
use \Swift_SmtpTransport as SmtpTransport;
use Swift_SmtpTransport;
use WebReinvent\VaahCms\Mail\TestMail;


class VaahSmtp{

    public $from_address;
    public $from_name;
    public $hostname;
    public $username;
    public $password;
    public $port;
    public $encryption;

    //-----------------------------------------------------
    function __construct($hostname, $port, $username, $password, $encryption = "ssl")
    {

        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
        $this->encryption = $encryption;
    }

    //----------------------------------------------------------
    /*
     * $to_array = ['receiver@domain.org', 'other@domain.org' => 'A name'];
     */
    public function send(
        $from_array,
        $to_array, $subject, $message,
        $cc_array=null, $bcc_array=null,
        $attachments_array=null
    )
    {

        try{
            $transport = (new Swift_SmtpTransport($this->hostname, $this->port, $this->encryption))
                ->setUsername($this->username)
                ->setPassword($this->password);


            $mailer = new Swift_Mailer($transport);

            $mail = (new Swift_Message($subject))
                ->setFrom($from_array)
                ->setTo($to_array);

            if(is_array($cc_array) && count($cc_array) > 0)
            {
                $mail->setCc($cc_array);
            }

            if( is_array($bcc_array) && count($bcc_array) > 0)
            {
                $mail->setBcc($bcc_array);
            }

            $mail->setBody($message, 'text/html');


            if(is_array($attachments_array) && count($attachments_array) > 0)
            {
                foreach ($attachments_array as $attachment)
                {
                    $mail->attach(
                        \Swift_Attachment::fromPath($attachment)->setDisposition('inline')
                    );
                }
            }


            $result = $mailer->send($mail);
            $response['status'] = 'success';
            $response['data']['smtp_response'] = $result;
            $response['messages'][]= 'Email has been sent';

        }catch(\Swift_TransportException $e)
        {
            $error = $e->getMessage();
            if (strpos($error, 'Connection refused') !== false) {
                $response['errors'][] = "Make sure port ".$this->port." is open on your server";
            }

            $current_page = url()->full();

            $response['status'] = 'failed';
            $response['errors'][]= $e->getMessage();
            if(env('APP_DEBUG'))
            {
                $response['hint'][] = $error.'<hr/>'.$current_page;
            }
        }


        return $response;
    }
    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------


}
