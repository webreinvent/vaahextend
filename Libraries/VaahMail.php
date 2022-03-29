<?php
namespace WebReinvent\VaahExtend\Libraries;

use WebReinvent\VaahCms\Entities\User;
use WebReinvent\VaahCms\Jobs\ProcessMails;
use WebReinvent\VaahCms\Mail\GenericMail;
use WebReinvent\VaahCms\Notifications\TestSmtp;
use Illuminate\Support\Facades\Notification;


class VaahMail{

    //----------------------------------------------------------
    //----------------------------------------------------------
    /*
     * $to = [
     *          ['email' => 'email@example.com', 'name' => 'name'],
     *          ['email' => 'email@exampl.com', 'name' => 'name 2'],
     *      ]
     */
    public static function dispatch($mail, $to=null, $priority='default')
    {
        if(config('settings.global.laravel_queues'))
        {
            $response = self::addInQueue($mail, $to, $priority);
        } else
        {
            $response = self::send($mail, $to);
        }

        return $response;

    }
    //----------------------------------------------------------
    public static function dispatchToUser($mail, User $user, $priority='default')
    {

        $to = [
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ];

        $response = self::dispatch($mail, $to, $priority);

        return $response;

    }
    //----------------------------------------------------------
    public static function addInQueue($mail, $to=null, $priority='default')
    {

        dispatch((new ProcessMails($mail, $to))->onQueue($priority));

        $response['status'] = 'success';
        $response['data'] = [];
        $response['messages'][] = 'Action was successful';

        return $response;

    }
    //-------------------------------------------------
    public static function send($mail, $to=null){

        try{
            \Mail::to($to)->send($mail);

            $response['status'] = 'success';
            $response['data'] = [];
            $response['messages'][] = 'Action was successful';
        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();

        }

        return $response;

    }
    //----------------------------------------------------------
    public static function dispatchGenericMail($content, User $user, $priority='default')
    {
        $to = [
            [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ];

        $mail = new GenericMail($content);

        $response = self::dispatch($mail, $to, $priority);

        return $response;

    }
    //----------------------------------------------------------
    public static function setupSmtp($request)
    {

    }
    //----------------------------------------------------------
    public static function sendTestEmail($request)
    {
        $rules = array(
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
            'test_email_to' => 'required',
        );

        $validator = \Validator::make( $request->all(), $rules);
        if ( $validator->fails() ) {

            $errors             = errorsToArray($validator->errors());
            $response['status'] = 'failed';
            $response['errors'] = $errors;
            return $response;
        }

        $inputs = [
            'driver' => $request->mail_driver,
            'host' => $request->mail_host,
            'port' => $request->mail_port,
            'username' => $request->mail_username,
            'password' => $request->mail_password,
            'from' => [
                'address' => $request->mail_from_address,
                'name' => $request->mail_from_name,
            ],
            "sendmail" => "/usr/sbin/sendmail -bs"
        ];

        if($request->mail_encryption != 'none')
        {
            $inputs['encryption'] = $request->mail_encryption;
        }

        $response['data']['inputs'] = $inputs;

        try{

            config(['mail' => $inputs]);

            Notification::route('mail', $request->test_email_to)
                ->notify(new TestSmtp());;

            $response['status'] = 'success';
            $response['data']['inputs'] = $inputs;
            $response['messages'][] = 'Test email successfully sent';


        }catch(\Exception $e)
        {
            $response['status'] = 'failed';
            $response['errors'][] = $e->getMessage();

        }

        if(env('APP_DEBUG'))
        {
            $response['hint'][] = '';
        }

        return $response;
    }
    //----------------------------------------------------------

    //----------------------------------------------------------

}
