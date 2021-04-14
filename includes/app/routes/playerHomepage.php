<?php
/**
 * Player Homepage Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/playerHomepage', function(Request $request, Response $response) use ($app) {
   // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  } else {
    // Check access
    // Check it is management account
    $player_access = checkPlayerAccess($app);

    if ($player_access == false) {
      // Navigate to player homepage
      return $response->withRedirect($this->router->pathFor('ManagementHomepage'));
    } 
  }
  
  // Check to see if logged in
  $logged_in = displayHeaderButton();

  $team_model = $app->getContainer()->get('teamModel');

  $team_info = getTeamInfo($app);

   return $this->view->render($response,
    'playerHomepage.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'page_heading' => 'Player Homepage',
        'is_logged_in' => $logged_in,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/playerHomepage',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
        'team_name' => $team_info['team_name'],
        'rating' => $team_info['rating']
    ]);
 })->setName('PlayerHomepage');


 function checkPlayerAccess($app) {
  // Get container
 $session_wrapper = $app->getContainer()->get('sessionWrapper');

 // Get account type and admin
 $account_type = $session_wrapper->getSessionVar('account_type');
 
 // Set access variable to false initially
 $access = false;
 if ($account_type !== 'Teacher') {
   // Check if user is not a teacher
   $access = true;
 }

 return $access;
}

 function getTeamInfo($app) {
   // Get containers
   $team_model = $app->getContainer()->get('teamModel');
   $session_wrapper = $app->getContainer()->get('sessionWrapper');
   $logger = $app->getContainer()->get('logger');

   // Set session wrapper
   $team_model->setSessionWrapper($session_wrapper);

   // Set team model logger
   $team_model->setLogger($logger);

   //Get session variables
   $team_info = $team_model->getTeamSessionVar();

   // Return team info
   return $team_info;
 }