<?php
// require_once("../entities/Attendance.php");

class AttendanceService
{

    public static function insert($data)
    {
        return $data->insert($data);
    }

    public static function getAttendanceDetails($selection, $search_query = '', $order = '', $limit = '')
    {
        $attendanceObj = new Attendance();
        return $attendanceObj->getAttendanceDetails($selection, $search_query, $order, $limit);
    }

    public static function getParticipantsNumberByAttendanceType($attendance_type, $event_code)
    {
        $attendanceObj = new Attendance();

        return $attendanceObj->getParticipantsNumberByAttendanceType($attendance_type, $event_code);
    }

    public static function hasAlreadyAttendedToday($participant_id, $event_code)
    {
        $attendanceObj = new Attendance();

        return $attendanceObj->hasAlreadyAttendedToday($participant_id, $event_code);
    }

    public static function getTotalAttendance($participant_id, $event_code)
    {
        $attendanceObj = new Attendance();

        return $attendanceObj->getTotalAttendance($participant_id, $event_code);
    }
}
