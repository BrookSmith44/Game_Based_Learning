<?php

/**
 * Model class for the team 
 */

 namespace FootballTriviaGame;

 class TeamModel {
    // Properties
    private $team_name;
    private $colour;
    private $skill_rating;
    private $db_connection_settings;
    private $db;
    private $sql_queries;
    private $session_wrapper;
    private $logger;
    private $games_played;
    private $games_won;
    private $games_lost;
    private $goals_scored;
 

    // Magic methods
    public function __construct() {
        $this->team_name = null;
        $this->colour = null;
        $this->skill_rating = null;
        $this->db_connection_settings = null;
        $this->db = null;
        $this->sql_queries = null;
        $this->session_wrapper = null;
        $this->logger = null;
        $this->games_played = null;
        $this->games_won = null;
        $this->games_lost = null;
        $this->goals_scored = null;
    }

    public function _destruct() {}

    // Setter methods
    public function setTeamName($team_name) {
        $this->team_name = $team_name;
    }

    public function setColour($colour) {
        $this->colour = $colour;
    }

    public function setSkillRating($skill_rating) {
        $this->skill_rating = $skill_rating;
    }

    public function setDbConnectionSettings($db_connection_settings) {
        $this->db_connection_settings = $db_connection_settings;
    }

    public function setDb($db) {
        $this->db = $db;
    }

    public function setSqlQueries($sql_queries) {
        $this->sql_queries = $sql_queries;
    }

    public function setSessionWrapper($session_wrapper) {
        $this->session_wrapper = $session_wrapper;
    }

    public function setLogger($logger) {
        $this->logger = $logger;
    }

    public function setGamesPlayed($games_played) {
        $this->games_played = $games_played;
    }

    public function setGamesWon($games_won) {
        $this->games_won = $games_won;
    }

    public function setGamesLost($games_lost) {
        $this->games_lost = $games_lost;
    }

    public function setGoalsScored($goals_scored) {
        $this->goals_scored = $goals_scored;
    }

    // Method to connect to database
    public function connect() {
        $this->db->setSQLQueries($this->sql_queries);
        $this->db->setDbConnectionSettings($this->db_connection_settings);
        $this->db->setLogger($this->logger);
        $this->db->makeDbConnection();
    }

    // Method to create the team
    public function createTeam() {
        // Connect to database
        $this->connect();

        // Get username saved in session variable
        $username = $this->session_wrapper->getSessionVar('username');

        // Set query parameter
        $query_parameters = [
            ':param_username' => $username
        ];

        // Get query for account id from sql class
        $query_string = $this->sql_queries->getAccountID();

        // Get account id to set as foreign key for team
        $user_id = $this->db->getValues($query_parameters, $query_string);

        // Reset query parameters for inserting team
        $query_parameters = [
            ':param_name' => $this->team_name,
            ':param_colour' => $this->colour,
            ':param_rating' => $this->skill_rating,
            ':param_id' => $user_id['account_id']
        ];

        // Reset query string for inserting team
        $query_string = $this->sql_queries->insertTeam();

        // Insert team into team table
        $store_results['insert_team'] = $this->db->storeData($query_parameters, $query_string);

        // Set query parameter
        $query_parameters = [
            ':param_id' => $user_id['account_id']
        ];
        
        // Reset query string to get team id
        $query_string = $this->sql_queries->getTeamID();

        $team_id = $this->db->getValues($query_parameters, $query_string);

        // Reset query parameters for inserting game stats
        $query_parameters = [
            ':param_played' => $this->games_played,
            ':param_won' => $this->games_won,
            ':param_lost' => $this->games_lost,
            ':param_scored' => $this->goals_scored,
            ':param_id' => $team_id['team_id']
        ];

        // Reset query string for inserting game stats
        $query_string = $this->sql_queries->insertGameStats();

        // Insert game stats into table
        $store_results['insert_stats'] = $this->db->storeData($query_parameters, $query_string);

        // Check all data was stored successfully
        if(count(array_unique($store_results)) === 1) {
            $store_result = current($store_results);
        }

        // Return storage results
        return $store_result;
    }
}