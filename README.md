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

# Google QPX Express API Key

First of all you have to enter and login in https://console.developers.google.com/apis/dashboard. 
Then enter to "Library" on the left side of the page. Search 'QPX Express Airfare API' and enable it. Then, go to 'Credentials'
click to 'Create Credentials'->'API Key' copy 'key' and click to the 'Restrict Key'.

/application/config/config.development.php

	'GPX_API_KEY' => ''

# MongoDB configuration

/application/config/config.development.php

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

Also urls can be checked and passed to variables by router

$router->route('search/:request/:request', 'tickets/result', array('request', 'result_id'));

    `search` is slug
    `:request` - its will be checked and will be passed to variable by router. 
    This variable can be called and used everywhere by `Router::get('request');
    

# Insert Data to Databases
---------------------------------------------
/application/libs/TicketsLogger.php


# Request to GOOGLE QPX Express API 
---------------------------------------------
/application/libs/TicketAPI.php

	public static function SearchTickets() {.....} // Request to API 
	public static function simpleSlice(){.....} // Build slice 

Documentation will be published as soon as posibible
