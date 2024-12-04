<?php
require_once("../entities/ErrorLog.php");

class ErrorLogService
{
    public static function getLogCode()
    {
        $logs = new ErrorLog();
        return $logs->getLogCode();
    }

    public static function insert($data)
    {
        return $data->insert($data);
    }

    public static function getErrorLogs($selection, $search_query = '', $order = '', $limit = '')
    {
        $logs = new ErrorLog();
        return $logs->getErrorLogs($selection, $search_query, $order, $limit);
    }
}
?>