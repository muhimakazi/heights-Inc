<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header("Content-Type:application/json");

require_once("../core/Init.php");
require_once("../entities/Participant.php");


$response = array();
$response['status'] = BAD_REQUEST;
$response['message'] = "Bad Request";


if (Input::checkInput('resource', 'get', '1')) {
    $request = Input::get('resource', 'get');
    
    switch ($request) {

        case 'new':
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                

                if(!$authToken=="" && !$authToken!=null){

                    $authToken = Httprequests::getBearerToken($authToken);
                    $activeUser=Hash::decryptAuthToken($authToken);

                    // Getting Incoming data and Doing a JSON Decode
                    $incomingData = file_get_contents('php://input');
                    $dataObj = json_decode(str_replace("\\", "", $incomingData));

                    // Checking if all needed parameters are part of the incoming request
                    $validParameters = true;
                    if (!DataValidation::dataContains("client_code", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("event_code", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("form_type", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("form_sub_type", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("form_name", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("form_data", $incomingData)) $validParameters = false;

                    if ($validParameters) {
                        // Getting sent values
                        $client_id = $dataObj->client_code;
                        $event_id= $dataObj->event_code;
                        $form_type= $dataObj->form_type;
                        $form_sub_type= $dataObj->form_sub_type;
                        $form_name= $dataObj->form_name;
                        $fields= $dataObj->form_data;

                        $formTypeID=Hash::decryptAuthToken($form_type);
                        $formSubTypeID=Hash::decryptAuthToken($form_sub_type);

                        // Generating the form code
                        $formCode = FormService::getFormCode();

                        $status = "ACTIVE";
                        $added_date = Time::getDate();
                        $added_time = Time::getTime();

                        // Creating the event type object
                        $formObj = new Form();

                        $formObj->setFormCode($formCode);
                        $formObj->setClientID($client_id);
                        $formObj->setEventID($event_id);
                        $formObj->setFormType($formTypeID);
                        $formObj->setFormSubType($formSubTypeID);
                        $formObj->setFormName($form_name);
                        $formObj->setFields($fields);
                        $formObj->setStatus($status);
                        $formObj->setAddedDate($added_date);
                        $formObj->setAddedTime($added_time);
                        $formObj->setAddedBy($activeUser);

                        // Checking if field doesn't exist yet
                        if (!FormService::exists($formObj)) {
                            if (FormService::insert($formObj)) {
                                // Form created successfully. Saving the record in logs
                                $operation = "NEW FORM - ".$formCode;
                                $comment = SUCCESSFUL;

                                if (LogService::newLog($operation, $comment, $activeUser)) {
                                    $response['status'] = SUCCESS;
                                    $response['message'] = SUCCESSFUL;
                                } else {
                                    $response['status'] = UNSUCCESSFUL;
                                    $response['message'] = "Unable to update logs";
                                }
                            } else {
                                // Error while creating the package
                                $response['status'] = UNSUCCESSFUL;
                                $response['message'] = UNSUCCESSFUL;
                            }
                        } else {
                            // Package already exist
                            $response['status'] = FORBIDDEN;
                            $response['message'] = "Form details already exist.";
                        }
                    } else {
                        // Invalid Parameters
                        $response['status'] = FORBIDDEN;
                        $response['message'] = "Invalid Parameters";
                    }

                    
                } else{
                    // Authentication token not provided
                    $response['status'] = "403";
                    $response['message'] = "Unauthorized";
                }

            } else{
                // Invalid Request Method
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }
        break; 

        case "update":
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                

                if(!$authToken=="" && !$authToken!=null){

                    $authToken = Httprequests::getBearerToken($authToken);
                    $activeUser=Hash::decryptAuthToken($authToken);

                    // Getting Incoming data and Doing a JSON Decode
                    $incomingData = file_get_contents('php://input');
                    $dataObj = json_decode(str_replace("\\", "", $incomingData));

                    // Checking if all needed parameters are part of the incoming request
                    $validParameters = true;
                    if (!DataValidation::dataContains("edit_key", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("form_data", $incomingData)) $validParameters = false;

                    if ($validParameters) {
                        // Getting sent values
                        $edit_key= $dataObj->edit_key;
                        $fields= $dataObj->form_data;

                        $formCode=Hash::decryptAuthToken($edit_key);


                        // Creating the event type object
                        $formObj = new Form();

                        $formObj->setFormCode($formCode);
                        $formObj->setFields($fields);

                        // Checking if field doesn't exist yet
                        if (FormService::find($formObj)) {
                            if (FormService::update($formObj)) {
                                // Form created successfully. Saving the record in logs
                                $operation = "UPDATED FORM - ".$formCode;
                                $comment = SUCCESSFUL;

                                if (LogService::newLog($operation, $comment, $activeUser)) {
                                    $response['status'] = SUCCESS;
                                    $response['message'] = SUCCESSFUL;
                                } else {
                                    $response['status'] = UNSUCCESSFUL;
                                    $response['message'] = "Unable to update logs";
                                }
                            } else {
                                // Error while creating the package
                                $response['status'] = UNSUCCESSFUL;
                                $response['message'] = UNSUCCESSFUL;
                            }
                        } else {
                            // Package already exist
                            $response['status'] = FORBIDDEN;
                            $response['message'] = "Form not found.";
                        }
                    } else {
                        // Invalid Parameters
                        $response['status'] = FORBIDDEN;
                        $response['message'] = "Invalid Parameters";
                    }

                    
                } else{
                    // Authentication token not provided
                    $response['status'] = "403";
                    $response['message'] = "Unauthorized";
                }

            } else{
                // Invalid Request Method
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }

        break;

        case "delete":
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                

                if(!$authToken=="" && !$authToken!=null){

                    $authToken = Httprequests::getBearerToken($authToken);
                    $activeUser=Hash::decryptAuthToken($authToken);

                    // Getting Incoming data and Doing a JSON Decode
                    $incomingData = file_get_contents('php://input');
                    $dataObj = json_decode(str_replace("\\", "", $incomingData));

                    // Checking if all needed parameters are part of the incoming request
                    $validParameters = true;

                    if (!DataValidation::dataContains("edit_key", $incomingData)) $validParameters = false;

                    if ($validParameters) {
                        // Getting sent values
                        $edit_key = $dataObj->edit_key;

                        // Getting the field code
                        $formCode = Hash::decryptAuthToken($edit_key, FormService::formCodePrefix());

                        if (FormService::delete($formCode)) {
                            // Form deleted successfully. Saving the record in logs
                            $operation = "DELETED FORM  - ".$formCode;
                            $comment = SUCCESSFUL;

                            if (LogService::newLog($operation, $comment, $activeUser)) {
                                $response['status'] = SUCCESS;
                                $response['message'] = SUCCESSFUL;
                            } else {
                                $response['status'] = UNSUCCESSFUL;
                                $response['message'] = "Unable to update logs";
                            }
                        } else {
                            // Error while creating the package
                            $response['status'] = UNSUCCESSFUL;
                            $response['message'] = UNSUCCESSFUL;
                        }
                    } else {
                        // Invalid Parameters
                        $response['status'] = FORBIDDEN;
                        $response['message'] = "Invalid Parameters";
                    }

                    
                } else{
                    // Authentication token not provided
                    $response['status'] = "403";
                    $response['message'] = "Unauthorized";
                }

            } else{
                // Invalid Request Method
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }

        break;


        case "listbyevent":
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                
                // Get the action value
                if(Input::checkInput('action', 'get', '1')){
                    
                    if(!$authToken=="" && !$authToken!=null){

                        $authToken = Httprequests::getBearerToken($authToken);
                        $activeUser=Hash::decryptAuthToken($authToken);
    
                        // Getting the fields list
                        
                        try {
                            $eventID=Input::get("action", "get");
                            $eventID=Hash::decryptAuthToken($eventID);

                            $selection="event.torus_forms.*, (SELECT firstname FROM admin.torus_users WHERE event.torus_forms.added_by=admin.torus_users.user_code) as created_by_firstname, (SELECT lastname FROM admin.torus_users WHERE event.torus_forms.added_by=admin.torus_users.user_code) as created_by_lastname, (SELECT COUNT(id) FROM event.torus_form_auto_response WHERE event.torus_forms.form_code=event.torus_form_auto_response.form_code AND event.torus_form_auto_response.status!='DELETED') AS auto_response_count";
                            $condition="WHERE event.torus_forms.status!='DELETED' AND event.torus_forms.event_id='".$eventID."' ";

                            $forms = FormService::formsList($selection, $condition);

                            $formsList = array();
                            foreach (json_decode($forms) as $form) {
                                $form_code = "" . $form["form_code"];
                                $edit_key = Hash::encryptStringAES($form_code, FormService::formCodePrefix());

                                $formData = array(
                                    "form_data" => $form,
                                    "edit_key" => $edit_key
                                );

                                array_push($formsList, $formData);
                            }

    
                            $response['status'] = SUCCESS;
                            $response['message'] = SUCCESSFUL;
                            $response['data'] = $formsList;
                        } catch (Exception $e) {
                            $response['status'] = UNSUCCESSFUL;
                            $response['message'] = UNSUCCESSFUL . " <br> " . $e->getMessage();
                        }
    
                        
                    } else{
                        // Authentication token not provided
                        $response['status'] = "403";
                        $response['message'] = "Unauthorized";
                    }
                    
                } else{
                    // Bad Request
                }

            } else{
                // Invalid Request Method
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }

        break;


        case "databycode":
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                
                // Get the action value
                if(Input::checkInput('action', 'get', '1')){
                    
                    if(!$authToken=="" && !$authToken!=null){

                        $authToken = Httprequests::getBearerToken($authToken);
                        $activeUser=Hash::decryptAuthToken($authToken);
    
                        // Getting the fields list
                        
                        try {
                            $formCode=Input::get("action", "get");
                            $formCode=Hash::decryptStringAES($eventID);

                            $selection="event.torus_forms.*, (SELECT firstname FROM admin.torus_users WHERE event.torus_forms.added_by=admin.torus_users.user_code) as created_by_firstname, (SELECT lastname FROM admin.torus_users WHERE event.torus_forms.added_by=admin.torus_users.user_code) as created_by_lastname";
                            $condition="WHERE event.torus_forms.status!='DELETED' AND event.torus_forms.form_code='".$formCode."' ORDER BY event.torus_forms.id ASC LIMIT 1";

                            $forms = FormService::formsList($selection, $condition);

                            // Extract the form object from the array
                            $formObj=json_decode($forms)[0];

                            // Extract the form fields
                            $fieldsObj= json_decode($formObj->fields);

                            $fields= $fieldsObj->fields;


                            $fieldsList = array();
                            foreach (json_decode($fields) as $field) {
                                $field_code = "" . $field;

                                // Decrypting the field code
                                $field_code= Hash::decryptStringAES($field_code);

                                $edit_key = Hash::encryptStringAES($form_code, FormService::formCodePrefix());

                                // Get the field data
                                $fieldData=FormFieldService::getFormFieldData($field_code);

                                array_push($fieldsList, $fieldData);
                            }

    
                            $response['status'] = SUCCESS;
                            $response['message'] = SUCCESSFUL;
                            $response['data'] = $fieldsList;
                        } catch (Exception $e) {
                            $response['status'] = UNSUCCESSFUL;
                            $response['message'] = UNSUCCESSFUL . " <br> " . $e->getMessage();
                        }
    
                        
                    } else{
                        // Authentication token not provided
                        $response['status'] = "403";
                        $response['message'] = "Unauthorized";
                    }
                    
                } else{
                    // Bad Request
                }

            } else{
                // Invalid Request Method
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }

        break;

        case "formdata":
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                
                // Get the action value
                if(Input::checkInput('action', 'get', '1')){
                    
                    if(!$authToken=="" && !$authToken!=null){

                        $authToken = Httprequests::getBearerToken($authToken);
                        $activeUser=Hash::decryptAuthToken($authToken);
    
                        // Getting Incoming data and Doing a JSON Decode
                        $incomingData = file_get_contents('php://input');
                        $dataObj = json_decode(str_replace("\\", "", $incomingData));

                        // Checking if all needed parameters are part of the incoming request
                        $validParameters = true;
                        if (!DataValidation::dataContains("event", $incomingData)) $validParameters = false;
                        if (!DataValidation::dataContains("category", $incomingData)) $validParameters = false;
                        if (!DataValidation::dataContains("subcategory", $incomingData)) $validParameters = false;

                        if ($validParameters) {

                            $event=$dataObj->event;
                            $category=$dataObj->category;
                            $subCategory=$dataObj->subcategory;

                            $eventID=Hash::decryptAuthToken($event);
                            $categoryID=Hash::decryptAuthToken($category);
                            $subCategoryID=Hash::decryptAuthToken($subCategory);

                            // Getting the fields list
                            try {
                                
                                $formCode=Input::get("action", "get");
                                $formCode=Hash::decryptStringAES($eventID);

                                $selection="event.torus_forms.*, (SELECT firstname FROM admin.torus_users WHERE event.torus_forms.added_by=admin.torus_users.user_code) as created_by_firstname, (SELECT lastname FROM admin.torus_users WHERE event.torus_forms.added_by=admin.torus_users.user_code) as created_by_lastname";
                                $condition="WHERE event.torus_forms.status!='DELETED' AND event.torus_forms.form_type='".$categoryID."' AND event.torus_forms.form_sub_type='".$subCategoryID."' AND event.torus_forms.event_id='".$eventID."' ORDER BY event.torus_forms.id ASC LIMIT 1";

                                $forms = FormService::formsList($selection, $condition);

                                $formObj=array();
                                $fieldsObj=array();

                                if(count(json_decode($forms))>0){
                                    $formObj=json_decode($forms)[0];
                                    $fieldsObj=$formObj->fields;
                                }

                                $response['status'] = SUCCESS;
                                $response['message'] = SUCCESSFUL;
                                $response['data'] = $forms;
                                $response['fields'] = $fieldsObj;
                            } catch (Exception $e) {
                                $response['status'] = UNSUCCESSFUL;
                                $response['message'] = UNSUCCESSFUL . " <br> " . $e->getMessage();
                            }


                        } else{
                            // Invalid Parameters
                            $response['status'] = FORBIDDEN;
                            $response['message'] = "Invalid Parameters";
                        }


                    } else{
                        // Authentication token not provided
                        $response['status'] = "403";
                        $response['message'] = "Unauthorized";
                    }
                    
                } else{
                    // Bad Request
                }

            } else{
                // Invalid Request Method
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }

        break;


    }

} 



?>