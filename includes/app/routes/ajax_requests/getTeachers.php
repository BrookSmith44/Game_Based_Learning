<?php
/**
 * get all the teachers from the database
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/getTeachers', function(Request $request, Response $response) use ($app) {
    // Get teachers
    $teacher_list = getTeachers($app);

    // empty array for rows
    $rows = [];

    foreach ($teacher_list as $teacher) {
        // Decrypt teacher datea from db
        $decrypted_data = decryptTeacherList($app, $teacher);
        // process decrypted data into html

        array_push($rows, $decrypted_data);
    }

    echo json_encode(array($rows));

 });

 function getTeachers($app) {
     // get containers
     $teacher_model = $app->getContainer()->get('teacherModel');
     $db = $app->getContainer()->get('dbh');
     $settings = $app->getContainer()->get('settings');
     $connection_settings = $settings['pdo_settings'];
     $sql_queries = $app->getContainer()->get('sqlQueries');
     $logger = $app->getContainer()->get('logger');

     // Set properties
     $teacher_model->setDb($db);
     $teacher_model->setDbConnectionSettings($connection_settings);
     $teacher_model->setSqlQueries($sql_queries);
     $teacher_model->setLogger($logger);

     // empty array for results
     $results = [];

     // Get teachers from database
     $results = $teacher_model->getAllTeachers();

     return $results;
 }

 // Function to decrypt teacher data
 function decryptTeacherList($app, $teacher) {
     // Get container
     $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
     $base64_wrapper = $app->getContainer()->get('base64Wrapper');

     // empty array for decrypted data
     $decrypted_data = [];

     // Insert data that does not need decrypting into array
     $decrypted_data['id'] = $teacher['account_id'];
     $decrypted_data['username'] = $teacher['account_username'];
     $decrypted_data['date_added'] = $teacher['date_added'];

     // Decrypt teacher info
     $decrypted_data['fname'] = $libsodium_wrapper->decryption(
         $base64_wrapper,
         $teacher['account_fname']
     );

     $decrypted_data['surname'] = $libsodium_wrapper->decryption(
        $base64_wrapper,
        $teacher['account_surname']
    );

    $decrypted_data['email'] = $libsodium_wrapper->decryption(
        $base64_wrapper,
        $teacher['account_email']
    );

    return $decrypted_data;
 }
