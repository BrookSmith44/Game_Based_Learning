<?php
/**
 * Player Homepage Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/playerHomepage', function(Request $request, Response $response) use ($app) {
   return $this->view->render($response,
    'playerHomepage.html.twig',
    [
        'css_path' => CSS_PATH,
        'page_heading' => 'Homepage',
    ]);
 })->setName('PlayerHomepage');