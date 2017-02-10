<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 4/25/15
 * Time: 4:38 AM
 */
class API extends Controller {

    public function request() {
        $post = Request::post('data');
        if (isset($post)) {
            $ticketsModel = $this->loadModel('TicketsModel');
            $request = $ticketsModel->saveSearch($post);
            if ($request == false) {
                $ret = array(
                    "sts" => "0",
                    "msg" => "Search can not be saved."
                );
            } else {
                $ret = array(
                    "sts" => "1",
                    "req" => $request
                );
            }
        } else {
            $ret = array(
                "sts" => "0",
                "msg" => "Sorry, something went wrong."
            );
        }

        echo json_encode($ret);
    }


}