<?php 



class ParticipationSubType{


    private $_Table;

    // Constructor
    public function __construct()
    {
        $this->_Table='future_participation_sub_type';
    }

    // Setters & Getters


    // DML Methods
    
    public function subTypesList($selection, $search_query = '', $order = '', $limit = '')
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

    

    public function getSubTypeData($type, $subtype){
        $participants=array();
        $sql="";
        $params="";
        try{

            if($type!=''){
                $sql="SELECT * FROM ".$this->_Table." WHERE id= ? AND participation_type_id= ?";
                $params=array(
                    $subtype,
                    $type
                );
            } 

            $participants=DBConnection::getInstance()->query($sql, $params);

        } catch(Exception $e){
            echo $e->getMessage();
        }

        return $participants->results()[0];
    }
}

?>