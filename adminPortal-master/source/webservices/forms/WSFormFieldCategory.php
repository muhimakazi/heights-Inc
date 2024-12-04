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

                    if (!DataValidation::dataContains("name", $incomingData)) $validParameters = false;

                    if ($validParameters) {
                        // Getting sent values
                        $category_name = $dataObj->name;

                        // Buikding the category code
                        $categoryCode = str_replace(" ", "_", $category_name);
                        $categoryCode=strtoupper($categoryCode);

                        $status = "ACTIVE";
                        $added_date = Time::getDate();
                        $added_time = Time::getTime();

                        // Creating the event type object
                        $formFieldCategoryObj = new FormFieldCategory();

                        $formFieldCategoryObj->setCode($categoryCode);
                        $formFieldCategoryObj->setCategoryName($category_name);
                        $formFieldCategoryObj->setStatus($status);
                        $formFieldCategoryObj->setAddedDate($added_date);
                        $formFieldCategoryObj->setAddedTime($added_time);
                        $formFieldCategoryObj->setAddedBy($activeUser);

                        // Checking if category doesn't exist yet
                        if (!FormFieldCategoryService::categoryExists($formFieldCategoryObj)) {
                            if (FormFieldCategoryService::insert($formFieldCategoryObj)) {
                                // Form Field category created successfully. Saving the record in logs
                                $operation = "NEW FORM FIELD CATEGORY - ".$categoryCode;
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
                            $response['message'] = "Category details already exist.";
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

                    if (!DataValidation::dataContains("name", $incomingData)) $validParameters = false;
                    if (!DataValidation::dataContains("edit_key", $incomingData)) $validParameters = false;

                    if ($validParameters) {
                        // Getting sent values
                        $category_name = $dataObj->name;
                        $edit_key = $dataObj->edit_key;

                        // Buikding the category code
                        $categoryID = Hash::decryptAuthToken($edit_key);

                        // Checking if category doesn't exist yet
                        if (!FormFieldCategoryService::conflicts($name, $categoryID)) {
                            if (FormFieldCategoryService::rename($name, $categoryID)) {
                                // Form Field category renamed successfully. Saving the record in logs
                                $operation = "NEW FORM FIELD CATEGORY NAME - ".$category_name;
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
                            $response['message'] = "Category details already exist.";
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

                        // Buikding the category code
                        $categoryID = Hash::decryptAuthToken($edit_key);

                        if (FormFieldCategoryService::delete($categoryID)) {
                            // Form Field category deleted successfully. Saving the record in logs
                            $operation = "DELETED FORM FIELD CATEGORY - ".$categoryID;
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

                    // Getting the category list
                    $selection = "*";
                    $condition = "WHERE status='ACTIVE'";

                    try {
                        $categories = FormFieldCategoryService::categoryList($selection, $condition);
                        $categoriesList = array();
                        foreach (json_decode($categories) as $category) {
                            $categoryID = $category->id;
                            $edit_key = Hash::encryptAuthToken($categoryID);

                            $categoryeData = array();
                            $categoryeData['category_data'] = $category;
                            $categoryeData['edit_key'] = $edit_key;

                            array_push($categoriesList, $categoryeData);
                        }

                        $response['status'] = SUCCESS;
                        $response['message'] = SUCCESSFUL;
                        $response['data'] = $categoriesList;
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


    }

} 



?>