<?php
/**
 * Login Page Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/login[/{err}]', function(Request $request, Response $response, $args) use ($app) {
  // Check to see if logged in
  $logged_in = displayHeaderButton();

  // Empty error message variable
  $err_message = '';
  if (isset($args['err'])) {
    if ($args['err'] == 'accessErr') {
        // Set error message to inform user they have to be logged in to gain access to the website
        $err_message = 'Must be logged in to gain access';
      }
  }

   return $this->view->render($response,
    'login.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'page_title' => 'Football Game-Based-Learning',
        'page_heading' => 'Log In',
        'is_logged_in' => $logged_in,
        'homepage_link' => '/football_trivia_game/public/',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
        'signup_action' => '/football_trivia_game/public/signupProcess',
        'login_action' => '/football_trivia_game/public/loginProcess',
        'signin_content' => 'Welcome back! Enter Details here To Sign In',
        'username' => 'Username',
        'fname' => 'First Name',
        'surname' => 'Surname',
        'email' => 'Email',
        'cemail' => 'Confirm Email',
        'pass' => 'Password',
        'cpass' => 'Confirm Password',
        'signup' => 'Sign Up',
        'signup_content' => 'New Here? Create An Account To Start Playing!',
        'err' => $err_message
    ]);
 })->setName('Login');
