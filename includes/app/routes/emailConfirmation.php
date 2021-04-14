<?php
/**
 * 
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/emailConfirmation', function(Request $request, Response $response) use ($app) {
    // Check to see if logged in
    $logged_in = displayHeaderButton();



   return $this->view->render($response,
    'emailConfirmation.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'list_data' => LISTDATA,
        'page_heading' => 'Homepage',
        'is_logged_in' => $logged_in,
        'logout_action' => '/football_trivia_game/public/logoutProcess',
        'homepage_link' => '/football_trivia_game/public/managementHomepage',
        'signin' => 'Sign In',
        'signout' => 'Sign Out',
    ]);
 })->setName('EmailConfirmation');