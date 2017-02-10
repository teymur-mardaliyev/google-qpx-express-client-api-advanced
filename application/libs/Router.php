<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 11/23/14
 * Time: 10:25 PM
 */

class Router {

    protected static $url = null;
    protected static $exp_url = null;
    protected static $page = null;
    protected static $id = null;
    public static $controller = 'Home';
    public static $action = 'index';
    protected static $routes = array();
    protected static $namedRoutes = array();
    public static $params = array();
    protected static $matchTypes = array(
        ":a" => '(\w+)',
        ":n" => '(\d+)',
        ":slug" => '([a-z0-9_-]+)',
        ":page" => '([0-9]+)',
        ":username" => '([a-z0-9_.-]{4,40})',
        ":nt_type" => '([0-9]{1})',
        ":keyword" => '(.+)',
        ":request" => '([a-z0-9]{32})',
    );
    protected static $patterns = array();
    protected static $target_end = false;

    function __construct($url) {

        if (strstr($url, '?')) $url = substr($url, 0, strpos($url, '?'));

        $url = rtrim($url, '/');

        self::$url = trim($url);

        self::$exp_url = explode('/', $url);

    }


    /*
     * Collects and divide
     * */

    public static function route($pattern, $target = '', $args = array()) {
        if ($pattern) {
            if (!isset(self::$routes[$pattern])) {
                $pattern = rtrim($pattern, '/');

                $pattern = explode('/', $pattern);

                $params = "";

                foreach ($pattern as $val) {
                    $params .= (array_key_exists($val, self::$matchTypes) ? self::$matchTypes[$val] : $val) . "/";
                }

                $pattern = rtrim($params, '/');

                $pattern = '/' . str_replace('/', '\/', $pattern) . '/';
                self::$routes[$pattern] = array('args' => $args, 'target' => $target);
            } else {
                throw new \Exception("Can not redeclare route '{$pattern}'.");
            }
        }
    }

    public static function execute() {

        $url = self::$url;

        $pattern = '/' . str_replace('/', '\/', $url) . '/';

        if (array_key_exists($pattern, self::$routes)) {
            self::targetMatch($pattern);
        } else {
            foreach (self::$routes as $pattern => $callback) {

                if (preg_match($pattern, $url)) {

                    /* If there are arguments, then check arguments */

                    if (!empty($callback['args']) and is_array($callback['args'])) {

                        preg_match_all($pattern, $url, $matches);
                        array_shift($matches);
                        foreach ($matches as $key => $match) {

                            if (array_key_exists(0, $match)) {
                                self::$params[$callback['args'][$key]] = htmlspecialchars($match[0]);
                            }
                        }
                        self::targetMatch($pattern);

                    } else {
                        self::targetMatch($url);
                    }

                    return true;
                }
            }
        }
    }


    protected static function loadMetodGET() {
        if (isset($_GET)) {
            foreach ($_GET as $key => $val) {
                if (!is_array($val)) {
                    if (!isset(self::$params["get_" . $key])) {
                        self::$params["get_" . $key] = htmlspecialchars($val);
                    }
                }
            }
        }
    }

    /*
     * @targetMatch parse router  to controller and action
     * */

    private static function targetMatch($target) {

        $target = self::$routes[$target]['target'];
        if ($target != '*') {
            $matches = explode('/', $target);

            if ($matches) {

                self::$page = $target;
                $page = $matches;

                self::$params["controller"] = isset($page[0]) ? $page[0] : 'Home';
                self::$params["action"] = isset($page[1]) ? $page[1] : 'PageNotFound';
                self::$target_end = true;

                return self::$page;
            } else {
                return false;
            }
        } else {
            self::$target_end = false;
        }
    }

    /*
     * Router runs here.
     * */

    public static function splitUrl() {

        self::execute();

        self::loadMetodGET();

    }

    public static function get($key) {
        return self::$params[$key];
    }

}