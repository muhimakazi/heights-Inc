<?php

class Attendance
{


    private $id;
    private $event_id;
    private $participant_id;
    private $location;
    private $status;
    private $added_date;
    private $added_time;
    private $scanned_by;


    private $_Table;

    // Constructor
    public function __construct()
    {
        $this->_Table = 'future_attendance';
        $this->_Particpant_Table = 'future_participants';
        $this->_Participation_type_table = 'future_participation_sub_type';
    }

    // Setters & Getters
    public function setID($id)
    {
        $this->id = $id;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setEventID($event_id)
    {
        $this->event_id = $event_id;
    }

    public function getEventID()
    {
        return $this->event_id;
    }

    public function setParticipantID($participant_id)
    {
        $this->participant_id = $participant_id;
    }

    public function getParticipantID()
    {
        return $this->participant_id;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
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

    public function setScannedBy($scanned_by)
    {
        $this->scanned_by = $scanned_by;
    }

    public function getScannedBy()
    {
        return $this->scanned_by;
    }


    // DML Methods

    public function insert($data)
    {
        $sql = "INSERT INTO " . $this->_Table . "(event_id, participant_id, location, status, added_date, added_time, scanned_by) VALUES(?,?,?,?,?,?,?)";

        try {
            $params = array(
                $data->getEventID(),
                $data->getParticipantID(),
                $data->getLocation(),
                $data->getStatus(),
                $data->getAddedDate(),
                $data->getAddedTime(),
                $data->getScannedBy()
            );

            DB::getInstance()->query($sql, $params);

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return false;
    }

    public function getAttendanceDetails($selection, $search_query = '', $order = '', $limit = '')
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


    public function getParticipantsNumberByAttendanceType($attendance_type, $event_code)
    {
        $participants = 0;
        $sql = "";
        $params = "";
        try {

            if ($attendance_type != '') {
                $sql = "SELECT COUNT($this->_Particpant_Table.id) AS NUMBER FROM " . $this->_Particpant_Table . " INNER JOIN " . $this->_Participation_type_table . " ON " . $this->_Particpant_Table . ".participation_sub_type_id=" . $this->_Participation_type_table . ".id WHERE " . $this->_Particpant_Table . ".event_id= ? AND " . $this->_Particpant_Table . ".status= ? AND " . $this->_Participation_type_table . ".category= ?";
                $params = array(
                    $event_code,
                    'APPROVED',
                    $attendance_type
                );
            }

            $participants = DB::getInstance()->query($sql, $params);
        } catch (Exception $e) {
            echo $e->getMessage;
        }

        return $participants->results()[0]->NUMBER;
    }

    public function hasAlreadyAttendedToday($participant_id, $event_code)
    {
        $participants = 0;
        $sql = "";
        $today = Time::getDate();
        $params = "";
        try {

            $sql = "SELECT id FROM " . $this->_Table . " WHERE participant_id= ? AND added_date= ? AND event_id= ?";
            $params = array(
                $participant_id,
                $today,
                $event_code
            );

            $participants = DB::getInstance()->query($sql, $params);

            if ($participants->count()) {
                return true;
            }
        } catch (Exception $e) {
            echo $e->getMessage;
        }

        return false;
    }

    public function getTotalAttendance($participant_id, $event_code)
    {
        $participants = 0;
        $sql = "";
        $particpations = 0;
        $params = "";
        try {

            $sql = "SELECT COUNT(id) AS NUMBER FROM " . $this->_Table . " WHERE participant_id= ? AND event_id= ?";
            $params = array(
                $participant_id,
                $event_code
            );

            $participants = DB::getInstance()->query($sql, $params);

            $particpations = $participants->first()->NUMBER;
        } catch (Exception $e) {
            echo $e->getMessage;
        }

        return $particpations;
    }
}
