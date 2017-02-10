<?php

class Application {

    public function __construct() {

        if (isset($_GET['url'])) {

            $controller = Router::get('controller');

            $controller_file = './application/controller/' . $controller . '.php';

            if (file_exists($controller_file)) {

                require_once $controller_file;

                $controller = new $controller();

                if (method_exists($controller, Router::get('action'))) {
                    $controller->{Router::get('action')}();
                } else {
                    //print_r($router);
                    $controller->index();
                }

            } else {

                require './application/controller/home.php';
                $home = new Home();
                $home->PageNotFound();

            }

        } else {
            require './application/controller/home.php';
            $home = new Home();
            $home->index();
        }
    }


}
