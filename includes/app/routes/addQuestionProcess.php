<?php
/**
 * Add question Process Route
 * 
 * handles data from the add question form
 * 
 * Encrypts and stores in the database
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/addQuestionProcess', function(Request $request, Response $response) use ($app) {
   // Get form data
   $form_values = $request->getParsedBody();

   // validate user inputs
   $cleaned_values = validateQuestionData($app, $form_values);

   // Store question data 
   $redirect = storeQuestionData($app, $cleaned_values);

    // Navigate to page with error
    return $response->withRedirect($this->router->pathFor($redirect['page'], ['err' => $redirect['err']]));



 });

 // Function to validate user inputs on add question form
 function validateQuestionData($app, $form_values) {
    // Get validator container
    $validator = $app->getContainer()->get('validator');

    // Set empty array for cleaned values
    $cleaned_values = [];

    // Validate data
    $cleaned_values['question'] = $validator->sanitizeString($form_values['question']);
    $cleaned_values['choice1'] = $validator->sanitizeString($form_values['choice1']);
    $cleaned_values['choice2'] = $validator->sanitizeString($form_values['choice2']);
    $cleaned_values['choice3'] = $validator->sanitizeString($form_values['choice3']);
    $cleaned_values['choice4'] = $validator->sanitizeString($form_values['choice4']);
    $cleaned_values['answer'] = $validator->sanitizeString($form_values['answer']);
    $cleaned_values['difficulty'] = $validator->sanitizeString($form_values['difficulty']);

    // Check to see whether subject comes from select or input
    if ($form_values['subject-radio'] == 'existing') {
        $cleaned_values['subject'] = $validator->sanitizeString($form_values['subject-select']);
    } else {
        $cleaned_values['subject'] = $validator->sanitizeString($form_values['subject-input']);
    }

    return $cleaned_values;
 }
 // Function to store question data
 function storeQuestionData($app, $cleaned_values) {
    // Get containers
    $question_model = $app->getContainer()->get('questionModel');
    $db = $app->getContainer()->get('dbh');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');

    // Set properties in the question model
    $question_model->setQuestion($cleaned_values['question']);
    $question_model->setChoice1($cleaned_values['choice1']);
    $question_model->setChoice2($cleaned_values['choice2']);
    $question_model->setChoice3($cleaned_values['choice3']);
    $question_model->setChoice4($cleaned_values['choice4']);
    $question_model->setAnswer($cleaned_values['answer']);
    $question_model->setSubject($cleaned_values['subject']);
    $question_model->setDifficulty($cleaned_values['difficulty']);
    $question_model->setDb($db);
    $question_model->setDbConnectionSettings($db_connection_settings);
    $question_model->setSessionWrapper($session_wrapper);
    $question_model->setSQLQueries($sql_queries);
    $question_model->setLogger($logger);

    // Call method to store question data in the database
    $store_result = $question_model->storeQuestionData();

    $redirect = [];

    // If data is stored successfully
   if ($store_result == true) {
      $redirect['page'] = 'AddQuestion';
      $redirect['err'] = 'storeSuccess';
   } else {
      $redirect['page'] = 'AddQuestion';
      $redirect['err'] = 'storeErr';
   }

    // Result stored - still need to deal with storage result and send user details to user email 
    return $redirect;
 }