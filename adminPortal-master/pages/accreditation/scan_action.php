<?php
require_once "../../core/init.php";

if (!$user->isLoggedIn()) {
	Redirect::to('login');
}

$response['success'] = array('success' => false, 'messages' => array(), 'data' => array());

$eventVenue = DB::getInstance()->query("SELECT `venue` FROM `future_event` WHERE `id` = $eventId");
$venue      = $eventVenue->first()->venue;

$session_name = "";

/** Load all participants table */
if (Input::checkInput('request', 'post', 1)) :
	$_post_request_ = Input::get('request', 'post');
	$_QR_CODE_ = Input::get('participantToken', 'post');
	$_QR_ID_   = FutureEventController::decodeQrString($_QR_CODE_, $eventId);

	// $_QR_ID_ = 'TRS042313005443';

	$responseData = array();
	$participantData = array();

	if (($_participants_data_ = FutureEventController::getParticipantByQrID($_QR_ID_))):
		$_ID_   = $_participants_data_->id;
		$participation_type_id = $_participants_data_->participation_type_id;
		$participation_sub_type_id = $_participants_data_->participation_sub_type_id;
		$participation_sub_type_name = $_participants_data_->participation_subtype_name;

		$responseData["name"] = html_entity_decode($_participants_data_->title.' '.$_participants_data_->firstname.' '.$_participants_data_->lastname);
		$responseData["organisation"] = html_entity_decode($_participants_data_->organisation_name);
		$responseData["country"] = countryCodeToCountry($_participants_data_->residence_country);
		$responseData["status"] = $_participants_data_->status;

		$responseData["pass"] = html_entity_decode($participation_sub_type_name);

		array_push($participantData, $responseData);

		$response['data'] = $responseData;

		$_USER_ID_ = $user->data()->id;
		$session_room = "";

		//SECURITY CHECK
		if($_USER_ID_ == 146 || $_USER_ID_ == 147 || $_USER_ID_ == 148 || $_USER_ID_ == 149 || $_USER_ID_ == 150 || $_USER_ID_ == 151 || $_USER_ID_ == 152 || $_USER_ID_ == 166) 
		{
			$_post_request_ = "checkIn";

		}

		// SESSION ATTENDANCE
		elseif ($_USER_ID_ == 160) {
			$_post_request_ = "sessionAttendance";
			$session_name = "CMPD"; 
			$session_room = "AD10";
		} elseif ($_USER_ID_ == 161) {
			$_post_request_ = "sessionAttendance";
			$session_name = "Careers Forum"; 
			$session_room = "MH4";
		} elseif ($_USER_ID_ == 162) {
			$_post_request_ = "sessionAttendance";
			$session_room = "MH1";
			$session_name = "FinTech Without Borders";
			$session_name = "Careers Forum";
		} elseif ($_USER_ID_ == 163) {
			$_post_request_ = "sessionAttendance";
			$session_room = "Auditorium";
			$session_name = "FinTech Without Borders";
		} elseif ($_USER_ID_ == 16) {
			$_post_request_ = "sessionAttendance";
		}

		//ISSUE BADGE
		else {
			$_post_request_ = "issueBadge";
		}

		$_post_request_ = "checkIn";
	
		switch ($_post_request_):
			case 'confirmPrinted':
				$_form_ = AccreditationController::updatePrintedBadgeStatus($_ID_);
			  if($_form_->ERRORS == false):
			      $response['success']  = true;
			 		$response['messages'] = "SUCCESS";   
			  else:
			      $response['success']  = false;
			      $response['messages'] = "Error {$_form_->ERRORS_STRING}";
			  endif; 

			// $_form_ = AccreditationController::updatePrintedBadgeStatus(1);
            // if($_form_->ERRORS == false):
            //     $response['success']  = true;
            //     $response['messages'] = "Successfully updated";   
            // 		$response['data'] = $participantData; 
            // else:
            //     $response['success']  = false;
            //     $response['messages'] = "Error {$_form_->ERRORS_STRING}";
            // endif;
            echo json_encode($response);
			break;
			
			/** Action - Issue badge */
			case 'issueBadge':
				$_POST = array('location' => $venue, 'Id' => Hash::encryptToken($_ID_));
				$_form_ = AccreditationController::updateIssueBadgeStatus(1);
			  if($_form_->ERRORS == false):
			      	$response['success']  = true;
			      	$response['messages'] = "ISSUED";
			  else:
			      	$response['success']  = false;
			      	$response['messages'] = "Error {$_form_->ERRORS_STRING}";
			  endif;
			  	echo json_encode($response);
			break;

			/** Action - Check in attendance */
			case 'checkIn':
				$_POST = array('eventId' => Hash::encryptAuthToken($eventId), 'location' => $venue, 'Id' => Hash::encryptToken($_ID_));
				$_form_ = AttendanceController::checkInAttendance();
			  	if($_form_->ERRORS == false):
			      	$response['success']  = true;
			      	$response['messages'] = "CHECKEDIN";    
			  	else:
			      	$response['success']  = false;
			      	$response['messages'] = "$_form_->ERRORS_STRING";
			  	endif;
			  	echo json_encode($response);
			break;

			/** Action - Check in attendance */
			case 'sessionAttendance':
				$session_name = "FinTech Without Borders";
				$session_room        = "Auditorium"; 
				$_POST = array(
					'eventId' => Hash::encryptAuthToken($eventId),
					'location' => $venue,
					'session_name' => $session_name,
					'session_room' => $session_room,
					'location' => $venue,
					'Id' => Hash::encryptToken($_ID_));
				AttendanceController::checkInSessionAttendance();
				$response['success']  = true;
		      	$response['messages'] = "ATTENDED"; 

			  	echo json_encode($response);
			break;

		endswitch;
	else:
		$response['success']  = false;
        $response['messages'] = "NOT FOUND OR REPORTED LOST";
        echo json_encode($response);
	endif;
endif;

?>