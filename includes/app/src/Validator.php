<?php
/**
 * class to validate and santize any data from the front-end
 *
 * @author - Brook Smith
 */

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
    public function sanitizeString($string_to_sanitize) {
        // Set bool to false initially - this represents that the string has not been sanitized yet
        $sanitized_string = false;

        // if statement to ensure that variable has value set
        if (!empty($string_to_sanitize)) {
            // strips tags, strip special characters - filter_var will return bool
            $sanitized_string = filter_var($string_to_sanitize, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        // Return sanitized value
        return $sanitized_string;
    }

    // Method to sanitize email
    public function sanitizeEmail($email_to_sanitize) {
        // Set variable to false initially - this represents email has not been sanitized yet
        $sanitized_email = false;

        // if statement to check variable has value set
        if (!empty($email_to_sanitize)) {
            // removed all special characters except letters, digits and some special characters such as "@"
            $sanitized_email = filter_var($email_to_sanitize, FILTER_SANITIZE_EMAIL);
        }

        // Return sanitized email
        return $sanitized_email;
    }

    public function sanitizeDate($date_to_sanitize) {
        // Set variable to false initially
        $sanitized_date = false;

        // Check parameter is not empty
        if (!empty($date_to_sanitize)) {
            // Remove any special characters from date
            $sanitized_date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $date_to_sanitize);
        }

        // Return date
        return $sanitized_date;
    }

    // Method to make sure no input fields are left empty
    public function checkIfEmpty($fname_input, $surname_input, $dob_input, $email_input, $cemail_input, $pass_input, $cpass_input) {
        // Set bool to false initially
        $input_entered = false;

        // If all of the values are not empty
        if (!empty($fname_input) && !empty($surname_input) && !empty($dob_input) && !empty($email_input) && !empty($cemail_input) && !empty($pass_input) && !empty($cpass_input)) {
            // set input entered bool to true
            $input_entered = true;
        }

        // return bool
        return $input_entered;
    }

    // Method to confirm values match
    public function confirmValuesMatch($value, $confirm_value) {
        // Set bool to false - represents value has not yet been validated
        $validated_value = false;

        // Check that values match
        if ($value == $confirm_value) {
            // set validated value bool to true
            $validated_value = true;
        }

        // Return bool
        return $validated_value;
    }
}