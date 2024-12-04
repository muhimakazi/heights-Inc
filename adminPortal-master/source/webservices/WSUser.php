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
        * Resource: newuser
        * Method: POST
        */
        case 'newuser':
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
                            $incomingData = file_get_contents('php://input');
                            $dataObject = json_decode(str_replace("\\", "", $incomingData));
                            // Checking if all needed parameters are part of the incoming request
                            $validParameters = true;

                            if (!DataValidation::dataContains("account_type", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("firstname", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("lastname", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("email", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("phone", $incomingData)) $validParameters = false;
                            if (!DataValidation::dataContains("company_code", $incomingData)) $validParameters = false;

                            if ($validParameters) {

                                // Getting a unique user code 
                                $user_code = UserService::getUserCode();

                                // Getting request parameters values
                                $account_type = $dataObj->account_type;
                                $firstname = $dataObj->firstname;
                                $lastname = $dataObj->lastname;
                                $email = $dataObj->email;
                                $primary_phone = $dataObj->phone;

                                $login_username = $dataObj->email;
                                $login_password = Hash::make(SALT, DEFAULT_PASSWORD);
                                $token = Hash::make(SALT, $user_code . $primary_phone . $email);
                                $token_generation_time = Time::getDateTime();
                                $token_expiry_time = Time::addHoursToDate($token_generation_time, 48);
                                $company_code = $dataObj->company_code;
                                $status = "ACTIVE";

                                $userObj = new User();

                                $userObj->setCode($user_code);
                                $userObj->setAccountType($account_type);
                                $userObj->setFirstName($firstname);
                                $userObj->setLastName($lastname);
                                $userObj->setEmailID($email);
                                $userObj->setMSISDN($primary_phone);
                                $userObj->setLoginUsername($login_username);
                                $userObj->setLoginPassword($login_password);
                                $userObj->setToken($token);
                                $userObj->setTokenGenerationTime($token_generation_time);
                                $userObj->setTokenExpiryTime($token_expiry_time);
                                $userObj->setCompanyCode($company_code);
                                $userObj->setStatus($status);
                                $userObj->setAddedOn(Time::getDateTime());
                                $userObj->setAddedBy($user_code);
                                $userObj->setModifiedOn(Time::getDateTime());
                                $userObj->setModifiedBy($user_code);

                                $selection = "id";
                                $unique_values = "code='" . $owner_code . "' OR email_id='" . $email . "' OR msisdn='" . $primary_phone . "'";

                                if (!UserService::checkUserDetails($selection, $unique_values)) {
                                    if (UserService::insert($userObj)) {

                                        $response['status'] = "200";
                                        $response['message'] = "User " . $firstname . " " . $lastname . " was created successfully";
                                    } else {
                                        // Error while creating the user account

                                        $log_status = false;

                                        // Recording the Error log
                                        $log = new ErrorLog();
                                        $error_message = $e->getMessage();
                                        $operation = "NEW_USER";
                                        $flag = "SEVERE";
                                        $device = "";
                                        $pageURL = $_SERVER['REQUEST_URI'];

                                        $log->setCode(AccessLogService::getLogCode());
                                        $log->setLogDate(Time::getDate());
                                        $log->setLogTime(Time::getTime());
                                        $log->setIncidentPageURL($pageURL);
                                        $log->setOperation($operation);
                                        $log->setLogMessage($error_message);
                                        $log->setActiveUser($user_code);
                                        $log->setDeviceDetails($device);
                                        $log->setAddedOn(Time::getDateTime());

                                        if (ErrorLogService::insert($log)) $log_status = true;

                                        $response['status'] = "500";
                                        $response['debug'] = $log_status;
                                        $response['message'] = "An error occured while completing this operation";
                                    }
                                } else {
                                    // User with the same unique details already exists
                                    $response['status'] = "500";
                                    $response['message'] = "A user with same details already exists";
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
            /*
        * Resource: list
        * Method: GET
        */
        case 'list':

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $authToken = Httprequests::getBearerToken($authToken);

                if ($authToken != null) {
                    // Check Authentication Token Validity
                    $authSelection = "code, company_code, token_expiry_time";
                    $authCondition = "WHERE token='" . $authToken . "'";
                    $userAuthData = json_decode(UserService::userList($authSelection, $authCondition));

                    if (@!DataValidation::isEmpty($userAuthData)) {

                        $token_expiration_date = $userAuthData[0]->token_expiry_time;
                        $user_code = $userAuthData[0]->code;
                        $company_code = $userAuthData[0]->company_code;

                        $token_expiration_date = preg_replace('/\:\s+/', ':', $token_expiration_date);

                        echo $token_expiration_date;

                        if (@!Time::todayIsAfter($token_expiration_date)) {

                            $status = "ACTIVE";

                            try {

                                $selection = "*";
                                $condition = "WHERE status='" . $status . "'";
                                $userData = json_decode(UserService::userList($selection, $condition));

                                $response['status'] = "200";
                                $response['message'] = "Operation completed successfully";
                                $response['data'] = $userData;
                            } catch (Exception $e) {
                                // Exception was thrown
                                $log_status = false;

                                // Recording the Error log
                                $log = new ErrorLog();
                                $error_message = $e->getMessage();
                                $operation = "LIST_USERS";
                                $flag = "SEVERE";
                                $device = "";
                                $pageURL = $_SERVER['REQUEST_URI'];

                                $log->setCode(AccessLogService::getLogCode());
                                $log->setLogDate(Time::getDate());
                                $log->setLogTime(Time::getTime());
                                $log->setIncidentPageURL($pageURL);
                                $log->setOperation($operation);
                                $log->setLogMessage($error_message);
                                $log->setActiveUser($user_code);
                                $log->setDeviceDetails($device);
                                $log->setAddedOn(Time::getDateTime());

                                if (ErrorLogService::insert($log)) $log_status = true;

                                $response['status'] = "500";
                                $response['message'] = "An error occured while completing this request.";
                                $response['debug'] = $log_status;
                                $response['data'] = [];
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

            /*
        * Resource: list
        * Method: GET
        */
        case 'emails':

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $status = "APPROVED";

                try {

                    $selection = "future_participants.id, future_participants.email";
                    $condition = " INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id 
                    WHERE future_participation_sub_type.category='INPERSON' AND future_participants.status='APPROVED' AND future_participants.event_id=8";
                    $userData = json_decode(UserService::userList($selection, $condition));

                    $response['status'] = "200";
                    $response['message'] = "Operation completed successfully";
                    $response['data'] = $userData;
                } catch (Exception $e) {

                    $response['status'] = "500";
                    $response['message'] = "An error occured while completing this request.";
                    // $response['debug'] = $log_status;
                    $response['data'] = [];
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
