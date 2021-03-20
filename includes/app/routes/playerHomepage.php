<?php
/**
 * Player Homepage Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/playerHomepage', function(Request $request, Response $response) use ($app) {

  $team_model = $app->getContainer()->get('teamModel');

  $team_info = getTeamInfo($app);

  if(isset($_POST['same'])) {
    $test = $_POST['same'];

  echo $test;
  }

   return $this->view->render($response,
    'playerHomepage.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'page_heading' => 'Homepage',
        'team_name' => $team_info['team_name'],
        'rating' => $team_info['rating']
    ]);
 })->setName('PlayerHomepage');

 function getTeamInfo($app) {
   // Get containers
   $team_model = $app->getContainer()->get('teamModel');
   $session_wrapper = $app->getContainer()->get('sessionWrapper');
   $logger = $app->getContainer()->get('logger');

   // Set session wrapper
   $team_model->setSessionWrapper($session_wrapper);

   // Set team model logger
   $team_model->setLogger($logger);

   //Get session variables
   $team_info = $team_model->getTeamSessionVar();

   // Return team info
   return $team_info;
 }

 function getTeamDataDb() {
  // get containers
  $db = $app->getContainer()->get('dbh');
  $team_model = $app->getContainer()->get('teamModel');
  $session_wrapper = $app->getContainer()->get('sessionWrapper');
  $sql_queries = $app->getContainer()->get('sqlQueries');
  $logger = $app->getContainer()->get('logger');
  $db_config = $app->getContainer()->get('settings');
  $db_connection_settings = $db_config['pdo_settings'];

  // Empty string for store result
  $store_result = '';

  $team_model->setDbConnectionSettings($db_connection_settings);
  $team_model->setDb($db);
  $team_model->setSQLQueries($sql_queries);
  $team_model->setSessionWrapper($session_wrapper);
  $team_model->setLogger($logger);

  $results = $team_model->getTeamData();
  
  return $results;
 }