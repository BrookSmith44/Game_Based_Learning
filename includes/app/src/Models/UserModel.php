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
     protected $team_exists;
     private $changed_pass;
     private $results;
     private $account_type;
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
        $this->account_type = null;
        $this->first_time_login = null;
        $this->team_exists = null;
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

    public function setChangedPassword($changed_pass) {
        $this->changed_pass = $changed_pass;
    }

    public function setAccountType($account_type) {
        $this->account_type = $account_type;
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
        $query_string = $this->sql_queries->getAccountID($this->account_type);

        // Execute query
        $results = $this->db->getValues($query_parameters, $query_string);

        // Set session wrapper logger
        $this->session_wrapper->setLogger($this->logger);

        // Call methods to create session vairables
        $store_results['is_logged_in'] = $this->session_wrapper->setSessionVar('is_logged_in', 'Y');
        $store_results['account_id'] = $this->session_wrapper->setSessionVar('account_id', $results['account_id']);
        $store_results['username'] = $this->session_wrapper->setSessionVar('username', $this->username);
        $store_results['fname'] = $this->session_wrapper->setSessionVar('fname', $this->fname);
        $store_results['surname'] = $this->session_wrapper->setSessionVar('surname', $this->surname);
        $store_results['account_type'] = $this->session_wrapper->setSessionVar('account_type', $this->account_type);

        if ($this->account_type == 'Teacher') {
            $store_results['admin'] = $this->session_wrapper->setSessionVar('admin', $results['admin']);
        }

        // Check all session variables were stored successfully
        if(count(array_unique($store_results)) === 1) {
            $store_result = current($store_results);
        }

        // Return store result
        return $store_result;
    }

    // Method to get session data 
    public function getSessionVar() {
        // Empty array for session data
        $session_data = [];
        // Set session wrapper logger
        $this->session_wrapper->setLogger($this->logger);

        // Call methods to create session vairables
        $session_data['is_logged_in'] = $this->session_wrapper->getSessionVar('is_logged_in');
        $session_data['account_id'] = $this->session_wrapper->getSessionVar('account_id');
        $session_data['username'] = $this->session_wrapper->getSessionVar('username');
        $session_data['fname'] = $this->session_wrapper->getSessionVar('fname');
        $session_data['surname'] = $this->session_wrapper->getSessionVar('surname');
        $session_data['account_type'] = $this->session_wrapper->getSessionVar('account_type');

        return $session_data;
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
                case 'General' ;
                // General Account
                    // Send user to team details route
                    $redirect['page'] = 'TeamDetails';
                    // No error
                    $redirect['err'] = '';
                    break;
                case 'Student':
                // Teacher Account
                case 'Teacher':
                    if ($this->changed_pass == true) {
                        // Send user to team details route
                        $redirect['page'] = 'TeamDetails';
                        // No error
                        $redirect['err'] = '';
                    } else {
                        // Send user to change password route
                        $redirect['page'] = 'ChangePassword';
                        $redirect['err'] = 'passErr';
                    }
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
                    // Send user to management homepage route
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
        $authenticate_result = $this->authenticate($query_results['account_password']);

        // Check authentication - 
        $redirect = $this->processAuthentication($authenticate_result, $query_results);
        
        return $redirect;
    }

    // Method to authenticate login details
    public function authenticate($password) {
        // set empty array for decryption
        $decrypted_data = [];

        // Decrypt password 
        $decrypted_data['password'] = $this->libsodium_wrapper->decryption(
            $this->base64_wrapper,
            $password
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

            // Decrypt fname 
            $this->surname = $this->libsodium_wrapper->decryption(
            $this->base64_wrapper,
            $query_results['account_surname']
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
                    if ($this->first_time_login !== 'Y') {
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
                    } 
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

    // Method to process password update - make sure user enters old password correctly before changing password
    public function processPasswordUpdate($new_pass) {
        // connect to database
        $this->connect();

        // Empty array for store results
        $store_results = [];

        // get username and account type from session variable
        $this->username = $this->session_wrapper->getSessionVar('username');
        $account_type = $this->session_wrapper->getSessionVar('account_type');

        // Set query parameters
        $query_parameters = [
            ':param_username' =>  $this->username
        ];

        // get sql query string
        $query_string = $this->sql_queries->loginQuery();

        // Execute query
        $results = $this->db->getValues($query_parameters, $query_string);
        
        // Authenticate old password to all user to update new password
        $store_result['authenticate'] = $this->authenticate($results['account_password']);
        // Check if it is correct password
        if ($store_result['authenticate'] == true) {
        
            // Update password
            $store_result['update'] = $this->updatePassword($new_pass);
        }

        return $store_result;
    }


    // Method to change password in the database
    public function updatePassword($new_pass) {
        // get username and account type from session variable
        $this->username = $this->session_wrapper->getSessionVar('username');

        // Hash password
        $hashed_password = $this->bycrypt->createHashedPassword($new_pass);

        // encrypt password
        $encrypted_string['password_and_nonce'] = $this->libsodium_wrapper->encryption($hashed_password);
    
        // Encode string
        $encrypted_password = $this->base64_wrapper->encode($encrypted_string['password_and_nonce']['nonce_and_encrypted_string']);

        // Set query parameters
        $query_parameters = [
            ':param_username' => $this->username,
            ':param_password' => $encrypted_password
        ];

        // Get query string
        $query_string = $this->sql_queries->updatePassword($this->account_type);

        // Execute query
        $store_result = $this->db->storeData($query_parameters, $query_string);

        return $store_result;
    }

    // Destroy session variables 
    public function destroySessionVar() {

        // Set session wrapper logger
        $this->session_wrapper->setLogger($this->logger);

        // Get account type before unsetting it
        $account_type = $this->session_wrapper->getSessionVar('account_type');

        // Call methods to destroy session vairables
        $this->session_wrapper->unsetSessionVar('account_id');
        $this->session_wrapper->unsetSessionVar('username');
        $this->session_wrapper->unsetSessionVar('fname');
        $this->session_wrapper->unsetSessionVar('surname');
        $this->session_wrapper->unsetSessionVar('account_type');
        $this->session_wrapper->unsetSessionVar('is_logged_in');

        // If account type is not teacher then unset team session variables
        if ($account_type !== 'Teacher') {
            $this->session_wrapper->unsetSessionVar('team_name');
            $this->session_wrapper->unsetSessionVar('colour');
            $this->session_wrapper->unsetSessionVar('rating');
            $this->session_wrapper->unsetSessionVar('team_id');
        } else {
            $this->session_wrapper->unsetSessionVar('admin');
        }

    }

    // Method to get user details
    public function getUserDetails() {
        // Connect to database
        $this->connect();

        // Empty variable for store result
        $results = [];

        // Set logger for session wrapper
        $this->session_wrapper->setLogger($this->logger);

        // Get account id
        $account_id = $this->session_wrapper->getSessionVar('account_id');
        $account_type = $this->session_wrapper->getSessionVar('account_type');

        // Set query parameters
        $query_parameters = [
            ':param_id' => $account_id
        ];

        // Get query string
        $query_string = $this->sql_queries->getAccountData($account_type);

        // Execute query
        $results = $this->db->getValues($query_parameters, $query_string);

        return $results;
    }

    // Method to update the user details
    public function updateUserDetails() {
        // Connect to database
        $this->connect();

        // Empty variable for store result
        $store_result = [];

        // Set logger for session wrapper
        $this->session_wrapper->setLogger($this->logger);

        // Get account type and id
        $account_type = $this->session_wrapper->getSessionVar('account_type');
        $account_id = $this->session_wrapper->getSessionVar('account_id');

        // Set query parameters
        $query_parameters = [
            ':param_username' => $this->username,
            ':param_fname' => $this->fname,
            ':param_surname' => $this->surname,
            ':param_email' => $this->email,
            ':param_id' => $account_id
        ];

        // Get query string
        $query_string = $this->sql_queries->updateAccountData($account_type);
        

        // Execute query
        $store_result['update_account'] = $this->db->storeData($query_parameters, $query_string);

        // Update team
        // Get team id
        $team_id = $this->session_wrapper->getSessionVar('team_id');

        // Set query parameters
        $query_parameters = [
            ':param_username' => $this->username,
            ':param_id' => $team_id
        ];

        // Get query string
        $query_string = $this->sql_queries->updateTeamForeignKey();
        
        // Execute query
        $store_result['update_team'] = $this->db->storeData($query_parameters, $query_string);

        if ($store_result['update_account'] == true && $store_result['update_team'] == true) {
            $store_result = true;
        } else {
            $store_result = false;
        }

        return $store_result;
    }

    // Get first time login
    public function getFirstTimeLogin() {
        // Connect to database
        $this->connect();

        // Get username and account type from session variables
        $account_id = $this->session_wrapper->getSessionVar('account_id');
        $this->account_type = $this->session_wrapper->getSessionVar('account_type');

        // Set query parameters
        $query_parameters = [
            ':param_id' => $account_id
        ];

        // get query string to check if user has logged in or not
        $query_string = $this->sql_queries->getFirstTimeLogin( $this->account_type);

        // Execute query
        $results = $this->db->getValues($query_parameters, $query_string);

        $this->first_time_login = $results['first_time_login'];

        return $this->first_time_login;
    }

    public function deleteAccount() {
        // Connect to database
        $this->connect();

        // store results arrat
        $store_results = [];

        // get username and and team id
        $this->username = $this->session_wrapper->getSessionVar('username');
        $team_id = $this->session_wrapper->getSessionVar('team_id');
        $account_type = $this->session_wrapper->getSessionVar('account_type');


        // Delete game statistics first if account is student or general
        if ($account_type !== 'Teacher') {
            // Set query parameters
            $query_parameters = [
                ':param' => $team_id
            ];

            // Delete hard statistics
            // Get delete query
            $query_string = $this->sql_queries->deleteRow('hard_statistics', 'team_id');

            // Execute query
            $store_results['hard_statistics'] = $this->db->storeData($query_parameters, $query_string);

            // Delete medium statistics
            // Get delete query
            $query_string = $this->sql_queries->deleteRow('medium_statistics', 'team_id');

            // Execute query
            $store_results['medium_statistics'] = $this->db->storeData($query_parameters, $query_string);

            // Delete easy statistics
            // Get delete query
            $query_string = $this->sql_queries->deleteRow('easy_statistics', 'team_id');

            // Execute query
            $store_results['easy_statistics'] = $this->db->storeData($query_parameters, $query_string);

            // Delete all statistics
            // Get delete query
            $query_string = $this->sql_queries->deleteRow('game_statistics', 'team_id');

            // Execute query
            $store_results['game_statistics'] = $this->db->storeData($query_parameters, $query_string);

            // Set query parameters
            $query_parameters = [
                ':param' => $this->username
            ];

            // Delete team
            // Get delete query
            $query_string = $this->sql_queries->deleteRow('team', 'username');

            // Execute query
            $store_results['team'] = $this->db->storeData($query_parameters, $query_string);

            // Delete account
            switch($account_type) {
                case 'Student':
                    // Get delete query
                    $query_string = $this->sql_queries->deleteRow('student_accounts', 'account_username');
                    break;
                case 'General':
                    $query_string = $this->sql_queries->deleteRow('general_accounts', 'account_username');
                    break;
            }

            // Execute query
            $store_results['account'] = $this->db->storeData($query_parameters, $query_string);
        } else {

            // Set query parameters
            $query_parameters = [
                ':param' => $this->username
            ];
            // get delete teacher query
            $query_string = $this->sql_queries->deleteRow('teacher_accounts', 'account_username');

            // Execute query
            $store_results['account'] = $this->db->storeData($query_parameters, $query_string);
        }   

        // Check all session variables were stored successfully
        if(count(array_unique($store_results)) === 1) {
            $store_result = current($store_results);
        }

        // Return store result
        return $store_result;

    }
}