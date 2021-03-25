<?php
/**
 * User Model Class
 */

 namespace Model;

 class UserModel {
     // Properties
     protected $username;
     protected $fname;
     protected $surname;
     private $dob;
     protected $email;
     protected $password;
     private $results;
     protected $first_time_login;
     protected $db_connection_settings;
     protected $db;
     protected $sql_queries;
     protected $session_wrapper;
     private $libsodium_wrapper;
     private $base64_wrapper;
     private $bycrypt;
     private $team_model;
     protected $logger;
 
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
        $this->bycrypt = null;
        $this->team_model = null;
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

    public function setBycrypt($bycrypt) {
        $this->bycrypt = $bycrypt;
    }

    public function setTeamModel($team_model) {
        $this->team_model = $team_model;
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

        $this->account_type = 'General';

        $this->fname = $this->libsodium_wrapper->decryption(
            $this->base64_wrapper,
            $this->fname
        );

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
        $this->db->setLogger($this->logger);
        $this->db->makeDbConnection();
    }

    // Method to store user account data
    public function signupStorage() {
        // Empty array for data to store
        $data_to_store = [];

        // Get sql query from class
        $query_string = $this->sql_queries->insertGeneralAccount();

        // Set query parameters
        $query_parameters = [
            ':param_username' => $this->username,
            ':param_fname' => $this->fname,
            ':param_surname' => $this->surname,
            ':param_dob' => $this->dob,
            ':param_email' => $this->email,
            ':param_pass' => $this->password,
            ':param_ftl' => $this->first_time_login,
            ':param_da' => date("Y-m-d"),
            ':param_acc_type' => 'General'
        ];

        // Call database handle to store data 
        $store_result = $this->db->storeData($query_parameters, $query_string);

        return $store_result;
    }

    public function setSessionData() { 
        // Create empty array for storage results
        $store_results = [];
        // Set store result to false initially
        $store_result = false;

        // Connect to database
        $this->connect();

        // Set query parameters
        $query_parameters = [
            ':param_username' => $this->username
        ];

        // Set query string
        $query_string = $this->sql_queries->getAccountID();

        // Execute query
        $results = $this->db->getValues($query_parameters, $query_string);

        // Set session wrapper logger
        $this->session_wrapper->setLogger($this->logger);

        // Call methods to create session vairables
        $store_results['is_logged_in'] = $this->session_wrapper->setSessionVar('is_logged_in', 'Y');
        $store_results['account_id'] = $this->session_wrapper->setSessionVar('account_id', $results['account_id']);
        $store_results['username'] = $this->session_wrapper->setSessionVar('username', $this->username);
        $store_results['fname'] = $this->session_wrapper->setSessionVar('fname', $this->fname);
        $store_results['account_type'] = $this->session_wrapper->setSessionVar('account_type', $this->account_type);

        // Check all session variables were stored successfully
        if(count(array_unique($store_results)) === 1) {
            $store_result = current($store_results);
        }

        // Return store result
        return $store_result;
    }

    // Method to redirect user to page
    public function redirect() {
        // Create empty array for redirect
        $redirect = [];

        // Check what type of account the user has
        if($this->first_time_login == 'Y') {
            // Switch case to get account type
            switch($this->account_type) {
                // General Account
                case 'General' :
                    // Send user to team details route
                    $redirect['page'] = 'TeamDetails';
                    // No error
                    $redirect['err'] = '';
                    break;
                // Student Account
                case 'Student';
                // Teacher Account
                case 'Teacher';
                    // Send user to change password route
                    $redirect['page'] = 'ChangePassword';
                    $redirect['err'] = 'passErr';
                    break;
            }
        } else {
            // Switch case to get account type
            switch($this->account_type) {
                // General Account
                case 'General' ;
                // Student Account
                case 'Student';
                    // Send user to team details route
                    $redirect['page'] = 'PlayerHomepage';
                    // No error
                    $redirect['err'] = '';
                    break;
                // Teacher Account
                case 'Teacher':
                    // Send user to change password route
                    $redirect['page'] = 'ManagementHomepage';
                    $redirect['err'] = '';
                    break;
            }
        }

        return $redirect;
    }

    public function checkUsername() {
        // Connect to database
        $this->connect();

        // Empty variable for check result
        $check_result = '';

        // Set username parameter
        $query_parameter = [
            ':param_username' => $this->username
        ];

        // Get query from sql class
        $query_string = $this->sql_queries->checkUsername();

        // Execute query
        $check_results = $this->db->getValues($query_parameter, $query_string);

         // Check all counts were returned as 0
         if ($check_results['count1'] == 0 && $check_results['count2'] == 0 && $check_results['count3'] == 0) {
            $check_result = 0;
        } else {
            $check_result = 1;
        }

        return $check_result;
    }

    // Method to process login
    public function processLogin() {
        // Connect to database
        $this->connect();

        // Set empty array for store results
        $store_results = [];

        // Set query parameters
        $query_parameters = [
            ':param_username' => $this->username
        ];

        // Set query string 
        $query_string = $this->sql_queries->loginQuery();

        // Execute query
        $query_results = $this->db->getValues($query_parameters, $query_string);

        // Call method to decrypt and check password
        $authenticate_result = $this->authenticateLogin($query_results);

        // Check authentication - 
        $redirect = $this->processAuthentication($authenticate_result, $query_results);
        
        return $redirect;
    }

    // Method to authenticate login details
    public function authenticateLogin($query_results) {
        // set empty array for decryption
        $decrypted_data = [];

        // Decrypt password 
        $decrypted_data['password'] = $this->libsodium_wrapper->decryption(
            $this->base64_wrapper,
            $query_results['account_password']
        );

        // Check inputted password against stored hashed password
        $authenticate = $this->bycrypt->authenticate($this->password, $decrypted_data['password']);

        // Return authentication result
        return $authenticate;
    } 

    // method to 
    public function processAuthentication($authenticate_result, $query_results) {
        // Empty arrrays for returned data
        // Create empty array for redirect
        $redirect = [];
        // Set empty array for team data
        $team_data = [];
        // Set empty array for store results
        $store_result = [];

        // Check authentication result
        if ($authenticate_result === true) {
            // Set class properties

            // Decrypt fname 
            $this->fname = $this->libsodium_wrapper->decryption(
            $this->base64_wrapper,
            $query_results['account_fname']
        );

            $this->account_type = $query_results['account_type'];
            $this->first_time_login = $query_results['first_time_login'];

            // Set user session variables
            $store_result = $this->setSessionData();

            if ($store_result == true) {

                // Get team data if general or student
                switch ($this->account_type) {
                case 'General';
                case 'Student';
                    // Set team model properties
                    $this->team_model->account_type = $this->account_type;
                    $this->team_model->username = $this->username;
                    $this->team_model->setDb($this->db);
                    $this->team_model->setSqlQueries($this->sql_queries);
                    $this->team_model->setDbConnectionSettings($this->db_connection_settings);
                    $this->team_model->setSessionWrapper($this->session_wrapper);
                    $this->team_model->setLogger($this->logger);
                    // get team data for this user
                    $team_data = $this->team_model->getTeamData();
                    // Set session team data
                    $store_result = $this->team_model->setTeamSessionVar();
                    break; 
            }

            if ($store_result == true) {
                // Redirect user
                $redirect = $this->redirect();
            } else {
                $redirect['page'] = 'Login';
                $redirect['err'] = 'storeErr';
            }
                
            } else {
                $redirect['page'] = 'Login';
                $redirect['err'] = 'storeErr';
            }
            
        } else {

            $redirect['page'] = 'Login';
            $redirect['err'] = 'passErr';
        }

        return $redirect;
    }

    // Method to change first time login once user has logged in
    public function updateFirstTimeLogin() {
        // Connect to database
        $this->connect();

        // Set properties
        $this->username = $this->session_wrapper->getSessionVar('username');
        $this->account_type = $this->session_wrapper->getSessionVar('account_type');

        // Set query parameters
        $query_parameters = [
            ':param_username' => $this->username,
            ':param_ftl' => 'N'
        ];

        // Get query string
        $query_string = $this->sql_queries->updateFirstTimeLogin($this->account_type);

        // Execute query
        $store_result = $this->db->storeData($query_parameters, $query_string);

        return $store_result;
    }

    // Destroy session variables 
    public function destroySessionVar() {

        // Set session wrapper logger
        $this->session_wrapper->setLogger($this->logger);

        // Call methods to destroy session vairables
        $this->session_wrapper->unsetSessionVar('account_id');
        $this->session_wrapper->unsetSessionVar('username');
        $this->session_wrapper->unsetSessionVar('fname');
        $this->session_wrapper->unsetSessionVar('account_type');
        $this->session_wrapper->unsetSessionVar('is_logged_in');

    }
}