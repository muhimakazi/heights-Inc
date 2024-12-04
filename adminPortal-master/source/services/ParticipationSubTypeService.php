<?php 
    require_once("../entities/ParticipationSubType.php");

    class ParticipationSubTypeService{
        
        public static function subTypesList($selection, $search_query = '', $order = '', $limit = ''){
            $dataObj= new ParticipationSubType();

            return $dataObj->subTypesList($selection, $search_query, $order, $limit);
        }


        public static function getSubTypeData($type, $subtype){
            $dataObj= new ParticipationSubType();

            return $dataObj->getSubTypeData($type, $subtype);
        }
    }
?>