<?php 

class ParticipationType{

    // Table Attributes
    private $id;
    private $code;
    private $name;
    private $payment_state;
    private $event_level;
    private $event_id;
    private $sub_type_state;
    private $visibility_sate;
    private $form_order;
    private $status;
    private $created_by;
    private $creation_date;

    private $_Table;
    private $_payment_entry_table;

    // Constructor
    public function __construct()
    {
        $this->_Table='future_participation_type';
    }

    // Setters & Getters


    // DML Methods
    
    public function participationTypeList($selection, $search_query = '', $order = '', $limit = '')
    {
        $userList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query . " " . $order . " " . $limit;

            $users = DBConnection::getInstance()->query($sql);

            if ($users->count()) {
                $userList = $users->results();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return json_encode($userList);
    }

    public function getTypeName($type){
        $typeName="";
        $sql="";
        $params="";
        try{

            if($type!=''){
                $sql="SELECT id, name as type_name FROM ".$this->_Table." WHERE id= ?";
                $params=array(
                    $type
                );
            } 

            $participants=DBConnection::getInstance()->query($sql, $params);
            $typeName= $participants->results()[0]->type_name;
        } catch(Exception $e){
            echo $e->getMessage();
        }

        return $typeName;
    }

    

}