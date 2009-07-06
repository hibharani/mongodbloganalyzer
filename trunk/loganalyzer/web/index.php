<?php

define('ROOT_PATH', dirname(__FILE__).'/..');

require_once ROOT_PATH . '/config/sf_requires.php';
require_once ROOT_PATH . '/config/app_requires.php';

$app = new LogAnalyzer();
$app->run()->send();