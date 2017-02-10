<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/2/15
 * Time: 2:55 AM
 */
class AirportModel {

    public function searchAirportName() {

        $request = Request::post('name');

        $q = Registry::get('db')->prepare("SELECT * FROM `" . DB_PREFIX . "_iata_codes` WHERE `name` LIKE :request OR `city` LIKE :request OR `location` LIKE :request GROUP BY id ORDER BY `priority` ASC,city ASC LIMIT 0,10");

        $q->execute(array(
            ":request" => "" . $request . "%"
        ));

        return $q->fetchAll();
    }

    public function getAirportByCode($code) {

        $q = Registry::get('db')->prepare("SELECT * FROM `" . DB_PREFIX . "_iata_codes` WHERE `code`=:code GROUP BY `code` LIMIT 0,1");

        try {
            $q->execute(array(
                ":code" => $code
            ));

            $r = $q->fetch();

            $this->updateAirportUsed($r);

            return $r;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    public function updateAirportUsed($r) {

        $q = Registry::get('db')->prepare("UPDATE `" . DB_PREFIX . "_iata_codes` SET `used`=used+1 WHERE id=:id");
        $q->execute(array(":id" => $r->id));
    }


}