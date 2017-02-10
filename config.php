<?php
mb_internal_encoding("UTF-8");
header('Content-Type: text/html; charset=utf-8');

date_default_timezone_set('Asia/Baku');

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

define('ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);

define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', 'http://');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);

define('DB_TYPE', 'mysql'); // Database type
define('DB_HOST', 'localhost'); // Database host
define('DB_NAME', 'github_tickets_db'); // Database name
define('DB_USER', 'root'); // Database username
define('DB_PASS', 'root'); // Database password
define('DB_PREFIX','test'); // Prefix of the tables
