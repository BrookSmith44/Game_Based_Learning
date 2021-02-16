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
 }