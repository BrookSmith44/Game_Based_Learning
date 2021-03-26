<?php
/**
 * change password Route
 * 
 * route to change password on the first time of logging in for security
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/changePasswordProcess', function(Request $request, Response $response) use ($app) {
    // Get form values
    $form_values = $request->getParsedBody();

    // Validate form values
    $cleaned_values = validateChangePassForm($app, $form_values);
    
    // Hash password - use function from signup process
    $hashed_password = hashPassword($app, $cleaned_values['new_password']);

    // Ecnrypt password
    $encryption_password = encryptPassword($app, $hashed_password);

    // Encode password
    $encoded_password = encodePassword($app, $encryption_password);

    // Update password and first time login
    $redirect = changePassword($app, $encoded_password);

    // Navigate to next page
   return $response->withRedirect($this->router->pathFor($redirect['page'], ['err' => $redirect['err']]));

 })->setName('ChangePassword');

 // Function to validate form values
 function validateChangePassForm($app, $form_values) {
     // Get validator container
     $validator = $app->getContainer()->get('validator');

     // empty array for cleaned values
     $cleaned_values = [];

     // Sanitize new password
     $cleaned_values['new_password'] = $validator->sanitizeString($form_values['new_password']);

     // return sanitized data
     return $cleaned_values;
 }

 // function to encrypt password
 function encryptPassword($app, $hashed_password) {
     // Get container
     $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');

     // Encrypt password
     $encryption_password = $libsodium_wrapper->encryption($hashed_password);

     return $encryption_password;
 }

 // function to encode password
 function encodePassword($app, $encryption_password) {
    // Get container
    $base64 = $app->getContainer()->get('base64Wrapper');

    // encode password
    $encoded_password = $base64->encode($encryption_password['nonce_and_encrypted_string']);

    return $encoded_password;
 }

 // Function to update password in the database
 function changePassword($app, $encoded_password) {
    // Get containers
    $user_model = $app->getContainer()->get('userModel');
    $db = $app->getContainer()->get('dbh');
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $settings = $app->getContainer()->get('settings');
    $connection_settings = $settings['pdo_settings'];
    $logger = $app->getContainer()->get('logger');

    // Set user properties
    $user_model->setPassword($encoded_password);
    $user_model->setDb($db);
    $user_model->setDbConnectionSettings($connection_settings);
    $user_model->setSqlQueries($sql_queries);
    $user_model->setSessionWrapper($session_wrapper);
    $user_model->setLogger($logger);

    // Empty array for store results
    $store_results = [];

    // Call methods to update password and first time login
    $store_results['password'] = $user_model->updatePassword();
    $store_results['first_time_login'] = $user_model->updateFirstTimeLogin();

    // Empty array for redirect
    $redirect = [];

    if ($store_results['password'] == false || $store_results['first_time_login'] == false) {
        // Set redirect
        $redirect['page'] = 'ChangePassword';
        $redirect['err'] = 'storeErr';
    } else {
        // Set redirect
        $redirect['page'] = 'ManagementHomepage';
        $redirect['err'] = '';
    }
    return $redirect;
 }