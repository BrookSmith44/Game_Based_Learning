<?php

/**
 * 
 * Route for ajax request to fetch
 * 
 */

// Get Request and Response
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response; 

$app->post('/getQuestions', function(Request $request, Response $response) use ($app) {
   // Get questions
   $questions_list = getQuestions($app);

   echo json_encode(array($questions_list));

});

function getQuestions($app) {
    // get containers
    $question_model = $app->getContainer()->get('questionModel');
    $db = $app->getContainer()->get('dbh');
    $settings = $app->getContainer()->get('settings');
    $connection_settings = $settings['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');

    // Set properties
    $question_model->setDb($db);
    $question_model->setDbConnectionSettings($connection_settings);
    $question_model->setSqlQueries($sql_queries);
    $question_model->setLogger($logger);

    // empty array for results
    $results = [];

    // Get teachers from database
    $results = $question_model->getAllQuestions();

    return $results;
}
