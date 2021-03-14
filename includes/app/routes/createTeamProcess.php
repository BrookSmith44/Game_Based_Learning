<?php
/**
 * Team Details Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/createTeamProcess', function(Request $request, Response $response) use ($app) {
     // Get form values
    $form_values = $request->getParsedBody();

    // Clean values
    $cleaned_values = cleanTeamValues($app, $form_values);

    var_dump($cleaned_values);
 })->setName('CreateTeam');

 // Function to clean form values
 function cleanTeamValues($app, $form_values) {
     // Empty array for cleaned values
     $cleaned_values = [];

    // Get validator container
    $validator = $app->getContainer()->get('validator');

    $cleaned_values['team_name'] = $validator->sanitizeString($form_values['team_name']);
    $cleaned_values['colour'] = $validator->sanitizeString($form_values['colour']);

    // Return cleaned values
    return $cleaned_values;
 }