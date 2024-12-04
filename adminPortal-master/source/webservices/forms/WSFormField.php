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
                    if (!DataValidation::dataContains("field_category", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("field_type", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("field_label", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("is_required", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("field_placeholder", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("default_value", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("field_value_type", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("values", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("specific_to", $incomingData)) $validParameters = false;

                    if ($validParameters) {
                        // Getting sent values
                        $field_category = $dataObj->field_category;
                        $field_type= $dataObj->field_type;
                        $field_label= $dataObj->field_label;
                        $is_required= $dataObj->is_required;
                        $field_placeholder= $dataObj->field_placeholder;
                        $default_value= $dataObj->default_value;
                        $field_value_type= $dataObj->field_value_type;
                        $values= $dataObj->values;
                        $specific_to= $dataObj->specific_to;

                        // Generating the category code
                        $fieldCode = FormFieldService::getFormFieldCode();

                        $status = "ACTIVE";
                        $added_date = Time::getDate();
                        $added_time = Time::getTime();

                        // Creating the event type object
                        $formFieldObj = new FormField();

                        $formFieldObj->setFieldCode($fieldCode);
                        $formFieldObj->setFieldCategory($field_category);
                        $formFieldObj->setFieldType($field_type);
                        $formFieldObj->setFieldLabel($field_label);
                        $formFieldObj->setIsRequired($is_required);
                        $formFieldObj->setFieldPlaceholder($field_placeholder);
                        $formFieldObj->setDefaultValue($default_value);
                        $formFieldObj->setFieldValueType($field_value_type);
                        $formFieldObj->setFieldValues($values);
                        $formFieldObj->setSpecificTo($specific_to);
                        $formFieldObj->setStatus($status);
                        $formFieldObj->setAddedDate($added_date);
                        $formFieldObj->setAddedTime($added_time);
                        $formFieldObj->setAddedBy($activeUser);

                        // Checking if field doesn't exist yet
                        if (!FormFieldService::fieldExists($formFieldObj)) {
                            if (FormFieldService::insert($formFieldObj)) {
                                // Form Field created successfully. Saving the record in logs
                                $operation = "NEW FORM FIELD - ".$fieldCode;
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
                            $response['message'] = "Field details already exist.";
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

        case "edit":
            
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
                    if (!DataValidation::dataContains("field_category", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("field_type", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("field_label", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("is_required", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("field_placeholder", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("default_value", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("field_value_type", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("values", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("specific_to", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("edit_key", $incomingData)) $validParameters = false;

                    if ($validParameters) {
                        // Getting sent values
                        $field_category = $dataObj->field_category;
                        $field_type= $dataObj->field_type;
                        $field_label= $dataObj->field_label;
                        $is_required= $dataObj->is_required;
                        $field_placeholder= $dataObj->field_placeholder;
                        $default_value= $dataObj->default_value;
                        $field_value_type= $dataObj->field_value_type;
                        $values= $dataObj->values;
                        $specific_to= $dataObj->specific_to;
                        $edit_key= $dataObj->edit_key;
                        

                        // Getting the field code
                        $fieldCode = Hash::decryptAuthToken($edit_key, FormFieldService::fieldCodePrefix());

                        // Creating the event type object
                        $formFieldObj = new FormField();

                        $formFieldObj->setFieldCode($fieldCode);
                        $formFieldObj->setFieldCategory($field_category);
                        $formFieldObj->setFieldType($field_type);
                        $formFieldObj->setFieldLabel($field_label);
                        $formFieldObj->setIsRequired($is_required);
                        $formFieldObj->setFieldPlaceholder($field_placeholder);
                        $formFieldObj->setDefaultValue($default_value);
                        $formFieldObj->setFieldValueType($field_value_type);
                        $formFieldObj->setFieldValues($values);
                        $formFieldObj->setSpecificTo($specific_to);

                        // Checking if field doesn't exist yet
                        if (!FormFieldService::conflicts($formFieldObj)) {
                            if (FormFieldService::update($formFieldObj)) {
                                // Form Field updated successfully. Saving the record in logs
                                $operation = "UPDATED FORM FIELD - ".$fieldCode;
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
                            $response['message'] = "Field details already exist.";
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
                        $fieldCode = Hash::decryptAuthToken($edit_key, FormFieldService::fieldCodePrefix());

                        if (FormFieldService::delete($fieldCode)) {
                            // Form Field deleted successfully. Saving the record in logs
                            $operation = "DELETED FORM FIELD - ".$fieldCode;
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


        case "list":
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                

                if(!$authToken=="" && !$authToken!=null){

                    $authToken = Httprequests::getBearerToken($authToken);
                    $activeUser=Hash::decryptAuthToken($authToken);

                    // Getting the fields list
                    $selection = "*";
                    $condition = "WHERE status='ACTIVE'";

                    try {
                        $fields = FormFieldCategoryService::categoryList($selection, $condition);
                        $fieldsList = array();
                        foreach (json_decode($fields) as $field) {
                            $field_code = $field->field_code;
                            $edit_key = Hash::encryptStringAES($field_code);

                            $fieldData = array();
                            $fieldData['field_data'] = $category;
                            $fieldData['edit_key'] = $edit_key;

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
                // Invalid Request Method
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }

        break;

        case "listwithcategory":
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                

                if(!$authToken=="" && !$authToken!=null){

                    $authToken = Httprequests::getBearerToken($authToken);
                    $activeUser=Hash::decryptAuthToken($authToken);

                    // Getting the fields list

                    try {

                        $field_category_selection = "*";
                        $field_category_condition = "WHERE status!='DELETED'";
                        $field_categories = FormFieldCategoryService::categoryList($field_category_selection, $field_category_condition);

                        $oragnisedFieldList = array();
                        foreach (json_decode($field_categories) as $field_category) {
                            $field_category_code = $field_category["category_code"];
                            $field_category_name = $field_category["category_name"];

                            $field_selection = "*";
                            $field_condition = "WHERE field_category='" . $field_category_code . "' AND status!='DELETED'";
                            $fields = FormFieldService::fieldsList($field_selection, $field_condition);

                            $fieldsList = array();
                            foreach (json_decode($fields) as $field) {
                                $field_code = "" . $field["field_code"];
                                $edit_key = Hash::encryptStringAES($field_code, FormFieldService::fieldCodePrefix());

                                $fieldData = array(
                                    "field_data" => $field,
                                    "edit_key" => $edit_key
                                );

                                array_push($fieldsList, $fieldData);
                            }

                            $organisedFieldsData = array(
                                "fields_category_code" => $field_category_code,
                                "fields_category_name" => $field_category_name,
                                "fields" => $fieldsList
                            );

                            array_push($oragnisedFieldList, $organisedFieldsData);
                        }


                        $response['status'] = SUCCESS;
                        $response['message'] = SUCCESSFUL;
                        $response['data'] = $oragnisedFieldList;
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
                // Invalid Request Method
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }

        break;

        case "data":
            
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
                            $fieldCode=Input::get("action", "get");
                            $fieldCode=Hash::decryptStringAES($fieldCode);

                            $selection = "*";
                            $condition = "WHERE field_code= '".$fieldCode."' AND status!='DELETED'";
                            $fields = FormFieldService::fieldsList($selection, $condition);

                            $fieldList = array();
                            foreach (json_decode($fields) as $field) {
                                $field_code = "" . $field["field_code"];
                                $edit_key = Hash::encryptStringAES($field_code, FormFieldService::fieldCodePrefix());

                                $fieldData = array(
                                    "field_data" => $field,
                                    "edit_key" => $edit_key
                                );

                                array_push($fieldList, $fieldData);
                            }

    
                            $response['status'] = SUCCESS;
                            $response['message'] = SUCCESSFUL;
                            $response['data'] = $fieldList;
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


    }

} 



?>