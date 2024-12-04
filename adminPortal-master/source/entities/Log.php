<?php

class Log
{

    // Table Attributes
    private $id;
    private $log_code;
    private $operation;
    private $comment;
    private $user_code;
    private $status;
    private $added_date;
    private $added_time;
    private $source_ip;

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

    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setUserCode($user_code)
    {
        $this->user_code = $user_code;
    }

    public function getUserCode()
    {
        return $this->user_code;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAddedDate($added_date)
    {
        $this->added_date = $added_date;
    }

    public function getAddedDate()
    {
        return $this->added_date;
    }

    public function setAddedTime($added_time)
    {
        $this->added_time = $added_time;
    }

    public function getAddedTime()
    {
        return $this->added_time;
    }

    public function setSourceIP($source_ip)
    {
        $this->source_ip = $source_ip;
    }

    public function getSourceIP()
    {
        return $this->source_ip;
    }

    // Constructor
    public function __construct()
    {
        $this->_Table = 'future_log';
    }

    // DML Operations

    public function getLogCode()
    {
        $rows = "0";
        $code = "";

        $sql = "SELECT COUNT(id) AS total_row FROM " . $this->_Table . " WHERE added_date BETWEEN '" . Time::getFirstDateOfCurrentYear() . "'  AND  '" . Time::getLastDateOfCurrentYear() . "'";
        $logs = DBConnection::getInstance()->query($sql);

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

    public function insert($data)
    {
        try {
            $sql = "INSERT INTO " . $this->_Table . "(log_code, operation, comment, user_code, status, added_date, added_time, source_ip) VALUES(?,?,?,?,?,?,?,?)";

            $params = array(
                $data->getCode(),
                $data->getOperation(),
                $data->getComment(),
                $data->getUserCode(),
                $data->getStatus(),
                $data->getAddedDate(),
                $data->getAddedTime(),
                $data->getSourceIP()
            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }


    public function getLogs($selection, $search_query = '', $order = '', $limit = '')
    {
        $logList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query . " " . $order . " " . $limit;

            $logs = DBConnection::getInstance()->query($sql);

            if ($logs->count()) {
                $logs = $logs->results();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return json_encode($logList);
    }
}