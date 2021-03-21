<?php
/**
 * Management Homepage Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/managementHomepage', function(Request $request, Response $response) use ($app) {
   return $this->view->render($response,
    'homepage.html.twig',
    [
        'css_path' => CSS_PATH,
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'page_heading' => 'Management Homepage',
    ]);
 })->setName('ManagementHomepage');