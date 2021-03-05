<?php
/**
 * User Model Class
 */

 namespace FootballTriviaGame;

 class UserModel {
     // Properties
     private $username;
     private $fname;
     private $surname;
     private $dob;
     private $email;
     private $password;
     private $results;
     private $first_time_login;
     private $db_connection_settings;
     private $db;
     private $sql_queries;
     private $session_wrapper;
     private $libsodium_wrapper;
     private $base64_wrapper;
     private $logger;
 
    // Methods
    public function __construct() {
        $this->username = null;
        $this->fname = null;
        $this->surname = null;
        $this->email = null;
        $this->dob = null;
        $this->password = null;
        $this->first_time_login = null;
        $this->results = null;
        $this->sql_queries = null;
        $this->db_connection_settings = null;
        $this->session_wrapper = null;
        $this->libsodium_wrapper = null;
        $this->base64_wrapper = null;
        $this->logger = null;
    }

    public function destruct() {}

    // Setter Methods
    public function setUsername($username) {
        $this->username = $username;
    }

    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setDob($dob) {
        $this->dob = $dob;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setFirstTimeLogin($first_time_login) {
        $this->first_time_login = $first_time_login;
    }

    public function setDbConnectionSettings($db_connection_settings) {
        $this->db_connection_settings = $db_connection_settings;
    }

    public function setDb($db) {
        $this->db = $db;
    }

    public function setSQLQueries($sql_queries) {
        $this->sql_queries = $sql_queries;
    }

    public function setSessionWrapper($session_wrapper) {
        $this->session_wrapper = $session_wrapper;
    }

    public function setLibsodiumWrapper($libsodium_wrapper) {
        $this->libsodium_wrapper = $libsodium_wrapper;
    }

    public function setBase64Wrapper($base64_wrapper) {
        $this->base64_wrapper = $base64_wrapper;
    }

    public function setLogger($logger) {
        $this->logger = $logger;
    }

    // Method to sign up user
    public function signupProcess() {
        // Connect
        $this->connect();

        // Store user data
        $store_result = $this->signupStorage();

        // If user data is stored successfully 
        // Check to see if data was stored successfully
        if ($store_result === true) {
            $store_result = $this->setSessionData();
         }

         return $store_result;
    }

    // Method to connect to database
    public function connect() {
        $this->db->setSQLQueries($this->sql_queries);
        $this->db->setDbConnectionSettings($this->db_connection_settings);
        $this->db->makeDbConnection();
    }

    // Method to store user account data
    public function signupStorage() {
        // Empty array for data to store
        $data_to_store = [];

        $data_to_store['username'] = $this->username;
        $data_to_store['fname'] = $this->fname;
        $data_to_store['surname'] = $this->surname;
        $data_to_store['dob'] = $this->dob;
        $data_to_store['email'] = $this->email;
        $data_to_store['pass'] = $this->password;
        $data_to_store['date_added'] = date("Y-m-d");
        $data_to_store['first_time_login'] = $this->first_time_login;
        $data_to_store['student'] = 'N';
        $data_to_store['teacher'] = 'N';
        $data_to_store['admin'] = 'N';
        $data_to_store['general'] = 'Y';

        // Call database handle to store data 
        $store_result = $this->db->storeData($data_to_store);

        return $store_result;
    }

    public function setSessionData() { 
        // Create empty array for storage results
        $store_results = [];
        // Set store result to false initially
        $store_result = false;

        // Decrypt first name to put into session variable
        $decrypted_data['fname'] = $this->libsodium_wrapper->decryption(
            $this->base64_wrapper,
            $this->fname
        );

        // Call methods to create session vairables
        $store_results['username'] = $this->session_wrapper->setSessionVar('username', $this->username);
        $store_results['fname'] = $this->session_wrapper->setSessionVar('fname', $decrypted_data['fname']);

        // Check all session variables were stored successfully
        if(count(array_unique($store_results)) === 1) {
            $store_result = current($store_results);
        }

        // Return store result
        return $store_result;
    }

    // Method to redirect user to page
    public function redirect() {
        // Connect to db
        $this->connect();

        // Get query string from sql queries 
        $query_string = $this->sql_queries->getAccountType();

        // Set query parameters
        $query_parameters = [
            ':param_username' => $this->username
        ];

        // Query database
        $results = $this->db->getValues($query_parameters, $query_string);

        // Check to see if results have been returned
        if(!empty($results)) {
            // Check what type of account the user has
            if($results['general'] == 'Y' || $results['student']) {
                // Check to see whether user has logged in before or not
                if($results['first_time_login'] == 'Y') {
                    // Set redirect variables
                    $redirect = 'TeamDetails';
                } else {
                    $redirect = 'PlayerHomepage';
                }
            } else {
                $redirect = 'ManagementHomepage';
            }
        }

        return $redirect;
    }
}