<?php
/**
 * Setttings.php
 */
// Display errors
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

// Get app url
$app_url = dirname($_SERVER['SCRIPT_NAME']);
// Get css path
$css_path = $app_url . '/css/style.css?' . date('His');
// Define css path
define('CSS_PATH', $css_path);
// Get user team class path
$user_team = $app_url . '/js/classes/UserTeam.js?' . date('His');
// Get user team class path
$opposition_team = $app_url . '/js/classes/OppositionTeam.js?' . date('His');
// Get user team class path
$match = $app_url . '/js/classes/Match.js?' . date('His');
// Get validate class path
$validate = $app_url . '/js/classes/Validate.js?' . date('His');
// Get list data class path
$list_data = $app_url . '/js/classes/ListData.js?' . date('His');
// Get js path
$js_path = $app_url . '/js/main.js?' . date('His');
// Get log file path
$log_file_path = '/xampp/htdocs/football_trivia_game/logs/';
// default password
$default = 'password';
// Define log file path
define('LOG_FILE_PATH', $log_file_path);
// Define the js path
define('JS_PATH', $js_path);
// Define the js validate class path
define('VALIDATE', $validate);
// Define the js list class path
define('LISTDATA', $list_data);
// Define the js list class path
define('USERTEAM', $user_team);
// Define the js list class path
define('OPPOSITIONTEAM', $opposition_team);
// Define the js list class path
define('MATCHCLASS', $match);
// Define bycrypt algorithm
define('BYCRYPT_ALGO', PASSWORD_DEFAULT);
// Define bycrypt cost
define('BYCRYPT_COST', 12);
// Define default password
define('DEFAULT_PASS', $default);

// Set settings
$settings = [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'auto_reload' => true,
            ]],
        'pdo_settings' => [
            'rdbms' => 'mysql',
            'host' => 'localhost',
            'db_name' => 'fb_tr_db',
            'port' => '3360',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => true,

            ]],
        'mail_server_settings' => [
            'host' => 'smtp.sendgrid.net',
            'username' => 'apikey',
            'password' => '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',
            'port' => '587'
        ]
    ]
];

return $settings;