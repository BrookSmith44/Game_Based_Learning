<?php
/**
 * Class for teacher model
 * Handles anything to do with the teacher
 */

 namespace Model;

 class TeacherModel extends UserModel {
     // Properties
     private $admin;

     // Magic methods
     public function __construct() {
         $this->admin = null;
     }

     public function __destruct() {}

     // Setter methods
     public function setAdmin($admin) {
         $this->admin = $admin;
     }

     // Method to store teacher data
     public function storeTeacherData() {
         // Create empty variable for the store result
         $store_result = '';

         // Connect to the db
         $this->connect();

         // Set query parameters
         $query_parameters = [
            ':param_username' => $this->username,
            ':param_fname' => $this->fname,
            ':param_surname' => $this->surname,
            ':param_email' => $this->email,
            ':param_pass' => $this->password,
            ':param_ftl' => $this->first_time_login,
            ':param_da' => date("Y-m-d"),
            ':param_acc_type' => 'Teacher',
            ':param_admin' => $this->admin
         ];

         // Get query string
         $query_string = $this->sql_queries->insertTeacherAccount();

         // Execute query
         $store_result = $this->db->storeData($query_parameters, $query_string);

         return $store_result;
     }

     public function checkUsername() {
         // Create empty variable for username check
         $check_result = '';

         // Connect to database
         $this->connect();

         // Set query parameters
         $query_parameters = [
             ':param_username' => $this->username
         ];

         // Set query string from SQL Queries
         $query_string = $this->sql_queries->checkUsername();

         // Execute query
         $check_results = $this->db->getValues($query_parameters, $query_string);

         // Check all counts were returned as 0
        if ($check_results['count1'] == 0 && $check_results['count2'] == 0 && $check_results['count3'] == 0) {
            $check_result = 0;
        } else {
            $check_result = 1;
        }

         return $check_result;
     }
 }