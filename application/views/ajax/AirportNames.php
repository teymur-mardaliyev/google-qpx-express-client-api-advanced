<?php
/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/1/15
 * Time: 7:55 PM
 */
$response = $this->loadModel("AirportModel")->searchAirportName();

$return = array();

foreach ($response as $key => $val) {


    $return[] = array(
        "name" => TicketAPI::unserRet($val->name),
        "code" => $val->code,
        "loc" => $val->location,
        "country" => TicketAPI::unserRet($val->country),
        "city" => TicketAPI::unserRet($val->city)
    );
}

echo json_encode($return);
