<?php 
    class FutureDataExport{

        public static function registrationCSVReport($data){

            if(!is_array($data)){
                return false;
            }

            $delegates=array();
            if (count($data)>0) {
                $counter = 0;
                foreach ($data as $participant) {
                  
                    $counter++;

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
                    $title=property_exists($fullDetails, "title")?$fullDetails->title:"";
                    $firstname=$participant->firstname;
                    $lastname=$participant->lastname;
                    $email=$participant->email;
                    $telephone=$participant->telephone;
                    $country=countryCodeToCountry($participant->residence_country);
                    $city=$participant->organisation_city;
                    $website=$participant->website;
                    $jobTitle=$participant->job_title;
                    $jobLevel=property_exists($fullDetails, "job_level1")?$fullDetails->job_level1:"";
                    $otherJobLevel="";
                    $idNumber = $participant->id_number;

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
                    $paymentStatus="N/A";
					$paymentData=FutureEventController::getEventParticipantPaymentDataByID($participant->id);

					if($paymentData){
						$paymentStatus=$paymentData->payment_transaction_status;
					}

                    if($participant->payment_state=="PAYABLE" && $participant->status=="APPROVED"){
						$paymentStatus="N/A";
					}
                    

                    $registrationStatus="COMPLETED";
                    $approvalStatus=$participant->status;
                    $isEarlyBird= ($participantSubType==95 || $participantSubType==97)?"YES":"NO";

                    // 6. Startup status
                    $isStartup=$participantCategory==37?"YES":"NO";

                    
                    // 7. Interests
                    $interestedInPreseedStartups="";
                    $interestedInSeedingStageStartups="";
                    $interestedInSeriesABC="";
                    $interestedInStartupsSeekingExit="";

                    $meetingOtherFintechs="";
                    $meetingInvestors="";
                    if(strtolower($organisationType)=="investor"){
                        $interestedInPreseedStartups= property_exists($fullDetails, "interested_in_preseed_startups")?$fullDetails->interested_in_preseed_startups:"";
                        $interestedInSeedingStageStartups= property_exists($fullDetails, "interested_in_seeding_stage_startups")?$fullDetails->interested_in_seeding_stage_startups:"";
                        $interestedInSeriesABC= property_exists($fullDetails, "interested_in_seriesABC_startups")?$fullDetails->interested_in_seriesABC_startups:"";
                        $interestedInStartupsSeekingExit= property_exists($fullDetails, "interested_in_startups_seeking_exit")?$fullDetails->interested_in_startups_seeking_exit:"";
                    
                    } else if(strtolower($organisationType)=="fintech"){
                        $meetingOtherFintechs= property_exists($fullDetails, "meeting_other_fintechs")?$fullDetails->meeting_other_fintechs:"";
                        $meetingInvestors= property_exists($fullDetails, "meeting_an_investor")?$fullDetails->meeting_an_investor:"";
                    }

                    // 8. Accreditation / Attendance
                    $issue_badge_status = $participant->issue_badge_status == 1 ? "YES" : "NO";
                    $checkAttendance = DB::getInstance()->query("SELECT COUNT(id) as total_count FROM `future_attendance` WHERE `participant_id` = '$participantID'");
                    if ($checkAttendance->first()->total_count > 0) {$attendStatus = "YES";} else {$attendStatus = "NO";}

                
                    /** Handle Sub Type */
                    $participation_sub_type_id = $participant->participation_sub_type_id;
                    $getEventParticipation = DB::getInstance()->get('future_participation_sub_type', array('id', '=', $participation_sub_type_id));
                    $eventParticipation = $getEventParticipation->first()->category;
                    $sub_type = $getEventParticipation->first()->name;
                
                    /** Handle Type */
                    $participation_type_id = $participant->participation_type_id;
                    $getEventParticipationType = DB::getInstance()->get('future_participation_type', array('id', '=', $participation_type_id));
                    $type = $getEventParticipationType->first()->name;
                
                    $delegates[] = array(
                        $counter,
                        // $participantCode,
                        // $type,
                        $sub_type,
                        $title,
                        iconv('UTF-8', 'windows-1252', html_entity_decode($firstname, ENT_QUOTES, 'UTF-8')),
                        iconv('UTF-8', 'windows-1252', html_entity_decode($lastname, ENT_QUOTES, 'UTF-8')),
                        $email,
                        $telephone,
                        $country,
                        // $city,
                        $jobTitle,
                        // $jobLevel,
                        $jobFunction,
                        $organisationName,
                        // $organisationType,
                        // $organisationSubType,
                        // $website,
                        // implode(",",$reasonsForVisit),
                        // $fintechStage,
                        // ($meetingInvestors=="YES"?"Meeting an investor, ":"")."".($meetingOtherFintechs=="YES"?"Meeting other FinTechs":""),
                        // ($interestedInPreseedStartups=="YES"?"Pre-seed startups, ":"")."".($interestedInSeedingStageStartups=="YES"?"Startups in seeding stage, ":"")."".($interestedInSeriesABC=="YES"?"Startups in early stage, ":"")."".($interestedInStartupsSeekingExit=="YES"?"Startups in late stage":""),
                        // implode(",",$otherEvents),
                        // $participant->privacy,
                        // $paymentStatus,
                        // $issue_badge_status,
                        // $attendStatus,
                        // $approvalStatus,
                        $idNumber,
                        $participant->reg_date
                        
                    );
                }
            }


            $filename = "registration_report_data_" . date('YmdHis') . ".csv";

            $output = fopen('php://output', 'w');
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            fputcsv($output, array(
                'No',
                // 'Registration number',
                // 'Category',
                'Pass Type',
                'Title',
                'First name',
                'Last name',
                'Email',
                'Telephone',
                'Country',
                // 'City',
                'Job title',
                // 'Job level',
                'Job function',
                'Organisation Name',
                // 'Organisation type',
                // 'Organisation sub type',
                // "Website",
                // 'Reasons for visit',
                // 'Startup stage',
                // 'Startup interests',
                // 'Investor interests',
                // 'Interested in other events',
                // 'Marketing consent',
                // 'Payment status',
                // 'Issue badge',
                // 'Attendance',
                // 'Approval status',
                'ID Number',
                'Registration date'
            ));

            if (count($delegates) > 0) {
                foreach ($delegates as $row) {
                    fputcsv($output, $row);
                }
            }

            $file_contents = stream_get_contents($output);
            return $file_contents;
            
        }




        public static function paymentCSVReport($data){

            if(!is_array($data)){
                return false;
            }

            $delegates=array();
            if (count($data)>0) {
                $counter = 0;
                foreach ($data as $payment_transaction_) {
                  
                    $counter++;

                    // Extracting values from the resultset

                    // 1. Contact Details
                      $transactionID=  $payment_transaction_->transaction_id;
                      $category= $payment_transaction_->participation_type_name;
                      $subCategory= $payment_transaction_->participation_subtype_name;
                      $participantName= $payment_transaction_->participant_firstname." ".$payment_transaction_->participant_lastname;
                      $jobTitle= $payment_transaction_->participant_job_title;
                      $organisationName= $payment_transaction_->participant_organization_name;
                      $amount= $payment_transaction_->transaction_amount;
                      $currency= $payment_transaction_->transaction_currency;
                      $paymentMethod= $payment_transaction_->payment_method;
                      $transactionStatus= $payment_transaction_->transaction_status;
                      $transactionDate= date('d/m/Y', $payment_transaction_->transaction_time);
                    

                
                    $delegates[] = array(
                        $counter,
                        $transactionID,
                        $category,
                        $subCategory,
                        $participantName,
                        $jobTitle,
                        $organisationName,
                        $amount,
                        $currency,
                        $paymentMethod,
                        $transactionStatus,
                        $transactionDate
                    );
                }
            }


            $filename = "payment_report_data_" . date('YmdHis') . ".csv";

            $output = fopen('php://output', 'w');
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            fputcsv($output, array(
                'No',
                'Transaction ID',
                'Category',
                'Sub category',
                'Participant name',    
                'Job title',
                'Organisation Name',
                "Amount",
                "Currency",
                'Payment method',
                'Transaction status',
                'Payment date'
            ));

            if (count($delegates) > 0) {
                foreach ($delegates as $row) {
                    fputcsv($output, $row);
                }
            }

            $file_contents = stream_get_contents($output);
            return $file_contents;

        }



        public static function attendanceCSVReport($data){

            if(!is_array($data)){
                return false;
            }

            $delegates=array();
            if (count($data)>0) {
                $counter = 0;
                foreach ($data as $participant) {
                  
                    $counter++;

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
                    $title=property_exists($fullDetails, "title")?$fullDetails->title:"";
                    $firstname=$participant->firstname;
                    $lastname=$participant->lastname;
                    $email=$participant->email;
                    $telephone=$participant->telephone;
                    $country=countryCodeToCountry($participant->residence_country);
                    $city=$participant->organisation_city;
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
                
                    /** Handle Sub Type */
                    $participation_sub_type_id = $participant->participation_sub_type_id;
                    $getEventParticipation = DB::getInstance()->get('future_participation_sub_type', array('id', '=', $participation_sub_type_id));
                    $eventParticipation = $getEventParticipation->first()->category;
                    $sub_type = $getEventParticipation->first()->name;
                
                    /** Handle Type */
                    $participation_type_id = $participant->participation_type_id;
                    $getEventParticipationType = DB::getInstance()->get('future_participation_type', array('id', '=', $participation_type_id));
                    $type = $getEventParticipationType->first()->name;
                
                    $delegates[] = array(
                        $counter,
                        // $participantCode,
                        $title,
                        iconv('UTF-8', 'windows-1252', html_entity_decode($firstname, ENT_QUOTES, 'UTF-8')),
                        iconv('UTF-8', 'windows-1252', html_entity_decode($lastname, ENT_QUOTES, 'UTF-8')),
                        $email,
                        $telephone,
                        $country,
                        // $city,
                        $jobTitle,
                        // $jobLevel,
                        // $jobFunction,
                        $organisationName,
                        // $organisationType,
                        $sub_type,
                        date("j F Y", strtotime($participant->added_date))
                        
                    );
                }
            }


            $filename = "attendance_report_data_" . date('YmdHis') . ".csv";

            $output = fopen('php://output', 'w');
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            fputcsv($output, array(
                'No',
                // 'Registration number',
                'Title',
                'First name',
                'Last name',
                'Email',
                'Telephone',
                'Country',
                // 'City',
                'Job title',
                // 'Job level',
                // 'Job function',
                'Organisation Name',
                // 'Organisation type',
                'Category',
                'Attendance date'
            ));

            if (count($delegates) > 0) {
                foreach ($delegates as $row) {
                    fputcsv($output, $row);
                }
            }

            $file_contents = stream_get_contents($output);
            return $file_contents;
            
        }


       
    }
?>