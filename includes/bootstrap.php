<?php 
/**
 * Bootstrap.php
 */

// Start the session
session_start();

// Require vendor
require 'vendor/autoload.php';

// Define app path
$app_path = __DIR__ . "/app/";

// Require settings
$settings = require $app_path . 'settings.php';

// Instantiate container
$container = new \Slim\Container($settings);

// Require the dependencies
require $app_path . 'dependencies.php';

// Instantiate app
$app = new \Slim\App($container);

// Require routes
require $app_path . 'routes.php';

// Execute app
$app->run();

session_regenerate_id(true);