<?php
/** 
 * SQL Queries Class
 */

 namespace Database;

 class SQLQueries {
     
    // Methods
    public function __construct() {}

    public function __destruct() {}

    public function getSessionData() {
        $query_string = "SELECT account_username, account_fname, first_time_login ";
        $query_string .= "FROM general_accounts ";
        $query_string .= "WHERE account_username = :param_username ";
        return $query_string;
    }

    public function insertGeneralAccount() {
        $query_string = "INSERT INTO general_accounts";
        $query_string .= "(account_username, account_fname, account_surname, account_date_of_birth, account_email, ";
        $query_string .= "account_password, date_added, first_time_login, account_type) ";
        $query_string .= "VALUES ";
        $query_string .= "( :param_username, :param_fname, :param_surname, :param_dob, :param_email, :param_pass, ";
        $query_string .= ":param_da, :param_ftl, :param_acc_type) ";
    
        return $query_string;
    }

    public function loginQuery() {
        $query_string = "SELECT account_fname, first_time_login, account_password, account_type FROM fb_tr_db.general_accounts WHERE account_username = :param_username ";
        $query_string .= "UNION ALL ";
        $query_string .= "SELECT account_fname, first_time_login, account_password, account_type  FROM fb_tr_db.student_accounts WHERE account_username = :param_username ";
        $query_string .= "UNION ALL ";
        $query_string .= "SELECT account_fname, first_time_login, account_password, account_type  FROM fb_tr_db.teacher_accounts WHERE account_username = :param_username ";
    
        return $query_string;
    }

    public function insertTeacherAccount() {
        $query_string = "INSERT INTO teacher_accounts";
        $query_string .= "(account_username, account_fname, account_surname, account_email, ";
        $query_string .= "account_password, date_added, first_time_login, account_type, admin) ";
        $query_string .= "VALUES ";
        $query_string .= "( :param_username, :param_fname, :param_surname, :param_email, :param_pass, ";
        $query_string .= ":param_da, :param_ftl, :param_acc_type, :param_admin) ";
    
        return $query_string;
    }

    public function getAccountType() {
        $query_string = "SELECT first_time_login, account_type ";
        $query_string .= "FROM general_accounts ";
        $query_string .= "WHERE account_username = :param_username ";

        return $query_string;
    }

    public function checkUsername() {
        $query_string = "SELECT ( ";
        $query_string .= "SELECT COUNT(*) ";
        $query_string .= "FROM general_accounts ";
        $query_string .= "WHERE account_username = :param_username ";
        $query_string .= ") AS count1, ";
        $query_string .= "(SELECT COUNT(*) ";
        $query_string .= "FROM teacher_accounts ";
        $query_string .= "WHERE account_username = :param_username ";
        $query_string .= ") AS count2, ";
        $query_string .= "(SELECT COUNT(*) ";
        $query_string .= "FROM student_accounts ";
        $query_string .= "WHERE account_username = :param_username ";
        $query_string .= ") AS count3 ";

        return $query_string;
    }

    public function getAccountID($account_type) {
        // Empty string for table name
        $table_name = '';
        $admin = '';

        // Switch to decide which table to query
        switch ($account_type) {
            case 'General': 
                $table_name = 'general_accounts';
                break;
            case 'Student':
                $table_name = 'student_accounts';
                break;
            case 'Teacher':
                $table_name = 'teacher_accounts';
                $admin =', admin';
        }

        $query_string = 'SELECT account_id' . $admin . ' ';
        $query_string .= 'FROM ' . $table_name . ' ';
        $query_string .= 'WHERE account_username = :param_username ';

        return $query_string;
    }

    public function insertTeam() {
        $query_string = 'INSERT INTO team ';
        $query_string .= '(team_name, team_colour, skill_rating, user_id) ';
        $query_string .= 'VALUES ';
        $query_string .= '(:param_name, :param_colour, :param_rating, :param_id) ';

        return $query_string;
    }

    public function getTeamID() {
        $query_string = 'SELECT team_id FROM team WHERE user_id = :param_id ';

        return $query_string;
    }

    public function getTeamData($account_type) {
        // Empty string for table name
        $table_name = '';

        // Switch to decide which table to query
        switch ($account_type) {
            case 'General': 
                $table_name = 'general_accounts';
                break;
            case 'Student':
                $table_name = 'student_accounts';
                break;
        }

        $query_string = 'SELECT team_name, team_colour, skill_rating FROM ' . $table_name . ' ';
        $query_string .= 'JOIN fb_tr_db.team ON ' . $table_name . '.account_id = team.user_id ';
        $query_string .= 'JOIN fb_tr_db.game_statistics ON team.team_id = game_statistics.team_id ';
        $query_string .= 'WHERE ' . $table_name . '.account_username = :param_username ';
        
        return $query_string;
    }

    public function insertGameStats() {
        $query_string = 'INSERT INTO game_statistics ';
        $query_string .= '(games_played, games_won, games_lost, goals_scored, team_id) ';
        $query_string .= 'VALUES ';
        $query_string .= '(:param_played, :param_won, :param_lost, :param_scored, :param_id) ';

        return $query_string;
    }

    public function updateFirstTimeLogin($account_type) {
        // Empty string for table name
        $table_name = '';

        // Switch to decide which table to query
        switch ($account_type) {
            case 'General': 
                $table_name = 'general_accounts';
                break;
            case 'Student':
                $table_name = 'student_accounts';
                break;
            case 'Teacher':
                $table_name = 'teacher_accounts';
                break;
        }
        
        $query_string = 'UPDATE ' . $table_name . ' ';
        $query_string .= 'SET first_time_login = :param_ftl ';
        $query_string .= 'WHERE account_username = :param_username ';
    
        return $query_string;
    }

    public function updatePassword($account_type) {
        // Empty string for table name
        $table_name = '';

        // Switch to decide which table to query
        switch ($account_type) {
            case 'General': 
                $table_name = 'general_accounts';
                break;
            case 'Student':
                $table_name = 'student_accounts';
                break;
            case 'Teacher':
                $table_name = 'teacher_accounts';
                break;
        }
        
        $query_string = 'UPDATE ' . $table_name . ' ';
        $query_string .= 'SET account_password = :param_password ';
        $query_string .= 'WHERE account_username = :param_username ';
    
        return $query_string;
    }

    public function getAllTeachers() {
        $query_string = 'SELECT account_id, account_username, account_fname, account_surname, account_email, date_added ';
        $query_string .= 'FROM teacher_accounts';

        return $query_string;
    }
 }