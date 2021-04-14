<?php

/**
 * 
 * Model class to handle interactions with the questions
 * 
 */

 namespace Model;

 class QuestionModel {
     // Properties
     private $question_id;
     private $question;
     private $choice1;
     private $choice2;
     private $choice3;
     private $choice4;
     private $answer;
     private $subject;
     private $difficulty;
     private $teacher_name;
     private $teacher_id;
     private $db_connection_settings;
     private $db;
     private $sql_queries;
     private $logger;
    
    // Magic methods
    public function __construct() {
        $this->question_id = null;
        $this->question = null;
        $this->choice1 = null;
        $this->choice2 = null;
        $this->choice3 = null;
        $this->choice4 = null;
        $this->answer = null;
        $this->subject = null;
        $this->difficulty = null;
        $this->teacher_name = null;
        $this->teacher_id = null;
        $this->db_connection_settings = null;
        $this->db = null;
        $this->sql_queries = null;
        $this->logger = null;
    }

    public function __destruct() {}
 
    // Setter Methdods
    public function setQuestionId($question_id) {
        $this->question_id = $question_id;
    }

    
    public function setQuestion($question) {
        $this->question = $question;
    }

    public function setChoice1($choice1) {
        $this->choice1 = $choice1;
    }

    public function setChoice2($choice2) {
        $this->choice2 = $choice2;
    }

    public function setChoice3($choice3) {
        $this->choice3 = $choice3;
    }

    public function setChoice4($choice4) {
        $this->choice4 = $choice4;
    }

    public function setAnswer($answer) {
        $this->answer = $answer;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setDifficulty($difficulty) {
        $this->difficulty = $difficulty;
    }

    public function setTeacherName($teacher_name) {
        $this->teacher_name = $teacher_name;
    }

    public function setTeacherId($teacher_id) {
        $this->teacher_id = $teacher_id;
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

    // Method to connect to database
    public function connect() {
        $this->db->setSQLQueries($this->sql_queries);
        $this->db->setDbConnectionSettings($this->db_connection_settings);
        $this->db->setLogger($this->logger);
        $this->db->makeDbConnection();
    }

    public function storeQuestionData() {
        // Connect to database
        $this->connect();

        // Create empty variable for store results
        $store_result = '';

        // Set session wrapper logger
        $this->session_wrapper->setLogger($this->logger);

        // Get teacher data from session
        // Get first and last name from session variables
        $fname = $this->session_wrapper->getSessionVar('fname');
        $surname = $this->session_wrapper->getSessionVar('surname');
        // Set teacher name property
        $this->teacher_name = $fname . ' ' . $surname;
        // Get account id of teacher from session variables
        $this->teacher_id = $this->session_wrapper->getSessionVar('account_id');

        // Set query parameters
        $query_parameters = [
            ':param_question' => $this->question,
            ':param_choice1' => $this->choice1,
            ':param_choice2' => $this->choice2,
            ':param_choice3' => $this->choice3,
            ':param_choice4' => $this->choice4,
            ':param_answer' => $this->answer,
            ':param_difficulty' => $this->difficulty,
            ':param_subject' => $this->subject, 
            ':param_teacher' => $this->teacher_name,
            ':param_da' => date("Y-m-d"), 
            ':param_id' => $this->teacher_id,  
        ];

        // Get query string to insert into questions table
        $query_string = $this->sql_queries->insertQuestion();

        // Execute query
        $store_result = $this->db->storeData($query_parameters, $query_string);

        return $store_result;
    }

    // Method to get the subjects
    public function getSubjects() {
        // Connect
        $this->connect();

        // Set empty array for results
        $results = [];

        // Set empty parameters
        $query_parameters = [
            ':param_difficulty' => $this->difficulty
        ];

        // get sql query
        $query_string = $this->sql_queries->getSubjects();

        // Execute query
        $results = $this->db->getMultipleValues($query_parameters, $query_string);

        // return results
        return $results;
    }

    // method to check how many questions are in each subject
    public function countQuestions() {
        // Connect
        $this->connect();

        // Set empty array for results
        $results = [];

        // Set empty parameters
        $query_parameters = [
            ':param_subject' => $this->subject
        ];

        // get sql query
        $query_string = $this->sql_queries->countQuestions();

        // Execute query
        $results = $this->db->getValues($query_parameters, $query_string);

        // return results
        return $results;
    }

    public function getData() {
        // Connect to database
        $this->connect();

        // Create empty array for results
        $results = [];

        // Set empty query parameters
        $query_parameters = [
            ':param_id' => $this->question_id
        ];

        // Get query string to get question data
        $query_string = $this->sql_queries->getQuestion();

        // Execute queries
        $results = $this->db->getValues($query_parameters, $query_string);

        return $results;
    }

    // Method to retrieve all questions from database
    public function getAllQuestions() {
        // Connect
        $this->connect();

        // Set empty array for results
        $results = [];

        // Set empty parameters
        $query_parameters = [];

        // get sql query
        $query_string = $this->sql_queries->getAllQuestions();

        // Execute query
        $results = $this->db->getMultipleValues($query_parameters, $query_string);

        // return results
        return $results;
    }

    public function getRandomQuestions() {
        // connect to database
        $this->connect();

        // Empty array for results
        $results = [];

        // Create empty array for used question ids
        $used_ids = [];

        // Create empty array for randomized questions
        $randomized_questions = [];

        // Create empty query parameters
        $query_parameters = [
            ':param_subject' => $this->subject,
            ':param_difficulty' => $this->difficulty,
        ];

        // Get query string to search for random questions
        $query_string = $this->sql_queries->getRandomQuestion();

        $i = 0;

        while ($i < 25) {
            // Get random question from database
            $results = $this->db->getValues($query_parameters, $query_string);

            // Check to see if question has already been stored
            if (!in_array($results['question_id'], $used_ids)) {
                // Store account id in used array
                array_push($used_ids, $results['question_id']);

                // Create object for question
                $question = [
                    'question' => $results['question'],
                    'choices' => [
                       'choice1' => $results['choice1'],
                       'choice2' => $results['choice2'],
                       'choice3' => $results['choice3'],
                       'choice4' => $results['choice4']
                    ],
                    'answer' => $results['answer']
                   ];

                   // push object into array
                   array_push($randomized_questions, $question);

                   // Iterate counter
                   $i++;
            }
        }

        return $randomized_questions;
    }

}