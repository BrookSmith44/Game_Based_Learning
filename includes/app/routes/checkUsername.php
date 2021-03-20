<?php 

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;
 
 $app->post('/checkUsername', function(Request $request, Response $response) use ($app) {

    $form_data = $request->getParsedBody();

    $cleaned_values = validateUsername($app, $form_data);
    
    $check = checkUsername($app, $cleaned_values['suggested_username']);

    return $check['COUNT(*)'];

 })->setName('test');

function validateUsername($app, $form_data) {
    // Empty arry to store cleaned values
    $cleaned_values = [];

    // Get validate container
    $validate = $app->getContainer()->get('validator');

    $cleaned_values['suggested_username'] = $validate->sanitizeString($form_data['suggested_username']);

    return $cleaned_values;
}

 function checkUsername($app, $suggested_username) {
     // get containers
     $db = $app->getContainer()->get('dbh');
     $user_model = $app->getContainer()->get('userModel');
     $session_wrapper = $app->getContainer()->get('sessionWrapper');
     $sql_queries = $app->getContainer()->get('sqlQueries');
     $logger = $app->getContainer()->get('logger');
     $db_config = $app->getContainer()->get('settings');
     $db_connection_settings = $db_config['pdo_settings'];
    
     $user_model->setDbConnectionSettings($db_connection_settings);
     $user_model->setDb($db);
     $user_model->setSQLQueries($sql_queries);
     $user_model->setSessionWrapper($session_wrapper);
     $user_model->setLogger($logger);
     $user_model->setUsername($suggested_username);

    $check = $user_model->checkUsername();

    return $check;
}