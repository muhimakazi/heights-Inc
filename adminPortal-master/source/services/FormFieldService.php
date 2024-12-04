<?php

class FormFieldService {
    
    public static function getFormFieldCode(){
        $fieldObj = new FormField();
        return $fieldObj->getFormFieldCode();
    }
    
    public static function insert($data){
        return $data->insert($data);
    }
    
    public static function update($data){
        return $data->update($data);
    }
    
    public static function fieldExists($data){
        return $data->fieldExists($data);
    }
    
    public static function conflicts($data){
        return $data->conflicts($data);
    }
    
    public static function delete($code){
        $status = "DELETED";
        $fieldObj = new FormField();
        return $fieldObj->updateStatus($status, $code);
    }
    
    
    public static function fieldsList($selection, $search_query){
        $fieldObj = new FormField();
        return $fieldObj->fieldsList($selection, $search_query);
    }
    
    public static function getFormFieldData($field_code){
        $field_data = new stdClass();
        $selection = "*";
        $search_query = " WHERE field_code='".$field_code."'";
        
        $fieldObj = new FormField();
        $fields = $fieldObj->fieldsList($selection, $search_query);

        if(is_array($fields) && count($fields)>0){
            try{
               $field_data = $fields[0]; 
            } catch(Exception $e){
                $e->getMessage();
            }
        }
        return $field_data;
    }
    
    
    public static function fieldCodePrefix(){
        // Torus Form Field
        return "TFF";
    }
}

?>