<?php 

class FormField {
    private $id;
    private $field_code;
    private $field_category;
    private $field_type;
    private $field_label;
    private $is_required;
    private $field_placeholder;
    private $default_value;
    private $status;
    private $added_date;
    private $added_time;
    private $added_by;
    private $field_value_type;
    private $field_values;
    private $specific_to;
    
    private $_Table;

    public function getId()  {
        return $this->id;
    }

    public function setId( $id)  {
        $this->id = $id;
    }

    public function getFieldCode()  {
        return $this->field_code;
    }

    public function setFieldCode( $field_code)  {
        $this->field_code = $field_code;
    }

    public function getFieldCategory()  {
        return $this->field_category;
    }

    public function setFieldCategory( $field_category)  {
        $this->field_category = $field_category;
    }

    public function getFieldType()  {
        return $this->field_type;
    }

    public function setFieldType( $field_type)  {
        $this->field_type = $field_type;
    }

    public function getFieldLabel()  {
        return $this->field_label;
    }

    public function setFieldLabel( $field_label)  {
        $this->field_label = $field_label;
    }

    public function getIsRequired()  {
        return $this->is_required;
    }

    public function setIsRequired( $is_required)  {
        $this->is_required = $is_required;
    }

    public function getFieldPlaceholder()  {
        return $this->field_placeholder;
    }

    public function setFieldPlaceholder( $field_placeholder)  {
        $this->field_placeholder = $field_placeholder;
    }

    public function getDefaultValue()  {
        return $this->default_value;
    }

    public function setDefaultValue( $default_value)  {
        $this->default_value = $default_value;
    }

    public function getStatus()  {
        return $this->status;
    }

    public function setStatus( $status)  {
        $this->status = $status;
    }

    public function getAddedDate()  {
        return $this->added_date;
    }

    public function setAddedDate( $added_date)  {
        $this->added_date = $added_date;
    }

    public function getAddedTime()  {
        return $this->added_time;
    }

    public function setAddedTime( $added_time)  {
        $this->added_time = $added_time;
    }

    public function getAddedBy()  {
        return $this->added_by;
    }

    public function setAddedBy( $added_by)  {
        $this->added_by = $added_by;
    }

    public function getFieldValueType()  {
        return $this->field_value_type;
    }

    public function setFieldValueType( $field_value_type)  {
        $this->field_value_type = $field_value_type;
    }

    public function getFieldValues()  {
        return $this->field_values;
    }

    public function setFieldValues( $field_values)  {
        $this->field_values = $field_values;
    }

    public function getSpecificTo()  {
        return $this->specific_to;
    }

    public function setSpecificTo( $specific_to)  {
        $this->specific_to = $specific_to;
    }


    public function __construct()
    {
        $this->_Table="torus_form_fields";
    }

    // Generating Form Field codes
    public function getFormFieldCode()
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

        // standing for Torus Form Field
        $code = "TFF" . Time::getYearIn2Digits() . "" . Time::getCurrentMonth() . "" . $prefix . "" . $rows;

        return $code;
    }

    public function insert($data) {
        $sql = "INSERT INTO ".$this->_Table."(field_code, field_category, field_type, field_label, is_required, field_placeholder, default_value, status, added_date, added_time, added_by, field_value_type, field_values, specific_to) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
       
        try{
            $params=array(
                $data->getCode(),
                $data->getFieldCategory(),
                $data->getFieldType(),
                $data->getFieldLabel(),
                $data->getIsRequired(),
                $data->getFieldPlaceholder(),
                $data->getDefaultValue(),
                $data->getStatus(),
                $data->getAddedDate(),
                $data->getAddedTime(),
                $data->getAddedBy(),
                $data->getFieldValueType(),
                $data->getValues(),
                $data->getSpecificTo(),
            );
            
            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch(Exception $e){
            throw $e->getMessage();
        }
        
        return false;
    }

    public function update($data) {
        $sql = "UPDATE ".$this->_Table." SET field_category= ?, field_type= ?, field_label= ?, is_required= ?, field_placeholder= ?, default_value= ?, field_value_type= ?, field_values= ?, specific_to= ? WHERE field_code= ?";
        
        try{
            $params=array(
                $data->getFieldCategory(),
                $data->getFieldType(),
                $data->getFieldLabel(),
                $data->getIsRequired(),
                $data->getFieldPlaceholder(),
                $data->getDefaultValue(),
                $data->getFieldValueType(),
                $data->getValues(),
                $data->getSpecificTo(),
                $data->getCode(),
            );
            
            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch(Exception $e){
            throw $e->getMessage();
        }
        
        return false;
    }

    public function updateStatus($status, $code){
        try{
            $sql="UPDATE ".$this->_Table." SET status= ? WHERE field_code= ?";
            
            $params=array(
                $status,
                $code
            );
            
            DBConnection::getInstance()->query($sql, $params);
    
            return true;

        } catch(Exception $e){
            throw $e->getMessage();
        }

        return false;
    }

    public function fieldExists($data){

        try{
            $sql="SELECT id FROM ".$this->_Table+" WHERE field_code= ? OR field_label= ?";
            
            $params=array(
                $data->getCode(),
                $data->getFieldLabel()
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

    public function conflicts($data){

        try{
            $sql="SELECT id FROM ".$this->_Table+" WHERE (field_code= ? OR field_label= ?) AND field_code!= ?";
            
            $params=array(
                $data->getCode(),
                $data->getFieldLabel(),
                $data->getCode(),
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

    public function fieldsList($selection, $search_query) {
        $fieldsList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query;

            $fields = DBConnection::getInstance()->query($sql);

            if ($fields->count()) {
                $fieldsList = $fields->results();
            }
        } catch (Exception $e) {
            throw $e->getMessage();
        }

        return json_encode($fieldsList);
    }
}

?>