<?php

class Notifications
{

    private static $_instance = null;

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Time();
        }
        return self::$_instance;
    }


    public static function sendEmail($recipients, $subject = '', $message = '')
    {

        $recipients_emails = $recipients;

        $request = array(
            "sendmail" => "true",
            "subject" => $subject,
            "message_body" => $message,
            "email" => "lucienmeru@gmail.com"
        );

        $url = "http://localhost/thefuture/adminPortal" . "/notifications/mailling";

        try {

            Httprequests::sendAsync($url, $request);

            return true;
        } catch (Exception $e) {
            echo $e->getMessage() . " " . $e->getTrace();
            return false;
        }
    }
}
