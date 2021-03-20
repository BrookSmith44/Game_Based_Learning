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

        // Get username and account_id saved in session variable
        $username = $this->session_wrapper->getSessionVar('username');

        $account_id = $this->session_wrapper->getSessionVar('account_id');

        // Reset query parameters for inserting team
        $query_parameters = [
            ':param_name' => $this->team_name,
            ':param_colour' => $this->colour,
            ':param_rating' => $this->skill_rating,
            ':param_id' => $account_id
        ];

        // Reset query string for inserting team
        $query_string = $this->sql_queries->insertTeam();

        // Insert team into team table
        $store_results['insert_team'] = $this->db->storeData($query_parameters, $query_string);

        // Set query parameter
        $query_parameters = [
            ':param_id' => $account_id
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

        // Set session variables
        $store_results['session_variables'] = $this->setTeamSessionVar($team_id['team_id'], $this->team_name, $this->colour, $this->skill_rating);
        
        // Check all data was stored successfully
        if(count(array_unique($store_results)) === 1) {
            $store_result = current($store_results);
        }

        // Return storage results
        return $store_result;
    }

    // Method to set team session variables
    public function setTeamSessionVar($team_id, $team_name, $colour, $rating) {
        // create empty variable for store results
        $store_results = [];

        $this->session_wrapper->setLogger($this->logger);

        // Set session variables
        $store_results['team_id'] = $this->session_wrapper->setSessionVar('team_id', $team_id);
        $store_results['team_name'] = $this->session_wrapper->setSessionVar('team_name', $team_name);
        $store_results['colour'] = $this->session_wrapper->setSessionVar('colour', $colour);
        $store_results['rating'] = $this->session_wrapper->setSessionVar('rating', $rating);

        // Check all data was stored successfully
        if(count(array_unique($store_results)) === 1) {
            $store_result = current($store_results);
        }

        return $store_result;
    }

    // Method to get session variables
    public function getTeamSessionVar() {
        // set empty array to store session variables
        $session_variables = [];

        $this->session_wrapper->setLogger($this->logger);

        $session_variables['team_id'] = $this->session_wrapper->getSessionVar('team_id');
        $session_variables['team_name'] = $this->session_wrapper->getSessionVar('team_name');
        $session_variables['colour'] = $this->session_wrapper->getSessionVar('colour');
        $session_variables['rating'] = $this->session_wrapper->getSessionVar('rating');

        return $session_variables;
    }

    public function getTeamData() {
        // Set empty array for results
        $results = [];

        $this->connect();

        // Get team id
        $session_variables = $this->getTeamSessionVar();

        // Set query parameter
        $query_parameters = [
            ':param_id' => $session_variables['team_id']
        ];
        
        // query to get team data
        $query_string = $this->sql_queries->getTeamData();

        // Call method to execute query
        $results = $this->db->getValues($query_parameters, $query_string);

        // Return results
        return $results;
    }
}