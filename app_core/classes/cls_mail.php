<?php
class cls_Mail {
    private $to = null;
    private $from = null;
    private $subject = null;
    private $body = null;
    private $headers = null;
    
    public function __construct() {}
    
    function create_Mail($to, $from, $subject, $body) {
        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->body = $body;
    }
    
    function send_Mail() {
        $this->addHeader('From: ' . $this->from . "\r\n");
        $this->addHeader('Reply-To: ' . $this->from . "\r\n");
        $this->addHeader('Return-Path: ' . $this->from . "\r\n");
        $this->addHeader('X-mailer: MusicApp 1.0' . "\r\n");
        $this->addHeader('Content-type: text/html' . "\r\n");
        
        mail($this->to, $this->subject, $this->body, $this->headers)
        or die("Lo sentimos, debes configurar un servidor de correo (SMTP) primero!");
    }
    
    function addHeader($header) {
        $this->headers .= $header;
    }
}
?>