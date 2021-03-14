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
// Get log file path
$log_file_path = 'Football_Trivia_Game/logs/';
// Define css path
define('CSS_PATH', $css_path);
// Get js path
$validate = $app_url . '/js/classes/Validate.js?' . date('His');
// Get js path
$js_path = $app_url . '/js/main.js?' . date('His');
// Define the js path
define('JS_PATH', $js_path);
// Define the js path
define('VALIDATE', $validate);
// Define bycrypt algorithm
define('BYCRYPT_ALGO', PASSWORD_DEFAULT);
// Define bycrypt cost
define('BYCRYPT_COST', 12);
// Define log file path
define('LOG_FILE_PATH', $log_file_path);

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
            'port' => '3306',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => true,

            ]
        ]
    ]
];

return $settings;