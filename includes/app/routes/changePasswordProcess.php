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

    // Update password and first time login
    $redirect = changePassword($app, $cleaned_values);

    // Navigate to next page
    return $response->withRedirect($this->router->pathFor($redirect['page'], ['err' => $redirect['err']]));

 })->setName('ChangePasswordProcess');

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

 // Function to update password in the database
 function changePassword($app, $cleaned_values) {
    // Get containers
    $user_model = $app->getContainer()->get('userModel');
    $db = $app->getContainer()->get('dbh');
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $settings = $app->getContainer()->get('settings');
    $connection_settings = $settings['pdo_settings'];
    $logger = $app->getContainer()->get('logger');
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
    $base64 = $app->getContainer()->get('base64Wrapper');
    $bycrypt = $app->getContainer()->get('bycryptWrapper');

    // Set user properties
    $user_model->setDb($db);
    $user_model->setDbConnectionSettings($connection_settings);
    $user_model->setSqlQueries($sql_queries);
    $user_model->setSessionWrapper($session_wrapper);
    $user_model->setLibsodiumWrapper($libsodium_wrapper);
    $user_model->setBase64Wrapper($base64);
    $user_model->setBycrypt($bycrypt);
    $user_model->setLogger($logger);

    // Get first time login
    $user_model->getFirstTimeLogin();

    // Empty array for store results
    $store_results = [];

    // Call methods to update password and first time login
    $store_results['password'] = $user_model->updatePassword($cleaned_values['new_password']);

    // Get account type
    $account_type = $session_wrapper->getSessionVar('account_type');

    // If student account do not update first time login as it will be updated at the team details page
    // Only update if its a teacher account
    if ($account_type == 'Teacher') {
        $store_results['first_time_login'] = $user_model->updateFirstTimeLogin();
    } else {
        $store_results['first_time_login'] = true;
        $user_model->setChangedPassword(true);
    }

    // Empty array for redirect
    $redirect = [];

    if ($store_results['password'] == false || $store_results['first_time_login'] == false) {
        // Set redirect
        $redirect['page'] = 'ChangePassword';
        $redirect['err'] = 'storeErr';
    } else {
        $redirect = $user_model->redirect();
    }

    return $redirect;
 }