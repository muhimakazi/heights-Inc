<?php
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
    header("Content-Type:application/json");

    require_once "../../core/init.php"; 
    
    $response['status'] = array('status' => "403", 'message' => 'Bad Request');
    $data = array();
    $authToken = Httprequests::getBearerToken();

    /** TEMPLATE APIs */ 
    if(Input::checkInput('request', 'post', 1)):
        $_post_request_ = Input::get('request', 'post');
        switch($_post_request_):

            /** GET CAPTCHA SESSION */
            case 'captchaSession':
                $response['status'] = "200";
                $response['message'] = $_SESSION['captcha'];
                echo json_encode($response);
            break;

            /** CUSTOM REGISTRATION FORM */
            case 'customRegistration':
                if (!empty($authToken)) {
                    if ($authToken == $templateAuthToken) {
                        $_form_ = FutureEventController::registerEventParticipant();
                        if($_form_->ERRORS == false):
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                        else:
                            $response['status'] = "400";
                            $response['message'] = $_form_->ERRORS_STRING;
                        endif;
                    } else {
                        $response['status'] = "400";
                        $response['message'] = "Invalid or Expired API Key";
                    }
                } else {
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }
                echo json_encode($response);
            break;

            /** CUSTOM REGISTRATION FORM */
            case 'registration':
                if (!empty($authToken)) {
                    if ($authToken == $templateAuthToken) {
                        // $_form_ = FutureEventController::registerEventParticipant();
                        // if($_form_->ERRORS == false):
                        //     $response['status'] = "200";
                        //     $response['message'] = "Operation completed successfully";
                        // else:
                        //     $response['status'] = "400";
                        //     $response['message'] = $_form_->ERRORS_STRING;
                        // endif;

                        $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                    } else {
                        $response['status'] = "400";
                        $response['message'] = "Invalid or Expired API Key";
                    }
                } else {
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }
                echo json_encode($response);
            break;

            /** CONTACT FORM */
            case 'contactForm':
                $headers = getallheaders();
                if (isset($headers['authToken'])) {
                    $authToken = $headers['authToken'];
                    if ($authToken == $templateAuthToken) {
                        $_form_ = TemplateController::contactForm();
                        if($_form_->ERRORS == false):
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                            $response['data'] = $data;  
                        else:
                            $response['status'] = "400";
                            $response['message'] = $_form_->ERRORS_STRING;
                        endif;
                    } else {
                        $response['status'] = "400";
                        $response['message'] = "Invalid or Expired API Key";
                    }
                } else {
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }
                echo json_encode($response);
            break;

            /** UPDATE PARTICIPANT REGISTRATION */
            case 'registrationUpdate':
                if (!empty($authToken)) {
                    if ($authToken == $templateAuthToken) {
                        $_form_ = FutureEventController::updateEventParticipantProfile();
                        if($_form_->ERRORS == false):
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                        else:
                            $response['status'] = "400";
                            $response['message'] = $_form_->ERRORS_STRING;
                        endif;
                    } else {
                        $response['status'] = "400";
                        $response['message'] = "Invalid or Expired API Key";
                    }
                } else {
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }
                echo json_encode($response);
            break;

            /** GET USER DETAILS */
            case 'getUserDetails':
                if (!empty($authToken)) {
                    if ($authToken == $templateAuthToken) {
                        $_PID_ = Hash::decryptToken(Input::get('userToken', 'post'));
                        $_PARTICIPANT_DATA_ = FutureEventController::getParticipantByID($_PID_);
                        if ($_PARTICIPANT_DATA_) {
                            $data = array(
                                "title" => $_PARTICIPANT_DATA_->title,
                                "firstname" => $_PARTICIPANT_DATA_->firstname,
                                "lastname" => $_PARTICIPANT_DATA_->lastname,
                                "email" => $_PARTICIPANT_DATA_->email,
                                "telephone" => $_PARTICIPANT_DATA_->telephone,
                            );
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                            $response['data'] = $data;
                        } else {
                            $response['status'] = "400";
                            $response['message'] = "User not fund";
                        }
                    } else {
                        $response['status'] = "400";
                        $response['message'] = "Invalid or Expired API Key";
                    }
                } else {
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }
                echo json_encode($response);
            break;

        endswitch;
    endif;      
    
?>


