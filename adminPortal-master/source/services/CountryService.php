<?php
require_once("../entities/Country.php");

class CountryService
{

    public static function insert($data)
    {
        return $data->insert($data);
    }

    public static function getcountryDetails($selection, $search_query = '', $order = '', $limit = '')
    {
        $coutries = new Country();
        return $coutries->getcountryDetails($selection, $search_query, $order, $limit);
    }
}
?>