<?php

class General
{
    // Secure HTML Input
    public static function escape($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }

    // Include Scripts From Root Path
    public static function import($path)
    {
        return require_once($_SERVER['DOCUMENT_ROOT'] . "/" . Config::get("server/appName") . "/{$path}");
    }

    // Path To Root Directory
    public static function root($path)
    {
        return $_SERVER['DOCUMENT_ROOT'] . "/" . Config::get("server/appName") . "/{$path}";
    }

    // absulot path used in links
    public static function linkto($path)
    {
        echo "/" . Config::get("server/appName") . "/{$path}";
    }

    //Print Success Message Style
    public static function SuccessMsg($message)
    {
        echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
			<span aria-hidden=\"true\">&times;</span>
			</button>
			{$message}
		</div>";
    }

    //Print Error Message Style
    public static function DangerMsg($message)
    {
        echo "<div class=\"alert alert-danger alert-dismissible show fade in\" role=\"alert\">
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
			<span aria-hidden=\"true\">&times;</span>
			</button>
			{$message}
		</div>";
    }


    // Function to get all the dates in given range 
    public static function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {

        // Declare an empty array 
        $array = array();

        // Variable that store the date interval 
        // of period 1 day 
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        // Use loop to store date into array 
        foreach ($period as $date) {
            $array[] = $date->format($format);
        }

        // Return the array elements 
        return $array;
    }
}
