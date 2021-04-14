<?php
/**
 * get team data from database
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/getGameData', function(Request $request, Response $response) use ($app) {
    // Get post data
    $post_data = $request->getParsedBody();

    // call function to get team data from team class
    $team_data = getTeamData($app);

   // Set empty array for questions
   $questions = [];

    if ($post_data['subject'] !== 'General') {
      $questions = getRandomQuestions($app, $post_data['subject'], $post_data['difficulty']);
    } else {
      // call function to get questions from general questions class
      $questions = getGeneralQuestions($app, $post_data['difficulty']);
    }

    // call function to get commentary from commentary class
    $commentary = getCommentary($app);

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
  function getRandomQuestions($app, $subject, $difficulty) {
   // Get containers
   $questions_model = $app->getContainer()->get('questionModel');
   $db = $app->getContainer()->get('dbh');
   $db_config = $app->getContainer()->get('settings');
   $db_connection_settings = $db_config['pdo_settings'];
   $sql_queries = $app->getContainer()->get('sqlQueries');
   $logger = $app->getContainer()->get('logger');

   // Set empty text variable
   $questions = [];

   // Set question properties
   $questions_model->setDb($db);
   $questions_model->setDbConnectionSettings($db_connection_settings);
   $questions_model->setSqlQueries($sql_queries);
   $questions_model->setLogger($logger);

   // Set difficulty 
   $questions_model->setSubject($subject);
   $questions_model->setDifficulty($difficulty);

   // Get questions
   $questions['questions'] = $questions_model->getRandomQuestions();

   return $questions;
}

 // Function to get Questions 
 function getGeneralQuestions($app, $difficulty) {
    // Get containers
    $questions_container = $app->getContainer()->get('generalQuestions');

    // Set empty text variable
    $questions = [];

    // Set difficulty 
    $questions_container->setDifficulty($difficulty);

    // Get questions
    $questions['questions'] = $questions_container->getQuestions();

    return $questions;
 }

 // Function to get commentary
 function getCommentary($app) {
     // Get containers
    $commentary_container = $app->getContainer()->get('commentary');
    // Empty array to fill data with and return
    $commentary = [];

    // Get commentary
    $commentary['commentary'] = $commentary_container->getCommentary();

    return $commentary;
 }