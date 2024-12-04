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

        /*
        * Resource: HUBSPOT SYNC API
        * Method: GET
        */
        case 'get':
            
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                
                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                

                if(!$authToken==""){
                    
                    $authToken = Httprequests::getBearerToken($authToken);
                    $api_key=$authToken;
                    

                    switch(trim($api_key)){

                        case "06M12L1E-39U99CR4-3I17UE2N-IFF06918-EVTCLSD":

                            $event_id=13;

                            try{
                                $selection="*";
                                $condition="WHERE event_id='".$event_id."' AND status!='DELETED'";
                                $order="ORDER BY id DESC";
                                $limit="LIMIT 1";

                                $participants= json_decode(ParticipantService::participantsList($selection, $condition));
                                $fullDetails=array();

                                $participantData=array();
                                $counter=0;

                                if(!empty($participants)){

                                    foreach($participants as $participant){

                                        @$fullDetails= html_entity_decode($participant->full_details);
    
                                        if(json_decode($fullDetails)!=null){
                                            
                                            $fullDetails=json_decode($fullDetails);
    
                                        } else{
                                            // Not a valid JSON Object
                                        }
    
                                        
                                        // Extracting values from the resultset
    
                                        // 1. Contact Details
                                            $participantID=$participant->id;
                                            $participantCode=$participant->participant_code;
                                            $participantCategory=$participant->participation_type_id;
                                            $participantSubType=$participant->participation_sub_type_id;
                                            $salutation=property_exists($fullDetails, "title")?$fullDetails->title:"";
                                            $firstname=$participant->firstname;
                                            $lastname=$participant->lastname;
                                            $email=$participant->email;
                                            $telephone=$participant->telephone;
                                            $country=$participant->residence_country;
                                            $jobTitle=$participant->job_title;
                                            $jobLevel=property_exists($fullDetails, "job_level1")?$fullDetails->job_level1:"";
                                            $otherJobLevel="";
    
                                            // Check if another Job Level was provided
                                            if(trim($jobLevel)=="Other") $otherJobLevel= property_exists($fullDetails, "job_level")?$fullDetails->job_level:"";
    
                                            $jobFunction=property_exists($fullDetails, "job_function1")?$fullDetails->job_function1:"";
                                            $otherJobFunction="";
    
                                            // Check if another job function was provided
                                            if(trim($jobFunction)=="Other") $otherJobFunction= property_exists($fullDetails, "job_function")?$fullDetails->job_function:"";
    
                                        
                                        // 2. Organisation Details
                                            $organisationName=$participant->organisation_name;
                                            $organisationType=property_exists($fullDetails, "organisation_type1")?$fullDetails->organisation_type1:"";
                                            $otherOrganisationType="";
    
                                            // Check if another Organisation Type was provided
                                            if(trim($organisationType)=="Other") $otherOrganisationType= property_exists($fullDetails, "organisation_type")?$fullDetails->organisation_type:"";
    
                                            // Getting Organisation subType for each Type
                                            $organisationSubType="";
    
                                            if(trim(strtolower($organisationType))=="fintech"){
                                                // If Organisation type is Fintech, get Fintech subtype
                                                $organisationSubType=property_exists($fullDetails, "finetech_firm_type")?$fullDetails->finetech_firm_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="financial institution"){
                                                // If Organisation type is Financial Institution, get Financial Institution subtype
                                                $organisationSubType=property_exists($fullDetails, "financial_institution_type")?$fullDetails->financial_institution_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="investor"){
                                                // If Organisation type is Investor, get Investor subtype
                                                $organisationSubType=property_exists($fullDetails, "investor_type")?$fullDetails->investor_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="corporate (non-ninance / technology industries)"){
                                                // If Organisation type is Corporate and Vertical Industry, get equivalent subtype
                                                $organisationSubType=property_exists($fullDetails, "corporate_type")?$fullDetails->corporate_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="technology"){
                                                // If Organisation type is Technology, get equivalent subtype
                                                $organisationSubType=property_exists($fullDetails, "technology_type")?$fullDetails->technology_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="digitalaAssets & blockchain"){
                                                // If Organisation type is Digital Assets and Blockchain, get equivalent subtype
                                                $organisationSubType=property_exists($fullDetails, "assets_type")?$fullDetails->assets_type:"";
                                                
                                            }
    
    
                                        // 3. Event Interests
                                        $fintechStage= property_exists($fullDetails, "stage_of_startup")?$fullDetails->stage_of_startup:"";
                                        $reasonsForVisit= property_exists($fullDetails, "participant_reasons_for_visit")?$fullDetails->participant_reasons_for_visit:[];
                                        $otherReasonsForVisit="";
                                        
                                        // 4. Event Interets
                                        $otherEvents= property_exists($fullDetails, "participant_event_interest")?$fullDetails->participant_event_interest:[];
    
    
                                        // 5. Approval Information
                                        $registrationStatus="COMPLETED";
                                        $approvalStatus=$participant->status;
                                        $paymentStatus=PaymentService::hasAlreadyPaid($participantID)?"COMPLETED":"PENDING";
                                        $isEarlyBird= ($participantSubType==95 || $participantSubType==97)?"YES":"NO";
    
                                        // 6. Startup status
                                        $isStartup=$participantCategory==37?"YES":"NO";
    
                                        // 7. Payment data
                                        $subCategoryData=ParticipationSubTypeService::getSubTypeData($participantCategory, $participantSubType);
                                        
                                        
                                        // 8. Referral data
                                        $referredBy= property_exists($fullDetails, "referral")?$fullDetails->referral:"N/A";

                                        
                                        // 9. Registration date
                                        $registeredOn= $participant->reg_date;

                                        $passFee= 0;
                                        $passFeeCurrency= "USD";
    
                                        if(!empty($subCategoryData)){
    
                                            if($subCategoryData->payment_state=="PAYABLE"){
                                                $passFee= $subCategoryData->price;
                                                $passFeeCurrency= $subCategoryData->currency;
                                            }
     
                                        }
    
    
                                        // Building the response
                                        $responseData=array();
    
                                        // Formatting the category
                                        $categoryText=ParticipationTypeService::getTypeName($participantCategory);
                                        $categoryText=str_replace(" ", "_", $categoryText);
                                        $categoryText=strtoupper(str_replace("-", "", $categoryText));


                                        // Checking the CMPD and Complementary Categories and appending the subcategory
                                        $subcategoryText="";
                                        if($participantCategory==39 || $participantCategory==45){
                                            $subcategoryText= $subCategoryData->name;
                                            $subcategoryText=str_replace(" ", "_", $subcategoryText);
                                            $subcategoryText=strtoupper(str_replace("-", "", $subcategoryText));

                                            // Renaming Delegate to Complementary for better understanding
                                            if($participantCategory==45) $categoryText="COMPLEMENTARY";

                                            // Renaming Hosted Media to Media for better understanding
                                            if($participantSubType==103) $subcategoryText="MEDIA";

                                            // Appending the subcategory to the category
                                            $categoryText=$categoryText."_".$subcategoryText;
                                        } 

                                        if ($participantSubType == 116 || $participantSubType == 117 || $participantSubType == 118) 
                                            $categoryText = 'COMPLEMENTARY_INDUSTRY';

                                        if ($participantSubType == 120) 
                                            $categoryText = 'COMPLEMENTARY_STARTUP';

                                        if ($participantSubType == 119 || $participantSubType == 128) 
                                            $categoryText = 'COMPLEMENTARY_SPEAKER_ONBOARDING';


                                        // Contact Details
                                        $responseData["participantCode"]= $participantCode;
                                        $responseData["category"]= $categoryText;
                                        $responseData["passFee"]= $passFee;
                                        $responseData["passFeeCurrency"]= $passFeeCurrency;
                                        $responseData["salutation"]= $salutation;
                                        $responseData["firstname"]= html_entity_decode($firstname);
                                        $responseData["lastname"]= html_entity_decode($lastname);
                                        $responseData["businessEmail"]= $email;
                                        $responseData["telephone"]= $telephone;
                                        
                                        
                                        // Organisation Details
                                        $responseData["companyName"]= $organisationName;
                                        $responseData["jobTitle"]= $jobTitle;
                                        $responseData["country"]= $country;
                                        $responseData["jobLevel"]= $jobLevel;
                                        $responseData["otherJobLevel"]= $otherJobLevel;
                                        $responseData["jobFunction"]= $jobFunction;
                                        $responseData["otherJobFunction"]= $otherJobFunction;
                                        $responseData["organisationType"]= $organisationType;
                                        $responseData["otherOrganisationType"]= $otherOrganisationType;
                                        
                                        $responseData["organisationSubType"]= $organisationSubType;
                                        
                                        $responseData["reasonsForVisit"]= $reasonsForVisit;
                                        $responseData["otherReasonsForVisit"]= $otherReasonsForVisit;
                                        $responseData["isStartup"]= $isStartup;
                                        $responseData["startupStage"]= $fintechStage;
                                        $responseData["interestedInOtherGlobalEvents"]= $otherEvents;
                                        

                                        // Referral Details
                                        $responseData["referredBy"]= $referredBy;
                                        
                                        // Approval & Payment Details
                                        $responseData["registrationStatus"]= $registrationStatus;
                                        $responseData["paymentStatus"]= $paymentStatus;
                                        $responseData["approvalStatus"]= $approvalStatus;
                                        $responseData["isEarlyBird"]= $isEarlyBird;

                                        // Registration Date & Time
                                        $responseData["registeredOn"]= $registeredOn;
    
                                        array_push($participantData, $responseData);
    
                                    }
    
    
    
                                    $response['status'] = "200";
                                    $response['message'] = "Operation completed successfully";
                                    $response['data'] = $participantData;

                                } else{
                                    // No data was found
                                    $response['status'] = "200";
                                    $response['message'] = "Operation completed successfully";
                                    $response['data'] = $participantData;
                                }

                                


                            } catch(Exception $e){
                                $response['status'] = "500";
                                $response['message'] = "An error occurred while processing this request";
                            }


                            break;


                        case "01M10R1G-39U99CR4-3I17UE2N-IFF06918-EVTCLSD":

                            $event_id=13;

                            try{
                                $selection="*";
                                $condition="WHERE event_id='".$event_id."' AND status='APPROVED'";
                                $order="ORDER BY id DESC";
                                $limit="LIMIT 1";

                                $participants= json_decode(ParticipantService::participantsList($selection, $condition));
                                $fullDetails=array();

                                $participantData=array();
                                $counter=0;

                                if(!empty($participants)){

                                    foreach($participants as $participant){

                                        @$fullDetails= html_entity_decode($participant->full_details);
    
                                        if(json_decode($fullDetails)!=null){
                                            
                                            $fullDetails=json_decode($fullDetails);
    
                                        } else{
                                            // Not a valid JSON Object
                                        }
    
                                        
                                        // Extracting values from the resultset
    
                                        // 1. Contact Details
                                            $participantID=$participant->id;
                                            $participantCode=$participant->participant_code;
                                            $participantCategory=$participant->participation_type_id;
                                            $participantSubType=$participant->participation_sub_type_id;
                                            $salutation=property_exists($fullDetails, "title")?$fullDetails->title:"";
                                            $firstname=$participant->firstname;
                                            $lastname=$participant->lastname;
                                            $email=$participant->email;
                                            $telephone=$participant->telephone;
                                            $country=$participant->residence_country;
                                            $idType=$participant->id_type;
                                            $idNumber=$participant->id_number;
                                            $jobTitle=$participant->job_title;
                                            $jobLevel=property_exists($fullDetails, "job_level1")?$fullDetails->job_level1:"";
                                            $otherJobLevel="";
    
                                            // Check if another Job Level was provided
                                            if(trim($jobLevel)=="Other") $otherJobLevel= property_exists($fullDetails, "job_level")?$fullDetails->job_level:"";
    
                                            $jobFunction=property_exists($fullDetails, "job_function1")?$fullDetails->job_function1:"";
                                            $otherJobFunction="";
    
                                            // Check if another job function was provided
                                            if(trim($jobFunction)=="Other") $otherJobFunction= property_exists($fullDetails, "job_function")?$fullDetails->job_function:"";
    
                                        
                                        // 2. Organisation Details
                                            $organisationName=$participant->organisation_name;
                                            $organisationType=property_exists($fullDetails, "organisation_type1")?$fullDetails->organisation_type1:"";
                                            $otherOrganisationType="";
    
                                            // Check if another Organisation Type was provided
                                            if(trim($organisationType)=="Other") $otherOrganisationType= property_exists($fullDetails, "organisation_type")?$fullDetails->organisation_type:"";
    
                                            // Getting Organisation subType for each Type
                                            $organisationSubType="";
    
                                            if(trim(strtolower($organisationType))=="fintech"){
                                                // If Organisation type is Fintech, get Fintech subtype
                                                $organisationSubType=property_exists($fullDetails, "finetech_firm_type")?$fullDetails->finetech_firm_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="financial institution"){
                                                // If Organisation type is Financial Institution, get Financial Institution subtype
                                                $organisationSubType=property_exists($fullDetails, "financial_institution_type")?$fullDetails->financial_institution_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="investor"){
                                                // If Organisation type is Investor, get Investor subtype
                                                $organisationSubType=property_exists($fullDetails, "investor_type")?$fullDetails->investor_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="corporate (non-ninance / technology industries)"){
                                                // If Organisation type is Corporate and Vertical Industry, get equivalent subtype
                                                $organisationSubType=property_exists($fullDetails, "corporate_type")?$fullDetails->corporate_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="technology"){
                                                // If Organisation type is Technology, get equivalent subtype
                                                $organisationSubType=property_exists($fullDetails, "technology_type")?$fullDetails->technology_type:"";
    
                                            } else if(trim(strtolower($organisationType))=="digitalaAssets & blockchain"){
                                                // If Organisation type is Digital Assets and Blockchain, get equivalent subtype
                                                $organisationSubType=property_exists($fullDetails, "assets_type")?$fullDetails->assets_type:"";
                                                
                                            }
    
    
                                        // 3. Event Interests
                                        $fintechStage= property_exists($fullDetails, "stage_of_startup")?$fullDetails->stage_of_startup:"";
                                        $reasonsForVisit= property_exists($fullDetails, "participant_reasons_for_visit")?$fullDetails->participant_reasons_for_visit:[];
                                        $otherReasonsForVisit="";
                                        
                                        // 4. Event Interets
                                        $otherEvents= property_exists($fullDetails, "participant_event_interest")?$fullDetails->participant_event_interest:[];
    
    
                                        // 5. Approval Information
                                        $registrationStatus="COMPLETED";
                                        $approvalStatus=$participant->status;
                                        $rgStatus=$participant->rg_status;
                                        $paymentStatus=PaymentService::hasAlreadyPaid($participantID)?"COMPLETED":"PENDING";
                                        $isEarlyBird= ($participantSubType==95 || $participantSubType==97)?"YES":"NO";
    
                                        // 6. Startup status
                                        $isStartup=$participantCategory==37?"YES":"NO";
    
                                        // 7. Payment data
                                        $subCategoryData=ParticipationSubTypeService::getSubTypeData($participantCategory, $participantSubType);
                                        
                                        
                                        // 8. Referral data
                                        $referredBy= property_exists($fullDetails, "referral")?$fullDetails->referral:"N/A";

                                        
                                        // 9. Registration date
                                        $registeredOn= $participant->reg_date;

                                        $passFee= 0;
                                        $passFeeCurrency= "USD";
    
                                        if(!empty($subCategoryData)){
    
                                            if($subCategoryData->payment_state=="PAYABLE"){
                                                $passFee= $subCategoryData->price;
                                                $passFeeCurrency= $subCategoryData->currency;
                                            }
     
                                        }
    
    
                                        // Building the response
                                        $responseData=array();
    
                                        // Formatting the category
                                        $categoryText=ParticipationTypeService::getTypeName($participantCategory);
                                        $categoryText=str_replace(" ", "_", $categoryText);
                                        $categoryText=strtoupper(str_replace("-", "", $categoryText));


                                        // Checking the CMPD and Complementary Categories and appending the subcategory
                                        $subcategoryText="";
                                        if($participantCategory==39 || $participantCategory==45){
                                            $subcategoryText= $subCategoryData->name;
                                            $subcategoryText=str_replace(" ", "_", $subcategoryText);
                                            $subcategoryText=strtoupper(str_replace("-", "", $subcategoryText));

                                            // Renaming Delegate to Complementary for better understanding
                                            if($participantCategory==45) $categoryText="COMPLEMENTARY";

                                            // Renaming Hosted Media to Media for better understanding
                                            if($participantSubType==103) $subcategoryText="MEDIA";

                                            // Appending the subcategory to the category
                                            $categoryText=$categoryText."_".$subcategoryText;
                                        } 

                                        if ($participantSubType == 116 || $participantSubType == 117 || $participantSubType == 118) 
                                            $categoryText = 'COMPLEMENTARY_INDUSTRY';

                                        if ($participantSubType == 120) 
                                            $categoryText = 'COMPLEMENTARY_STARTUP';

                                        if ($participantSubType == 119 || $participantSubType == 128) 
                                            $categoryText = 'COMPLEMENTARY_SPEAKER_ONBOARDING';


                                        // Contact Details
                                        $responseData["participantCode"]= $participantCode;
                                        $responseData["category"]= $categoryText;
                                        $responseData["passFee"]= $passFee;
                                        $responseData["passFeeCurrency"]= $passFeeCurrency;
                                        $responseData["salutation"]= $salutation;
                                        $responseData["firstname"]= html_entity_decode($firstname);
                                        $responseData["lastname"]= html_entity_decode($lastname);
                                        $responseData["email"]= $email;
                                        $responseData["telephone"]= $telephone;
                                        
                                        
                                        // Organisation Details
                                        $responseData["companyName"]= $organisationName;
                                        $responseData["jobTitle"]= $jobTitle;
                                        $responseData["country"]= $country;
                                        $responseData["jobLevel"]= $jobLevel;
                                        $responseData["otherJobLevel"]= $otherJobLevel;
                                        $responseData["jobFunction"]= $jobFunction;
                                        $responseData["otherJobFunction"]= $otherJobFunction;
                                        $responseData["organisationType"]= $organisationType;
                                        $responseData["otherOrganisationType"]= $otherOrganisationType;
                                        
                                        $responseData["organisationSubType"]= $organisationSubType;
                                        
                                        $responseData["reasonsForVisit"]= $reasonsForVisit;
                                        $responseData["otherReasonsForVisit"]= $otherReasonsForVisit;
                                        $responseData["isStartup"]= $isStartup;
                                        $responseData["startupStage"]= $fintechStage;
                                        $responseData["interestedInOtherGlobalEvents"]= $otherEvents;
                                        

                                        // Referral Details
                                        $responseData["referredBy"]= $referredBy;
                                        
                                        // Approval & Payment Details
                                        $responseData["registrationStatus"]= $registrationStatus;
                                        $responseData["paymentStatus"]= $paymentStatus;
                                        $responseData["approvalStatus"]= $approvalStatus;
                                        $responseData["rgStatus"]= $rgStatus;
                                        $responseData["isEarlyBird"]= $isEarlyBird;

                                        // Accreditation Details
                                        $responseData["idType"]= $idType;
                                        $responseData["idNumber"]= $idNumber;

                                        // Registration Date & Time
                                        $responseData["registeredOn"]= $registeredOn;
    
                                        array_push($participantData, $responseData);
    
                                    }
    
    
    
                                    $response['status'] = "200";
                                    $response['message'] = "Operation completed successfully";
                                    $response['data'] = $participantData;

                                } else{
                                    // No data was found
                                    $response['status'] = "200";
                                    $response['message'] = "Operation completed successfully";
                                    $response['data'] = $participantData;
                                }

                                


                            } catch(Exception $e){
                                $response['status'] = "500";
                                $response['message'] = "An error occurred while processing this request";
                            }


                            break;

                        default:
                            // Invalid API KEY
                            $response['status'] = "400";
                            $response['message'] = "Invalid or Expired API Key";
                        break;

                    }


                } else{
                    // API Key is not provided
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }

            }   

            break;

        /*
        * Resource: RG SYNC API
        * Method: POST
        */

        case 'post':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
                

                if(!$authToken==""){
                    
                    $authToken = Httprequests::getBearerToken($authToken);
                    $api_key=$authToken;
                    

                    switch(trim($api_key)){

                        case "01M10R1G-39U99CR4-3I17UE2N-IFF06918-EVTCLSD":
                            if (Input::checkInput('participantCode', 'post', '1') AND Input::checkInput('rgStatus', 'post', '1')) {
                                $participant_code = Input::get('participantCode', 'post');
                                $status = Input::get('rgStatus', 'post');

                                $findDel = DBConnection::getInstance()->query("SELECT `id`, `participant_code` FROM `future_participants` WHERE `participant_code` = '$participant_code'");

                                if ($findDel->count()) {
                                    try {
                                        $update = DBConnection::getInstance()->query("UPDATE `future_participants` set `rg_status` = '$status' WHERE `participant_code` = '$participant_code'");

                                        if ($update) {
                                            $response['status'] = "200";
                                            $response['message'] = "SUCCESS";
                                        }

                                    } catch(Exception $e){
                                        // An error occured while processing the request
                                        $response['status'] = "500";
                                        $response['message'] = 'An error occured while processing the request';
                                    }
                                } else {
                                    $response['status'] = "403";
                                    $response['message'] = "Delegate was not found";
                                }

                            } else {
                                $response['status'] = "400";
                                $response['message'] = "Incomplete information";
                            }
                            break;

                        default:
                            // Invalid API KEY
                            $response['status'] = "400";
                            $response['message'] = "Invalid or Expired API Key";
                        break;

                    }
                } else{
                    // API Key is not provided
                    $response['status'] = "403";
                    $response['message'] = "API Key missing";
                }
            }

            break;


        case "figures":

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                $requestHeaders = getallheaders();
                $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');

                if(!$authToken==""){
                    
                    $authToken = Httprequests::getBearerToken($authToken);

                    $event_id=Hash::decryptToken($authToken);

                    try{

                        $selection="*";
                        $condition="WHERE event_id='".$event_id."' AND status!='DELETED'";
                        $order="ORDER BY id DESC";
                        $limit="LIMIT 1";
    
                        $participants= json_decode(ParticipantService::participantsList($selection, $condition));
    
                        $totalRegistrations=0;
                        $pendingRegistrations=0;
                        $approvedRegistrations=0;
                        $rejectedRegistrations=0;
    
                        // Numbers by category
                        $vip=0;
                        $vipPercentage=0;
                        $vipPending=0;
                        $vipPendingPercentage=0;
                        $vipApproved=0;
                        $vipApprovedPercentage=0;

                        $industry=0;
                        $industryPercentage=0;
                        $industryPending=0;
                        $industryPendingPercentage=0;
                        $industryApproved=0;
                        $industryApprovedPercentage=0;

                        $startup=0;
                        $startupPercentage=0;
                        $startupPending=0;
                        $startupPendingPercentage=0;
                        $startupApproved=0;
                        $startupApprovedPercentage=0;

                        $government=0;
                        $governmentPercentage=0;
                        $governmentPending=0;
                        $governmentPendingPercentage=0;
                        $governmentApproved=0;
                        $governmentApprovedPercentage=0;

                        $media=0;
                        $mediaPercentage=0;
                        $mediaPending=0;
                        $mediaPendingPercentage=0;
                        $mediaApproved=0;
                        $mediaApprovedPercentage=0;

                        $cmpd=0;
                        $cmpdPercentage=0;
                        $cmpdPending=0;
                        $cmpdPendingPercentage=0;
                        $cmpdApproved=0;
                        $cmpdApprovedPercentage=0;

                        $speaker=0;
                        $speakerPercentage=0;
                        $speakerPending=0;
                        $speakerPendingPercentage=0;
                        $speakerApproved=0;
                        $speakerApprovedPercentage=0;

                        $figuresData=array();
    
                        if(!empty($participants)){

                            $fullDetails=array();
    
                            foreach($participants as $participant){

                                @$fullDetails= html_entity_decode($participant->full_details);
    
                                if(json_decode($fullDetails)!=null){
                                    
                                    $fullDetails=json_decode($fullDetails);

                                } else{
                                    // Not a valid JSON Object
                                }
    
                                $registration_status=$participant->status;
    
                                if($registration_status=="PENDING"){
                                    $pendingRegistrations++;
                                }
    
                                if($registration_status=="APPROVED"){
                                    $approvedRegistrations++;
                                }
    
                                if($registration_status=="DENIED"){
                                    $rejectedRegistrations++;
                                }


                                // Checking the categories
                                $participantType=$participant->participation_type_id;
                                $participantSubType=$participant->participation_sub_type_id;

                                // Industry - 36
                                if($participantType==36){
                                    $industry++;
                                    if($registration_status == "PENDING"){
                                        $industryPending++;
                                    }
                                    else if($registration_status == "APPROVED"){
                                        $industryApproved++;
                                    }
                                }
                                
                                // Startup - 37
                                else if($participantType==37){
                                    $startup++;
                                    if($registration_status == "PENDING"){
                                        $startupPending++;
                                    }
                                    else if($registration_status == "APPROVED"){
                                        $startupApproved++;
                                    }
                                }

                                // VIP - 41
                                else if($participantType==41){
                                    $vip++;
                                    if($registration_status == "PENDING"){
                                        $vipPending++;
                                    }
                                    else if($registration_status == "APPROVED"){
                                        $vipApproved++;
                                    }
                                }

                                // Governemt - 38
                                else if($participantType==38){
                                    $government++;
                                    if($registration_status == "PENDING"){
                                        $governmentPending++;
                                    }
                                    else if($registration_status == "APPROVED"){
                                        $governmentApproved++;
                                    }
                                }

                                // Media - 40
                                else if($participantType==40){
                                    $media++;
                                    if($registration_status == "PENDING"){
                                        $mediaPending++;
                                    }
                                    else if($registration_status == "APPROVED"){
                                        $mediaApproved++;
                                    }
                                }

                                // CMPD - 39
                                else if($participantType==39){
                                    
                                    $cmpd++;

                                    // Check for the CMP subcategory and add to its equivalent category
                                    if($participantSubType==99){
                                        // CMPD - Industry
                                        $industry++;
                                    }

                                    else if($participantSubType==100){
                                        // CMPD - Startup
                                        $startup++;
                                    }

                                    else if($participantSubType==101){
                                        // CMPD - VIP
                                        $vip++;
                                    }

                                    else if($participantSubType==102){
                                        // CMPD - Government
                                        $vip++;
                                    }

                                    else if($participantSubType==103){
                                        // CMPD - Media
                                        $media++;
                                    }

                                    if($registration_status == "PENDING"){
                                        $cmpdPending++;
                                    }
                                    else if($registration_status == "APPROVED"){
                                        $cmpdApproved++;
                                    }
                                }

                                // Delegate (Complementary) - 45
                                else if($participantType==45){
                                    // Check for the Complementary subcategory and add to its equivalent category
                                    if($participantSubType==106 || $participantSubType==116 || $participantSubType==117 || $participantSubType==118){
                                        // Complementary - Industry
                                        $industry++;
                                        if($registration_status == "PENDING"){
                                            $industryPending++;
                                        }
                                        else if($registration_status == "APPROVED"){
                                            $industryApproved++;
                                        }
                                    }

                                    else if($participantSubType==107 || $participantSubType==120){
                                        // Complementary - Startup
                                        $startup++;
                                        if($registration_status == "PENDING"){
                                            $startupPending++;
                                        }
                                        else if($registration_status == "APPROVED"){
                                            $startupApproved++;
                                        }
                                    }

                                    else if($participantSubType==108){
                                        // Complementary - Media
                                        $media++;
                                        if($registration_status == "PENDING"){
                                            $mediaPending++;
                                        }
                                        else if($registration_status == "APPROVED"){
                                            $mediaApproved++;
                                        }
                                    }

                                    else if($participantSubType==109){
                                        // Complementary - Government
                                        $government++;
                                        if($registration_status == "PENDING"){
                                            $governmentPending++;
                                        }
                                        else if($registration_status == "APPROVED"){
                                            $governmentApproved++;
                                        }
                                    }

                                    else if($participantSubType==110 || $participantSubType == 119 || $participantSubType == 128 || $participantSubType == 137){
                                        // Complementary - VIP
                                        $vip++;
                                        if($registration_status == "PENDING"){
                                            $vipPending++;
                                        }
                                        else if($registration_status == "APPROVED"){
                                            $vipApproved++;
                                        }
                                    } else if($participantSubType == 119){
                                        // Complementary - SPEAKER
                                        // $speaker++;
                                        // if($registration_status == "PENDING"){
                                        //     $speakerPending++;
                                        // }
                                        // else if($registration_status == "APPROVED"){
                                        //     $speakerApproved++;
                                        // }
                                    }
                                }

                                // Complementary - SPEAKER
                                if(property_exists($fullDetails, "bio")){
                                    $speaker++;
                                    if($registration_status == "PENDING"){
                                        $speakerPending++;
                                    }
                                    else if($registration_status == "APPROVED"){
                                        $speakerApproved++;
                                    }
                                }

    
                                $totalRegistrations++;
                            }
    

                            
                            // General Participant Figures
                            $generalOverview=array(
                                "totalRegistrations"=>$totalRegistrations,
                                "pendingRegistrations"=>$pendingRegistrations,
                                "approvedRegistrations"=>$approvedRegistrations,
                                "rejectedRegistrations"=>$rejectedRegistrations,
                            );



                            // Figures By Category

                                // Calculating the percentages
                                $industryPercentage=($industry*100)/$totalRegistrations;
                                $industryPendingPercentage = ($industryPending*100)/$pendingRegistrations;
                                $industryApprovedPercentage = ($industryApproved*100)/$approvedRegistrations;

                                $startupPercentage=($startup*100)/$totalRegistrations;
                                $startupPendingPercentage = ($startupPending*100)/$pendingRegistrations;
                                $startupApprovedPercentage = ($startupApproved*100)/$approvedRegistrations;

                                $governmentPercentage=($government*100)/$totalRegistrations;
                                $governmentPendingPercentage = ($governmentPending*100)/$pendingRegistrations;
                                $governmentApprovedPercentage = ($governmentApproved*100)/$approvedRegistrations;

                                $mediaPercentage=($media*100)/$totalRegistrations;
                                $mediaPendingPercentage = ($mediaPending*100)/$pendingRegistrations;
                                $mediaApprovedPercentage = ($mediaApproved*100)/$approvedRegistrations;

                                $vipPercentage=($vip*100)/$totalRegistrations;
                                $vipPendingPercentage = ($vipPending*100)/$pendingRegistrations;
                                $vipApprovedPercentage = ($vipApproved*100)/$approvedRegistrations;

                                $cmpdPercentage=($cmpd*100)/$totalRegistrations;
                                $cmpdPendingPercentage = ($cmpdPending*100)/$pendingRegistrations;
                                $cmpdApprovedPercentage = ($cmpdApproved*100)/$approvedRegistrations;

                                $speakerPercentage=($speaker*100)/$totalRegistrations;
                                $speakerPendingPercentage = ($speakerPending*100)/$pendingRegistrations;
                                $speakerApprovedPercentage = ($speakerApproved*100)/$approvedRegistrations;
                                
                            $categoryOverview=array(
                                "industry"=>$industry,
                                "industryPercentage"=>number_format($industryPercentage, 2),

                                "startup"=>$startup,
                                "startupPercentage"=>number_format($startupPercentage, 2),

                                "government"=>$government,
                                "governmentPercentage"=>number_format($governmentPercentage, 2),

                                "media"=>$media,
                                "mediaPercentage"=>number_format($mediaPercentage, 2),

                                "vip"=>$vip,
                                "vipPercentage"=>number_format($vipPercentage, 2),

                                "cmpd"=>$cmpd,
                                "cmpdPercentage"=>number_format($cmpdPercentage, 2),

                                "speaker"=>$speaker,
                                "speakerPercentage"=>number_format($speakerPercentage, 2)
                            );

                            $categoryPending = array(
                                "industry"=>$industryPending,
                                "industryPercentage"=>number_format($industryPendingPercentage, 2),

                                "startup"=>$startupPending,
                                "startupPercentage"=>number_format($startupPendingPercentage, 2),

                                "government"=>$governmentPending,
                                "governmentPercentage"=>number_format($governmentPendingPercentage, 2),

                                "media"=>$mediaPending,
                                "mediaPercentage"=>number_format($mediaPendingPercentage, 2),

                                "vip"=>$vipPending,
                                "vipPercentage"=>number_format($vipPendingPercentage, 2),

                                "cmpd"=>$cmpdPending,
                                "cmpdPercentage"=>number_format($cmpdPendingPercentage, 2),

                                "speaker"=>$speakerPending,
                                "speakerPercentage"=>number_format($speakerPendingPercentage, 2)
                            );

                            $categoryApproved = array(
                                "industry"=>$industryApproved,
                                "industryPercentage"=>number_format($industryApprovedPercentage, 2),

                                "startup"=>$startupApproved,
                                "startupPercentage"=>number_format($startupApprovedPercentage, 2),

                                "government"=>$governmentApproved,
                                "governmentPercentage"=>number_format($governmentApprovedPercentage, 2),

                                "media"=>$mediaApproved,
                                "mediaPercentage"=>number_format($mediaApprovedPercentage, 2),

                                "vip"=>$vipApproved,
                                "vipPercentage"=>number_format($vipApprovedPercentage, 2),

                                "cmpd"=>$cmpdApproved,
                                "cmpdPercentage"=>number_format($cmpdApprovedPercentage, 2),

                                "speaker"=>$speakerApproved,
                                "speakerPercentage"=>number_format($speakerApprovedPercentage, 2)
                            );


                            // Building the data Object
                            $figuresData["general"]= $generalOverview;
                            $figuresData["categories"]= $categoryOverview;
                            $figuresData["categoriesPending"]= $categoryPending;
                            $figuresData["categoriesApproved"]= $categoryApproved;
    
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                            $response['data'] = $figuresData;
    
                        } else{
                            // No data was found
                            $response['status'] = "200";
                            $response['message'] = "Operation completed successfully";
                            $response['data'] = $figuresData;
                        }
    
    
    
                    } catch(Exception $e){
                        // An error occured while processing the request
                        $response['status'] = "500";
                        $response['message'] = "An error occured while processing the request";
                    }

                } else{
                    // Auth Token Not provided
                    $response['status'] = "403";
                    $response['message'] = "Unauthorized";


                }


            } else{
                // Method not allowed
                $response['status'] = "405";
                $response['message'] = "Method not allowed";
            }

            break;

            
            case "paymentdata":

                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
                    $requestHeaders = getallheaders();
                    $authToken = Httprequests::getRequestHeader($requestHeaders, 'Authorization');
    
                    if(!$authToken==""){
                        
                        $authToken = Httprequests::getBearerToken($authToken);
    
                        $event_id=Hash::decryptToken($authToken);
    
                        try{
    
                            $selection="*";
                            $condition="WHERE event_id='".$event_id."' AND (transaction_status!='IGNORED' AND transaction_status!='CANCELLED')";
                            $order="ORDER BY id DESC";
                            $limit="";
                            
                            $payments= json_decode(PaymentService::paymentTransactionsList($selection, $condition)); 
        
                            $completedAmount=0;
                            $totalCompleted=0;
                            $completedPercentage=0;

                            $pendingAmount=0;
                            $totalPending=0;
                            $pendingPercentage=0;

                            $refundedAmount=0;
                            $totalRefunded=0;
                            $refundedPercentage=0;
        
                            $totalIndustry=0;
                            $totalStartup=0;

                            $totalTransactions=0;
                            

                            $figuresData=array();
        
                            if(!empty($payments)){
        
                                foreach($payments as $payment){
        
                                    $transactionStatus=$payment->transaction_status;
        
                                    if($transactionStatus=="COMPLETED"){
                                        $completedAmount+=$payment->amount;
                                        $totalCompleted++;
                                    }
        
                                    else if($transactionStatus=="PENDING"){
                                        $pendingAmount+=$payment->amount;
                                        $totalPending++;
                                    }
        
                                    else if($transactionStatus=="REFUNDED"){
                                        $refundedAmount+=$payment->amount;
                                        $totalRefunded++;
                                    }

                                    if($payment->participation_type_id==36){
                                        $totalIndustry++;
                                    }

                                    if($payment->participation_type_id==37){
                                        $totalStartup++;
                                    }
        
                                    $totalTransactions++;
                                }
        

                                // Calculating the percentage
                                $completedPercentage=($totalCompleted*100)/$totalTransactions;
                                $pendingPercentage=($totalPending*100)/$totalTransactions;
                                $refundedPercentage=($totalRefunded*100)/$totalTransactions;


                                // Building the data Object
                                $figuresData=array(
                                    "completedAmount"=>$completedAmount,
                                    "totalCompleted"=>$totalCompleted,
                                    "completedPercentage"=>number_format($completedPercentage, 2),

                                    "pendingAmount"=>$pendingAmount,
                                    "totalPending"=>$totalPending,
                                    "pendingPercentage"=>number_format($pendingPercentage, 2),

                                    "refundedAmount"=>$refundedAmount,
                                    "totalRefunded"=>$totalRefunded,
                                    "refundedPercentage"=>number_format($refundedPercentage, 2),

                                    "totalIndustry"=>$totalIndustry,
                                    "totalStartup"=>$totalStartup
                                );
        
                                $response['status'] = "200";
                                $response['message'] = "Operation completed successfully";
                                $response['data'] = $figuresData;
        
                            } else{
                                // No data was found
                                $response['status'] = "200";
                                $response['message'] = "Operation completed successfully";
                                $response['data'] = $figuresData;
                            }
        
        
        
                        } catch(Exception $e){
                            // An error occured while processing the request
                            $response['status'] = "500";
                            $response['message'] = "An error occured while processing the request";
                        }
    
                    } else{
                        // Auth Token Not provided
                        $response['status'] = "403";
                        $response['message'] = "Unauthorized";
    
    
                    }
    
    
                } else{
                    // Method not allowed
                    $response['status'] = "405";
                    $response['message'] = "Method not allowed";
                }
    
                break;
        
        default:

            $response['status'] = BAD_REQUEST;
            $response['message'] = "Bad Request";
            
            break;
    }
}


echo json_encode($response);