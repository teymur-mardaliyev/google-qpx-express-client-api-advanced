<?php

class Controller {


    function __construct() {
        Session::init();
    }


    public function loadModel($model_name) {

        $model = 'application/models/' . strtolower($model_name) . '.php';
        if (file_exists($model)) {
            require_once $model;
            return new $model_name();
        } else {
            echo "Model: ``" . $model_name . "`` Dosn`t exists!";
        }

    }

    public function loadLibrary($library_name, $args) {
        $library = 'application/libs/' . strtolower($library_name) . '.php';
        if (file_exists($library)) {
            require_once $library;
            return new $library_name($args);
        } else {
            echo "Library: ``" . $library_name . "`` Dosn`t exists!";
        }

    }

    public function loadPackages($packages_name) {
        $package = 'application/libs/packages/' . strtolower($packages_name) . '.php';
        if (file_exists($package)) {
            require_once $package;
        } else {
            echo "Package: ``" . $packages_name . "`` Dosn`t exists!";
        }

    }

    public function loadTemplate($templates, $data = '') {
        if (is_array($templates)) {
            foreach ($templates as $key => $val) {
                $file = 'application/views/' ./*strtolower*/
                    ($val) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                } else {
                    require_once 'application/views/_templates/error404.php';
                }
            }
        } else {
            require './application/controller/home.php';
            $home = new Home();
            $home->index();
        }
    }

    public function renderFeedbackMessages() {
        // echo out the feedback messages (errors and success messages etc.),
        // they are in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
        $file = 'application/views/_templates/feedback.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            echo 'Not found';//require_once 'application/views/_templates/error404.php';
        }
        // delete these messages (as they are not needed anymore and we want to avoid to show them twice
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

}
