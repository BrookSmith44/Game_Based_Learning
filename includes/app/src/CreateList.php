<?php

/**
 * 
 * Class to generate the email template that will be sent to users 
 * to provide their login details
 */

 namespace FootballTriviaGame;

 class CreateList {
     // Properties
     private $account_id;
     private $fname;
     private $surname;
     private $username;
     private $email;
     private $date_added;

     // Magic methods
    public function __construct() {
        $this->account_id = null;
        $this->fname = null;
        $this->surname = null;
        $this->username = null;
        $this->email = null;
        $this->date_added = null;
    }

    public function __destruct() {}

    // Setter methods
    public function setAccountID($account_id) {
        $this->account_id = $account_id;
    }

    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setDateAdded($date_added) {
        $this->date_added = $date_added;
    }

    public function generateHTML() {
        $output = '
            <tr>
                <td>' .  $this->account_id . '</td>
                <td>' .  $this->username . '</td>
                <td>' .  $this->fname . ' ' . $this->surname . '</td>
                <td>' .  $this->email . '</td>
                <td>' .  $this->date_added . '</td>
            </tr>
        ';


        return $output;
    }
}