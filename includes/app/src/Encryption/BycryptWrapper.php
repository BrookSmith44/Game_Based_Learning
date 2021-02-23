<?php

/**
 * 
 * Wrapper Class for the bycrypt library
 */

namespace Encryption;

class BycryptWrapper {
    // Methods
    public function __construct() {}

    public function __destruct() {}

    // Method to create hashed password
    public function createHashedPassword($string) {
        // set local variable to parameter value
        $password_to_hash = $string;

        // Empty variable for hashed password
        $hashed_password = '';

        if (!empty($password_to_hash)) {
            // Set options array cost to settings bycrypt cost
            $options = array('cost' => BYCRYPT_COST);
            // Hash password
            $hashed_password = password_hash($password_to_hash, BYCRYPT_ALGO, $options);
        }

        // Return hashed password
        return $hashed_password;
    }

    // method to unhash and authenticate password
    public function authenticate($string, $stored_hashed_password) {
        // Initially set authentication to true
        $authentication = false;
        // Set local variable to parameter value
        $entered_password = $string;
        $hashed_password = $stored_hashed_password;

        // Check variables are not empty
        if (!empty($entered_password) && !empty($hashed_password)) {
            // Check entered password is the same as hashed password
            if (password_verify($entered_password, $hashed_password)) {
                // Set authentication to true
                $authentication = true;
            }

            // Return authentication
            return $authentication;
        }
    }
}