<?php

class User
{

    // Table attributes
    private $id;
    private $account_type;
    private $code;
    private $firstname;
    private $lastname;
    private $email_id;
    private $msisdn;
    private $login_username;
    private $login_password;
    private $token;
    private $token_generation_time;
    private $token_expiry_time;
    private $company_code;
    private $status;
    private $added_on;
    private $added_by;
    private $modified_on;
    private $modified_by;

    private $_Table;

    // Setters and Getters

    public function setID($id)
    {
        $this->id = $id;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setAccountType($account_type)
    {
        $this->account_type = $account_type;
    }

    public function getAccountType()
    {
        return $this->account_type;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getFirstName()
    {
        return $this->firstname;
    }

    public function setLastName($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getLastName()
    {
        return $this->lastname;
    }

    public function setEmailID($email_id)
    {
        $this->email_id = $email_id;
    }

    public function getEmailID()
    {
        return $this->email_id;
    }

    public function setMSISDN($msisdn)
    {
        $this->msisdn = $msisdn;
    }

    public function getMSISDN()
    {
        return $this->msisdn;
    }

    public function setLoginUsername($login_username)
    {
        $this->login_username = $login_username;
    }

    public function getLoginUsername()
    {
        return $this->login_username;
    }

    public function setLoginPassword($login_password)
    {
        $this->login_password = $login_password;
    }

    public function getLoginPassword()
    {
        return $this->login_password;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setTokenGenerationTime($token_generation_time)
    {
        $this->token_generation_time = $token_generation_time;
    }

    public function getTokenGenerationTime()
    {
        return $this->token_generation_time;
    }

    public function setTokenExpiryTime($token_expiry_time)
    {
        $this->token_expiry_time = $token_expiry_time;
    }

    public function getTokenExpiryTime()
    {
        return $this->token_expiry_time;
    }

    public function setCompanyCode($company_code)
    {
        $this->company_code = $company_code;
    }

    public function getCompanyCode()
    {
        return $this->company_code;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAddedOn($added_on)
    {
        $this->added_on = $added_on;
    }

    public function getAddedOn()
    {
        return $this->added_on;
    }

    public function setAddedBy($added_by)
    {
        $this->added_by = $added_by;
    }

    public function getAddedBy()
    {
        return $this->added_by;
    }

    public function setModifiedOn($modified_on)
    {
        $this->modified_on = $modified_on;
    }

    public function getModifiedOn()
    {
        return $this->modified_on;
    }

    public function setModifiedBy($modified_by)
    {
        $this->modified_by = $modified_by;
    }

    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    // Constructor
    public function __construct()
    {
        $this->_Table = "future_participants";
    }


    // Generating user codes
    public function getUserCode()
    {
        $rows = "0";
        $code = "";

        $sql = "SELECT COUNT(id) AS total_row FROM " . $this->_Table . " WHERE added_date BETWEEN '" . Time::getFirstDateOfCurrentYear() . "'  AND  '" . Time::getLastDateOfCurrentYear() . "'";
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
        $code = "CDBU" . Time::getYearIn2Digits() . "" . Time::getCurrentMonth() . "" . $prefix . "" . $rows;

        return $code;
    }

    // Generating Company codes
    public function get_CompanyCode()
    {
        $rows = "0";
        $code = "";

        $sql = "SELECT COUNT(id) AS total_row FROM " . $this->_Table . " WHERE added_date BETWEEN '" . Time::getFirstDateOfCurrentYear() . "'  AND  '" . Time::getLastDateOfCurrentYear() . "'";
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

        // Standing for Normal User
        $code = "RCP" . Time::getYearIn2Digits() . "" . Time::getCurrentMonth() . "" . $prefix . "" . $rows;

        return $code;
    }


    public function insert($data)
    {

        try {
            $sql = "INSERT INTO " . $this->_Table . "(account_type, code, firstname, lastname, email_id, msisdn, login_username, login_password, token, token_generation_time, token_expiry_time, company_code, status, added_on, added_by, modified_on, modified_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $params = array(
                $data->getAccountType(),
                $data->getCode(),
                $data->getFirstName(),
                $data->getLastName(),
                $data->getEmailID(),
                $data->getMSISDN(),
                $data->getLoginUsername(),
                $data->getLoginPassword(),
                $data->getToken(),
                $data->getTokenGenerationTime(),
                $data->getTokenExpiryTime(),
                $data->getCompanyCode(),
                $data->getStatus(),
                $data->getAddedOn(),
                $data->getAddedBy(),
                $data->getModifiedOn(),
                $data->getModifiedBy(),
            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function userList($selection, $search_query = '', $order = '', $limit = '')
    {
        $userList = array();

        try {
            $sql = "SELECT " . $selection . "  FROM " . $this->_Table . " " . $search_query . " " . $order . " " . $limit;

            $users = DBConnection::getInstance()->query($sql);

            if ($users->count()) {
                $userList = $users->results();
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return json_encode($userList);
    }

    public function checkUserDetails($selection, $search_query, $order = '', $limit = '')
    {

        try {
            $sql = "SELECT " . $selection . " FROM " . $this->_Table . " " . $search_query . " " . $order . " " . $limit;
            $users = DBConnection::getInstance()->query($sql);

            if ($users->count()) {
                return true;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return false;
    }

    public function changePassword($data)
    {

        try {
            $sql = "UPDATE " . $this->_Table . " SET login_password= ? WHERE code= ?";
            $params = array(
                $data->getLoginPassword(),
                $data->getCode()
            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateToken($data)
    {

        try {
            $sql = "UPDATE " . $this->_Table . " SET token= ? WHERE code= ?";
            $params = array(
                $data->getToken(),
                $data->getCode()
            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($data)
    {
        $status = DELETED;
        try {
            $sql = "UPDATE " . $this->_Table . " SET status= ? WHERE code= ?";
            $params = array(
                $status,
                $data->getCode()

            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function lock($data)
    {

        $status = LOCKED;
        try {
            $sql = "UPDATE " . $this->_Table . " SET status= ? WHERE code= ?";
            $params = array(
                $status,
                $data->getCode()

            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function unlock($data)
    {

        $status = UNLOCKED;
        try {
            $sql = "UPDATE " . $this->_Table . " SET status= ? WHERE code= ?";
            $params = array(
                $status,
                $data->getCode()

            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}











?>