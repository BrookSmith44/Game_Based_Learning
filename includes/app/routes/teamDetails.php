<?php
/**
 * Team Details Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/teamDetails', function(Request $request, Response $response) use ($app) {
   // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  }
  // Check to see if logged in
  $logged_in = displayHeaderButton(); 
  
  return $this->view->render($response,
    'editTeam.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'page_heading' => 'Team Details',
        'is_logged_in' => $logged_in,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/playerHomepage',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
        'heading' => 'Create Team',
        'edit_team_content' => 'Fill out the details of your team',
        'name' => 'Team Name',
        'colour' => 'Choose Your Team Colour: ',
        'action' => '/football_trivia_game/public/createTeamProcess'
    ]);
 })->setName('TeamDetails');