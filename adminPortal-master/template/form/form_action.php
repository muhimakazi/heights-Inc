<?php
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Access-Control-Allow-Methods: GET, POST, PUT');
    header("Content-Type:application/json");

    require_once "../../core/init.php"; 
    
    $response['status'] = array('status' => "403", 'message' => 'Bad Request');
    $data = array();
    $authToken = Httprequests::getBearerToken();

    // 1. CREATE / UPDATE FORM

    /** TEMPLATE APIs */ 
    if(Input::checkInput('request', 'post', 1)):
        $_post_request_ = Input::get('request', 'post');
        switch($_post_request_):

            /** LOAD EVENT PASS FORMS */
            case 'loadEventForms':
                if (!empty($authToken)) {
                    if ($authToken == $templateAuthToken) {
                        $_filter_condition_ = " AND publish_status = 1";
                        $eventId = Hash::decryptToken(Input::get('eventToken', 'post'));
                        $order = " ORDER BY form_order ASC";
                        $formRecords = TemplateController::getForms($eventId, $_filter_condition_, $order);

                        if ($formRecords) {
                            $count_ = 0;
                            foreach($formRecords as $form_) {
                                $data[] = array(
                                    "edit_key" => Hash::encryptToken($form_->id),
                                    "form_name" => $form_->form_name,
                                    "form_note" => $form_->form_note
                                );
                            }
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                            $response['data'] = $data;
                        } else {
                            $response['status'] = "400";
                            $response['message'] = "No form recorded";
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

            /** FORM DETAILS */
            case 'formDetails':
                if (!empty($authToken)) {
                    $responseData = array();
                    $formData = array();
                    if ($authToken == $templateAuthToken) {
                        $_filter_condition_ = " AND publish_status = 1";
                        $formId = Hash::decryptToken(Input::get('formToken', 'post'));
                        $formDetails = TemplateController::formDetails($formId, $_filter_condition_);
                        if ($formDetails) {
                            $responseData["form_name"] = $formDetails->form_name." Form";
                            $responseData["form_note"] = $formDetails->form_note;
                            array_push($formData, $responseData);

                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                            $response['data'] = $formData;
                        } else {
                            $response['status'] = "400";
                            $response['message'] = "Invalid form parameters";
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

            // LOAD FORM CONTENT
            case 'formContent':
                $_form_content = FormController::loadFormContent();
                echo $_form_content;
            break;
        endswitch;
    endif;      
    
?>


