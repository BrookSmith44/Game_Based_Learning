<?php

/**
 * 
 * Route for ajax request to fetch
 * 
 */

// Get Request and Response
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response; 

$app->post('/getStudents', function(Request $request, Response $response) use ($app) {
   // Get students
   $students_list = getStudents($app);

   // empty array for rows
   $rows = [];

   foreach ($students_list as $student) {
       // Decrypt students data from db
       $decrypted_data = decryptStudentList($app, $student);
       // process decrypted data into html

       array_push($rows, $decrypted_data);
   }

   echo json_encode(array($rows));

});

function getStudents($app) {
    // get containers
    $student_model = $app->getContainer()->get('studentModel');
    $db = $app->getContainer()->get('dbh');
    $settings = $app->getContainer()->get('settings');
    $connection_settings = $settings['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');

    // Set properties
    $student_model->setDb($db);
    $student_model->setDbConnectionSettings($connection_settings);
    $student_model->setSqlQueries($sql_queries);
    $student_model->setLogger($logger);

    // empty array for results
    $results = [];

    // Get teachers from database
    $results = $student_model->getAllStudents();

    return $results;
}

 // Function to decrypt students data
 function decryptStudentList($app, $student) {
    // Get container
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');

    // empty array for decrypted data
    $decrypted_data = [];

    // Insert data that does not need decrypting into array
    $decrypted_data['id'] = $student['account_id'];
    $decrypted_data['username'] = $student['account_username'];
    $decrypted_data['date_added'] = $student['date_added'];

    // Decrypt students info
    $decrypted_data['fname'] = $libsodium_wrapper->decryption(
        $base64_wrapper,
        $student['account_fname']
    );

    $decrypted_data['surname'] = $libsodium_wrapper->decryption(
       $base64_wrapper,
       $student['account_surname']
   );

   $decrypted_data['email'] = $libsodium_wrapper->decryption(
       $base64_wrapper,
       $student['account_email']
   );

   $decrypted_data['teacher_name'] = $libsodium_wrapper->decryption(
    $base64_wrapper,
    $student['teacher_name']
    );

   return $decrypted_data;
}