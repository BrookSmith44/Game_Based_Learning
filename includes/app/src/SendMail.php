<?php

/**
 * 
 * Class to automatically send emails to user 
 * 
 * Will be used to send students and teachers emails 
 * with their automatically generated username and default password
 * 
 */

namespace FootballTriviaGame;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

 class SendMail {
    // Properties
    private $mail;
    private $fname;
    private $email_address;
    private $subject;
    private $content;
    private $non_html_content;
    private $server_settings;
    private $logger;

    // Magic methods
    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->fname = null;
        $this->email_address = null;
        $this->subject = null;
        $this->content = null;
        $this->non_html_content = null;
        $this->server_settings = null;
        $this->logger = null;
    }

    public function __destruct() {}

    // Setter Methods
    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function setEmailAddress($email_address) {
        $this->email_address = $email_address;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setNonHTMLContent($non_html_content) {
        $this->non_html_content = $non_html_content;
    }

    public function setServerSettings($server_settings) {
        $this->server_settings = $server_settings;
    }

    public function setLogger($logger) {
        $this->logger = $logger;
    }


    // Method to set server settings
    public function serverSettings() {
        // get mail server settings
        $server_settings = $this->server_settings;

        $mail = $this->mail;

        // Enable verbose debug output
        //$mail->SMTPDebug = 1;
        // Send using SMTP
        $mail->isSMTP();
        // Set the SMTP server to send through
        $mail->Host = $server_settings['host'];
        // Enable SMTP authentication
        $mail->SMTPAuth = true;
        // SMTP username
        $mail->Username = $server_settings['username'];
        // SMTP password
        $mail->Password = $server_settings['password'];
        // Enable TLS encryption
        $mail->SMTP = 'tls';
        // Port
        $mail->Port = $server_settings['port'];
    }

    public function recipients() {

        $mail = $this->mail;

        // Set from email
        $mail->setFrom('testmailer484@gmail.com', 'Test Mailer');
        // Send to recipient
        $mail->addAddress($this->email_address, $this->fname);
    }

    public function content() {

        $mail = $this->mail;
        // Set mail format to HTML
        $mail->isHTML(true);
        // Set the subject
        $mail->Subject = $this->subject;
        // Set email content
        $mail->Body = $this->content;
        // Set email content for non html email clients
        $mail->AltBodt = $this->non_html_content;
    }

    public function sendMail() {
        // Set empty variable to sent mail success
        $sent_success = false;

        $mail = $this->mail;
        // Try to send mail 
        try {
            $this->serverSettings();
            $this->recipients();
            $this->content();

            $mail->send();

            $sent_success = true;

            $this->logger->notice('Email successfully sent');

        } catch (\Exception $e) {
            $this->logger->warning('Message could not be sent: Mailer Error ' . $mail->ErrorInfo);
        }

        return $sent_success;
    }
 }