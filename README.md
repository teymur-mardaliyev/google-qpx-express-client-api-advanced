# Google QPX Express API Advanced - PHP, MySQL, MongoDB

# Database configuration

/config.php

	define('DB_TYPE', 'mysql'); // Database type
	define('DB_HOST', 'localhost'); // Database host
	define('DB_NAME', 'database_name'); // Database name
	define('DB_USER', 'user_name'); // Database username
	define('DB_PASS', 'password'); // Database password
	define('DB_PREFIX','prefix'); // Prefix of the tables
---------------------------------

/application/config/config.development.php

# Google QPX Express API Key

	'GPX_API_KEY' => ''

# MongoDB configuration

	'MONGODB_HOST' => '127.0.0.1:27017',
	'MONGODB_NAME' => 'tickets_com',

---------------------------------------------

# Router configuration

/application/config/settings.php

$router->route('requested/url', 'controller/action');

# Router regex

    ":a" => '(\w+)', // only alphabet 
    ":n" => '(\d+)', // only numeric
    ":slug" => '([a-z0-9_-]+)', // alpha-numeric and symbols - _
    ":page" => '([0-9]+)', // only number
    ":keyword" => '(.+)', // everthing
    ":request" => '([a-z0-9]{32})', // only alpha-numeric and max 32 characters

Also urls can be checked and pass to variables by router

$router->route('search/:request/:request', 'tickets/result', array('request', 'result_id'));

    `search` is slug
    `:request` - its will be checked and will be pass to variable by router. This variable can be called and used everywhere by `Router::get('request');`
    

# Insert Data to Databases
---------------------------------------------
/application/libs/TicketsLogger.php


# Request to GOOGLE QPX Express API 
---------------------------------------------
/application/libs/TicketAPI.php


Request to API 
	public static function SearchTickets() {.....}
Build slice 
	public static function simpleSlice(){.....}

As soon as posilble 
