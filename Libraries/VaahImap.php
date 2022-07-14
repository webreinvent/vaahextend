<?php
namespace WebReinvent\VaahExtend\Libraries;


class VaahImap{

    public $mailbox;
    public $username;
    public $password;
    public $attachments_ignore;
    public $attachments_folder;
    public $server_encoding;
    public $imap;
    public $mail_uids;

    //----------------------------------------------------------
    function __construct($mailbox, $username, $password, $attachments_ignore=true,  $attachments_folder=null, $server_encoding='UTF-8')
    {
        $this->mailbox = $mailbox;
        $this->username = $username;
        $this->password = $password;

        if(!$attachments_folder)
        {
            $attachment_folder = 'imap/'.\Str::slug($username).'-'.date('Y-m');
            if (!\File::isDirectory(storage_path($attachments_folder))) {
                \File::makeDirectory(storage_path($attachments_folder), 0755, true, true);
            }
        }

        $this->attachments_ignore = $attachments_ignore;
        $this->attachments_folder = $attachments_folder;
        $this->server_encoding = $server_encoding;
    }
    //----------------------------------------------------------
    function test(): array
    {

        $connect = @imap_open($this->mailbox, $this->username, $this->password);

        if(!$connect)
        {
            $imap_error = imap_last_error();
            $errors = ["Error opening mailbox: ".imap_last_error()];
            if($imap_error === 'Too many login failures' && str_contains($this->mailbox, 'gmail'))
            {
                $errors[] = "Make sure you enable the imap & turn on
'Allow less secure apps' option for your Google Account.";
            }

            $response['status'] = "failed";
            $response['errors'] = $errors;

        } else
        {
            $response['status'] = "success";
            $response['messages'][] = "Successfully connected with IMAP server";
            imap_close($connect);
        }

        return $response;
    }
    //----------------------------------------------------------
    function connect($search_by='SINCE', $search_value=null)
    {

        $response = $this->test();

        if(isset($response['status']) && $response['status'] == 'failed')
        {
            return $response;
        }

        if($search_by=='SINCE' && !$search_value){
            $search_value = date('d F Y');
        }

        $this->imap = new \PhpImap\Mailbox(
            $this->mailbox,
            $this->username,
            $this->password,
            $this->attachments_folder,
            $this->server_encoding
        );

        if($this->attachments_ignore == true)
        {
            $this->imap->setAttachmentsIgnore(true);
        }

        try {
            if($search_by == 'SINCE')
            {
                $this->mail_uids = $this->imap->searchMailbox(
                    'SINCE "'.$search_value.'"', SE_UID
                );
            } else{
                $this->mail_uids = $this->searchMailBox('UNSEEN', SE_UID);
            }
        } catch(\PhpImap\ConnectionException $ex)
        {
            $response = [
                'status' => "failed",
                'errors' => ['IMAP connection failed: '.$ex]
            ];
            return $response;
        }


        if(count($this->mail_uids) < 1) {
            $response = [
                'status' => "success",
                'data' => [],
                'messages' => ['Mailbox is empty'],
            ];
            return $response;
        }

    }
    //----------------------------------------------------------
    function getMails($search_by='SINCE', $search_value=null)
    {

        $connect = $this->connect($search_by, $search_value);

        if(isset($connect['status']) && $connect['status'] == 'failed')
        {
            return $connect;
        }

        if(count($this->mail_uids) < 1 )
        {
            $response = [
                'status' => "success",
                'data' => [],
                'messages' => ['Mailbox is empty'],
            ];
            return $response;
        }

        $data = [];

        $i = 0;
        foreach ($this->mail_uids as $mail_uid){
            $data[$i] = $this->getMail($mail_uid);
            $i++;
        }

        $response = [
            'status' => "success",
            'data' => $data,
        ];
        return $response;
    }
    //----------------------------------------------------------
    function getMail($uid)
    {
        $mail = $this->imap->getMail($uid);

        $data['mail_uid'] = $uid;
        $data['contacts']['from'] = $this->getFrom($mail);
        $data['contacts']['reply_to'] = $this->getReplyTo($mail);
        $data['contacts']['sender'] = $this->getSender($mail);
        $data['contacts']['to'] = $this->getTo($mail);
        $data['contacts']['cc'] = $this->getCc($mail);
        $data['contacts']['bcc'] = $this->getBcc($mail);

        $data['date_time'] = $mail->date;

        $data['subject'] = $mail->subject;

        if(!isset($data['subject']) || $data['subject'] == "")
        {
            $data['subject'] = "(no subject)";
        }

        $data['message_html'] = $mail->textHtml;
        $data['message_plain'] = $mail->textPlain;

        $data['has_attachments'] = null;

        if($mail->hasAttachments())
        {
            $data['has_attachments'] = true;
        }

        $data['mail'] = $mail;

        return $data;
    }
    //----------------------------------------------------------

    //----------------------------------------------------------
    function getContactArray($mail_contact, $type)
    {
        if(count($mail_contact) < 1 || empty($mail_contact))
        {
            return null;
        }

        $i = 0;
        $result = array();



        foreach ($mail_contact as $key => $item)
        {

            if(!isset($item) || empty($item))
            {
                continue;
            }

            if(!isset($item->host))
            {
                continue;
            }

            if(isset($item->mailbox) && $item->mailbox == 'undisclosed-recipients')
            {
                continue;
            }



            $result[$i]['type'] = $type;


            if(isset($item->personal))
            {
                $result[$i]['name'] = $item->personal;
            }


            if(isset($item->mailbox) && isset($item->host))
            {
                $result[$i]['email'] = $item->mailbox."@".$item->host;
            }


            $i++;
        }

        return $result;
    }
    //----------------------------------------------------------
    function getFrom($mail)
    {
        $from = null;

        if(isset($mail->headers->from)){
            $from = $this->getContactArray($mail->headers->from, 'from');
        }

        if(empty($from))
        {
            $from['name'] = $mail->fromName;
            $from['email'] = $mail->fromAddress;
            $from['type'] = 'from';
        }

        return $from;
    }
    //----------------------------------------------------------
    function getReplyTo($mail)
    {
        $reply_to = null;
        if(isset($mail->headers->reply_to)){
            $reply_to = $this->getContactArray($mail->headers->reply_to, 'reply_to');
        }

        return $reply_to;

    }
    //----------------------------------------------------------
    function getSender($mail)
    {
        $sender = null;
        if(isset($mail->headers->sender)){
            $sender = $this->getContactArray($mail->headers->sender, 'sender');
        }

        return $sender;
    }
    //----------------------------------------------------------
    function getTo($mail)
    {
        $emails = null;
        if(isset($mail->headers->to)){
            $emails = $this->getContactArray($mail->headers->to, 'to');
        }
        return $emails;
    }
    //----------------------------------------------------------
    function getCc($mail)
    {
        $emails = null;
        if(isset($mail->headers->cc)){
            $emails = $this->getContactArray($mail->headers->cc, 'cc');
        }
        return $emails;
    }
    //----------------------------------------------------------
    function getBcc($mail)
    {
        $emails = null;
        if(isset($mail->headers->bcc)){
            $emails = $this->getContactArray($mail->headers->bcc, 'bcc');
        }
        return $emails;
    }
    //----------------------------------------------------------

    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------
}
