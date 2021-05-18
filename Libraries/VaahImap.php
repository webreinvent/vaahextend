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
            $response['status'] = "failed";
            $response['errors'][] = "Error opening mailbox: ".imap_last_error();
        } else
        {
            $response['status'] = "success";
            $response['messages'][] = "Successfully connected with IMAP server";
            imap_close($connect);
        }

        return $response;
    }
    //----------------------------------------------------------
    function connect($search_by='SINCE', $search_value=null): array
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

        if(!$this->mail_uids) {
            $response = [
                'status' => "success",
                'data' => [],
                'messages' => ['Mailbox is empty'],
            ];
            return $response;
        }

    }
    //----------------------------------------------------------
    function getMails($search_by='SINCE', $search_value=null): array
    {

        $connect = $this->connect($search_by, $search_value);

        if(isset($connect['status']) && $connect['status'] == 'failed')
        {
            return $connect;
        }

        if(count($this->mail_uids) > 0 )
        {
            $response = [
                'status' => "success",
                'data' => [],
                'messages' => ['Mailbox is empty'],
            ];
            return $response;
        }

        $data = [];

        foreach ($this->mail_uids as $mail_uid){
            $data[$mail_uid] = $this->getMail($mail_uid);
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

        $data['mail'] = $mail;

        $data['from'] = $this->getFrom($mail);
        $data['to'] = $this->getTo($mail);
        $data['cc'] = $this->getCc($mail);
        $data['bcc'] = $this->getBcc($mail);
        $data['message_html'] = $mail->textHtml;
        $data['message_plain'] = $mail->textPlain;
        $data['date'] = $mail->date;

        $data['subject'] = $mail->subject;

        if(!isset($data['subject']) || $data['subject'] == "")
        {
            $data['subject'] = "(no subject)";
        }

        $data['has_attachments'] = false;

        if($mail->hasAttachments())
        {
            $data['has_attachments'] = true;
        }

        return $mail;
    }
    //----------------------------------------------------------

    //----------------------------------------------------------
    function getContactArray($mail_contact, $type): array
    {
        if(count($mail_contact) < 1 || empty($mail_contact))
        {
            return array();
        }

        $i = 0;
        $result = array();
        foreach ($mail_contact as $key => $item)
        {

            if(!isset($item))
            {
                continue;
            }

            if (strpos($item, 'undisclosed-recipients') !== false) {
                continue;
            }
            $result[$i]['type'] = $type;
            $result[$i]['name'] = $item;
            $result[$i]['email'] = $key;
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
    function getTo($mail)
    {
        $emails = null;
        if(isset($mail->to)){
            $emails = $this->getContactArray($mail->to, 'from');
        }
        return $emails;
    }
    //----------------------------------------------------------
    function getCc($mail)
    {
        $emails = null;
        if(isset($mail->cc)){
            $emails = $this->getContactArray($mail->cc, 'cc');
        }
        return $emails;
    }
    //----------------------------------------------------------
    function getBcc($mail)
    {
        $emails = null;
        if(isset($mail->bcc)){
            $emails = $this->getContactArray($mail->bcc, 'bcc');
        }
        return $emails;
    }
    //----------------------------------------------------------

    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------
}
