<?php

/**
 * 
 * Route for ajax request to fetch
 * 
 */

// Get Request and Response
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response; 

$app->post('/getLeaderboard', function(Request $request, Response $response) use ($app) {

    // get session container
    $session_wrapper = $app->getContainer()->get('sessionWrapper');

    // Get account type from session variable
    $account_type = $session_wrapper->getSessionVar('account_type');

    if ($account_type == 'Student') {
        $leaderboard = getLeaderboard($app);

        echo json_encode(array($leaderboard));
    } else {
        $message['error'] = 'Only Student accounts can access leaderboard feature';
        echo json_encode(array($message));
    }

});

function getLeaderboard($app) {
    // get containers
    $student_model = $app->getContainer()->get('studentModel');
    $db = $app->getContainer()->get('dbh');
    $settings = $app->getContainer()->get('settings');
    $connection_settings = $settings['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $logger = $app->getContainer()->get('logger');

    // Set properties
    $student_model->setDb($db);
    $student_model->setDbConnectionSettings($connection_settings);
    $student_model->setSqlQueries($sql_queries);
    $student_model->setSessionWrapper($session_wrapper);
    $student_model->setLogger($logger);

    // empty array for results
    $results = [];

    // Get teachers from database
    $results = $student_model->getLeaderboard();

    $leaderboard = decryptLeaderboard($app, $results);

    return $leaderboard;
}

function decryptLeaderboard($app, $data) {
     // Get container
     $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
     $base64_wrapper = $app->getContainer()->get('base64Wrapper');
 
     // empty array for decrypted data
     $decrypted_data = [];

     // Create empty array for leaderboard
     $leaderboard = [];

     foreach($data as $student) {
        // Decrypt students info
        $decrypted_data['fname'] = $libsodium_wrapper->decryption(
            $base64_wrapper,
            $student['account_fname']
        );

        $decrypted_data['surname'] = $libsodium_wrapper->decryption(
        $base64_wrapper,
        $student['account_surname']
        );

        // Create variable to store name
        $name = $decrypted_data['fname'] . ' ' . $decrypted_data['surname'];
        
        // Create object
        $student_obj = [
            'name' => $name,
            'skill_rating' => $student['skill_rating'],
            'team_name' => $student['team_name']
        ];

        // Push object into array
        array_push($leaderboard, $student_obj);
     }

     return $leaderboard;
}