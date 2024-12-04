<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type:application/json");

require_once("../core/Init.php");
require_once("../entities/User.php");



$response = array();
$response['status'] = BAD_REQUEST;
$response['message'] = "Bad Request";

if (Input::checkInput('resource', 'get', '1')) {
    $request = Input::get('resource', 'get');

    switch ($request) {
            /*
        * Resource: send
        * Method: POST
        */
        case 'send':
            /*
            * METHOD: GET
            */
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $failedEmails = array();
                // Checking if all needed parameters are part of the incoming request
                $validParameters = true;

                if ($validParameters) {
                    $emails = array(
                        "lucienmeru@gmail.com",
                        "mikindip@gmail.com",
                        "colombe@cube.rw"
                    );

                    foreach ($emails as $email) {

                        try {
                            // Notifications::sendEmail($email, "BULK EMAIL TEST");

                            $request = array(
                                "sendmail" => "true",
                                "subject" => "BULK EMAILS TESTING",
                                "message_body" => "just testing",
                                "email" => $email
                            );

                            $url = "http://localhost/thefuture/adminPortal/notifications/mailling";

                            Httprequests::send($url, "POST", $request);
                        } catch (Exception $e) {
                            array_push($failedEmails, $email);
                        }
                    }

                    $response['status'] =  SUCCESS;
                    $response['message'] = "Emails sent";
                    $response['failue'] = $failedEmails;
                } else {
                    $response['status'] =  BAD_REQUEST;
                    $response['message'] = "Invalid Parameters";
                }
            } else {
                $response['status'] = BAD_REQUEST_METHOD;
                $response['message'] = "Method Not Allowed";
            }
            break;

        default:

            $response['status'] = BAD_REQUEST;
            $response['message'] = "Bad Request";
            break;
    }
}


echo json_encode($response);
