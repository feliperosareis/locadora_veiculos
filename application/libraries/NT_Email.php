<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Email
 *
 * @author Felipe Rosa
 */
class NT_Email extends CI_Email {

    public function __construct() {
        parent::__construct();
    }

    public function send() {
        $ci = & get_instance();
        $sw = $ci->nt_global_parametros->q('default_mailer_swift');
        if ($sw == "true") {
            $this->sendUsingSwift();
        } else {
            return parent::send();
        }
    }

    private function sendUsingSwift() {

        require_once (APPPATH . 'third_party/swift/lib/swift_required.php');
        
        
        $transport = Swift_SmtpTransport::newInstance($this->smtp_host, $this->smtp_port)
                ->setUsername($this->smtp_user)
                ->setPassword($this->smtp_pass);

        
        $mailer = Swift_Mailer::newInstance($transport);

                
        $message = Swift_Message::newInstance($this->_wtsubject)
                ->setFrom($this->_wtfrom)
                ->setTo($this->_recipients)
                ->setReplyTo($this->_wtreplyto)
                ->setBody($this->_wtmessage,'text/html');

        $result = $mailer->send($message);
        
        return $result;
    }

}