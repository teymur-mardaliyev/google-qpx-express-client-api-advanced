<?php

class LanguagePack {
    private static $config;

    public static function get($key) {
        if (!self::$config) {
            self::$config = require_once('application/libs/packages/langs/en.php');
        }

        return self::$config[$key];
    }


    public static function translate($traslate, $args = array()) {

        return str_replace(array_keys($args), array_values($args), $traslate);

    }

}
