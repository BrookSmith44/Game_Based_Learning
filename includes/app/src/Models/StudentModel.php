<?php

/**
 * 
 * Student class to handle any student data and proccesses
 * 
 */


namespace Model;

class StudentModel extends UserModel {
    // Properties
    private $teacher_name;

    // Magic Methods
    public function __construct() {
        $this->teacher_name = null;
    }

    public function __destruct() {}

    // Setter Methods
    public function setTeacherName($teacher_name) {
        $this->teacher_name = $teacher_name;
    }

    public function storeStudentData() {
        // Connect to database
        $this->connect();

        // Create empty store results variable
        $store_results = '';

        // Get teacher id
        $teacher_id = $this->session_wrapper->getSessionVar('account_id');

        // Create query parameters
        $query_parameters = [
            ':param_username' => $this->username,
            ':param_fname' => $this->fname,
            ':param_surname' => $this->surname,
            ':param_email' => $this->email,
            ':param_pass' => $this->password,
            ':param_ftl' => $this->first_time_login,
            ':param_da' => date("Y-m-d"),
            ':param_acc_type' => 'Student',
            ':param_teacher' => $this->teacher_name,
            ':param_id' => $teacher_id
        ];

        // $get query string to insert into student accounts table
        $query_string = $this->sql_queries->insertStudentAccount();

        // Execute query
        $store_result = $this->db->storeData($query_parameters, $query_string);

        return $store_result;
    }

    public function getData() {
        // Connect to database
        $this->connect();

        // Create empty array for results
        $results = [];

        // Set empty query parameters
        $query_parameters = [
            ':param_username' => $this->username
        ];

        // Get query string to get student data
        $query_string = $this->sql_queries->getStudent();

        // Execute queries
        $results = $this->db->getValues($query_parameters, $query_string);

        return $results;
    }

    public function getAllStudents() {
        // Connect to database
        $this->connect();

        // Create empty array for results
        $results = [];

        // Set empty query parameters
        $query_parameters = [];

        // Get query string to get all student data
        $query_string = $this->sql_queries->getAllStudents();

        // Execute queries
        $results = $this->db->getMultipleValues($query_parameters, $query_string);

        return $results;
    }

    public function getLeaderboard() {
        // Connect to database
        $this->connect();

        // Create empty array for results
        $results = [];

        // Get acccount id
        $account_id = $this->session_wrapper->getSessionVar('account_id');

        // Set query parameters
        $query_parameters = [
            ':param_id' => $account_id
        ];

        // get query string to get teacher id from database
        $query_string = $this->sql_queries->getTeacherId();

        // Execute query
        $results = $this->db->getValues($query_parameters, $query_string);

        // With the teacher id get all students with the same teacher id
        // Set query parameters
        $query_parameters = [
            ':param_id' => $results['teacher_id']
        ];

        // Set sql query to get all students who were added to the system by the same teacher
        $query_string = $this->sql_queries->getLeaderboard();

        // Execute query
        $results = $this->db->getMultipleValues($query_parameters, $query_string);

        return $results;
    }

    public function updateData($username_id) {
        // Connect to database
        $this->connect();

        // Create empty array for store results
        $store_result = [];

        // Set empty query parameters
        $query_parameters = [
            ':param_username' => $this->username,
            ':param_fname' => $this->fname,
            ':param_surname' => $this->surname,
            ':param_email' => $this->email,
            ':param_username_id' => $username_id
        ];

        // Get query string to update student data
        $query_string = $this->sql_queries->updateStudent();

        // Execute queries
        $store_result = $this->db->storeData($query_parameters, $query_string);

        return $store_result;
    }

    public function deleteData() {
        // Connect to database
        $this->connect();

        // Create empty array for store results
        $store_result = [];

        // Set empty query parameters
        $query_parameters = [
            ':param' => $this->username,
        ];

        // Get query string to delete student data
        $query_string = $this->sql_queries->deleteRow('student_accounts', 'account_username');

        // Execute queries
        $store_result = $this->db->storeData($query_parameters, $query_string);

        return $store_result;
    }
}