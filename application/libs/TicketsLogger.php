<?php

/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/6/15
 * Time: 5:48 PM
 */
class TicketsLogger {

    public static $db;

    public static function connection() {
        $m = new MongoClient("mongodb://" . Config::get('MONGODB_HOST'));
        self::$db = $m->{Config::get('MONGODB_NAME')};
    }

    public static function saveLog($data, $collection, $is_array = false) {

        if ($is_array == false) {
            $data = (array)$data;
        }

        try {
            $collection = self::$db->{$collection};
            $collection->insert($data);

        } catch (MongoException $e) {
            echo $e->getMessage();
        }

    }

    public static function getTicket($collection, $query = array(), $fields = array()) {

        try {
            $collection = self::$db->{$collection};
            $data = $collection->findOne($query, $fields);
            return json_decode(json_encode($data));
        } catch (MongoException $e) {
            echo $e->getMessage();
        }
    }


}
