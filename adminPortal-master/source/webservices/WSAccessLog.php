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

        case 'list':
            /*
            * METHOD: GET
            */
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');

                if ($authToken) {

                    // Check Authentication Token Validity
                    $authSelection = "code, company_code, token_expiry_time";
                    $authCondition = "WHERE token='" . $authToken . "'";
                    $userAuthData = json_decode(UserService::userList($authSelection, $authCondition));

                    if (!DataValidation::isEmpty($userAuthData)) {

                        $token_expiration_date = $userAuthData->token_expiry_time;
                        $user_code = $userAuthData->code;
                        $company_code->company_code;

                        if (!Time::todayIsAfter($token_expiration_date)) {

                            try {
                                if (true) {
                                    $response['status'] = SUCCESS;
                                    $response['message'] = "Authentication done successfully";
                                    $response['data'] = [];
                                } else {
                                    $response['status'] = FAILIURE;
                                    $response['message'] = "FAIL TO UPDATE LOGS ";
                                }
                            } catch (Exception $e) {
                            }
                        } else {
                            $response['status'] = UNAUTHORIZED;
                            $response['message'] = "Invalid Token";
                        }
                    } else {
                        $response['status'] = FORBIDDEN;
                        $response['message'] = "Forbidden";
                    }
                } else {
                    $response['status'] = FORBIDDEN;
                    $response['message'] = "Forbidden Access";
                }
            } else {
                $response['status'] = BAD_REQUEST_METHOD;
                $response['message'] = "Method Not Allowed";
            }

            break;

        case 'new':
            /*
            * METHOD: POST
            */
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                if ($authToken) {

                    // Check Authentication Token Validity
                    $authSelection = "code, company_code, token_expiry_time";
                    $authCondition = "WHERE token='" . $authToken . "'";
                    $userAuthData = json_decode(UserService::userList($authSelection, $authCondition));

                    if (!DataValidation::isEmpty($userAuthData)) {

                        $token_expiration_date = $userAuthData->token_expiry_time;
                        $user_code = $userAuthData->code;
                        $company_code->company_code;

                        if (!Time::todayIsAfter($token_expiration_date)) {

                            $user_code = $access_data->user_code;
                            $user_telephone = $access_data->telephone;
                            $incomingData = file_get_contents('php://input');
                            $dataObject = json_decode(str_replace("\\", "", $incomingData));

                            $validParameters = true;
                            if (!DataValidation::dataContains("flag", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("log_date", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("log_time", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("page_url", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("operation", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("log_message", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("user_code", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("device", $incomingData)) $validParameters = false;

                            if ($validParameters) {
                                // Getting request parameters values
                                $flag = $dataObj->flag;
                                $log_date = $dataObj->log_date;
                                $log_time = $dataObj->log_time;
                                $page_url = $dataObj->page_url;
                                $operation = $dataObj->operation;
                                $log_message = $dataObj->log_message;
                                $user_code = $dataObj->user_code;
                                $device = $dataObj->device;

                                $accessLogBean = new AccessLog();

                                $error_log = "";

                                $accessLogBean->setCode(AccessLogService::getLogCode());
                                $accessLogBean->setFlag($flag);
                                $accessLogBean->setLogDate($log_date);
                                $accessLogBean->setLogTime($log_time);
                                $accessLogBean->setActivityPageURL($page_url);
                                $accessLogBean->setOperation($operation);
                                $accessLogBean->setLogMessage($log_message);
                                $accessLogBean->setUserCode($user_code);
                                $accessLogBean->setDeviceDetails($device);
                                $accessLogBean->setAddedOn(Time::getDateTime());

                                if (AccessLogService::insert($accessLogBean)) {
                                    $response['status'] = "200";
                                    $response['success'] = "1";
                                    $response['message'] = "Operation completed successfully";
                                } else {
                                    $response['status'] = "500";
                                    $response['success'] = "0";
                                    $response['message'] = "Error while completing this task...Try again later";
                                }
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
