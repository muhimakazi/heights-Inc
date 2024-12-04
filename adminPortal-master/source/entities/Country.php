<?php

class Country
{
    // Table Attributes
    private $id;
    private $country_iso_code;
    private $country_name;
    private $country_phone_code;
    private $country_continent;
    private $country_time_zone;
    private $status;
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

    public function setCountryIsoCode($country_iso_code)
    {
        $this->country_iso_code = $country_iso_code;
    }

    public function getCountryIsoCode()
    {
        return $this->country_iso_code;
    }

    public function setCountryName($country_name)
    {
        $this->country_name = $country_name;
    }

    public function getCountryName()
    {
        return $this->country_name;
    }

    public function setCountryPhoneCode($country_phone_code)
    {
        $this->country_phone_code = $country_phone_code;
    }

    public function getCountryPhoneCode()
    {
        return $this->country_phone_code;
    }

    public function setCountryContinent($country_continent)
    {
        $this->country_continent = $country_continent;
    }

    public function getCountryContinent()
    {
        return $this->country_continent;
    }

    public function setCountryTimeZone($country_time_zone)
    {
        $this->country_time_zone = $country_time_zone;
    }

    public function getCountryTimeZone()
    {
        return $this->country_time_zone;
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


    // Constructor
    public function __construct()
    {
        $this->_Table = "country_details";
    }

    // DML Operations
    public function insert($data)
    {
        try {
            $sql = "INSERT INTO " . $this->_Table . "(country_iso_code, country_name, country_phone_code, country_continent, country_time_zone, status, added_on) VALUES (?,?,?,?,?,?,?)";
            $params = array(
                $data->getCountryIsoCode(),
                $data->getCountryName(),
                $data->getCountryPhoneCode(),
                $data->getCountryContinent(),
                $data->getCountryTimeZone(),
                $data->getStatus(),
                $data->getAddedOn()
            );

            DBConnection::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getcountryDetails($selection, $search_query = '', $order = '', $limit = '')
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
