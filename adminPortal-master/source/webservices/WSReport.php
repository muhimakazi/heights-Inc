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
        case 'figures':
            /*
            * METHOD: POST
            */
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');

                // echo $authToken;

                if ($authToken) {
                    // Check Authentication Token Validity
                    $authSelection = "id, token_expiry_time";
                    $authCondition = "WHERE token='" . Httprequests::getBearerToken($authToken) . "'";
                    $userAuthData = json_decode(UserService::userList($authSelection, $authCondition,'','LIMIT 1'));

                    // echo json_encode($userAuthData);

                    if (!DataValidation::isEmpty($userAuthData)) {

                        $token_expiration_date = "2022-06-30 4: 34: 21";
                        // $userAuthData[0]->token_expiry_time;

                        $user_code = $userAuthData[0]->id;

                        // echo $token_expiration_date ." VS ".$userAuthData[0]->token_expiry_time;

                        if (!Time::todayIsAfter($token_expiration_date)) {
                            $incomingData = file_get_contents('php://input');
                            $dataObj = json_decode(str_replace("\\", "", $incomingData));

                            $registrationData = array();
                            $attendanceData = array();
                            $paymentData = array();
                            $revenueData = array();
                            $delegateData = array();
                            $refundData = array();
                            $geographicalData = array();

                            $responseDataObj = array();
                            $debugData = array();

                            // Checking if all needed parameters are part of the incoming request
                            $validParameters = true;

                            if (!DataValidation::dataContains("registration", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("attendance", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("payment", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("revenue", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("delegate", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("refund", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("geographical", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("event_id", $incomingData)) $validParameters = false;

                            if ($validParameters) {

                                // Getting request parameters values
                                $registration = $dataObj->registration;
                                $attendance = $dataObj->attendance;
                                $payment = $dataObj->payment;
                                $revenue = $dataObj->revenue;
                                $delegate = $dataObj->delegate;
                                $refund = $dataObj->refund;
                                $geographical = $dataObj->geographical;
                                $event_id = $dataObj->event_id;

                                // GETTING REGISTRATION FIGURES
                                if ($registration) {

                                    try {
                                        $complementary = ParticipantService::getParticipantsNumberByAccessType('FREE', $event_id);
                                        $paying = ParticipantService::getParticipantsNumberByAccessType('PAYING', $event_id);
                                        $total = (int)($complementary + $paying);

                                        $registrationData['complementary'] = $complementary;
                                        $registrationData['paying'] = $paying;
                                        $registrationData['total'] = $total;

                                        $responseDataObj["registration_details"] = $registrationData;
                                    } catch (Exception $e) {
                                        $log_status = false;

                                        // Recording the Error log
                                        $log = new ErrorLog();
                                        $error_message = $e->getMessage();
                                        $operation = "GET_REGISTRATION_REPORT_FIGURES";
                                        $flag = "SEVERE";
                                        $device = "";
                                        $pageURL = $_SERVER['REQUEST_URI'];
                                        $logCode = AccessLogService::getLogCode();

                                        $log->setCode($logCode);
                                        $log->setLogDate(Time::getDate());
                                        $log->setLogTime(Time::getTime());
                                        $log->setIncidentPageURL($pageURL);
                                        $log->setOperation($operation);
                                        $log->setLogMessage($error_message);
                                        $log->setActiveUser($user_code);
                                        $log->setDeviceDetails($device);
                                        $log->setAddedOn(Time::getDateTime());

                                        if (ErrorLogService::insert($log)) $log_status = true;

                                        $debug = array(
                                            "status" => FAILURE,
                                            "log_status" => $log_status,
                                            "message" => 'An error occured while fetching Registration data. Log Reference: ' . $logCode
                                        );

                                        array_push($debugData, $debug);
                                    }
                                } else {
                                    $registrationData = [];
                                    array_push($responseDataObj, $registrationData);
                                }

                                if ($payment) {
                                    try {
                                        $completed = PaymentService::getTransactionsByStatus('COMPLETED', $event_id);
                                        $pending = PaymentService::getTransactionsByStatus('PENDING', $event_id);
                                        $total = (int)($completed + $pending);

                                        $paymentData['completed'] = $completed;
                                        $paymentData['pending'] = $pending;
                                        $paymentData['total'] = $total;

                                        $responseDataObj["payment_details"] = $paymentData;
                                    } catch (Exception $e) {
                                        $log_status = false;

                                        // Recording the Error log
                                        $log = new ErrorLog();
                                        $error_message = $e->getMessage();
                                        $operation = "GET_PAYMENT_REPORT_FIGURES";
                                        $flag = "SEVERE";
                                        $device = "";
                                        $pageURL = $_SERVER['REQUEST_URI'];
                                        $logCode = AccessLogService::getLogCode();

                                        $log->setCode($logCode);
                                        $log->setLogDate(Time::getDate());
                                        $log->setLogTime(Time::getTime());
                                        $log->setIncidentPageURL($pageURL);
                                        $log->setOperation($operation);
                                        $log->setLogMessage($error_message);
                                        $log->setActiveUser($user_code);
                                        $log->setDeviceDetails($device);
                                        $log->setAddedOn(Time::getDateTime());

                                        if (ErrorLogService::insert($log)) $log_status = true;

                                        $debug = array(
                                            "status" => FAILURE,
                                            "log_status" => $log_status,
                                            "message" => 'An error occured while fetching Attendance data. Log Reference: ' . $logCode
                                        );

                                        array_push($debugData, $debug);
                                    }
                                } else {
                                    $paymentData = [];
                                    array_push($responseDataObj, $paymentData);
                                }

                                if ($attendance) {
                                    try {
                                        $inperson = AttendanceService::getParticipantsNumberByAttendanceType('INPERSON', $event_id);
                                        $virtual = AttendanceService::getParticipantsNumberByAttendanceType('VIRTUAL', $event_id);
                                        $total = (int)($inperson + $virtual);

                                        $attendanceData['inperson'] = $inperson;
                                        $attendanceData['virtual'] = $virtual;
                                        $attendanceData['total'] = $total;

                                        $responseDataObj["attendance_details"] = $attendanceData;
                                    } catch (Exception $e) {
                                        $log_status = false;

                                        // Recording the Error log
                                        $log = new ErrorLog();
                                        $error_message = $e->getMessage();
                                        $operation = "GET_PAYMENT_REPORT_FIGURES";
                                        $flag = "SEVERE";
                                        $device = "";
                                        $pageURL = $_SERVER['REQUEST_URI'];
                                        $logCode = AccessLogService::getLogCode();

                                        $log->setCode($logCode);
                                        $log->setLogDate(Time::getDate());
                                        $log->setLogTime(Time::getTime());
                                        $log->setIncidentPageURL($pageURL);
                                        $log->setOperation($operation);
                                        $log->setLogMessage($error_message);
                                        $log->setActiveUser($user_code);
                                        $log->setDeviceDetails($device);
                                        $log->setAddedOn(Time::getDateTime());

                                        if (ErrorLogService::insert($log)) $log_status = true;

                                        $debug = array(
                                            "status" => FAILURE,
                                            "log_status" => $log_status,
                                            "message" => 'An error occured while fetching Payment data. Log Reference: ' . $logCode
                                        );

                                        array_push($debugData, $debug);
                                    }
                                } else {
                                    $attendanceData = [];
                                    array_push($responseDataObj, $attendanceData);
                                }

                                if ($revenue) {
                                    try {

                                        $methodsSelection = "DISTINCT(payment_method)";
                                        $methodCond = "WHERE event_id='" . $event_id . "'";
                                        $methods = json_decode(PaymentService::transactionsList($methodsSelection, $methodCond));
                                        $total_revenue = 0;
                                        foreach ($methods as $method) :
                                            // echo json_encode($method->payment_method);
                                            if (trim($method->payment_method) != "") {
                                                $revenue = PaymentService::getTransactionsRevenueByMethod($method->payment_method, $event_id);
                                                // echo $revenue;
                                                $revenueData['' . $method->payment_method . ''] = $revenue;
                                                $total_revenue += (int) $revenue;
                                            }
                                        endforeach;


                                        $revenueData['total'] = $total_revenue;

                                        $responseDataObj["revenue_details"] = $revenueData;
                                    } catch (Exception $e) {
                                        $log_status = false;

                                        // Recording the Error log
                                        $log = new ErrorLog();
                                        $error_message = $e->getMessage();
                                        $operation = "GET_REVENUE_REPORT_FIGURES";
                                        $flag = "SEVERE";
                                        $device = "";
                                        $pageURL = $_SERVER['REQUEST_URI'];
                                        $logCode = AccessLogService::getLogCode();

                                        $log->setCode($logCode);
                                        $log->setLogDate(Time::getDate());
                                        $log->setLogTime(Time::getTime());
                                        $log->setIncidentPageURL($pageURL);
                                        $log->setOperation($operation);
                                        $log->setLogMessage($error_message);
                                        $log->setActiveUser($user_code);
                                        $log->setDeviceDetails($device);
                                        $log->setAddedOn(Time::getDateTime());

                                        if (ErrorLogService::insert($log)) $log_status = true;

                                        $debug = array(
                                            "status" => FAILURE,
                                            "log_status" => $log_status,
                                            "message" => 'An error occured while fetching Attendance data. Log Reference: ' . $logCode
                                        );

                                        array_push($debugData, $debug);
                                    }
                                } else {
                                    $revenueData = [];
                                    array_push($responseDataObj, $revenueData);
                                }

                                if ($delegate) {
                                    try {

                                        $typesSelection = "id, name";
                                        $typesCond = "WHERE status='ACTIVE'";
                                        $types = json_decode(ParticipationTypeService::participationTypeList($typesSelection, $typesCond));
                                        $total_delegates = 0;
                                        // echo json_encode($types);
                                        foreach ($types as $type) :

                                            if (trim($type->id) != "") {
                                                $delegates = ParticipantService::getParticipantsNumberByType($type->id, $event_id);
                                                // echo $revenue;
                                                $delegatesData['' . $type->name . ''] = $delegates;
                                                $total_delegates += (int) $delegates;
                                            }
                                        endforeach;

                                        $delegatesData['total'] = $total_delegates;

                                        $responseDataObj["delegate_details"] = $delegatesData;
                                    } catch (Exception $e) {
                                        $log_status = false;

                                        // Recording the Error log
                                        $log = new ErrorLog();
                                        $error_message = $e->getMessage();
                                        $operation = "GET_DELEGATES_REPORT_FIGURES";
                                        $flag = "SEVERE";
                                        $device = "";
                                        $pageURL = $_SERVER['REQUEST_URI'];
                                        $logCode = AccessLogService::getLogCode();

                                        $log->setCode($logCode);
                                        $log->setLogDate(Time::getDate());
                                        $log->setLogTime(Time::getTime());
                                        $log->setIncidentPageURL($pageURL);
                                        $log->setOperation($operation);
                                        $log->setLogMessage($error_message);
                                        $log->setActiveUser($user_code);
                                        $log->setDeviceDetails($device);
                                        $log->setAddedOn(Time::getDateTime());

                                        if (ErrorLogService::insert($log)) $log_status = true;

                                        $debug = array(
                                            "status" => FAILURE,
                                            "log_status" => $log_status,
                                            "message" => 'An error occured while fetching Attendance data. Log Reference: ' . $logCode
                                        );

                                        array_push($debugData, $debug);
                                    }
                                } else {
                                    $delegatesData = [];
                                    array_push($responseDataObj, $delegatesData);
                                }

                                if ($refund) {
                                } else {
                                    $refundData = [];
                                    array_push($responseDataObj, $refundData);
                                }

                                if ($geographical) {
                                } else {
                                    $refundData = [];
                                    array_push($responseDataObj, $refundData);
                                }



                                $response['status'] = "200";
                                $response['message'] = "Operation completed";
                                $response['data'] = $responseDataObj;
                                $response['debug'] = $debugData;
                            } else {
                                $response['status'] =  BAD_REQUEST;
                                $response['message'] = "Invalid Parameters";
                            }
                        } else {
                            $response['status'] = UNAUTHORIZED;
                            $response['message'] = "Token Expired";
                        }
                    } else {
                        $response['status'] = UNAUTHORIZED;
                        $response['message'] = "invalid token";
                    }
                } else {
                    $response['status'] = FORBIDDEN;
                    $response['message'] = "Forbidden";
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
