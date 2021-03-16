<?php
/**
 * 
 * sessionWrapper.php
 * 
 * Class to set, edit and delete stored session variables
 */

 namespace Database;

 class SessionWrapper {
    // Properties
    private $logger;

    // Magic methods
    public function __constuct() {
        $this->logger = null;
    }

    public function __destruct() {}

    // Setter Methods
    public function setLogger($logger) {
        $this->logger = $logger;
    }

    // Method to set session variables
    public function setSessionVar($key, $value) {
        // Set to false initially while session variable not set
        $set_successful = false;

        // Check if parameters are not empty
        if (!empty($value)) {
            try {
                    // Set session variable to value parameter
                    $_SESSION[$key] = $value;
                    // Compare session variable and value parameter
                    if (strcmp($_SESSION[$key], $value) == 0) {
                        $set_successful = true;
                        $this->logger->notice('Session key ' . $key . ' set successfully!');
                    } 
                } catch (Exception $e) {
                    $this->logger->warning("Failed to set session variable");
                }
            }

        // Return success variable
        return $set_successful;
    }

    // Method to unset session variables
    public function unsetSessionVar($key) {
        // Set success variable
        $unset_successful = false;
        try {
            // Check if the session variable is set
            if (isset($_SESSION[$key])) {
                // unset session variable
                unset($_SESSION[$key]);
            }

            // Ensure that variable has been unset
            if(!isset($_SESSION[$key])) {
                // Set success variable to true
                $unset_successful = true;
                $this->logger->notice('Session key ' . $key . ' unset successfully!');
            }
        } catch (Exception $e) {
            $this->logger->warning("Failed to unset session variable");
        }

        // return success variable
        return $unset_successful;
    }

    // Method to get session variables
    public function getSessionVar($key) {
        // Set session value to false initially
        $session_value = false;

        try {
            // Check to see if session variable is set
            if (isset($_SESSION[$key])) {
                $session_value = $_SESSION[$key];
            }

        } catch (Exception $e) {
            $this->logger->warning("Falied to get session variable");
        }
        // return session value
        return $session_value;
    }
}