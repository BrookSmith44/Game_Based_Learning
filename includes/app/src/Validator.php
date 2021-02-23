<?php

namespace FootballTriviaGame;

class Validator {

    // Empty magic construct method
    public function __construct()
    {

    }
    // Empty magic destruct method
    public function __destruct() {

    }

    // Method to sanitize strings for safety (defends against sql injection, etc)
    // e.g. change single quotes to double quotes
    public function sanitizeString($string_to_sanitize)
    {
        // Set bool to false initially - this represents that the string has not been sanitized yet
        $sanitized_string = false;

        // if statement to ensure that variable has value set
        if (!empty($string_to_sanitize)) {
            // strips tags, strip special characters - filter_var will return bool
            $sanitized_string = filter_var($string_to_sanitize, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        // Return bool
        return $sanitized_string;
    }

    // Method to sanitize email
    public function sanitizeEmail($email_to_sanitize)
    {
        // Set boot to false initially - this represents email has not been sanitized yet
        $sanitized_email = false;

        // if statement to check variable has value set
        if (!empty($email_to_sanitize)) {
            // removed all special characters except letters, digits and some special characters such as "@"
            $sanitized_email = filter_var($email_to_sanitize, FILTER_SANITIZE_EMAIL);
        }

        // return bool
        return $sanitized_email;
    }

    // Method to make sure no input fields are left empty
    public function checkIfEmpty($fname_input, $surname_input, $dob_input, $email_input, $cemail_input, $pass_input, $cpass_input) {

    }

    // Method to validate email
    public function validateEmail($email_to_validate)
    {
        // Set bool to false - represents email has not yet been validated
        $validated_email = false;

        // if statement to check variable has set value
        if (!empty($email_to_validate)) {
            // if statement to check if email is valid
        }
    }
}