<?php
    require_once(appSource . "/entities/RFLParticipant.php");

    class RFLParticipantService{

        public static function getParticipantCode(){
            $participantObj= new RFLParticipant();
            return $participantObj->getParticipantCode();
        }

        public static function exists($data){
            return $data->exists($data);
        }

        public static function insert($data){
            return $data->insert($data);
        }

        public static function participantsList($selection, $search_query, $order, $limit){
            $participantObj= new RFLParticipant();
            return $participantObj->participantsList($selection, $search_query, $order, $limit);
        }

        public static function getParticipantPassPrice($id){
            $participantObj= new RFLParticipant();
            return $participantObj->getParticipantPassPrice($id);
        }

        public static function getParticipantByID($id){
            $selection="*";
            $search_query=" WHERE id=".$id."";
            $order=" ORDER BY id DESC";
            $limit=" LIMIT 1";

            $participantObj= new RFLParticipant();
            return $participantObj->participantsList($selection, $search_query, $order, $limit);
        }

        public static function RFLParticipantCodePrefix(){
            // standing for RFL Participant
            return "RFL-P";
        }
    }
    
?>