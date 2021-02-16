<?php
/**
 * Homepage Route
 */

 // Get Request and Response
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response; 

 $app->get('/', function(Request $request, Response $response) use ($app) {
   return $this->view->render($response,
    'homepage.html.twig',
    [
        'css_path' => CSS_PATH,
        'page_heading' => 'Homepage',
    ]);
 });




 function testDb($app, $name) {
     $db = $app->getContainer()->get('dbh');
     $database_model = $app->getContainer()->get('databaseModel');
     $sql_queries = $app->getContainer()->get('sqlQueries');

     $db_config = $app->getContainer()->get('settings');
     $db_connection_settings = $db_config['pdo_settings'];

     $database_model->setDb($db);
     $database_model->setDbConnectionSettings($db_connection_settings);
     $database_model->setSQLQueries($sql_queries);
 }