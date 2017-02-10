<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

return array(

	'URL' => 'http://' . $_SERVER['HTTP_HOST'] . str_replace('public', '', dirname($_SERVER['SCRIPT_NAME'])),

	'PATH_CONTROLLER' => realpath(dirname(__FILE__).'/../../') . '/application/controller/',
	'PATH_VIEW' => realpath(dirname(__FILE__).'/../../') . '/application/view/',

	'DEFAULT_CONTROLLER' => 'index',
	'DEFAULT_ACTION' => 'index',

	'TICKET_COOKIE'  => 864000,
	'COOKIE_PATH' => '/',

	'CONTENT_LANG' => array(
        'az' => 'Azərbaycanca',
        'en' => 'English'
    ),

    'DEFAULT_LANG' => 'en',
    'DATETIME' => date("Y-m-d G:i:s"),
    'SETLOCALE'=> array(
        'az' => 'az_AZ.UTF-8',
        'en' => 'en_US.UTF-8',
        'ru' => 'ru_RU.UTF-8',
    ),

	'SALECOUNTRY' => 'US',
	'SOLUTIONS'	  => 40,

	'GPX_API_KEY' => 'AIzaSyCkE7lv0T-90zfiVpE_lkoSMFZuEPGkqLQ',

	'MONGODB_HOST' => '127.0.0.1:27017',
	'MONGODB_NAME' => 'tickets_com',
);
