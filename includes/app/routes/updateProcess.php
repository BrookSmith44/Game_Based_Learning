<?php
/**
 * Update process, use route parameters to decipher which table and row it is updating
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/updateProcess/{table}/{id}', function(Request $request, Response $response, $args) use ($app) {
   // Get form data
   $form_values = $request->getParsedBody();

   // validate user inputs
   $cleaned_values = validateUpdateData($app, $form_values, $args);

   $redirect = handleUpdateRequest($app, $cleaned_values, $args);
   
   // Navigate to next page
   return $response->withRedirect($this->router->pathFor($redirect['page'], ['table' => $redirect['table'], 'id' => $redirect['id'], 'err' => $redirect['err']]));

 });

function validateUpdateData($app, $form_values, $args) {
    // Get containers
    $validator = $app->getContainer()->get('validator');


    // Empty array for cleaned data
    $cleaned_values = [];

    // Empty array for cleaned values
    switch ($args['table']) {
        case 'student';
        case 'teacher':
            $cleaned_values['username'] = $validator->sanitizeString($form_values['username']);
            $cleaned_values['fname'] = $validator->sanitizeString($form_values['fname']);
            $cleaned_values['surname'] = $validator->sanitizeString($form_values['surname']);
            $cleaned_values['email'] = $validator->sanitizeString($form_values['email']);
            // add admin to cleaned values if teacher update
            if ($args['table'] == 'teacher') {
                // if form admin checkbox is checked then set to Y to be stored
                if ($form_values['update_admin'] == true) {
                    $cleaned_values['admin'] = 'Y';
                }  else {
                    // if not set to N to be stored
                    $cleaned_values['admin'] = 'N';
                }
            }
            break;
        case 'question':
            $cleaned_values['question'] = $validator->sanitizeString($form_values['question']);
            $cleaned_values['choice1'] = $validator->sanitizeString($form_values['choice1']);
            $cleaned_values['choice2'] = $validator->sanitizeString($form_values['choice2']);
            $cleaned_values['choice3'] = $validator->sanitizeString($form_values['choice3']);
            $cleaned_values['choice4'] = $validator->sanitizeString($form_values['choice4']);
            $cleaned_values['answer'] = $validator->sanitizeString($form_values['answer']);
            $cleaned_values['difficulty'] = $validator->sanitizeString($form_values['difficulty']);
            $cleaned_values['subject'] = $validator->sanitizeString($form_values['subject']);
            break;
    }

    return $cleaned_values;
}

function handleUpdateRequest($app, $cleaned_values, $args) {
    // Get containers
    $student_model = $app->getContainer()->get('studentModel');
    $teacher_model = $app->getContainer()->get('teacherModel');
    $question_model = $app->getContainer()->get('questionModel');

    // Empty array for redirect
    $redirect = [];

    switch($args['table']) {
        case 'teacher';
        case 'student':
            // Check username
            $check = checkUsername($app, $cleaned_values['username']);
            // Only run code if passed username check
            if ($check == 0) {
                // Empty model variable
                $model = '';
                // Get model
                if ($args['table'] == 'teacher') {
                    $model = $teacher_model;
                } else {
                    $model = $student_model;
                }
                // Update details
                $store_result = updateData($app, $model, $cleaned_values, $args);

                // Redirect back to display page with success message or error message
                if ($store_result == true) {
                    $redirect['page'] = 'DisplayData';
                    $redirect['table'] = $args['table'];
                    $redirect['id'] = $cleaned_values['username'];
                    $redirect['err'] = 'updateSuccess';
                } else {
                    $redirect['page'] = 'Update';
                    $redirect['table'] = $args['table'];
                    $redirect['id'] = $args['id'];
                    $redirect['err'] = 'storeErr';
                }
            } else {
                $redirect['page'] = 'Update';
                $redirect['table'] = $args['table'];
                $redirect['id'] = $args['id'];
                $redirect['err'] = 'existErr';
            }
            break;
        case 'question':
            // Update details
            $store_result = updateData($app, $question_model, $cleaned_values, $args);

            if ($store_result == true) {
                $redirect['page'] = 'DisplayData';
                $redirect['table'] = $args['table'];
                $redirect['id'] = $args['id'];
                $redirect['err'] = 'updateSuccess';
            } else {
                $redirect['page'] = 'Update';
                $redirect['table'] = $args['table'];
                $redirect['id'] = $args['id'];
                $redirect['err'] = 'storeErr';
            }
            break;
    }

    return $redirect;
}

function setProperties($app, $model) {
    $db = $app->getContainer()->get('dbh');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');

    $model->setDb($db);
    $model->setDbConnectionSettings($db_connection_settings);
    $model->setSQLQueries($sql_queries);
    $model->setLogger($logger);
}

function updateData($app, $model, $cleaned_values, $args) {
    // Set properties
    setProperties($app, $model);

    $store_result = [];

    switch($args['table']) {
        case 'teacher';
        case 'student':
            // Set username property
            $model->setUsername($cleaned_values['username']);

            // Set admin if teacher form
            if ($args['table'] == 'teacher') {
                $model->setAdmin($cleaned_values['admin']);
            }

            // Encrypt data
            $encrypted_data = [];

            $encrypted_data['fname'] = encryptString($app, $cleaned_values['fname']);
            $encrypted_data['surname'] = encryptString($app, $cleaned_values['surname']);
            $encrypted_data['email'] = encryptString($app, $cleaned_values['email']);

            // Set encrypted data to properties
            $model->setFname($encrypted_data['fname']);
            $model->setSurname($encrypted_data['surname']);
            $model->setEmail($encrypted_data['email']);

            $store_result = $model->updateData($args['id']);
            break;
        case 'question':
                // Set question properties
                $model->setQuestionId($args['id']);
                $model->setQuestion($cleaned_values['question']);
                $model->setChoice1($cleaned_values['choice1']);
                $model->setChoice2($cleaned_values['choice2']);
                $model->setChoice3($cleaned_values['choice3']);
                $model->setChoice4($cleaned_values['choice4']);
                $model->setAnswer($cleaned_values['answer']);
                $model->setSubject($cleaned_values['subject']);
                $model->setDifficulty($cleaned_values['difficulty']);

                // Store data
                $store_result = $model->updateData();
            break;
    }

    return $store_result;
}