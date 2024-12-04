<?php 

class Form {
    
    private $id;
    private $form_code;
    private $client_id;
    private $event_id;
    private $form_type;
    private $form_sub_type;
    private $form_name;
    private $fields;
    private $status;
    private $added_date;
    private $added_time;
    private $added_by;

    private $_Table;


    public function getId() {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getFormCode() {
        return $this->form_code;
    }

    public function setFormCode( $form_code) {
        $this->form_code = $form_code;
    }

    public function getClientId() {
        return $this->client_id;
    }

    public function setClientId( $client_id) {
        $this->client_id = $client_id;
    }

    public function getEventId() {
        return $this->event_id;
    }

    public function setEventId( $event_id) {
        $this->event_id = $event_id;
    }

    public function getFormType() {
        return $this->form_type;
    }

    public function setFormType( $form_type) {
        $this->form_type = $form_type;
    }

    public function getFormSubType() {
        return $this->form_sub_type;
    }

    public function setFormSubType( $form_sub_type) {
        $this->form_sub_type = $form_sub_type;
    }

    public function getFormName() {
        return $this->form_name;
    }

    public function setFormName( $form_name) {
        $this->form_name = $form_name;
    }

    public function getFields() {
        return $this->fields;
    }

    public function setFields( $fields) {
        $this->fields = $fields;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus( $status) {
        $this->status = $status;
    }

    public function getAddedDate() {
        return $this->added_date;
    }

    public function setAddedDate( $added_date) {
        $this->added_date = $added_date;
    }

    public function getAddedTime() {
        return $this->added_time;
    }

    public function setAddedTime( $added_time) {
        $this->added_time = $added_time;
    }

    public function getAddedBy() {
        return $this->added_by;
    }

    public function setAddedBy( $added_by) {
        $this->added_by = $added_by;
    }


    public function __construct()
    {
        $this->_Table="torus_forms";
    }


    // Generating Form codes
    public function getRcordCode()
    {
        $rows = "0";
        $code = "";

        $sql = "SELECT COUNT(id) AS total_row FROM " . $this->_Table . " WHERE added_date BETWEEN '" . Time::getFirstDateOfCurrentYear() . "'  AND  '" . Time::getLastDateOfCurrentYear() . "'";
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

        // standing for Torus Registration Form
        $code = "TRF" . Time::getYearIn2Digits() . "" . Time::getCurrentMonth() . "" . $prefix . "" . $rows;

        return $code;
    }

    public function insert($data) {
        $sql = "INSERT INTO ".$this->_Table."(form_code, client_id, event_id, form_type, form_sub_type, form_name, fields, status, added_date, added_time, added_by) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
       
        try{
            $params=array(
                $data->getFormCode(),
                $data->getClientID(),
                $data->getEventID(),
                $data->getFormType(),
                $data->getFormSubType(),
                $data->getFormName(),
                $data->getFields(),
                $data->getStatus(),
                $data->getAddedDate(),
                $data->getAddedTime(),
                $data->getAddedBy()
            );
            
            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch(Exception $e){
            throw $e->getMessage();
        }
        
        return false;
    }

    public function update($data) {
        $sql = "UPDATE ".$this->_Table." SET fields= ? WHERE form_code= ?";
       
        try{
            $params=array(
                $data->getFields(),
                $data->getFormCode()
            );
            
            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch(Exception $e){
            throw $e->getMessage();
        }
        
        return false;
    }

    public function formExists($data){

        try{
            $sql="SELECT id, form_code FROM ".$this->_Table." WHERE form_code= ? OR (form_type= ? AND form_sub_type= ? AND event_id= ? AND form_name= ?)";
            
            $params=array(
                $data->getFormCode(),
                $data->getFormType(),
                $data->getFormSubType(),
                $data->getEventId(),
                $data->getFormName()
            );
            
            $fields = DBConnection::getInstance()->query($sql, $params);
    
            if ($fields->count()) {
                return true;
            }

        } catch(Exception $e){
            throw $e->getMessage();
        }

        return false;
    }

    public function formsList($selection, $search_query) {
        $formsList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query;

            $forms = DBConnection::getInstance()->query($sql);

            if ($forms->count()) {
                $formsList = $forms->results();
            }
        } catch (Exception $e) {
            throw $e->getMessage();
        }

        return json_encode($formsList);
    }

    public function getFormCodeIfExists($data) {
        $form_code = "";

        try {
            $sql = "SELECT id, form_code FROM ".$this->_Table." WHERE form_code= ? OR (form_type= ? AND form_sub_type= ? AND event_id= ? AND form_name= ?)";

            $params=array(
                $data->getFormCode(),
                $data->getFormType(),
                $data->getFormSubType(),
                $data->getEventId(),
                $data->getFormName()
            );

            $forms = DBConnection::getInstance()->query($sql, $params);

            if ($forms->count()) {
                $form_code = $forms->first()->form_code;
            }
        } catch (Exception $e) {
            throw $e->getMessage();
        }

        return $form_code;
    }

    public function getFormDetailsByFormCode($form_code) {
        $formDetails = "";

        try {
            $sql = "SELECT id, fields FROM ".$this->_Table." WHERE form_code= ? AND status!= ? order by id ASC";

            $params=array(
                $form_code,
                "DELETED"
            );

            $forms = DBConnection::getInstance()->query($sql, $params);

            if ($forms->count()) {
                $formDetails = $forms->first()->fields;
            }
        } catch (Exception $e) {
            throw $e->getMessage();
        }

        return json_encode($formDetails);
    }

    public function getFormDetailsByFormTypeAndEventID($form_type, $event_id) {
        $formDetails = "";

        try {
            $sql = "SELECT id, fields FROM ".$this->_Table." WHERE form_type= ? AND event_id= ? AND status!= ? order by id ASC";

            $params=array(
                $form_type,
                $event_id,
                "DELETED"
            );

            $forms = DBConnection::getInstance()->query($sql, $params);

            if ($forms->count()) {
                $formDetails = $forms->first()->fields;
            }
        } catch (Exception $e) {
            throw $e->getMessage();
        }

        return json_encode($formDetails);
    }

    public function find($form_code) {
        
        try {
            $sql = "SELECT id, form_code FROM ".$this->_Table." WHERE form_code= ?";

            $params=array(
                $form_code,
            );

            $forms = DBConnection::getInstance()->query($sql, $params);

            if ($forms->count()) {
                return true;
            }
        } catch (Exception $e) {
            throw $e->getMessage();
        }

        return false;
    }
    
}


?>