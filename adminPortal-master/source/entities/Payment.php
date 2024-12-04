<?php 



class Payment{


    private $_Table;

    // Constructor
    public function __construct()
    {
        $this->_Table='future_payment_transaction_entry';
    }

    // Setters & Getters


    // DML Methods
    public function hasAlreadyPaid($participant_id){
        
        $sql="";
        $params="";
        try{

            if($participant_id!=''){
                $sql="SELECT id, transaction_status FROM ".$this->_Table." WHERE participant_id= ? AND transaction_status= ?";
                $params=array(
                    $participant_id,
                    "COMPLETED"
                );
            } 

            $participants=DBConnection::getInstance()->query($sql, $params);
            
            if($participants->count()){
                return true;
            }

        } catch(Exception $e){
            echo $e->getMessage();
            return false;
        }

        return false;
    }

    public function paymentTransactionsList($selection, $search_query = '', $order = '', $limit = '')
    {
        $transactionsList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query . " " . $order . " " . $limit;

            $transactions = DBConnection::getInstance()->query($sql);

            if ($transactions->count()) {
                $transactionsList = $transactions->results();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return json_encode($transactionsList);
    }

    
}

?>