<?php
/**
 * Login Page Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/login', function(Request $request, Response $response) use ($app) {
   return $this->view->render($response,
    'login.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'page_title' => 'Football Game-Based-Learning',
        'page_heading' => 'Log In',
        'signin' => 'Sign In',
        'signup_action' => '/football_trivia_game/public/signupProcess',
        'login_action' => '/loginProcess',
        'signin_content' => 'Welcome back! Enter Details here To Sign In',
        'fname' => 'First Name',
        'surname' => 'Surname',
        'email' => 'Email',
        'cemail' => 'Confirm Email',
        'pass' => 'Password',
        'cpass' => 'Confirm Password',
        'signup' => 'Sign Up',
        'signup_content' => 'New Here? Create An Account To Start Playing!',
    ]);
 });
