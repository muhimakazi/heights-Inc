<?php

class DataValidation
{

    public static function dataContains($key, $JSONSet)
    {

        $data = array();
        $data = json_decode($JSONSet);

        if (array_key_exists($key, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public static function isNull($input)
    {
        if ($input == null) {
            return true;
        } else {
            return false;
        }
    }

    public static function isEmpty($input)
    {
        if (empty($input)) {
            return true;
        } else {
            return false;
        }
    }
}
