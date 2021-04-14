<?php

// Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;
 
 $app->post('/getSubjects', function(Request $request, Response $response) use ($app) {
    // Get difficulty
    $post_data = $request->getParsedBody();

    // Get session wrapper
    $session_wrapper = $app->getContainer()->get('sessionWrapper');

    // Get account type
    $account_type = $session_wrapper->getSessionVar('account_type');

    // Empty array to return data to javascript
    $array = [];

    // check account type
    if ($account_type == 'General') {
        // Create object for general
        $general = ['subject' => 'General'];
        // push general into array
        array_push($array, $general);
    } else {
        // Call function to return subjects from database
        $subjects = getSubjects($app, $post_data['difficulty']);

        // Check if subject are for game or add form
        if ($post_data['display_type'] == 'game') {
            // Loop through subjects
            for ($i = 0; $i < count($subjects); $i++) {
                // Count amount of questions for reach subject
                $count = countQuestions($app, $subjects[$i]['subject']);
                // Only push into array if the subject has at least 25 questions
                if ($count['COUNT(*)'] >= 25 ) {
                    // Create object for subject
                    $subject = ['subject' =>  $subjects[$i]['subject']];
                    //Push into array
                    array_push($array, $subject);
                }
            }
        } else {
            foreach ($subjects as $subject) {
                $option = ['subject' => $subject];
                // Call function to return subjects from database
                array_push($array, $subject);
            }
        } 

        // push general into array as well so students can play with geneal questions as well
        // Create object for general
        $general = ['subject' => 'General'];
        array_push($array, $general);
    }

    echo json_encode(array($array));

 })->setName('GetSubjects');

 function getSubjects($app, $difficulty) {
     // Get containers
     $question_model = $app->getContainer()->get('questionModel');
     $db = $app->getContainer()->get('dbh');
     $settings = $app->getContainer()->get('settings');
     $db_connection_settings = $settings['pdo_settings'];
     $sql_queries = $app->getContainer()->get('sqlQueries');
     $logger = $app->getContainer()->get('logger');

     // Set question model properties
     $question_model->setDb($db);
     $question_model->setDbConnectionSettings($db_connection_settings);
     $question_model->setSqlQueries($sql_queries);
     $question_model->setLogger($logger);

     // Set empty array for subjexts
     $subjects = [];

     // Set difficulty property
     $question_model->setDifficulty($difficulty);

     // Run question method to return all subjects
     $subjects = $question_model->getSubjects();

     return $subjects;
 }


 function countQuestions($app, $subject) {
     // Get containers
     $question_model = $app->getContainer()->get('questionModel');
     $db = $app->getContainer()->get('dbh');
     $settings = $app->getContainer()->get('settings');
     $db_connection_settings = $settings['pdo_settings'];
     $sql_queries = $app->getContainer()->get('sqlQueries');
     $logger = $app->getContainer()->get('logger');

     // Set question model properties
     $question_model->setDb($db);
     $question_model->setDbConnectionSettings($db_connection_settings);
     $question_model->setSqlQueries($sql_queries);
     $question_model->setLogger($logger);

     // Set empty array for subjexts
     $subjects = [];

     // Set difficulty property
     $question_model->setSubject($subject);

     // Run question method to return all subjects
     $subjects = $question_model->countQuestions();

     return $subjects;
 }