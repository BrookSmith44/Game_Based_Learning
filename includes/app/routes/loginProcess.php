<?php
/**
 * login process route
 * 
 * Route to handle the process of logging the user in 
 * and directing them to page depending on account type
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/loginProcess', function(Request $request, Response $response) use ($app) {
   // Get form data
   $form_values = $request->getParsedBody();

   // validate user inputs
   $cleaned_values = validateLoginData($app, $form_values);

   // process login details
   $redirect = processLoginDetails($app, $cleaned_values);


   // Navigate to next page
   return $response->withRedirect($this->router->pathFor($redirect['page'], ['err' => $redirect['err']]));


 });

  // Function to validate user inputs on login form
  function validateLoginData($app, $form_values) {
    // Get validator container
    $validator = $app->getContainer()->get('validator');

    // Set empty array for cleaned values
    $cleaned_values = [];

    // Validate data
    $cleaned_values['username'] = $validator->sanitizeString($form_values['signin_username']);
    $cleaned_values['password'] = $validator->sanitizeString($form_values['signin_pass']);

    return $cleaned_values;
 }

 // Function to process login details
 function processLoginDetails($app, $cleaned_values) {
    // Get containers
    $user_model = $app->getContainer()->get('userModel');
    $team_model = $app->getContainer()->get('teamModel');
    $db = $app->getContainer()->get('dbh');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $libsodium = $app->getContainer()->get('libSodiumWrapper');
    $base64 = $app->getContainer()->get('base64Wrapper');
    $bycrypt = $app->getContainer()->get('bycryptWrapper');

    // Set classes and models
    $user_model->setDb($db);
    $user_model->setDbConnectionSettings($db_connection_settings);
    $user_model->setSQLQueries($sql_queries);
    $user_model->setLogger($logger);
    $user_model->setSessionWrapper($session_wrapper);
    $user_model->setTeamModel($team_model);

    // Set username and password
    $user_model->setUsername($cleaned_values['username']);
    $user_model->setPassword($cleaned_values['password']);

    // Set encryption wrappers
    $user_model->setLibsodiumWrapper($libsodium);
    $user_model->setBase64Wrapper($base64);
    $user_model->setBycrypt($bycrypt);

    // Process login method
    $redirect = $user_model->processLogin();

    return $redirect;
 }