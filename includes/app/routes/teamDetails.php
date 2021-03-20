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
        'js_path' => JS_PATH,
        'validate' => VALIDATE,
        'page_heading' => 'Team Details',
        'heading' => 'Create Team',
        'edit_team_content' => 'Fill out the details of your team',
        'name' => 'Team Name',
        'colour' => 'Choose Your Team Colour: ',
        'action' => '/football_trivia_game/public/createTeamProcess'
    ]);
 })->setName('TeamDetails');