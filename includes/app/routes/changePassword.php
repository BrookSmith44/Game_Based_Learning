<?php
/**
 * change password Route
 * 
 * route to change password on the first time of logging in for security
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/changePassword[/{err}]', function(Request $request, Response $response, $args) use ($app) {
    // Check to see if session logged in is set
    if (!isset($_SESSION['is_logged_in'])) {
    // Navigate to login page with error
    return $response->withRedirect($this->router->pathFor('Login', ['err' => 'accessErr']));
    }
    
    // Empty variable for error message
    $err_message = '';

    // Check to see if logged in
    $logged_in = displayHeaderButton();

    // Error if user tries change page before changing password
    if ($args['err'] == 'formErr') {
        $err_message = 'You must change password before moving on!';
    }




   return $this->view->render($response,
    'changePassword.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'page_heading' => 'Homepage',
        'is_logged_in' => $logged_in,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/changePassword/formErr',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
        'heading' => 'Change Password',
        'err' => $err_message,
        'action' => '/football_trivia_game/public/changePasswordProcess'
    ]);
 })->setName('ChangePassword');