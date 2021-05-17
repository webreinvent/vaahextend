<?php
namespace WebReinvent\VaahExtend\Libraries;


class VaahImap{

    public $mailbox;
    public $username;
    public $password;
    public $attachment_folder;
    public $server_encoding;

    //----------------------------------------------------------
    function __construct($mailbox, $username, $password, $attachment_folder, $server_encoding='UTF-8')
    {
        $this->mailbox = $mailbox;
        $this->username = $username;
        $this->password = $password;
        $this->attachment_folder = $attachment_folder;
        $this->server_encoding = $server_encoding;
    }
    //----------------------------------------------------------
    //----------------------------------------------------------
    //----------------------------------------------------------
}
