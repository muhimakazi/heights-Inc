<?php
require_once("../entities/AccessLog.php");

class AccessLogService
{

    public static function getLogCode()
    {
        $logs = new AccessLog();
        return $logs->getLogCode();
    }

    public static function insert($data)
    {
        return $data->insert($data);
    }

    public static function getAccessLogs($selection, $search_query = '', $order = '', $limit = '')
    {
        $logs = new AccessLog();
        return $logs->getAccessLogs($selection, $search_query, $order, $limit);
    }
}
?>