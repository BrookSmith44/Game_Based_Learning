<?php
/**
 * Add Teacher Process Route
 * 
 * handles data from the add teacher form
 * 
 * Encrypts and stores in the database
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/addTeacherProcess', function(Request $request, Response $response) use ($app) {
    $form_values = $request->getParsedBody();
    var_dump($form_values);
 });