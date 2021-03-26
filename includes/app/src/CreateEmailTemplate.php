<?php

/**
 * 
 * Class to generate the email template that will be sent to users 
 * to provide their login details
 */

 namespace FootballTriviaGame;

 class CreateEmailTemplate {
     // Properties
     private $fname;
     private $usename;
     private $password;

     // Magic methods
    public function __construct() {
        $this->fname = null;
        $this->username = null;
        $this->password = null;
    }

    public function __destruct() {}

    // Setter methods
    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function generateHTML() {
        $output = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Automated Username and Password</title>
        </head>
        <body>
            <table style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                <tbody>
                    <tr style="height: 10vh; background-color: seagreen; color: white; margin: 1em 0">
                        <th></th>
                        <th style="height: 10vh; display: flex; flex-direction: column; justify-content: center;">
                            <h3 style="width: 70%; padding: 0 15%;">Game-Based-Learning</h3>
                        </th>
                        <th></th>
                    </tr>
                    <tr style="height: 90vh;">
                    <td style="width: 25%; background-color: #ccc;"></td>
                        <td style="width: 50%; padding: 0 5%; text-align: center; background-color: white;"> 
                            <h4>Hello ' . $this->fname . ', </h4>
                            <br>
                            <p>This is an automated email to provide your login details: </p>
                            <br>
                            <br>
                            <p>Username: ' . $this->username . '</p>
                            <p>Password: ' . $this->password . '</p>
                        </td>
                        <td style="width: 25%; background-color: #ccc;"></td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>
        ';


        return $output;
    }

    public function generateNonHTML() {
        $output = 'Hello ' . $this->fname . ', ';
        $output .= 'This is an automated email to provide your login details: ';
        $output .= 'Username: ' . $this->username . '';
        $output .= 'Password: ' . $this->password . '';

        return $output;
    }
 }