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

use \Mailjet\Resources;

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
    private $error;

    // Magic methods
    public function __construct() {
        $this->mail = new \Mailjet\Client('d810881eac40a908b989eae102df1621','fe8c4040c1944642d3770b0089df094a',true,['version' => 'v3.1']);
        $this->fname = null;
        $this->email_address = null;
        $this->subject = null;
        $this->content = null;
        $this->non_html_content = null;
        $this->server_settings = null;
        $this->logger = null;
        $this->error = null;
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

    public function setLogger($logger) {
        $this->logger = $logger;
    }

    public function sendMail() {
        // Set empty variable to sent mail success
        $sent_success = false;

        $mail = $this->mail;
        // Try to send mail 
        try {
            $body = [
            'Messages' => [
                [
                'From' => [
                    'Email' => "automated-email@footballthemedlearning.uk",
                    'Name' => "FootballThemedLearning"
                ],
                'To' => [
                    [
                    'Email' => $this->email_address,
                    'Name' => $this->fname
                    ]
                ],
                'Subject' => $this->subject,
                'TextPart' => $this->non_html_content,
                'HTMLPart' => $this->content,
                'CustomID' => "AutomatedEmailLoginDetails"
                ]
            ]
            ];

            $response = $mail->post(Resources::$Email, ['body' => $body]);
            $sent_success = $response->success();
            $this->error = $response->getData();

            $this->logger->notice('Email successfully sent');

        } catch (\Exception $e) {
            $this->logger->warning('Message could not be sent: Mailer Error ' . $this->error);
        }

        return $sent_success;
    }
 }