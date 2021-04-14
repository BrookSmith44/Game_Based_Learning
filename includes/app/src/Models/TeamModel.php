<?php

/**
 * Model class for the team 
 */

 namespace Model;

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
    private $subject;
    private $games_played;
    private $games_won;
    private $games_drawn;
    private $games_lost;
    private $goals_scored;
    private $goals_conceded;
    private $questions_answered;
    private $answers_correct;
    private $rating_change;
    private $difficulty;

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
        $this->subject = null;
        $this->games_played = null;
        $this->games_won = null;
        $this->games_drawn = null;
        $this->games_lost = null;
        $this->goals_scored = null;
        $this->goals_conceded = null;
        $this->questions_answered = null;
        $this->answers_correct = null;
        $this->rating_change = null;
        $this->difficulty = null;
    }

    public function _destruct() {
        
    }

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

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setGamesPlayed($games_played) {
        $this->games_played = $games_played;
    }

    public function setGamesWon($games_won) {
        $this->games_won = $games_won;
    }

    public function setGamesDrawn($games_drawn) {
        $this->games_drawn = $games_drawn;
    }

    public function setGamesLost($games_lost) {
        $this->games_lost = $games_lost;
    }

    public function setGoalsScored($goals_scored) {
        $this->goals_scored = $goals_scored;
    }

    public function setGoalsConceded($goals_conceded) {
        $this->goals_conceded = $goals_conceded;
    }

    public function setQuestionsAnswered($questions_answered) {
        $this->questions_answered = $questions_answered;
    }

    public function setAnswersCorrect($answers_correct) {
        $this->answers_correct = $answers_correct;
    }

    public function setRatingChange($rating_change) {
        $this->rating_change = $rating_change;
    }

    public function setDifficulty($difficulty) {
        $this->difficulty = $difficulty;
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
        $this->username = $this->session_wrapper->getSessionVar('username');

        // Reset query parameters for inserting team
        $query_parameters = [
            ':param_name' => $this->team_name,
            ':param_colour' => $this->colour,
            ':param_rating' => $this->skill_rating,
            ':param_username' => $this->username
        ];

        // Reset query string for inserting team
        $query_string = $this->sql_queries->insertTeam();

        // Insert team into team table
        $store_results['insert_team'] = $this->db->storeData($query_parameters, $query_string);

        // Set query parameter
        $query_parameters = [
            ':param_username' => $this->username
        ];
        
        // Reset query string to get team id
        $query_string = $this->sql_queries->getTeamID();

        // Query db to retrieve team id
        $results = $this->db->getValues($query_parameters, $query_string);

        // Reset query parameters for inserting game stats
        $query_parameters = [
            ':param_played' => $this->games_played,
            ':param_won' => $this->games_won,
            ':param_drawn' => $this->games_drawn,
            ':param_lost' => $this->games_lost,
            ':param_scored' => $this->goals_scored,
            ':param_conceded' => $this->goals_conceded,
            ':param_questions' => $this->questions_answered,
            ':param_correct' => $this->answers_correct,
            ':param_id' => $results['team_id']
        ];

        // Create array for table names
        $tables = ['game_statistics', 'easy_statistics', 'medium_statistics', 'hard_statistics'];

        // Loop through array and insert game stats
        for ($i = 0; $i < count($tables); $i++) {
            // Reset query string for inserting game stats
            $query_string = $this->sql_queries->insertGameStats($tables[$i]);

            // Insert game stats into table
            $store_results['insert_stats' . $i] = $this->db->storeData($query_parameters, $query_string);
        }

        // Set session variables
        $store_results['session_variables'] = $this->setTeamSessionVar();
        
        // Check all data was stored successfully
       if(count(array_unique($store_results)) === 1) {
            $store_result = current($store_results);
        }

        // Return storage results
        return $store_result;
    }

    // Method to update team details
    public function updateTeam() {
        // Connect to database
        $this->connect();

        // empty variable for store error
        $store_result = [];

        // Set session logger
        $this->session_wrapper->setLogger($this->logger);

        // Get team id 
        $team_id = $this->session_wrapper->getSessionVar('team_id');

        // Set query parameters
        $query_parameters = [
            ':param_name' => $this->team_name,
            ':param_colour' => $this->colour,
            ':param_id' => $team_id
        ];

        // Get query string to update database
        $query_string = $this->sql_queries->updateTeam();

        // Execute query
        $store_result = $this->db->storeData($query_parameters, $query_string);

        // Check data has been stored successfully, then update session variables
        if ($store_result == true) {
            // Reset session variables
            $this->session_wrapper->setSessionVar('team_name', $this->team_name);
            $this->session_wrapper->setSessionVar('colour', $this->colour);
        }

        return $store_result;
    }

    // Method to update the game statistics
    public function updateGameStats() {
        // Connect to database
        $this->connect();

        // Empty store result
        $store_result = [];

        $team_id = $this->session_wrapper->getSessionVar('team_id');

        // Set query paramameters
        $query_parameters = [
            ':param_played' => $this->games_played,
            ':param_won' => $this->games_won,
            ':param_drawn' => $this->games_drawn,
            ':param_lost' => $this->games_lost,
            ':param_scored' => $this->goals_scored,
            ':param_conceded' => $this->goals_conceded,
            ':param_questions' => $this->questions_answered,
            ':param_correct' => $this->answers_correct,
            ':param_id' => $team_id
        ];

        // Create column names
        $column1 = strtolower($this->subject . '_answered');
        $column2 = strtolower($this->subject . '_correct');

        // Set query paramameters
        $query_parameters1 = [
            ':param_subject' => $column1,
        ];

        // Set query paramameters
        $query_parameters2 = [
            ':param_id' => $team_id
        ];

        // Set query paramameters
        $query_parameters3 = [
            ':param_questions' => $this->questions_answered,
            ':param_correct' => $this->answers_correct,
            ':param_id' => $team_id
        ];

        // Update specific difficulty table
        $difficulty_table = strtolower($this->difficulty . '_statistics');

        // Empty variable for table name
        $table = ['game_statistics', $difficulty_table];

        for ($i = 0; $i < count($table); $i++) {
            // get query string
            $query_string = $this->sql_queries->updateGameStats($table[$i]);

            // execute query
            $store_result['first_update' . $i] = $this->db->storeData($query_parameters, $query_string);

            // get query string
            $query_string = $this->sql_queries->checkColumnExists($table[$i]);

            // execute query
            $column_exist = $this->db->getValues($query_parameters1, $query_string);

            // If statement to check if column exists - if not add column
            if ($column_exist['COUNT(*)'] == 0) {
                // get query string
                $query_string = $this->sql_queries->addGameStatsColumn($table[$i], $column1, $column2);

                // execute query
                $store_result['add_columns' . $i] = $this->db->storeData($query_parameters2, $query_string);
            } else {
                // get query string
                $query_string = $this->sql_queries->checkSubjectNull($table[$i], $column1, $column2);

                // execute query
                $results = $this->db->getValues($query_parameters2, $query_string);


                if (($results[$column1] == null) || ($results[$column2] == null)) {
                    // get query string
                    $query_string = $this->sql_queries->updateToDefault($table[$i], $column1, $column2);

                    // execute query
                    $store_result['set_default' . $i] = $this->db->storeData($query_parameters2, $query_string);
                }
            }

            // Update game statistics
            // get query string
            $query_string = $this->sql_queries->updateSubjectStats($table[$i], $column1, $column2);

            // execute query
            $store_result['second_update' . $i] = $this->db->storeData($query_parameters3, $query_string);   
        }

        return $store_result;
    }

    public function updateSkillRating() {
        // Connect to database
        $this->connect();

        // Set logger for session wrapper
        $this->session_wrapper->setLogger($this->logger);

        // Empty store result
        $store_result = '';

        $team_id = $this->session_wrapper->getSessionVar('team_id');

        // Set query paramameters
        $query_parameters = [
            ':param_change' => $this->rating_change,
            ':param_id' => $team_id
        ];

        // create empty query string
        $query_string = '';

        // Get current skill rating
        $current_rating = $session_variables['rating'] = $this->session_wrapper->getSessionVar('rating');

        if ($this->games_won == 1 || $this->games_lost == 1) {
            // check to see if user won or lost
            if ($this->games_won == 1) {
                // get query string
                $query_string = $this->sql_queries->increaseSkillRating();
                // Update rating 
                $new_rating = $current_rating + $this->rating_change;
                // New rating
                $store_results['rating'] = $this->session_wrapper->setSessionVar('rating', $new_rating);
            } else if ($this->games_lost == 1) {
                // get query string
                $query_string = $this->sql_queries->decreaseSkillRating();
                // Update rating 
                $new_rating = $current_rating - $this->rating_change;
                // New rating
                $store_results['rating'] = $this->session_wrapper->setSessionVar('rating', $new_rating);
            }

            // execute query
            $store_result = $this->db->storeData($query_parameters, $query_string);
        }

        return $store_result;
    }

    // Method to set team session variables
    public function setTeamSessionVar() {
        // create empty variable for store results
        $store_results = [];

        // Get team id
        $this->connect();

        // Set session logger for wrapper
        $this->session_wrapper->setLogger($this->logger);

        // Get user id
        $this->username = $this->session_wrapper->getSessionVar('username');

        // Set query paramameters
        $query_parameters = [
            ':param_username' => $this->username
        ];

        // Get query string
        $query_string = $this->sql_queries->getTeamID();

        $results = $this->db->getValues($query_parameters, $query_string);

        // Set session variables
        $store_results['team_id'] = $this->session_wrapper->setSessionVar('team_id', $results['team_id']);
        $store_results['team_name'] = $this->session_wrapper->setSessionVar('team_name', $this->team_name);
        $store_results['colour'] = $this->session_wrapper->setSessionVar('colour', $this->colour);
        $store_results['rating'] = $this->session_wrapper->setSessionVar('rating', $this->skill_rating);

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

        $session_variables['team_name'] = $this->session_wrapper->getSessionVar('team_name');
        $session_variables['colour'] = $this->session_wrapper->getSessionVar('colour');
        $session_variables['rating'] = $this->session_wrapper->getSessionVar('rating');

        return $session_variables;
    }

    public function getTeamData() {
        // Set empty array for results
        $results = [];

        $this->connect();

        // Set logger for session wrapper
        $this->session_wrapper->setLogger($this->logger);

        // Get account type and username from session variables
        $this->account_type = $this->session_wrapper->getSessionVar('account_type');
        $username = $this->session_wrapper->getSessionVar('username');

        // Set query parameter
        $query_parameters = [
            ':param_username' => $username
        ];
        
        // query to get team data
        $query_string = $this->sql_queries->getTeamData($this->account_type);

        // Call method to execute query
        $results = $this->db->getValues($query_parameters, $query_string);

        // Set class properties
        $this->team_name = $results['team_name'];
        $this->colour = $results['team_colour'];
        $this->skill_rating = $results['skill_rating'];

        // Return results
        return $results;
    }

    public function getGameStats() {
        // Set empty array for results
        $results = [];

        // Connect to the database
        $this->connect();

        // Set logger for session wrapper
        $this->session_wrapper->setLogger($this->logger);

        // Get team id from session variables
        $team_id = $this->session_wrapper->getSessionVar('team_id');;

        // set query parametersd
        $query_parameters = [
            ':param_id' => $team_id,
        ];

        $table = ['game', 'easy', 'medium', 'hard'];

        for ($i = 0; $i < count($table); $i++) {
            // Set query string 
            $query_string = $this->sql_queries->getGameStats($table[$i]);

            // Execute query
            $results[$table[$i]] = $this->db->getValues($query_parameters, $query_string);
        }

        return $results;
    }

    // Destroy session variables 
    public function destroySessionVar() {

        // Set session wrapper logger
        $this->session_wrapper->setLogger($this->logger);

        // Call methods to destroy session vairables
        $this->session_wrapper->unsetSessionVar('team_name');
        $this->session_wrapper->unsetSessionVar('colour');
        $this->session_wrapper->unsetSessionVar('rating');

    }
}