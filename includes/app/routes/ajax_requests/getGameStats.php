<?php

// Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;
 
 $app->post('/getGameStats', function(Request $request, Response $response) use ($app) {
   $post_data = $request->getParsedBody();

   $game_stats = getGameStats($app, $post_data);
   
    echo json_encode(array($game_stats));

 })->setName('GetGameStats');

 function getGameStats($app, $post_data) {
    // Get containers
    $team_model = $app->getContainer()->get('teamModel');
    $db = $app->getContainer()->get('dbh');
    $settings = $app->getContainer()->get('settings');
    $db_connection_settings = $settings['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $logger = $app->getContainer()->get('logger');

    // Set team model properties
    $team_model->setDb($db);
    $team_model->setDbConnectionSettings($db_connection_settings);
    $team_model->setSessionWrapper($session_wrapper);
    $team_model->setSqlQueries($sql_queries);
    $team_model->setLogger($logger);

    // Empty array for game stats
    $game_stats =[];

    if ($post_data['account_type'] == 'player') {
      // Get game stats from db
      $game_stats = $team_model->getUserGameStats();
    } else if ($post_data['account_type'] == 'management') {
      // Get game stats from db
      $game_stats = $team_model->getStudentData($post_data['username']);
    }

    return $game_stats;
 }