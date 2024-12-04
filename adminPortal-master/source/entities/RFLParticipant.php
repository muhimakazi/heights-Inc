<?php 

class RFLParticipant{

    private $id;
    private $participant_code;
    private $event_id;
    private $email_id;
    private $organisation_id;
    private $participant_pass_type;
    private $participant_pass_amount;
    private $participant_data;
    private $status;
    private $registration_date;
    private $registration_time;
    private $registered_from;

    private $_Table="";

    // Setters and Getters
    public function setID($id){
        $this->id=$id;
    }

    public function getID(){
        return $this->id;
    }

    public function setCode($participant_code){
        $this->participant_code=$participant_code;
    }

    public function getCode(){
        return $this->participant_code;
    }

    public function setEventID($event_id){
        $this->event_id=$event_id;
    }

    public function getEventID(){
        return $this->event_id;
    }

    public function setEmailID($email_id){
        $this->email_id=$email_id;
    }

    public function getEmailID(){
        return $this->email_id;
    }

    public function setOrganisationID($organisation_id){
        $this->organisation_id=$organisation_id;
    }

    public function getOrganisationID(){
        return $this->organisation_id;
    }

    public function setParticipantPassType($participant_pass_type){
        $this->participant_pass_type=$participant_pass_type;
    }

    public function getParticipantPassType(){
        return $this->participant_pass_type;
    }

    public function setParticipantPassAmount($participant_pass_amount){
        $this->participant_pass_amount=$participant_pass_amount;
    }

    public function getParticipantPassAmount(){
        return $this->participant_pass_amount;
    }

    public function setParticipantData($participant_data){
        $this->participant_data=$participant_data;
    }

    public function getParticipantData(){
        return $this->participant_data;
    }

    public function setStatus($status){
        $this->status=$status;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setRegistrationDate($registration_date){
        $this->registration_date=$registration_date;
    }

    public function getRegistrationDate(){
        return $this->registration_date;
    }

    public function setRegistrationTime($registration_time){
        $this->registration_time=$registration_time;
    }

    public function getRegistrationTime(){
        return $this->registration_time;
    }

    public function setRegisteredFrom($registered_from){
        $this->registered_from=$registered_from;
    }

    public function getRegisteredFrom(){
        return $this->registered_from;
    }


    // Constructor
    public function __construct()
    {
        $this->_Table="future_rfl_particpants";
    }


    // Generating Participant codes
    public function getParticipantCode()
    {
        $rows = "0";
        $code = "";

        $sql = "SELECT COUNT(id) AS total_row FROM " . $this->_Table . " WHERE registration_date BETWEEN '" . Time::getFirstDateOfCurrentYear() . "'  AND  '" . Time::getLastDateOfCurrentYear() . "'";
        $users = DBConnection::getInstance()->query($sql);

        if ($users->count()) {
            $rows = (int) ($users->first()->total_row + 1);
        }

        $size = 5 - strlen($rows);
        $prefix = "";
        if ($size != 0) {
            for ($i = 0; $i < $size; $i++) {
                $prefix = $prefix . "0";
            }
        }

        // standing for RFL Participant
        $code = "RFL-P" . Time::getYearIn2Digits() . "" . Time::getCurrentMonth() . "" . $prefix . "" . $rows;

        return $code;
    }

    public function insert($data){

        $sql="INSERT INTO ".$this->_Table."(participant_code, event_id, email_id, organisation_id, participant_pass_type, participant_pass_amount, participant_data, status, registration_date, registration_time, registered_from) VALUES(?,?,?,?,?,?,?,?,?,?,?)";

        try{
            $params=array(
                $data->getCode(),
                $data->getEventID(),
                $data->getEmailID(),
                $data->getOrganisationID(),
                $data->getParticipantPassType(),
                $data->getParticipantPassAmount(),
                $data->getParticipantData(),
                $data->getStatus(),
                $data->getRegistrationDate(),
                $data->getRegistrationTime(),
                $data->getRegisteredFrom()
            );
            
            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch(Exception $e){
            throw $e->getMessage();
        }

        return false;
    }

    public function exists($data)
    {

        try {
            $sql = "SELECT id, participant_code FROM " . $this->_Table . " WHERE participant_code= ? OR ((email_id= ? AND organisation_id= ?) AND event_id= ?)";
            $params=array(
                $data->getCode(),
                $data->getEmailID(),
                $data->getOrganisationID(),
                $data->getEventID()
            );
            
            $users = DBConnection::getInstance()->query($sql, $params);

            if ($users->count()) {
                return true;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return false;
    }

    public function participantsList($selection, $search_query = '', $order = '', $limit = ''){
        $participantsList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query . " " . $order . " " . $limit;

            $participants = DBConnection::getInstance()->query($sql);

            if ($participants->count()) {
                $participantsList = $participants->results();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return json_encode($participantsList);      
    }

    public function getParticipantPassPrice($id)
    {
        $amount=0.0;
        try {
            $sql = "SELECT id, participant_code, participant_pass_amount FROM " . $this->_Table . " WHERE id= ?";
            $params=array(
                $id
            );
            
            $users = DBConnection::getInstance()->query($sql, $params);

            if ($users->count()) {
                $amount= $users->first()->participant_pass_amount;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $amount;
    }

}

?>