<?php
class Logs
{

    public static function newLog($operation, $comment, $user_id)
    {

        // $log_code = self::getLogCode();
        $log_code = "";
        $operation = $operation;
        $comment = $comment;
        $user_id = $user_id;
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

        $controller = new Controller();

        try {
            $array = array( 
                "log_code" => $log_code,
                "operation" => $operation,
                "comment" => $log_description,
                "user_code" => $user_id,
                "status" => $status,
                "added_date" => $added_date,   
                "added_time" => $added_time,   
                "source_ip" => $source_ip
            ); 
            $controller->create('future_log',$array);

            return true;

        } catch(Exception $error) {
            return false;
        }
    }

    public function getLogCode()
    {
        $rows = "0";
        $code = "";

        $sql = "SELECT COUNT(id) AS total_row FROM `future_log` WHERE `added_date` BETWEEN '" . Time::getFirstDateOfCurrentYear() . "'  AND  '" . Time::getLastDateOfCurrentYear() . "'";
        $logs = DB::getInstance()->query($sql);

        if ($logs->count()) {
            $rows = (int) ($logs->first()->total_row + 1);
        }

        $size = 5 - strlen($rows);
        $prefix = "";
        if ($size != 0) {
            for ($i = 0; $i < $size; $i++) {
                $prefix = $prefix . "0";
            }
        }

        // standing for Normal User
        $code = "TLOG" . Time::getYearIn2Digits() . "" . Time::getCurrentMonth() . "" . $prefix . "" . $rows;

        return $code;
    }
}