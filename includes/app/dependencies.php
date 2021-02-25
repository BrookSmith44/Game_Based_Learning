<?php
/**
 * Dependencies
 */

use \Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FingersCrossedHandler;

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
    $dbh = new \Database\Dbh();
    return $dbh;
};

// Container for the database model class
$container['userModel'] = function () {
    $database_model = new \FootballTriviaGame\UserModel();
    return $database_model;
};


// Container for the SQL Queries class
$container['sqlQueries'] = function () {
    $sql_queries = new \Database\SQLQueries();
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

// Logger
$container['logger'] = function () {
    $logger = new Logger('logger');

    $session_log_notices = LOG_FILE_PATH . 'notices.log';
    $stream_notices = new StreamHandler($session_log_notices, Logger::NOTICE);
    $logger->pushHandler($stream_notices);

    $session_log_warnings = LOG_FILE_PATH . 'warnings.log';
    $stream_warnings = new StreamHandler($session_log_warnings, Logger::WARNING);
    $logger->pushHandler($stream_warnings);

    $logger->pushProcessor(function ($record) {
        $record['context']['sid'] = session_id();
        return $record;
    });

    return $logger;
};
