<?php
/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 5/20/15
 * Time: 12:14 AM
 */

class Airports extends Controller{
    /**
     * PAGE: index
     * This method handles what
     */

    public function SearchAirport(){
        $this->loadTemplate(array('ajax/AirportNames'));
    }

}
