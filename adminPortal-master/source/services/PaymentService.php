<?php 
    require_once("../entities/Payment.php");

    class PaymentService{
        
        public static function hasAlreadyPaid($participant_id){
            $paymentObj= new Payment();

            return $paymentObj->hasAlreadyPaid($participant_id);
        }

        public static function paymentTransactionsList($selection, $search_query = '', $order = '', $limit = ''){
            $paymentObj= new Payment();

            return $paymentObj->paymentTransactionsList($selection, $search_query, $order, $limit);
        }
        
    }
?>