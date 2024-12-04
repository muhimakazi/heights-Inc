<?php 
    require_once(appSource . "/entities/RFLPayment.php");

    class RFLPaymentService{
        
        public static function getTransactionCode(){
            $paymentObj= new RFLPayment();
            return $paymentObj->getTransactionCode();
        }

        public static function insert($data){
            return $data->insert($data);
        }

        public static function updateTransaction($data){
            return $data->updateTransaction($data);
        }

        public static function ignoreUncompletedPaymentRequestsByParticipant($eventID, $participantID, $participation_type){
            $paymentObj= new RFLPayment();
            return $paymentObj->ignoreUncompletedPaymentRequestsByParticipant($eventID, $participantID, $participation_type);
        }

        public static function transactionsList($selection, $search_query = '', $order = '', $limit = ''){
            $paymentObj= new RFLPayment();
            return $paymentObj->transactionsList($selection, $search_query, $order, $limit);
        }

        public static function getDataByTransactionID($trans_id, $external_trans_id){
            $paymentObj= new RFLPayment();
            return $paymentObj->getDataByTransactionID($trans_id, $external_trans_id);
        }

        public static function hasAlreadyPaid($eventID, $participantID){
            $paymentObj= new RFLPayment();
            return $paymentObj->hasAlreadyPaid($eventID, $participantID);
        }

        public static function makeTransationToken($transaction_id)
        {
            return md5(sha1($transaction_id . date('yh')));
        }
        
    }

?>