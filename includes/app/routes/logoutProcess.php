<?php
/**
 * Homepage Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/logoutProcess', function(Request $request, Response $response) use ($app) {
    // Check to see if logged in
    logout($app);

    // Navigate to next page
    return $response->withRedirect($this->router->pathFor('Homepage'));

 });

 function logout($app) {
     // get containers
     $user_model = $app->getContainer()->get('userModel');
     $team_model = $app->getContainer()->get('userModel');
     $session_wrapper = $app->getContainer()->get('sessionWrapper');
     $logger = $app->getContainer()->get('logger');

     // Set session wrapper in models
     $user_model->setSessionWrapper($session_wrapper);
     $team_model->setSessionWrapper($session_wrapper);

     // Set logger in models
     $user_model->setLogger($logger);
     $team_model->setLogger($logger);

     // Destroy session variables
     $user_model->destroySessionVar();
     $team_model->destroySessionVar();
 }