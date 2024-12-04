<?php

class AccessLog
{

    // Table Attributes
    private $id;
    private $log_code;
    private $flag;
    private $log_date;
    private $log_time;
    private $activity_page_url;
    private $operation;
    private $log_message;
    private $user_code;
    private $device_details;
    private $added_on;

    private $_Table;


    // Getters and Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCode($log_code)
    {
        $this->log_code = $log_code;
    }

    public function getCode()
    {
        return $this->log_code;
    }

    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    public function getFlag()
    {
        return $this->flag;
    }

    public function setLogDate($log_date)
    {
        $this->log_date = $log_date;
    }

    public function getLogDate()
    {
        return $this->log_date;
    }

    public function setLogTime($log_time)
    {
        $this->log_time = $log_time;
    }

    public function getLogTime()
    {
        return $this->log_time;
    }

    public function setActivityPageURL($activity_page_url)
    {
        $this->activity_page_url = $activity_page_url;
    }

    public function getActivityPageURL()
    {
        return $this->activity_page_url;
    }

    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function setLogMessage($log_message)
    {
        $this->log_message = $log_message;
    }

    public function getLogMessage()
    {
        return $this->log_message;
    }

    public function setUserCode($user_code)
    {
        $this->user_code = $user_code;
    }

    public function getUserCode()
    {
        return $this->user_code;
    }

    public function setDeviceDetails($device_details)
    {
        $this->device_details = $device_details;
    }

    public function getDeviceDetails()
    {
        return $this->device_details;
    }

    public function setAddedOn($added_on)
    {
        $this->added_on = $added_on;
    }

    public function getAddedOn()
    {
        return $this->added_on;
    }

    // Constructor
    public function __construct()
    {
        $this->_Table = 'cdb_access_log';
    }


    // DML Operations

    public function getLogCode()
    {
        $rows = "0";
        $code = "";

        $sql = "SELECT COUNT(id) AS total_row FROM " . $this->_Table . " WHERE log_date BETWEEN '" . Time::getFirstDateOfCurrentYear() . "'  AND  '" . Time::getLastDateOfCurrentYear() . "'";
        $users = DBConnection::getInstance()->query($sql);

        if ($users->count()) {
            $rows = (int) ($users->first()->total_row + 1);
        }

        $size = 5 - strlen($rows);
        $prefix = "";
        if ($size != 0) {
            for ($i = 0; $i < $size; $i++) {
                $prefix = $prefix . "0";
            }
        }

        // standing for Normal User
        $code = "ALG" . Time::getYearIn2Digits() . "" . Time::getCurrentMonth() . "" . $prefix . "" . $rows;

        return $code;
    }

    public function insert($data)
    {
        try {
            $sql = "INSERT INTO " . $this->_Table . "(log_code, flag, log_date, log_time, activity_page_url, operation, log_message, user_code, device_details, added_on) VALUES(?,?,?,?,?,?,?,?,?,?)";

            $params = array(
                $data->getCode(),
                $data->getFlag(),
                $data->getLogDate(),
                $data->getLogTime(),
                $data->getActivityPageURL(),
                $data->getOperation(),
                $data->getLogMessage(),
                $data->getUserCode(),
                $data->getDeviceDetails(),
                $data->getAddedOn()
            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getAccessLogs($selection, $search_query = '', $order = '', $limit = '')
    {
        $countryList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query . " " . $order . " " . $limit;

            $country = DBConnection::getInstance()->query($sql);

            if ($country->count()) {
                $countryList = $country->results();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return json_encode($countryList);
    }
}
