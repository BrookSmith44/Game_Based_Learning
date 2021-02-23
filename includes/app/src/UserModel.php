<?php
/**
 * User Model Class
 */

 namespace FootballTriviaGame;

 class UserModel {
     // Properties
     private $username;
     private $server_type;
     private $password;
     private $results;
     private $db_connection_settings;
     private $db;
     private $sql_queries;
 
    // Methods
    public function __construct() {
        $this->username = null;
        $this->server_type = null;
        $this->password = null;
        $this->results = null;
        $this->sql_queries = null;
        $this->db_connection_settings = null;
    }

    public function destruct() {}

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setServerType($server_type) {
        $this->server_type = $server_type;
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

    public function getData($param_value) {
        $this->db->setSQLQueries($this->sql_queries);
        $this->db->setDbConnectionSettings($this->db_connection_settings);
        $this->db->makeDbConnection();

        return $this->db->getValues($param_value);
    }
}