<?php

/**
 * 
 * Route to return random commentary 
 * 
 */
/*
 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->post('/getCommentary', function(Request $request, Response $response) use ($app) {
    
    $ajax_params = $request->getParsedBody();

    $commentary = getCommentary($app, $ajax_params);

    if ($ajax_params['commentary_type'] == 'Question') {
        echo json_encode($commentary);
    } else {
        echo $commentary;
    }
 });

 // Function get commentary based on type provided from ajax parameters
 function getCommentary($app, $params) {
     // Get containers
     $questions_container = $app->getContainer()->get('generalQuestions');
     $commentary_container =$app->getContainer()->get('commentary');

     // Set empty commentary variable
     $commentaryArray = [];

     // Set team name
     $commentary_container->setTeamName($params['team_name']);

     switch ($params['commentary_type']) {
        case 'Possession' :
            $commentaryArray = $commentary_container->possessionCommentary();
            $commentary = getRandomCommentary($commentaryArray);
            break;
        case 'Attacking' :
            $commentaryArray = $commentary_container->attackingCommentary();
            $commentary = getRandomCommentary($commentaryArray);
            break;
        case 'Defending' :
            $commentaryArray = $commentary_container->defendingCommentary();
            $commentary = getRandomCommentary($commentaryArray);
            break;
        case 'Question' :
            $commentaryArray = $questions_container->createEasyQuestions();
            $commentary = getRandomCommentary($commentaryArray);
            break;
    }

    return $commentary;
 }

 // Get random number between 0 and array length
 function getRandomCommentary($commentaryArray) {
    // Get max 
    $max = count($commentaryArray);

    $i = mt_rand(0, $max - 1);

    return $commentaryArray[$i];
}
*/