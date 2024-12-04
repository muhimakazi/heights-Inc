<?php 


class FormFieldCategory {

    private $id;
    private $category_code;
    private $category_name;
    private $status;
    private $added_date;
    private $added_time;
    private $added_by;
    private $_Table;
    
    public function setID($id) {
        $this->id = $id;
    }
    
    public function getID() {
        return $this->id;
    }
    
    public function setCode($category_code) {
        $this->category_code = $category_code;
    }
    
    public function getCode() {
        return $this->category_code;
    }
    
    public function setCategoryName($category_name) {
        $this->category_name = $category_name;
    }
    
    public function getCategoryName() {
        return $this->category_name;
    }
    
    public function setStatus($status) {
        $this->status = $status;
    }
    
    public function getStatus() {
        return $this->status;
    }
    
    public function setAddedDate($added_date) {
        $this->added_date = $added_date;
    }
    
    public function getAddedDate() {
        return $this->added_date;
    }
    
    public function setAddedTime($added_time) {
        $this->added_time = $added_time;
    }
    
    public function getAddedTime() {
        return $this->added_time;
    }
    
    public function setAddedBy($added_by) {
        $this->added_by = $added_by;
    }
    
    public function getAddedBy() {
        return $this->added_by;
    }
    
    public function __construct() {
        $this->_Table = "torus_form_field_category";
    }
    
    public function insert($data) {
        $sql = "INSERT INTO ".$this->_Table."(category_code, category_name, status, added_date, added_time, added_by) VALUES (?,?,?,?,?,?)";
        
        try{
            $params=array(
                $data->getCode(),
                $data->getCategoryName(),
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
    
    public function categoryList($selection, $search_query) {
        $categoryList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query;

            $categories = DBConnection::getInstance()->query($sql);

            if ($categories->count()) {
                $categoryList = $categories->results();
            }
        } catch (Exception $e) {
            throw $e->getMessage();
        }

        return json_encode($categoryList);
    }

    public function categoryExists($data){

        try{
            $sql="SELECT id, category_code FROM ".$this->_Table." WHERE (category_code= ? OR upper(category_name)= upper(?)) ORDER BY id ASC LIMIT 1";
            
            $params=array(
                $data->getCode(),
                $data->getCategoryName()
            );
            
            $categories = DBConnection::getInstance()->query($sql, $params);
    
            if ($categories->count()) {
                return true;
            }

        } catch(Exception $e){
            throw $e->getMessage();
        }

        return false;
    }

    public function conflicts($name, $id){

        try{
            $sql="SELECT id, category_code FROM ".$this->_Table." WHERE (upper(category_name)=upper(?)) AND (id!= ?) ORDER BY id ASC LIMIT 1";
            
            $params=array(
                $name,
                $id
            );
            
            $categories = DBConnection::getInstance()->query($sql, $params);
    
            if ($categories->count()) {
                return true;
            }

        } catch(Exception $e){
            throw $e->getMessage();
        }

        return false;
    }

    public function updateStatus($status, $id){
        try{
            $sql="UPDATE ".$this->_Table." SET status= ? WHERE id= ?";
            
            $params=array(
                $status,
                $id
            );
            
            DBConnection::getInstance()->query($sql, $params);
    
            return true;

        } catch(Exception $e){
            throw $e->getMessage();
        }

        return false;
    }

    public function rename($name, $id){
        try{
            $sql="UPDATE ".$this->_Table." SET category_name= ? WHERE id= ?";
            
            $params=array(
                $name,
                $id
            );
            
            DBConnection::getInstance()->query($sql, $params);
    
            return true;

        } catch(Exception $e){
            throw $e->getMessage();
        }

        return false;
    }

    public function getCategoryCodeByID($id){
        $categoryCode="";
        try{
            $sql="SELECT category_code FROM ".$this->_Table." WHERE id= ?";
            
            $params=array(
                $id
            );
            
            $categories = DBConnection::getInstance()->query($sql, $params);
    
            if ($categories->count()) {
                $categoryCode=$categories->first()->category_code;
            }

        } catch(Exception $e){
            throw $e->getMessage();
        }

        return $categoryCode;
    }
    
} 
?>