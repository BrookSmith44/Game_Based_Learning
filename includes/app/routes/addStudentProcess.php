<?php
/**
 * Add student Process Route
 * 
 * handles data from the add student form
 * 
 * Encrypts and stores in the database
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/addStudentProcess', function(Request $request, Response $response) use ($app) {
   // Get form data
   $form_values = $request->getParsedBody();

   // validate user inputs
   $cleaned_values = validateStudentData($app, $form_values);

   // Encrypt data
   $encrypted = encryptStudentData($app, $cleaned_values);

   // Encode data
   $encoded = encodeStudentData($app, $encrypted);

   // Store student data 
   $redirect = storeStudentData($app, $encoded, $cleaned_values);

   // Navigate to next page
   return $response->withRedirect($this->router->pathFor($redirect['page'], ['err' => $redirect['err']]));

 });

 // Function to validate user inputs on add student form
 function validateStudentData($app, $form_values) {
    // Get validator container
    $validator = $app->getContainer()->get('validator');

    // Set empty array for cleaned values
    $cleaned_values = [];

    // Validate data
    $cleaned_values['fname'] = $validator->sanitizeString($form_values['fname']);
    $cleaned_values['surname'] = $validator->sanitizeString($form_values['surname']);
    $cleaned_values['email'] = $validator->sanitizeString($form_values['email']);

    return $cleaned_values;
 }

 // Function to encrypt the data
 function encryptStudentData($app, $cleaned_values) {
   // Get container for libsodium wrapper
   $libsodium = $app->getContainer()->get('libSodiumWrapper');
   $session_wrapper = $app->getContainer()->get('sessionWrapper');

   // Get teacher name to encrypt
   // Get first name
   $fname = $session_wrapper->getSessionVar('fname');
   $surname = $session_wrapper->getSessionVar('surname');
   $teacher_name = $fname . ' ' . $surname;

   // Set empty array for encrypted data
   $encrypted = [];

   // Encrypt data
   $encrypted['fname'] = $libsodium->encryption($cleaned_values['fname']);
   $encrypted['surname'] = $libsodium->encryption($cleaned_values['surname']);
   $encrypted['email'] = $libsodium->encryption($cleaned_values['email']);
   $encrypted['teacher_name'] = $libsodium->encryption($teacher_name);

   // Hash the default password before 
   $hashed_password = hashPassword($app, DEFAULT_PASS);

   $encrypted['password'] = $libsodium->encryption($hashed_password);

   return $encrypted;
 }

 // Function to encode the already encrypted data
 function encodeStudentData($app, $encrypted) {
    // Get base64 wrapper
    $base64 = $app->getContainer()->get('base64Wrapper');

    // Set empty array for the encoded data
    $encoded = [];

    // Encode encryption data
    $encoded['fname'] = $base64->encode($encrypted['fname']['nonce_and_encrypted_string']);
    $encoded['surname'] = $base64->encode($encrypted['surname']['nonce_and_encrypted_string']);
    $encoded['email'] = $base64->encode($encrypted['email']['nonce_and_encrypted_string']);
    $encoded['password'] = $base64->encode($encrypted['password']['nonce_and_encrypted_string']);
    $encoded['teacher_name'] = $base64->encode($encrypted['teacher_name']['nonce_and_encrypted_string']);

    return $encoded;
 }

 // Function to store student data
 function storeStudentData($app, $data, $cleaned_values) {
    // Get containers
    $student_model = $app->getContainer()->get('studentModel');
    $db = $app->getContainer()->get('dbh');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');

    // Set properties in the student model
    $student_model->setFname($data['fname']);
    $student_model->setSurname($data['surname']);
    $student_model->setEmail($data['email']);
    $student_model->setFirstTimeLogin('Y');
    $student_model->setPassword($data['password']);
    $student_model->setTeacherName($data['teacher_name']);
    $student_model->setDb($db);
    $student_model->setDbConnectionSettings($db_connection_settings);
    $student_model->setSessionWrapper($session_wrapper);
    $student_model->setSQLQueries($sql_queries);
    $student_model->setLogger($logger);

    // Set check to 1 to run while loop
    $check_result = 1;

    // run while to create new username if current username already exists
    while ($check_result == 1) {
      // Create username 
      // Create and check username is not already in db 
      $username = createUsername($cleaned_values['fname'], $cleaned_values['surname']);

      // Set the username
      $student_model->setUsername($username);

      // Check the username does not already exist
      $check_result = $student_model->checkUsername();
    }

    // Call method to store student data in the database
    $store_result = $student_model->storeStudentData();

    $redirect = [];

    // If data is stored successfullt
   if ($store_result == true) {
      // Send mail to recipient
      $send_success = sendMail($app, $cleaned_values, $username);

      if ($send_success == true) {
         $redirect['page'] = 'AddStudent';
         $redirect['err'] = 'storeSuccess';

      } else {
         $redirect['page'] = 'Addstudent';
         $redirect['err'] = 'emailErr';
      }
   } else {
      $redirect['page'] = 'Addstudent';
      $redirect['err'] = 'storeErr';
   }

    // Result stored - still need to deal with storage result and send user details to user email 

    return $redirect;
 }