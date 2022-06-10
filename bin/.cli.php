<?php

// command line loader

defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'development');
define('APPLICATION_PATH', '/tmp');

// include path and environment
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
$libPath = realpath(__DIR__ . '/../src');
set_include_path($libPath . PATH_SEPARATOR . get_include_path());

// Fonction de traduction renvoit le texte non traduit
function __($txt) { return $txt; }

// Autoloads
require_once __DIR__ . '/../vendor/autoload.php';
