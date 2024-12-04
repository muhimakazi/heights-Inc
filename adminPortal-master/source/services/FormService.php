<?php 

require_once(appSource."/entities/Form.php");

class FormService {
    
    public static function getFormCode() {
        $formObj = new Form();
        return $formObj->getRcordCode();
    }
    
    public static function insert($data) {
        return $data->insert($data);
    }
    
    public static function update($data) {
        return $data->update($data);
    }
    
    public static function exists($data) {
        return $data->exists($data);
    }

    public static function delete($formCode) {
        $formObj = new Form();
        return false;
    }
    
    public static function formsList($selection, $search_query) {
        $formObj = new Form();
        return $formObj->formsList($selection, $search_query);
    }
    
    public static function getFormCodeIfExists($data) {
        return $data->getFormCodeIfExists($data);
    }
    
    public static function find($form_code) {
        $formObj = new Form();
        return $formObj->find($form_code);
    }
    
    public static function getFormDetailsByFormCode($form_code) {
        $formObj = new Form();
        return $formObj->getFormDetailsByFormCode($form_code); 
    }

    public static function getFormDetailsByFormTypeAndEventID($form_type, $event_id) {
        $formObj = new Form();
        return $formObj->getFormDetailsByFormTypeAndEventID($form_type, $event_id); 
    }
    
    public static function formCodePrefix() {
        // Torus Registration Form
        return "TRF";
    }
}


?>