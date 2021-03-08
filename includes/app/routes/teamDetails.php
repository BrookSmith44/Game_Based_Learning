<?php
/**
 * Team Details Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/teamDetails', function(Request $request, Response $response) use ($app) {
   return $this->view->render($response,
    'editTeam.html.twig',
    [
        'css_path' => CSS_PATH,
        'page_heading' => 'Team Details',
    ]);
 })->setName('TeamDetails');