<?php
/** 
 * SQL Queries Class
 */

 namespace Database;

 class SQLQueries {
     
    // Methods
    public function __construct() {}

    public function __destruct() {}

    public function getFname() {
        $query_string = "SELECT account_fname ";
        $query_string .= "FROM fb_tr_accounts ";
        $query_string .= "WHERE account_fname = :param_value ";
        return $query_string;
    }

    public function insertGeneralAccount() {
        $query_string = "INSERT INTO fb_tr_accounts";
        $query_string .= "(account_username, account_fname, account_surname, account_date_of_birth, account_email, ";
        $query_string .= "account_password, date_added, first_time_login, student, teacher, admin, general) ";
        $query_string .= "VALUES ";
        $query_string .= "( :param_username, :param_fname, :param_surname, :param_dob, :param_email, :param_pass, ";
        $query_string .= ":param_da, :param_ftl, :param_student, :param_teacher, :param_admin, :param_general) ";
    
        return $query_string;
    }
 }