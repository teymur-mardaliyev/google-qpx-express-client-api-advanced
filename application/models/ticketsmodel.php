<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/2/15
 * Time: 7:03 PM
 */
class TicketsModel {

    public function saveSearch($data) {

        foreach ($data as $key => $val) {
            $$key = !is_array($val) ? htmlspecialchars($val) : "";
        }

        $departureAirport = isset($departureAirport) ? CheckTicketValues::IATACODE($departureAirport) : "";
        $arrivalAirport = isset($arrivalAirport) ? CheckTicketValues::IATACODE($arrivalAirport) : "";
        $departureDate = isset($departureDate) ? CheckTicketValues::departureDate($departureDate) : "";
        $arrivalDate = isset($arrivalDate) ? CheckTicketValues::departureDate($arrivalDate) : "";

        $q = Registry::get('db')->prepare("INSERT INTO `" . DB_PREFIX . "_ticket_search_options`
                            (`request_id`,`session_id`,`cookie_id`,`multi`,`slice` , `departureAirport`, `arrivalAirport`,
                             `departureDate`,`flightbackDate`, `passengerClass`, `adult`, `kids`, `baby`,
                             `ip`,`error_info`, `datetime`)
                            VALUES (:request_id,:session_id,:cookie_id,:multi,:slice,:departureAirport,:arrivalAirport,
                            :departureDate,:flightbackDate,:passengerClass,:adult,:kids,:baby,:ip,:error_info,:datetime)");

        $slice = TicketAPI::generateSlice($data);

        if ($slice != false) {
            $slice = json_encode($slice);
            if ($multi == 0) {
                $error_info = strtotime($departureDate) < strtotime(date("Y-m-d")) ? "ticket-departureDate" : "";
            }


            $request_id = md5(time());

            try {

                $q->execute(array(
                    ":request_id" => $request_id,
                    ":session_id" => session_id(),
                    ":cookie_id" => Cookies::getTicketCookieID(),
                    ":multi" => "$multi",
                    ":slice" => "$slice",
                    ":departureAirport" => $departureAirport,
                    ":arrivalAirport" => $arrivalAirport,
                    ":departureDate" => $departureDate,
                    ":flightbackDate" => $arrivalDate,
                    ":passengerClass" => CheckTicketValues::preferredCabin($grade),
                    ":adult" => CheckTicketValues::isnumeric($adult, 1),
                    ":kids" => CheckTicketValues::isnumeric($kids, 0),
                    ":baby" => CheckTicketValues::isnumeric($baby, 0),
                    ":ip" => $_SERVER['REMOTE_ADDR'],
                    ":error_info" => $error_info,
                    ":datetime" => Config::get('DATETIME')
                ));

                return $request_id;

            } catch (PDOException $e) {
                echo $e->getMessage();
            }

        } else {
            return false;
        }
    }


    public function getRequestByID($request) {

        $q = Registry::get('db')->prepare("SELECT * FROM `" . DB_PREFIX . "_ticket_search_options` AS srop
                            WHERE `request_id`=:request_id GROUP BY `id` LIMIT 0,1");

        try {
            $q->execute(array(
                ":request_id" => $request
            ));

            return $q->fetch();

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function createResultID($request) {

        $q = Registry::get('db')->prepare("INSERT INTO `" . DB_PREFIX . "_ticket_search_results`
        (`result_id`, `search_id`, `request_id`, `session_id`, `used`, `ip`, `datetime`)
        VALUES
        (:result_id,:search_id,:request_id,:session_id,'0',:ip,:datetime)");

        $result_id = md5(time());

        try {

            $q->execute(array(
                ":result_id" => $result_id,
                ":search_id" => $request->id,
                ":request_id" => $request->request_id,
                ":session_id" => session_id(),
                ":ip" => $_SERVER["REMOTE_ADDR"],
                ":datetime" => Config::get('DATETIME')
            ));

            return $result_id;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getResultByID($request) {

        $q = Registry::get('db')->prepare("SELECT * FROM `" . DB_PREFIX . "_ticket_search_results`
                                WHERE
                                `result_id`=:result_id AND `search_id`=:search_id AND
                                (UNIX_TIMESTAMP(`datetime`) + (60*10))>UNIX_TIMESTAMP() AND `ip`=:ip ");

        try {

            $q->execute(array(
                ":result_id" => Router::get('result_id'),
                ":search_id" => $request->id,
                ":ip" => $_SERVER["REMOTE_ADDR"]
            ));

            if ($q->rowCount() > 0) {
                return $q->fetch();
            } else {
                return false;
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }


    }

    public function updateResultByID($request) {

        $q = Registry::get('db')->prepare("UPDATE `" . DB_PREFIX . "_ticket_search_results` SET `used`=used+1 WHERE `result_id`=:result_id AND `search_id`=:search_id");
        try {

            $q->execute(array(
                ":result_id" => Router::get('result_id'),
                ":search_id" => $request->id
            ));

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function AirportDB($data) {

        $airports = array();

        $q = Registry::get('db')->prepare("SELECT
                                  `code`, `icao`, `name`, `location`, `lat`, `lng`, `country`,
                                  `city`, `timezone`, `json`, `used`, `status`
                                  FROM
                                  `" . DB_PREFIX . "_iata_codes`
                                  WHERE
                                  `code`=:code AND `status`<>'0'");

        foreach ($data as $key => $val) {

            $q->execute(array(
                ":code" => $val->code
            ));

            $airports[$val->code]["google_name"] = $val->name;
            $airports[$val->code]["city_code"] = $val->city;
            $airports[$val->code]["data"] = $q->fetch();
        }

        return Request::jende($airports);
    }


    public function getLastSearchs($mytickets = false) {

        $sql = "AND `cookie_id`";
        $sql .= ($mytickets == true && Request::cookie('mytickets') == true) ? "=" : "<>";
        $sql .= "'" . Request::cookie('mytickets') . "'";
        $q = Registry::get('db')->prepare("SELECT
                                `id`, `request_id`, `slice`, `departureAirport`,`arrivalAirport`,`multi`
                              FROM
                                `" . DB_PREFIX . "_ticket_search_options`
                              WHERE
                              (UNIX_TIMESTAMP(`departureDate`) + (60*60*24))>UNIX_TIMESTAMP() AND
                              `status`=:status AND `multi`=:multi " . $sql . "
                              GROUP BY
                              arrivalAirport,departureAirport
                              ORDER BY
                              `datetime`
                              DESC
                              LIMIT 0,5");
        try {
            $q->execute(array(
                ":status" => 1,
                ":multi" => 0
            ));

            return $q->fetchAll();

        } catch (PDOException $e) {
            echo $e->getMessage();
        }


    }


}