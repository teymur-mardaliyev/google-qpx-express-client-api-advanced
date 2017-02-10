<?php
/**
 * Created by PhpStorm.
 * User: Tima
 * Date: 6/2/15
 * Time: 7:02 PM
 */

class Tickets extends Controller{

    public function request(){
        $post = Request::post('data');
        if(isset($post)) {
            $ticketsModel = $this->loadModel('TicketsModel');
            $request = $ticketsModel->saveSearch($post);
            if($request==false){
                Redirect::to('search/error');
            }else{
                Redirect::to('search/'.$request);
            }
        }else{
            Redirect::home();
        }
    }

    public function loadData(){
        try{
            $ticketsModel           =   $this->loadModel('TicketsModel');

            $data["request"]        =   $ticketsModel->getRequestByID(Request::post('request'));

            TicketAPI::$request     =   new stdClass();
            TicketAPI::setPassengers('adultCount',$data["request"]->adult);
            TicketAPI::setPassengers('childCount',$data["request"]->kids);
            TicketAPI::setPassengers('infantInLapCount',$data["request"]->baby);

            $slice = json_decode($data["request"]->slice);

            TicketAPI::setSlice($slice);

            $search_result          =   TicketAPI::SearchTickets();
            $search_result          =   json_decode($search_result);
            $search_result->search_options  = $data["request"];
            $search_result->result_id   =   $ticketsModel->createResultID($data["request"]);

            TicketsLogger::connection();
            TicketsLogger::saveLog($search_result,'tickets');

            TicketsLogger::saveLog($data["request"],'searchLogs');

            echo $search_result->result_id;
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public function search(){

        $ticketsModel           =   $this->loadModel('TicketsModel');

        $data["request"]        =   $ticketsModel->getRequestByID(Router::get('request'));
        $data["ticketModel"]    =   $ticketsModel;
        $data["airportModel"]   =   $this->loadModel('AirportModel');

        $slice = json_decode($data["request"]->slice);
        
        $data["slice"]      = $slice;
        $data["html_title"] = '';
        $data["title"]      = '';
        
        foreach($slice as $key => $val){
            $next = $key+1;
            if($data["request"]->multi==1 || $key==0) {
                $departure = $data["airportModel"]->getAirportByCode($val->origin);
                $data["html_title"] .=  '<span class="airport-city">'.TicketAPI::unserRet($departure->city).'</span>';
                $data["title"] .= TicketAPI::unserRet($departure->city);
                TicketAPI::AirportOnMap($departure);
            }

            $data["html_title"] .= ' <span class="longrightarrow"></span> ';
            $data["title"] .= ' - ';
            
            $arrival = $data["airportModel"]->getAirportByCode($val->destination);
            $data["html_title"] .= '<span class="airport-city">'. TicketAPI::unserRet($arrival->city).'</span>';
            $data["title"] .= TicketAPI::unserRet($arrival->city);
            
            TicketAPI::AirportOnMap($arrival);

            if($data["request"]->multi==1 && isset($slice[$next])){
                $data["html_title"] .= ' <span class="rang"></span> ';
                $data["title"] .= ' - ';
            }
        }
        
        $this->loadTemplate(array('_templates/header','tickets/search','_templates/footer'),$data);

    }

    public function result(){
        
        $ticketsModel           =   $this->loadModel('TicketsModel');
        $data["request"]        =   $ticketsModel->getRequestByID(Router::get('request'));
        $data["ticketModel"]    =   $ticketsModel;
        $data["airportModel"]   =   $this->loadModel('AirportModel');
        $data["result"]         =   $ticketsModel->getResultByID($data["request"]);


        if($data["result"]!=false){
            TicketsLogger::connection();
            $data["tickets"] = TicketsLogger::getTicket('tickets',array("result_id"=>$data["result"]->result_id));
            $page   = 'tickets/result';
        }else{
            $page   = 'tickets/search';
        }
        
        $slice = json_decode($data["request"]->slice);
        
        $data["slice"]      = $slice;
        $data["html_title"] = '';
        $data["title"]      = '';
        
        foreach($slice as $key => $val){
            $next = $key+1;
            if($data["request"]->multi==1 || $key==0) {
                $departure = $data["airportModel"]->getAirportByCode($val->origin);
                $data["html_title"] .=  '<span class="airport-city">'.TicketAPI::unserRet($departure->city).'</span>';
                $data["title"] .= TicketAPI::unserRet($departure->city);
                TicketAPI::AirportOnMap($departure);
            }

            $data["html_title"] .= ' <span class="longrightarrow"></span> ';
            $data["title"] .= ' - ';
            
            $arrival = $data["airportModel"]->getAirportByCode($val->destination);
            $data["html_title"] .= '<span class="airport-city">'. TicketAPI::unserRet($arrival->city).'</span>';
            $data["title"] .= TicketAPI::unserRet($arrival->city);
            
            TicketAPI::AirportOnMap($arrival);

            if($data["request"]->multi==1 && isset($slice[$next])){
                $data["html_title"] .= ' <span class="rang"></span> ';
                $data["title"] .= ' - ';
            }
        }
        
        $this->loadTemplate(array('_templates/header',$page,'_templates/footer'),$data);

    }

    public function error(){
        //if(count(Session::get('feedback_negative'))>0) {
            $this->loadTemplate(array('_templates/header', 'tickets/error', '_templates/footer'));
        //}else{
           // Redirect::home();
        //}
    }

}