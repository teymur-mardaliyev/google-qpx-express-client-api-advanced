<?php
/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 11/24/14
 * Time: 6:13 PM
 */


$router = new Router($url);

$router->route('index', 'home/index', array('page')); // Home page controller/action

$router->route('api/request', 'api/request'); // Request to Google QPX with ajax

$router->route('search/request', 'tickets/request');
$router->route('search/error', 'tickets/error', array('request'));

$router->route('search/:request/:request', 'tickets/result', array('request', 'result_id'));

$router->route('search/:request', 'tickets/search', array('request'));

/* Ajax requests */
$router->route('ajax/airportnames', 'airports/SearchAirport');
$router->route('ajax/tickets/search', 'tickets/loadData');
$router->route('ajax/subscribe/email', 'ajax/subscribe');

$router->splitUrl();
