<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/5/15
 * Time: 2:29 PM
 */
class CheckTicketValues {

    public static function departureDate($date, $return = false) {
        return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date) != false ? $date : $return;
    }

    public static function isnumeric($value, $return) {
        return preg_match('/(^[' . $return . '-9]{1})$/i', $value) != false ? $value : $return;
    }

    public static function IATACODE($code, $return = '') {
        return preg_match('/(^[A-Z]{3})$/i', $code) != false ? $code : $return;
    }

    public static function preferredCabin($grade) {
        switch ($grade) {
            case 1;
                return 'COACH';
                break;
            case 2;
                return 'PREMIUM_COACH';
                break;
            case 3;
                return 'BUSINESS';
                break;
            case 4;
                return 'FIRST';
                break;
            default;
                return 'COACH';
                break;
        }
    }

}