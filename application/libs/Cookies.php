<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/4/15
 * Time: 5:17 PM
 */
class Cookies {

    public static function getTicketCookieID() {
        if (Request::cookie('mytickets') != false) {
            $cookie_id = Request::cookie('mytickets');
        } else {
            $cookie_id = md5(time());
            setcookie('mytickets', $cookie_id, time() + Config::get('TICKET_COOKIE'), Config::get('COOKIE_PATH'));
        }

        return $cookie_id;
    }

}