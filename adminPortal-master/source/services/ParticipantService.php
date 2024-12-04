<?php 
    require_once(appSource."/entities/Participant.php");

    class ParticipantService{
        
        public static function participantsList($selection, $search_query = '', $order = '', $limit = ''){
            $participantsObj= new Participant();

            return $participantsObj->participantsList($selection, $search_query, $order, $limit);
        }

        // public static function getParticipantsNumberByAccessType($access_type, $event_code){
        //     $participantsObj= new Participant();

        //     return $participantsObj->getParticipantsNumberByAccessType($access_type, $event_code);
        // }

        public static function getParticipantsNumberByType($type, $event_code){
            $participantsObj= new Participant();

            return $participantsObj->getParticipantsNumberByType($type, $event_code);
        }
    }
?>