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
        $query_string = "SELECT account_fname, account_surname, first_time_login, account_password, account_type FROM fb_tr_db.general_accounts WHERE account_username = :param_username ";
        $query_string .= "UNION ALL ";
        $query_string .= "SELECT account_fname, account_surname, first_time_login, account_password, account_type  FROM fb_tr_db.student_accounts WHERE account_username = :param_username ";
        $query_string .= "UNION ALL ";
        $query_string .= "SELECT account_fname, account_surname, first_time_login, account_password, account_type  FROM fb_tr_db.teacher_accounts WHERE account_username = :param_username ";
    
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

    public function insertStudentAccount() {
        $query_string = "INSERT INTO student_accounts";
        $query_string .= "(account_username, account_fname, account_surname, account_email, ";
        $query_string .= "account_password, date_added, first_time_login, account_type, teacher_name, teacher_id) ";
        $query_string .= "VALUES ";
        $query_string .= "( :param_username, :param_fname, :param_surname, :param_email, :param_pass, ";
        $query_string .= ":param_da, :param_ftl, :param_acc_type, :param_teacher, :param_id) ";
    
        return $query_string;
    }

    public function insertQuestion() {
        $query_string = 'INSERT INTO questions ';
        $query_string .= '(question, choice1, choice2, choice3, choice4, answer, difficulty, subject, teacher_name, teacher_id, date_added) ';
        $query_string .= 'VALUES ';
        $query_string .= '(:param_question, :param_choice1, :param_choice2, :param_choice3, :param_choice4, :param_answer, :param_difficulty,  ';
        $query_string .= ':param_subject, :param_teacher, :param_id, :param_da)';

        return $query_string;
    }

    public function updateAccountData($account_type) {
        // Set empty table variable
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
        }

        $query_string = 'UPDATE ' . $table_name . ' SET account_username = :param_username, account_fname = :param_fname, account_surname = :param_surname, ';
        $query_string .= 'account_email = :param_email WHERE account_id = :param_id';

        return $query_string;
    }

    public function getAccountData($account_type) {
        // Set empty table variable
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
        }
        $query_string = 'SELECT account_username, account_fname, account_surname, account_email FROM ' . $table_name . ' WHERE account_id = :param_id';

        return $query_string;
    }

    public function countQuestions() {
        $query_string = 'SELECT COUNT(*) FROM questions WHERE subject = :param_subject';

        return $query_string;
    }

    public function getQuestion() {
        $query_string = 'SELECT question_id, question, choice1, choice2, choice3, choice4, answer, difficulty, subject, teacher_name, date_added FROM questions WHERE question_id = :param_id ';

        return $query_string;
    }

    public function getAllQuestions() {
        $query_string = 'SELECT question_id, question, choice1, choice2, choice3, choice4, answer, difficulty, subject, teacher_name, date_added FROM questions ';

        return $query_string;
    }

    public function getRandomQuestion() {
        $query_string = 'SELECT question_id, question, choice1, choice2, choice3, choice4, answer FROM questions ';
        $query_string .= 'WHERE difficulty = :param_difficulty AND subject = :param_subject ';
        $query_string .= 'ORDER BY RAND() LIMIT 1';

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
        $query_string .= '(team_name, team_colour, skill_rating, username) ';
        $query_string .= 'VALUES ';
        $query_string .= '(:param_name, :param_colour, :param_rating, :param_username) ';

        return $query_string;
    }

    public function updateTeam() {
        $query_string = 'UPDATE team SET team_name = :param_name, team_colour = :param_colour WHERE team_id = :param_id';

        return $query_string;
    }

    public function updateTeamForeignKey() {
        $query_string = 'UPDATE team SET username = :param_username WHERE team_id = :param_id';

        return $query_string;
    }

    public function getTeamID() {
        $query_string = 'SELECT team_id FROM team WHERE username = :param_username ';

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
        $query_string .= 'JOIN fb_tr_db.team ON ' . $table_name . '.account_username = team.username ';
        $query_string .= 'JOIN fb_tr_db.game_statistics ON team.team_id = game_statistics.team_id ';
        $query_string .= 'WHERE ' . $table_name . '.account_username = :param_username ';
        
        return $query_string;
    }

    public function insertGameStats($table) {
        $query_string = 'INSERT INTO ' . $table . ' ';
        $query_string .= '(games_played, games_won, games_drawn, games_lost, goals_scored, goals_conceded, questions_answered, questions_correct, team_id) ';
        $query_string .= 'VALUES ';
        $query_string .= '(:param_played, :param_won, :param_drawn, :param_lost, :param_scored, :param_conceded, :param_questions, :param_correct, :param_id) ';

        return $query_string;
    }

    public function updateGameStats($table) {
        $query_string = 'UPDATE ' . $table . ' ';
        $query_string .= 'SET games_played = games_played + :param_played, games_won = games_won + :param_won, games_drawn = games_drawn + :param_drawn, ';
        $query_string .= 'games_lost = games_lost + :param_lost, goals_scored = goals_scored + :param_scored, goals_conceded = goals_conceded + :param_conceded, ';
        $query_string .= 'questions_answered = questions_answered + :param_questions, questions_correct = questions_correct + :param_correct ';
        $query_string .= 'WHERE team_id = :param_id';

        return $query_string;
    }

    public function increaseSkillRating() {
        $query_string = 'UPDATE team ';
        $query_string .= 'SET skill_rating = skill_rating + :param_change ';
        $query_string .= 'WHERE team_id = :param_id';

        return $query_string;
    }

    public function decreaseSkillRating() {
        $query_string = 'UPDATE team ';
        $query_string .= 'SET skill_rating = skill_rating - :param_change ';
        $query_string .= 'WHERE team_id = :param_id';

        return $query_string;
    }

    public function checkColumnExists($table) {
        $query_string = 'SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "' .  $table. '" AND COLUMN_NAME = :param_subject';

        return $query_string;
    }

    public function addGameStatsColumn($table, $column1, $column2) {
        $query_string = 'ALTER TABLE ' . $table . ' '; 
        $query_string .= 'ADD COLUMN ' . $column1 . ' VARCHAR(45) NULL AFTER questions_correct, ';
        $query_string .= 'ADD COLUMN ' . $column2 . ' VARCHAR(45) NULL AFTER ' . $column1 . '; ';
        $query_string .= 'UPDATE ' . $table . ' SET ' . $column1 . ' = 0, ';
        $query_string .= $column2 . ' = 0 WHERE team_id = :param_id';

        return $query_string;
    }

    public function checkSubjectNull($table, $column1, $column2) {
        $query_string = 'SELECT ' . $column1 . ', ' . $column2 . ' FROM ' . $table . ' WHERE team_id = :param_id'; 

        return $query_string;
    }

    public function updateToDefault($table, $column1, $column2) {
        $query_string = 'UPDATE ' . $table . ' SET ' . $column1 . ' = 0, ';
        $query_string .= $column2 . ' = 0 WHERE team_id = :param_id';

        return $query_string;
    }

    public function updateSubjectStats($table, $column1, $column2) {
        $query_string = 'UPDATE ' . $table . ' SET ' . $column1 . ' = ' . $column1 . ' + :param_questions, ';
        $query_string .= $column2 . ' = ' . $column2 . ' + :param_correct WHERE team_id = :param_id ';

        return $query_string;
    }

    public function getGameStats($table) {
        $query_string = 'SELECT * FROM ' . $table . '_statistics WHERE team_id = :param_id ';

        return $query_string;
    }

    public function getTeacherId() {
        $query_string = 'SELECT teacher_id FROM student_accounts WHERE account_id = :param_id';

        return $query_string;
    }

    public function getLeaderboard() {
        $query_string = 'SELECT student_accounts.account_id, student_accounts.account_fname, student_accounts.account_surname, student_accounts.teacher_id,  team.skill_rating, team.team_name FROM student_accounts ';
        $query_string .= 'JOIN team ON student_accounts.account_username = team.username WHERE teacher_id = :param_id ORDER BY team.skill_rating DESC LIMIT 10';

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

    public function getTeacher() {
        $query_string = 'SELECT account_id, account_username, account_fname, account_surname, account_email, date_added, admin ';
        $query_string .= 'FROM teacher_accounts WHERE account_username = :param_username';

        return $query_string;
    }

    public function getStudent() {
        $query_string = 'SELECT account_id, account_username, account_fname, account_surname, account_email, date_added, teacher_name ';
        $query_string .= 'FROM student_accounts WHERE account_username = :param_username';

        return $query_string;
    }
    
    public function getAllStudents() {
        $query_string = 'SELECT account_id, account_username, account_fname, account_surname, account_email, date_added, teacher_name ';
        $query_string .= 'FROM student_accounts';

        return $query_string;
    }

    public function getSubjects() {
        $query_string = 'SELECT DISTINCT subject FROM questions WHERE difficulty = :param_difficulty';

        return $query_string;
    }

    public function getFirstTimeLogin($account_type) {
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

        $query_string = 'SELECT first_time_login FROM ' . $table_name . ' WHERE account_id = :param_id ';

        return $query_string;
    }

    public function updateStudent() {
        $query_string = 'UPDATE student_accounts SET account_username = :param_username, account_fname = :param_fname, account_surname = :param_surname, account_email = :param_email ';
        $query_string .= 'WHERE account_username = :param_username_id';
    }

    public function updateTeacher() {
        $query_string = 'UPDATE teacher_accounts SET account_username = :param_username, account_fname = :param_fname, account_surname = :param_surname, account_email = :param_email, admin = :param_admin ';
        $query_string .= 'WHERE account_username = :param_username_id';

        return $query_string;
    }

    public function updateQuestion() {
        $query_string = 'UPDATE questions SET question = :param_question, choice1 = :param_choice1, choice2 = :param_choice2, choice3 = :param_choice3, choice4 = :param_choice4, ';
        $query_string .= 'answer = :param_answer, difficulty = :param_difficulty, subject = :param_subject ';
        $query_string .= 'WHERE question_id = :param_id';

        return $query_string;
    }

    public function deleteRow($table_name,  $column) {
        $query_string = 'DELETE FROM ' . $table_name . ' WHERE ' . $column . ' = :param ';

        return $query_string;
    }
 }