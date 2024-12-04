<?php 
    require_once("../entities/ParticipationType.php");

    class ParticipationTypeService{
        
        public static function participationTypeList($selection, $search_query = '', $order = '', $limit = ''){
            $participantsObj= new ParticipationType();

            return $participantsObj->participationTypeList($selection, $search_query, $order, $limit);
        }

        public static function getTypeName($type){
            $dataObj= new ParticipationType();

            return $dataObj->getTypeName($type);
        }
    }
?>