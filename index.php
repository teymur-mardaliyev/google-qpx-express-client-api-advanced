<?php

// load application config (error reporting etc.)
require_once 'config.php';

$url = isset($_GET['url']) ? $_GET['url'] : ""; // website.com/?url=link

$db = DatabaseFactory::getFactory()->getConnection();

Registry::set('db',$db);

require_once APP . 'config/settings.php';

// Load librari class
function __autoload($class) {
    $file = APP . 'libs/' . $class . '.php';
    if (file_exists($file)) {
        require_once($file);
    } else {
        echo $file;
    }
}

// start the application
$app = new Application();