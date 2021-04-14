<?php
// Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;
 
 $app->post('/uploadStats', function(Request $request, Response $response) use ($app) {
    // Get difficulty
    $post_data = $request->getParsedBody();

    uploadGameStats($app, $post_data);

 })->setName('UploadStats');

 // Upload Stats
function uploadGameStats($app, $game_stats) {
    // Get containers
    $team_model = $app->getContainer()->get('teamModel');
    $db = $app->getContainer()->get('dbh');
    $settings = $app->getContainer()->get('settings');
    $db_connection_settings = $settings['pdo_settings'];
    $sql_queries = $app->getContainer()->get('sqlQueries');
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $logger = $app->getContainer()->get('logger');

    // Set team model properties
    $team_model->setDb($db);
    $team_model->setDbConnectionSettings($db_connection_settings);
    $team_model->setSessionWrapper($session_wrapper);
    $team_model->setSqlQueries($sql_queries);
    $team_model->setLogger($logger);

    // set propeties
    $team_model->setGamesPlayed($game_stats['games_played']);
    $team_model->setGamesWon($game_stats['games_won']);
    $team_model->setGamesDrawn($game_stats['games_drawn']);
    $team_model->setGamesLost($game_stats['games_lost']);
    $team_model->setGoalsScored($game_stats['goals_scored']);
    $team_model->setGoalsConceded($game_stats['goals_conceded']);
    $team_model->setQuestionsAnswered($game_stats['questions_answered']);
    $team_model->setAnswersCorrect($game_stats['answers_correct']);
    $team_model->setRatingChange($game_stats['rating_change']);
    $team_model->setSubject($game_stats['subject']);
    $team_model->setDifficulty($game_stats['difficulty']);

    // Update game stats
    $team_model->updateGameStats();

    // Update skill rating
    $team_model->updateSkillRating();
}