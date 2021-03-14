<?php
/**
 * Dependencies
 */

use Monolog\Logger;
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

// Container for the session wrapper class
$container['sessionWrapper'] = function () {
    $session_wrapper = new \Database\SessionWrapper();
    return $session_wrapper;
};


// Container for the SQL Queries class
$container['sqlQueries'] = function () {
    $sql_queries = new \Database\SQLQueries();
    return $sql_queries;
};

// Container for the LibSodium Wrapper class
$container['libSodiumWrapper'] = function () {
    $libsodium = new \Encryption\LibSodiumWrapper();
    return $libsodium;
};

// Container for the Base64 Wrapper class
$container['base64Wrapper'] = function () {
    $base64 = new \Encryption\Base64Wrapper();
    return $base64;
};

// Container for the BycryptWrapper class
$container['bycryptWrapper'] = function () {
    $bycrypt = new \Encryption\BycryptWrapper();
    return $bycrypt;
};

// Container for the Validator class
$container['validator'] = function () {
    $validator = new \FootballTriviaGame\Validator();
    return $validator;
};

// Logger
$container['logger'] = function () {
    $logger = new Logger('logger');

    $log_notices = LOG_FILE_PATH . 'notices.log';
    $stream_notices = new StreamHandler($log_notices, Logger::NOTICE);
    $logger->pushHandler($stream_notices);

    $log_warnings = LOG_FILE_PATH . 'warnings.log';
    $stream_warnings = new StreamHandler($log_warnings, Logger::WARNING);
    $logger->pushHandler($stream_warnings);

    $logger->pushProcessor(function ($record) {
        $record['context']['sid'] = session_id();
        return $record;
    });

    return $logger;
};
