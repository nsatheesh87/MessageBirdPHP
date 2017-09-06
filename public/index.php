<?php

use MessageBird\Application;
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

define('BASE_DIR', realpath(__DIR__ . '/..'));
require_once(BASE_DIR . '/src/Application.php');

$app = new Application();
$app->run();
