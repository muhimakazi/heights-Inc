<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type:application/json");

require_once("../core/Init.php");
require_once("../entities/User.php");
require_once("../entities/Attendance.php");

$response = array();
$response['status'] = BAD_REQUEST;
$response['message'] = "Bad Request";

if (Input::checkInput('resource', 'get', '1')) {
    $request = Input::get('resource', 'get');

    switch ($request) {

        case 'new':
            /*
            * METHOD: POST
            */
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $validParameters = true;

                if ($validParameters) {
                    // Getting request parameters values
                    $eventKey = Input::get('event', 'get');
                    $participantKey = Input::get('participant', 'get');

                    $event_id = Hash::decryptAuthToken($eventKey);
                    $participant_id = Hash::decryptAuthToken($participantKey);
                    $location = "Serena Hotel";
                    $status = "CHECKEDIN";
                    $device = $_SERVER['HTTP_USER_AGENT'];

                    $attendanceBean = new Attendance();

                    $error_log = "";
                    // $passes = 6;

                    // Check if Participant is on daily pass
                    // $selection = "participation_type_id, participation_sub_type_id";
                    // $condition = " WHERE id=" . $participant_id . "'";

                    // $participantData = UserService::userList($selection, $condition);

                    // $participantData = json_decode($participantData[0]);

                    // echo $participantData;

                    // $participation_type = $participantData->participation_type_id;
                    // $participation_sub_type = $participantData->participation_sub_type_id;


                    // if ($participation_type == 83 || $participation_type == 87 || $participation_type == 91) {
                    //     $passes = 1;
                    // }

                    // if ($participation_type == 84 || $participation_type == 88 || $participation_type == 92) {
                    //     $passes = 2;
                    // }

                    // if ($participation_type == 85 || $participation_type == 89 || $participation_type == 93) {
                    //     $passes = 3;
                    // }

                    // if ($participation_type == 86 || $participation_type == 90 || $participation_type == 94) {
                    //     $passes = 4;
                    // }



                    // if ($participation_type == 0) {
                    // Get total attendance by today
                    // $participant_current_attendance_count = AttendanceService::getTotalAttendance($participant_id, $event_id);

                    // if ($participant_current_attendance_count < $passes) {
                    $attendanceBean->setEventID($event_id);
                    $attendanceBean->setParticipantID($participant_id);
                    $attendanceBean->setLocation($location);
                    $attendanceBean->setStatus($status);
                    $attendanceBean->setAddedDate(Time::getDate());
                    $attendanceBean->setAddedTime(Time::getTime());
                    $attendanceBean->setScannedBy($device);

                    if (!AttendanceService::hasAlreadyAttendedToday($participant_id, $event_id)) {
                        if (AttendanceService::insert($attendanceBean)) {
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                        } else {
                            $response['status'] = "500";
                            $response['success'] = "0";
                            $response['message'] = "Error while completing this task...Try again later";
                        }
                    } else {
                        // Participant has already been scanned
                        $response['status'] = "200";
                        $response['message'] = "Badge has already been scanned";
                    }
                    // } else {
                    //     // Day Passes expired
                    //     echo "<br><br><h2 style='color: red!important;'>AUTHORIZED DAYS EXCEEDED</h2><br><br>";
                    //     $response['status'] = "500";
                    //     $response['success'] = "0";
                    //     $response['message'] = "Authorized days exceeded.";
                    // }
                    // } else {
                    //     $attendanceBean->setEventID($event_id);
                    //     $attendanceBean->setParticipantID($participant_id);
                    //     $attendanceBean->setLocation($location);
                    //     $attendanceBean->setStatus($status);
                    //     $attendanceBean->setAddedDate(Time::getDate());
                    //     $attendanceBean->setAddedTime(Time::getTime());
                    //     $attendanceBean->setScannedBy($device);

                    //     if (!AttendanceService::hasAlreadyAttendedToday($participant_id, $event_id)) {
                    //         if (AttendanceService::insert($attendanceBean)) {
                    //             $response['status'] = "200";
                    //             $response['message'] = "Operation completed successfully";
                    //         } else {
                    //             $response['status'] = "500";
                    //             $response['success'] = "0";
                    //             $response['message'] = "Error while completing this task...Try again later";
                    //         }
                    //     } else {
                    //         // Participant has already been scanned
                    //         $response['status'] = "200";
                    //         $response['message'] = "Badge has already been scanned";
                    //     }
                    // }
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
?>

<script type='text/javascript'>
    window.setTimeout(function() {
        window.close();
    }, 2000);
</script>