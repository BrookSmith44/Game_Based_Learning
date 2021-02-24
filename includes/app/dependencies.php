<?php
/**
 * Dependencies
 */

 // Container for the view
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
        [
            'debug' => true // This line should enable debug mode
        ]
    );

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

// Container for the database class
$container['dbh'] = function () {
    $dbh = new \FootballTriviaGame\Dbh();
    return $dbh;
};

// Container for the database model class
$container['userModel'] = function () {
    $database_model = new \FootballTriviaGame\UserModel();
    return $database_model;
};


// Container for the SQL Queries class
$container['sqlQueries'] = function () {
    $sql_queries = new \FootballTriviaGame\SQLQueries();
    return $sql_queries;
};

// Container for the LibSodium Wrapper class
$container['libSodiumWrapper'] = function () {
    $sql_queries = new \Encryption\LibSodiumWrapper();
    return $sql_queries;
};

// Container for the Base64 Wrapper class
$container['base64Wrapper'] = function () {
    $sql_queries = new \Encryption\Base64Wrapper();
    return $sql_queries;
};

// Container for the BycryptWrapper class
$container['bycryptWrapper'] = function () {
    $sql_queries = new \Encryption\BycryptWrapper();
    return $sql_queries;
};

// Container for the Validator class
$container['validator'] = function () {
    $sql_queries = new \FootballTriviaGame\Validator();
    return $sql_queries;
};

