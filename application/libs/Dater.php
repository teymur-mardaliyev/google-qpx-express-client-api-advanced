<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 2/11/15
 * Time: 3:08 AM
 */
class Dater {

    protected $args = array();

    function __construct($args = array()) {
        $this->args = $args;
    }


    public function toDateTime($str) {

        $str = date('Y-m-d G:i:s', $str);
        $val = explode(" ", $str);
        $dateval = explode("-", $val[0]);

        $dateval = $dateval[2] . " " . Timer::$date_names[$dateval[1]] . " " . $dateval[0];
        return $dateval;
    }

    public function time2str($ts) {

        if (!ctype_digit($ts))
            $ts = strtotime($ts);
        $diff = time() - $ts;
        if ($diff == 0)
            return 'now';
        elseif ($diff > 0) {

            $day_diff = floor($diff / 86400);

            if ($day_diff == 0) {

                if ($diff < 60) return 'seconds before';
                if ($diff < 120) return '1 minute before';
                if ($diff < 3600) return floor($diff / 60) . ' minute before';
                if ($diff < 7200) return '1 saat öncə';
                if ($diff < 86400) return floor($diff / 3600) . ' hour before';

            }

            if ($day_diff == 1) return 'yesturday';
            if ($day_diff < 7) return $day_diff . ' day before';
            if ($day_diff < 31) return ceil($day_diff / 7) . ' week before';
            if ($day_diff < 60) return 'last month';

            return $this->toDateTime($ts);

        }

    }

    public static function humanTiming($time) {

        $time = time() - $time; // to get the time since that moment

        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'sec.'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text;
        }

    }

    public function mkrtimeToDatetime($datetime, $mkrtime) {
        return date($datetime, $mkrtime);
    }


}