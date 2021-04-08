<?php
/**
 * get team data from database
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/getGameData', function(Request $request, Response $response) use ($app) {

    // call function to get team data from team class
    $team_data = getTeamData($app);

    // call function to get questions from general questions class
    $questions = getQuestions($app);

    // call function to get commentary from commentary class
    $commentary = getCommentary($app, );

    // Send data back to js in json format to easily split arrays
    echo json_encode(array($team_data, $questions, $commentary));

 });

 // Function to return team data
 function getTeamData($app) {
     // Get containers
     $team_model = $app->getContainer()->get('teamModel');
     $db = $app->getContainer()->get('dbh');
     $db_config = $app->getContainer()->get('settings');
     $db_connection_settings = $db_config['pdo_settings'];
     $sql_queries = $app->getContainer()->get('sqlQueries');
     $session_wrapper = $app->getContainer()->get('sessionWrapper');
     $logger = $app->getContainer()->get('logger');

     // Set team model properties
     $team_model->setDb($db);
     $team_model->setDbConnectionSettings($db_connection_settings);
     $team_model->setSqlQueries($sql_queries);
     $team_model->setSessionWrapper($session_wrapper);
     $team_model->setLogger($logger);

     $team_data = $team_model->getTeamData();

     return $team_data;
 }

 // Function to get Questions 
 function getQuestions($app) {
    // Get containers
    $questions_container = $app->getContainer()->get('generalQuestions');

    // Set empty text variable
    $questions = [];

    $questions['questions'] = $questions_container->getQuestions();

    return $questions;
 }

 // Function to get commentary
 function getCommentary($app) {
     // Get containers
    $commentary_container = $app->getContainer()->get('commentary');
    // Empty array to fill data with and return
    $commentary = [];

    $commentary['commentary'] = $commentary_container->getCommentary();
    /*// Get all types of commentary
    $commentary['possession'] = $commentary_container->possessionCommentary();
    $commentary['attacking'] = $commentary_container->attackingCommentary();
    $commentary['defending'] = $commentary_container->defendingCommentary();
    */

    return $commentary;
 }