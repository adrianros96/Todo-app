<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once("Application.php");
require_once("LocalSettings.php");
require_once("ProductionSettings.php");

$app = new Application();
$app->run();

