<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/3/15
 * Time: 9:13 PM
 */
class Timer {

    public static $date_names = array(

        "month" => array(
            "01" => "January",
            "02" => "February",
            "03" => "March",
            "04" => "April",
            "05" => "May",
            "06" => "June",
            "07" => "July",
            "08" => "August",
            "09" => "September",
            "10" => "October",
            "11" => "November",
            "12" => "December",
        ),
        "month_short" => array(
            "01" => "Jan",
            "02" => "Feb",
            "03" => "Mar",
            "04" => "Apr",
            "05" => "May",
            "06" => "June",
            "07" => "July",
            "08" => "Aug.",
            "09" => "Sept.",
            "10" => "Oct.",
            "11" => "Nov.",
            "12" => "Dec.",
        ),
        "week" => array(
            "0" => "Sunday",
            "1" => "Monday",
            "2" => "Tuesday",
            "3" => "Wednesday",
            "4" => "Thursday",
            "5" => "Friday",
            "6" => "Saturday",
        ),
        "week_short" => array(
            "0" => "Sun.",
            "1" => "Mon.",
            "2" => "Tu.",
            "3" => "Wen.",
            "4" => "Th.",
            "5" => "Fri.",
            "6" => "Sat.",
        ),

        "time_names" => array(
            "sec" => "secund",
            "min" => "minute",
            "hour" => "hour",
            "day" => "day",
            "week" => "week",
            "year" => "year",
        )
    );

    public static function convertDate($from, $to) {

        $old = strtotime($from);

        return date($to, $old);
    }

    public static function convertLocDate($from, $to) {

        $old = strtotime($from);
        $locale = 'en_US.UTF-8';
        setlocale(LC_TIME, $locale);
        return strftime($to, $old);
    }

    public static function convertHumanReadable($time) {
        $seconds = $time * 60;
        $hours = floor($seconds / 3600);
        $min = floor(($seconds - ($hours * 3600)) / 60);

        return $hours . " " . self::$date_names['time_names']["hour"] . " " . $min . " " . self::$date_names['time_names']["min"];
    }


}