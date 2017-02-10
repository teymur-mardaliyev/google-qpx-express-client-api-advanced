<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/2/15
 * Time: 7:19 PM
 */
class TicketAPI {

    public static $request;
    public static $airports = array();
    public static $locations = array();

    public static function setPassengers($type, $val) {
        @self::$request->request->passengers->$type = $val;
    }

    public static function setSlice($slice = array()) {
        self::$request->request->slice = $slice;
    }

    public static function SearchTickets() {

        self::$request->request->saleCountry = Config::get('SALECOUNTRY');
        self::$request->request->solutions = Config::get('SOLUTIONS');
        $data_string = json_encode(self::$request);

        $ch = curl_init('https://www.googleapis.com/qpxExpress/v1/trips/search?key=' . Config::get('GPX_API_KEY'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;

    }

    public static function getAirportName($code) {
        return self::$airports[$code];
    }

    public static function generateSlice($data) {

        switch ($data["multi"]) {
            case 0;
                return self::simpleSlice($data);
                break;
            default;
                return self::simpleSlice($data);
                break;
        }
    }


    public static function simpleSlice($data) {

        $error = false;
        $departureAirport = CheckTicketValues::IATACODE($data["departureAirport"]);
        $arrivalAirport = CheckTicketValues::IATACODE($data["arrivalAirport"]);
        $departureDate = CheckTicketValues::departureDate($data["departureDate"], "");
        $arrivalDate = CheckTicketValues::departureDate($data["arrivalDate"], "");
        $passengerClass = CheckTicketValues::preferredCabin($data["grade"]);

        if (empty($departureAirport) or empty($arrivalAirport)) {
            Session::add('feedback_negative', "Flight destinations are incorrect.");
            $error = true;
        }

        if (strtotime($departureDate) < strtotime(date("Y-m-d"))) {
            Session::add('feedback_negative', "Flight dates are incorrect.");
            $error = true;
        }

        if ($arrivalDate != '') {
            if (strtotime($arrivalDate) < strtotime(date("Y-m-d"))) {
                Session::add('feedback_negative', "Return date is incorrect.");
                $error = true;
            }
            if (strtotime($arrivalDate) < strtotime($departureDate)) {
                Session::add('feedback_negative', "Return date can not be before departure date.");
                $error = true;
            }
        }

        if ($error == false) {

            $slice = array(
                array(
                    "origin" => $departureAirport,
                    "destination" => $arrivalAirport,
                    "date" => $departureDate,
                    "preferredCabin" => $passengerClass
                )
            );

            if ($arrivalDate != '') {
                $slice[] = array(
                    "origin" => $arrivalAirport,
                    "destination" => $departureAirport,
                    "date" => $arrivalDate,
                    "preferredCabin" => $passengerClass
                );
            }

            return $slice;
        } else {
            return false;
        }

    }

    public static function AirportOnMap($data) {
        self::$locations[] = array(
            "departure_city" => self::unserRet($data->city),
            "departure_airport" => self::unserRet($data->name),
            "location" => $data->location,
            "airport_position" => array(
                "lat" => $data->lat,
                "lng" => $data->lng
            )
        );
    }

    public static function AircraftDB($data) {

        $return = new stdClass();
        foreach ($data as $key => $val) {
            $return->{$val->code} = $val;
        }

        return $return;
    }

    public static function CarrierDB($data) {

        $return = new stdClass();
        foreach ($data as $key => $val) {
            $return->{$val->code} = $val->name;
        }

        return $return;
    }

    public static function CityDB($data) {

        $return = new stdClass();
        foreach ($data as $key => $val) {
            $return->{$val->code} = $val->name;
        }

        return $return;
    }

    public static function currency($money) {
        $ret = preg_replace('/^([A-Z]{3})([0-9.]+)/i', "\\2<span class='currancy \\1'></span>", strtolower($money));

        return $ret;
    }

    public static function is_serialized($data) {
        return (@unserialize($data) !== false);
    }

    public static function unserRet($data) {

        $unserzed = @unserialize($data);

        if ((@unserialize($data) !== false) == true) {
            return $unserzed["en"];
        } else {
            return $data;
        }

    }

    public static function retSerTitle($data) {

        if (self::is_serialized($data) == false) {
            $title = $data;
        } else {

            $ser_title = self::unserRet($data);

            if ($ser_title["az"] != '') {
                $title = $ser_title["az"];
            } elseif ($ser_title["ru"] != '') {
                $title = $ser_title["ru"];
            } elseif ($ser_title["en"] != '') {
                $title = $ser_title["en"];
            } else {
                $title = $ser_title["loc"];
            }

        }
        return $title;
    }

    public static function TicketGrade($data) {
        switch ($data) {
            case 'COACH';
                return "economy";
                break;
            case 'PREMIUM_COACH';
                return "premium economy";
                break;
            case 'BUSINESS';
                return "business";
                break;
            case 'FIRST';
                return "first class";
                break;
            default;
                return "economy";
                break;
        }
    }

}