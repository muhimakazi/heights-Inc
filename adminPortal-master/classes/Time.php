<?php

class Time
{

    private static $_instance = null;

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Time();
        }
        return self::$_instance;
    }

    public static function getDate()
    {
        return date("Y-m-d");
    }

    public static function getDateTime()
    {
        return date("Y-m-d H:i:s");
    }

    public static function getTime()
    {
        return date("H:i:s");
    }

    public static function getYear()
    {
        return date("Y");
    }

    public static function getYearIn2Digits()
    {
        return date("y");
    }

    public static function getCurrentMonth()
    {
        return date("m");
    }

    public static function getCurrentDay()
    {
        return date("d");
    }

    public static function getFirstDateOfCurrentYear()
    {
        return Time::getYear() . "-01-01";
    }

    public static function getLastDateOfCurrentYear()
    {
        return Time::getYear() . "-12-31";
    }

    public static function getFirstDateOfCurrentMonth()
    {
        return date("Y-m") . "-01";
    }

    public static function getLastDateOfCurrentMonth()
    {
        return date("Y-m-t");
    }

    public static function getTimeStamp()
    {
        return date("Y.m.d.H.i.s");
    }

    public static function todayIsAfter($reference_date)
    {
        $current_date = date('Y-m-d H:i:s');
        $reference_date = date('Y-m-d H:i:s', strtotime(preg_replace('/\:\s+/', ':', $reference_date)));

        if ($current_date > $reference_date) {
            return true;
        }

        return false;
    }

    public static function addHoursToDate($date, $hours)
    {
        $next_date = date("Y-m-d H:i:s", strtotime($date . "+" . $hours . " hours"));

        return $next_date;
    }



    /**
     * Function to convert a date to a personalized format
     * Accepts two date separators ("-" and "/")
     */
    public static function formatDate($date_str, $old_format, $new_format)
    {

        $date = $date_str;

        if (strpos($new_format, "/") && !strpos($old_format, "/")) {
            $date = str_replace('-', '/', $date_str);
        }

        $newDate = date($new_format, strtotime($date));

        return $newDate;
    }
}
