<?php 



class Participant{


    private $_Table;

    // Constructor
    public function __construct()
    {
        $this->_Table='future_participants';
    }

    // Setters & Getters


    // DML Methods
    
    public function participantsList($selection, $search_query = '', $order = '', $limit = '')
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

    // public function getParticipantsNumberByAccessType($access_type, $event_code){
    //     $participants=0;
    //     $sql="";
    //     $params="";
    //     try{

    //         if($access_type=='PAYING'){
    //             $sql="SELECT COUNT(id) AS NUMBER FROM ".$this->_Table." WHERE event_id= ? AND status= ? AND id IN (SELECT participant_id FROM ".$this->_Payment_entry_table." WHERE event_id= ? AND (transaction_type= ? AND transaction_status!= ?))";
    //             $params=array(
    //                 $event_code,
    //                 'APPROVED',
    //                 $event_code,
    //                 'PAY_EVENT',
    //                 'IGNORED'
    //             );
    //         } else if($access_type=='FREE'){
    //             $sql="SELECT COUNT(id) AS NUMBER FROM ".$this->_Table." WHERE event_id= ? AND status= ? AND id NOT IN (SELECT participant_id FROM ".$this->_Payment_entry_table." WHERE event_id= ?)";
    //             $params=array(
    //                 $event_code,
    //                 'APPROVED',
    //                 $event_code
    //             );
    //         }

    //         $participants=DBConnection::getInstance()->query($sql, $params);

    //     } catch(Exception $e){
    //         echo $e->getMessage();
    //     }

    //     return $participants->results()[0]->NUMBER;
    // }

    public function getParticipantsNumberByType($type, $event_code){
        $participants=0;
        $sql="";
        $params="";
        try{

            if($type!=''){
                $sql="SELECT COUNT(id) AS NUMBER FROM ".$this->_Table." WHERE event_id= ? AND participation_type_id= ? AND status= ?";
                $params=array(
                    $event_code,
                    $type,
                    'APPROVED'
                );
            } 

            $participants=DBConnection::getInstance()->query($sql, $params);

        } catch(Exception $e){
            echo $e->getMessage();
        }

        return $participants->results()[0]->NUMBER;
    }
}

?>