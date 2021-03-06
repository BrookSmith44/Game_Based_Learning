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

    $store_result = storeTeamData($app, $cleaned_values);

    $redirect = redirectCreateTeam($app, $store_result);

    // Navigate to next page
    return $response->withRedirect($this->router->pathFor($redirect['page'], ['err' => $redirect['err']]));

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

 // Upload user data to accounts table
function storeTeamData($app, $cleaned_values) {
    // get containers
    $db = $app->getContainer()->get('dbh');
    $team_model = $app->getContainer()->get('teamModel');
    $user_model = $app->getContainer()->get('userModel');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];

    // Empty string for store result
    $store_result = [];

    // Set team models properties
    $team_model->setTeamName($cleaned_values['team_name']);
    $team_model->setColour($cleaned_values['colour']);
    $team_model->setDbConnectionSettings($db_connection_settings);
    $team_model->setDb($db);
    $team_model->setSQLQueries($sql_queries);
    $team_model->setSessionWrapper($session_wrapper);
    $team_model->setLogger($logger);

    // Set user model properties
    $user_model->setDbConnectionSettings($db_connection_settings);
    $user_model->setDb($db);
    $user_model->setSQLQueries($sql_queries);
    $user_model->setSessionWrapper($session_wrapper);
    $user_model->setLogger($logger);

    $first_time_login = $user_model->getFirstTimeLogin();
    
    if ($first_time_login == 'Y') {
        // Set stat propeties
        $team_model->setSkillRating(100);
        $team_model->setGamesPlayed(0);
        $team_model->setGamesWon(0);
        $team_model->setGamesLost(0);
        $team_model->setGoalsScored(0);
        $team_model->setGamesDrawn(0);
        $team_model->setGoalsConceded(0);
        $team_model->setQuestionsAnswered(0);
        $team_model->setAnswersCorrect(0);
        // Store team data
        $store_result['team_storage'] = $team_model->createTeam();
        $store_result['update_ftl'] = $user_model->updateFirstTimeLogin();
    } else {
        $store_result['team_storage'] = $team_model->updateTeam();
    }

    return $store_result;
}

function redirectCreateTeam($app, $store_result) {
    // Empty redirect array
    $redirect = [];
    $user_model = $app->getContainer()->get('userModel');
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $logger = $app->getContainer()->get('logger');
    $db = $app->getContainer()->get('dbh');
    $db_config = $app->getContainer()->get('settings');
    $db_connection_settings = $db_config['pdo_settings'];

    // Set user model properties
    $user_model->setDbConnectionSettings($db_connection_settings);
    $user_model->setDb($db);
    $user_model->setSQLQueries($sql_queries);
    $user_model->setLogger($logger);

    $first_time_login = $user_model->getFirstTimeLogin();

    // if storeage fails send back to create team form with error message
    // If storage successful redirect to player homepage 
    if (($store_result['team_storage'] === true && $store_result['update_ftl'] == true) || ($store_result['team_storage'] === true && $first_time_login == 'N')) {
        $redirect['page'] = 'PlayerHomepage';
        $redirect['err'] = '';
    } else {
        $redirect['page'] = 'TeamDetails';
        $redirect['err'] = 'storeErr';
    }

    return $redirect;
}