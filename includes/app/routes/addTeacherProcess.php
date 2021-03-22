<?php
/**
 * Add Teacher Process Route
 * 
 * handles data from the add teacher form
 * 
 * Encrypts and stores in the database
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/addTeacherProcess', function(Request $request, Response $response) use ($app) {
   // Get form data
   $form_values = $request->getParsedBody();

   // validate user inputs
   $cleaned_values = validateTeacherData($app, $form_values);

   // Encrypt data
   $encrypted = encryptTeacherData($app, $cleaned_values);

   // Encode data
   $encoded = encodeTeacherData($app, $encrypted);

   // insert admin value into encoded array to be passed into store function
   $encoded['admin'] = $cleaned_values['admin'];

   // Store teacher data 
   $store_result = storeTeacherData($app, $encoded, $cleaned_values);
 });

 // Function to validate user inputs on add teacher form
 function validateTeacherData($app, $form_values) {
    // Get validator container
    $validator = $app->getContainer()->get('validator');

    // Set empty array for cleaned values
    $cleaned_values = [];

    // Validate data
    $cleaned_values['fname'] = $validator->sanitizeString($form_values['teacher-fname']);
    $cleaned_values['surname'] = $validator->sanitizeString($form_values['teacher-surname']);
    $cleaned_values['email'] = $validator->sanitizeString($form_values['teacher-email']);

    // If admin checkbox has been checked set to Y for CHAR data type
    if (isset($form_values['teacher_admin'])) {
      $cleaned_values['admin'] = 'Y';
      // If admin checkbox is not checked set to N
    } else {
       $cleaned_values['admin'] = 'N';
    }

    return $cleaned_values;
 }

 // Function to encrypt the data
 function encryptTeacherData($app, $cleaned_values) {
   // Get container for libsodium wrapper
   $libsodium = $app->getContainer()->get('libSodiumWrapper');

   // Set empty array for encrypted data
   $encrypted = [];

   // Encrypt data
   $encrypted['fname'] = $libsodium->encryption($cleaned_values['fname']);
   $encrypted['surname'] = $libsodium->encryption($cleaned_values['fname']);
   $encrypted['email'] = $libsodium->encryption($cleaned_values['fname']);

   // Hash the default password before 
   $hashed_password = hashPassword($app, DEFAULT_PASS);

   $encrypted['password'] = $libsodium->encryption($hashed_password);

   return $encrypted;
 }

 // Function to encode the already encrypted data
 function encodeTeacherData($app, $encrypted) {
    // Get base64 wrapper
    $base64 = $app->getContainer()->get('base64Wrapper');

    // Set empty array for the encoded data
    $encoded = [];

    // Encode encryption data
    $encoded['fname'] = $base64->encode($encrypted['fname']['nonce_and_encrypted_string']);
    $encoded['surname'] = $base64->encode($encrypted['surname']['nonce_and_encrypted_string']);
    $encoded['email'] = $base64->encode($encrypted['email']['nonce_and_encrypted_string']);
    $encoded['password'] = $base64->encode($encrypted['password']['nonce_and_encrypted_string']);

    return $encoded;
 }

 // Function to store teacher data
 function storeTeacherData($app, $data, $cleaned_values) {
    // Get teacher model
    $teacher_model = $app->getContainer()->get('teacherModel');
    $db = $app->getContainer()->get('dbh');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');

    // Set properties in the teacher model
    $teacher_model->setFname($data['fname']);
    $teacher_model->setSurname($data['surname']);
    $teacher_model->setEmail($data['email']);
    $teacher_model->setAdmin($data['admin']);
    $teacher_model->setFirstTimeLogin('Y');
    $teacher_model->setPassword($data['password']);
    $teacher_model->setDb($db);
    $teacher_model->setDbConnectionSettings($db_connection_settings);
    $teacher_model->setSQLQueries($sql_queries);
    $teacher_model->setLogger($logger);

    // Set check to 1 to run while loop
    $check_result = 1;

    // run while to create new username if current username already exists
    while ($check_result == 1) {
      // Create username 
      // Create and check username is not already in db 
      $username = createUsername($cleaned_values['fname'], $cleaned_values['surname']);

      // Set the username
      $teacher_model->setUsername($username);

      // Check the username does not already exist
      $check_result = $teacher_model->checkUsername();
    }

    // Call method to store teacher data in the database
    $store_result = $teacher_model->storeTeacherData();

    // Result stored - still need to deal with storage result and send user details to user email 

    return $store_result;
 }

 // Function tp create username
 function createUsername($fname, $surname) {
    // Get first letter of the first name
    $first_letter = substr($fname, 0, 1);

    // Get first letter of surname
    $second_letter = substr($surname, 0, 1);

    // Create random number to be added to username
    $random_num = mt_rand(0, 99999);

    // Put together to create username
    $username = $first_letter . $second_letter . $random_num;

    return $username;
 }