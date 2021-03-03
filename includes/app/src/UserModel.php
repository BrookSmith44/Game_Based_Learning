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
     private $db_connection_settings;
     private $db;
     private $sql_queries;
     private $session_wrapper;
     private $logger;
 
    // Methods
    public function __construct() {
        $this->username = null;
        $this->fname = null;
        $this->surname = null;
        $this->email = null;
        $this->dob = null;
        $this->password = null;
        $this->results = null;
        $this->sql_queries = null;
        $this->db_connection_settings = null;
        $this->session_wrapper = null;
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

    public function setLogger($logger) {
        $this->logger = $logger;
    }

    // Method to connect to database
    public function connect() {
        $this->db->setSQLQueries($this->sql_queries);
        $this->db->setDbConnectionSettings($this->db_connection_settings);
        $this->db->makeDbConnection();
    }

    // Method to store user account data
    public function signupStorage() {
        $this->connect();

        // Empty array for data to store
        $data_to_store = [];

        $data_to_store['username'] = $this->username;
        $data_to_store['fname'] = $this->fname;
        $data_to_store['surname'] = $this->surname;
        $data_to_store['dob'] = $this->dob;
        $data_to_store['email'] = $this->email;
        $data_to_store['pass'] = $this->password;
        $data_to_store['date_added'] = date("Y-m-d");
        $data_to_store['first_time_login'] = 'Y';
        $data_to_store['student'] = 'N';
        $data_to_store['teacher'] = 'N';
        $data_to_store['admin'] = 'N';
        $data_to_store['general'] = 'Y';

        // Call database handle to store data 
        $store_results = $this->db->storeData($data_to_store);

        if ($store_results === false) {
            // Get session data
            $session_data = $this->getSessionData();
            var_dump($session_data);
            $this->session_wrapper->setLogger($this->logger);
            $this->session_wrapper->setSessionVar('username', $this->username);
        }

        return $store_results;

    }

    public function getSessionData() {
        $this->connect();

        $store_results = $this->db->getValues($this->username);

        return $store_results;
    }
}