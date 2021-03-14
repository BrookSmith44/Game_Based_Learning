<?php

/**
 * Model class for the team 
 */

 class TeamModel {
    // Properties
    private $team_name;
    private $colour;
    private $db_connection_settings;
    private $db;
    private $sql_queries;
 

    // Magic methods
    public function __construct() {
        $this->team_name = null;
        $this->colour = null;
        $this->db_connection_settings = null;
        $this->db = null;
        $this->sql_queries = null;
    }

    public function _destruct() {}

    // Setter methods
    public function setTeamName($team_name) {
        $this->team_name = $team_name;
    }

    public function setColour($colour) {
        $this->colour = $colour;
    }

    public function setConnectionSettings($db_connection_settings) {
        $this->db_connection_settings = $db_connection_settings;
    }

    public function setDb($db) {
        $this->db = $db;
    }

    public function setSqlQueries($sql_queries) {
        $this->sql_queries = $sql_queries;
    }

    // Method to connect to database
    public function connect() {
        $this->db->setSQLQueries($this->sql_queries);
        $this->db->setDbConnectionSettings($this->db_connection_settings);
        $this->db->makeDbConnection();
    }

    
}