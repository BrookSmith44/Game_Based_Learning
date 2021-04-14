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

// Container for the user model class
$container['userModel'] = function () {
    $user_model = new \Model\UserModel();
    return $user_model;
};

// Container for the team model class
$container['teamModel'] = function () {
    $team_model = new \Model\TeamModel();
    return $team_model;
};

// Container for the teacher model class
$container['teacherModel'] = function () {
    $teacher_model = new \Model\TeacherModel();
    return $teacher_model;
};

// Container for the student model class
$container['studentModel'] = function () {
    $student_model = new \Model\StudentModel();
    return $student_model;
};

// Container for the questions model class
$container['questionModel'] = function () {
    $questions_model = new \Model\QuestionModel();
    return $questions_model;
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

// Container for the mail class
$container['sendMail'] = function () {
    $send_mail = new \FootballTriviaGame\SendMail();
    return $send_mail;
};

// Container for the email template class
$container['emailTemplate'] = function () {
    $send_mail = new \FootballTriviaGame\CreateEmailTemplate();
    return $send_mail;
};

// Container for the create list class
$container['createList'] = function () {
    $send_mail = new \FootballTriviaGame\CreateList();
    return $send_mail;
};

// Container for the general questions class
$container['generalQuestions'] = function () {
    $general_questions = new \Game\GeneralQuestions();
    return $general_questions;
};

// Container for the commentary class
$container['commentary'] = function () {
    $commentary = new \Game\Commentary();
    return $commentary;
};

// Create container for two different kind of loggers
// One logger will handles notices and the other handles warning
$container['logger'] = function() {
    // Instantiate logger
  $logger = new Logger('logger');

  // Notices logger
    // Set notices log path
    $notices_log = LOG_FILE_PATH . 'notices.log';
    // Create stream handler for notices logger
    $stream_notices = new StreamHandler($notices_log, Logger::NOTICE);
    // Push stream handler into logger object
    $logger->pushHandler($stream_notices);

    // Warning logger
    // Set warning log path
    $warning_log = LOG_FILE_PATH . 'warnings.log';
    // Create stream handler for warnings logger
    $stream_warnings = new StreamHandler($warning_log, Logger::WARNING);
    // Push stream handler into logger object
    $logger->pushHandler($stream_warnings);

    $logger->pushProcessor(function ($record) {
        $record['context']['sid'] = session_id();
        return $record;
    });

    // Return looger
    return $logger;
};
