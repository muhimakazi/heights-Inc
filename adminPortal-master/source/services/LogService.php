<?php
require_once(appSource . "/entities/Log.php");
require_once(appSource . "/general/Time.php");
require_once(appSource . "/httprequests/Httprequests.php");

class LogService
{

    public static function getLogCode()
    {
        $logs = new Log();
        return $logs->getLogCode();
    }

    public static function newLog($operation, $comment, $user_code)
    {

        $log_code = LogService::getLogCode();
        $operation = $operation;
        $comment = $comment;
        $user_code = $user_code;
        $status = "ACTIVE";
        $added_date = Time::getDate();
        $added_time = Time::getTime();

        // Client's IP Address and IP Info
        $source_ip = Httprequests::getUserIP();
        $ip_info = Httprequests::ip_info($source_ip) == NULL ? "N/A" : Httprequests::ip_info($source_ip);
        $device_info = $_SERVER['HTTP_USER_AGENT'];

        // Creating the comment array
        $log_description = array(
            $comment,
            $ip_info,
            $device_info
        );

        $log_description = json_encode($log_description);

        $logBean = new Log();

        $logBean->setCode($log_code);
        $logBean->setOperation($operation);
        $logBean->setComment($log_description);
        $logBean->setUserCode($user_code);
        $logBean->setStatus($status);
        $logBean->setAddedDate($added_date);
        $logBean->setAddedTime($added_time);
        $logBean->setSourceIP($source_ip);

        return $logBean->insert($logBean);
    }

    public static function getLogs($selection, $search_query = '', $order = '', $limit = '')
    {
        $logs = new Log();
        return $logs->getLogs($selection, $search_query, $order, $limit);
    }
}