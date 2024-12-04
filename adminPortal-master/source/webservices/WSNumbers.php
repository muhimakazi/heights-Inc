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
        * Resource: figures
        * Method: POST
        */
        case 'alphabetical':
            /*
            * METHOD: POST
            */
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                // Checking if all needed parameters are part of the incoming request
                $validParameters = true;

                if ($validParameters) {

                    $letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

                    $lettersObj = array();

                    foreach ($letters as $l) {
                        $lettersObj[$l] = 0;
                    }
                    $_filter_condition_ = "";
                    try {

                        $sql = "SELECT * FROM future_participants WHERE event_id=10 AND status='APPROVED'";

                        $_LIST_DATA_ = DBConnection::getInstance()->query($sql);
                        // FutureEventController::getParticipantsByEventID($_EVENT_ID_, $_filter_condition_);

                        $counter = 0;
                        $letter_count = 0;

                        foreach ($_LIST_DATA_->results() as $participant) {
                            $firstname = $participant->firstname;
                            $first_letter = substr($firstname, 0, 1);


                            foreach ($letters as $letter) {
                                if (strtoupper($first_letter) == $letter) {
                                    $lettersObj[strtoupper($letter)] = (int)$lettersObj[strtoupper($letter)] + 1;
                                }
                            }

                            $counter++;
                        }


                        $responseDataObj["registration_details"] = $lettersObj;
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }



                    $response['status'] = "200";
                    $response['message'] = "Operation completed";
                    $response['data'] = $responseDataObj;
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
