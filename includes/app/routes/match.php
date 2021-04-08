<?php
/**
 * 
 * Route for the user to play the game
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/match', function(Request $request, Response $response) use ($app) {
    // Set variable to indicate in match
    $in_match = true;
    // Check to see if logged in
    $logged_in = displayHeaderButton();

    // Check to see if session logged in is set
  if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
  }


   return $this->view->render($response,
    'match.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'user_team' => USERTEAM,
        'opposition_team' => OPPOSITIONTEAM,
        'match' => MATCHCLASS,
        'page_heading' => 'Homepage',
        'is_logged_in' => $logged_in,
        'in_match' => $in_match,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
    ]);
 })->setName('EmailConfirmation');