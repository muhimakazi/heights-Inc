<?php
class FutureEventController

{
	public static function createEvent()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'event-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$controller = new \Controller();

			$str = new \Str();

			$event_code = $str->data_in($_EDIT['event_code']);
			$event_name = $str->data_in($_EDIT['event_name']);
			$event_type = $str->data_in($_EDIT['event_type']);
			$client = $str->data_in($_EDIT['client']);
			$event_venue = $str->data_in($_EDIT['event_venue']);
			$ticket_type = $str->data_in($_EDIT['ticket_type']);
			$start_date = $str->data_in($_EDIT['start_date']);
			$end_date = $str->data_in($_EDIT['end_date']);
			$primary_color = $str->data_in($_EDIT['primary_color']);
			$secondary_color = $str->data_in($_EDIT['secondary_color']);
			$response['primaryColor'] = $primary_color;
			$response['secondaryColor'] = $secondary_color;
			$general_style = json_encode($response);

			$created_by = Session::get(Config::get('sessions/session_name'));

			$_ID = self::getLastID('future_event') + 1;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
	                'event_code'    => $event_code,
	                'event_name'    => $event_name,
	                'event_type'    => $event_type,
	                'client_id'     => $client,
	                'venue'         => $event_venue,
	                'ticket_type'   => $ticket_type,
	                'start_date'    => $start_date,
	                'end_date'      => $end_date,
	                'general_style' => $general_style,
	                'status'        => "ACTIVE",
	                'banner'        => "",
	                'creation_date' => date('Y-m-d')
	            );

				try {
					$controller->create("future_event", $_fields);

					// UPDATING LOGS
					$operation = 'NEW EVENT';
					$comment = "EVENT ID: ".$_ID;
					Logs::newLog($operation, $comment, $created_by);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e;
				}
			}
		} else {
			foreach ($validate->errors() as $error) {
                $diagnoArray[] = $error . "<br>";
            }
            $diagnoArray[0] = 'ERRORS_FOUND';
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => json_encode($diagnoArray),
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "Event successfully created",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function updateEvent()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'event-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$controller = new \Controller();

			$str = new \Str();

			$_ID = Hash::decryptToken($str->data_in($_EDIT['eventId']));
			$event_code = $str->data_in($_EDIT['event_code']);
			$event_name = $str->data_in($_EDIT['event_name']);
			$event_type = $str->data_in($_EDIT['event_type']);
			$client = $str->data_in($_EDIT['client']);
			$event_venue = $str->data_in($_EDIT['event_venue']);
			$ticket_type = $str->data_in($_EDIT['ticket_type']);
			$start_date = $str->data_in($_EDIT['start_date']);
			$end_date = $str->data_in($_EDIT['end_date']);
			$primary_color = $str->data_in($_EDIT['primary_color']);
			$secondary_color = $str->data_in($_EDIT['secondary_color']);
			$style['primaryColor'] = $primary_color;
			$style['secondaryColor'] = $secondary_color;
			$styles = json_encode($style);

			$eventDetails = self::getEventDetailsByID($_ID);
			$general_style = html_entity_decode($eventDetails->general_style);
			if(json_decode($general_style) != null){
		        $styles = Functions::json_overwrite($general_style, $styles);
		    }


			$created_by = Session::get(Config::get('sessions/session_name'));

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
	                'event_code'    => $event_code,
	                'event_name'    => $event_name,
	                'event_type'    => $event_type,
	                'client_id'     => $client,
	                'venue'         => $event_venue,
	                'ticket_type'   => $ticket_type,
	                'start_date'    => $start_date,
	                'end_date'      => $end_date,
	                'general_style' => $styles
	            );

				try {
					$controller->update("future_event", $_fields, $_ID);

					// UPDATING LOGS
					$operation = 'UPDATE_EVENT';
					$comment = "EVENT ID: ".$_ID;
					Logs::newLog($operation, $comment, $created_by);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e;
				}
			}
		} else {
			foreach ($validate->errors() as $error) {
                $diagnoArray[] = $error . "<br>";
            }
            $diagnoArray[0] = 'ERRORS_FOUND';
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => json_encode($diagnoArray),
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'SUCCESS_STRING' => "Event successfully updated",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function getEvents($_filter_condition_, $order = "")
	{
		$FutureEventTable = new \FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_event.*, future_client.organisation as organisation FROM future_event INNER JOIN future_client ON future_event.client_id = future_client.id WHERE future_event.status != 'DELETED'  $_filter_condition_ $order");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getEventsCount($_filter_condition_ = "")
	{
		$FutureEventTable = new \FutureAccount();
		$FutureEventTable->selectQuery("SELECT COUNT(future_event.id) as total_count FROM future_event INNER JOIN future_client ON future_event.client_id = future_client.id WHERE future_event.status != 'DELETED' $_filter_condition_ ORDER BY future_event.id DESC");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->total_count;
		return false;
	}

	public static function getEventDetailsByID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_event WHERE id = {$eventID}");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function checkIfEventHasEnded($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id, end_date FROM future_event  WHERE id = {$eventID} ORDER BY  id DESC LIMIT 1");
		if ($FutureEventTable->count()) {
			$end_date = dateFormat($FutureEventTable->first()->end_date);
			if ($end_date." 23:59:00" < date("Y-m-d H:i:s"))
				return true;
		}
		return false;
	}

	public static function registerEventParticipant($_REGISTRATION_STATE_ = 'PUBLIC')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = ' ';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		$_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();


			/**eventParticipation */
			$_EvPCode_ = !Input::checkInput('_EvPCode_', 'post', 1) ? '' : $str->data_in($_EDIT['_EvPCode_']);
			$eventParticipationEncrypted = $str->data_in($_EDIT['eventParticipation']);
			$eventParticipationSubTypeID = Hash::decryptToken($eventParticipationEncrypted);

			if ($eventParticipationSubTypeID != 0):
				/** Get Participation Type And Sub Type Event Details */
				$_participation_sub_type_data_ = self::getPacipationSubCategoryByID($eventParticipationSubTypeID);
				if (!$_participation_sub_type_data_):
					return (object)[
						'ERRORS' => true,
						'ERRORS_SCRIPT' => "Invalid data",
						'ERRORS_STRING' => "Invalid data"
					];
				endif;

				$eventParticipationTypeID = $_participation_sub_type_data_->id;
				$_PARTICIPATION_PAYMENT_TYPE_ = $_participation_sub_type_data_->sub_type_payment_state;
			else:
				$eventParticipationTypeID = 0;
				$_EvPCode_ = "TEMP01";
			endif;

			/** Event */
			$eventID = $str->data_in(Hash::decryptToken($_EDIT['eventId']));

			// TEMPLATE FORM
			$formID = !Input::checkInput('formToken', 'post', 1) ? 0 : $str->data_in(Hash::decryptToken($_EDIT['formToken']));

			/** PRIVATE REGISTRATION */
			$_REGISTRATION_PRIVATE_ACCESS_TOKEN_ = NULL;
			$_private_link_ID = NULL;
			if ($_REGISTRATION_STATE_ == 'PRIVATE') :
				$_REGISTRATION_PRIVATE_ACCESS_TOKEN_ = $str->data_in($_EDIT['eventInvitation']);
				$_private_link_ID = Hash::decryptAuthToken($_REGISTRATION_PRIVATE_ACCESS_TOKEN_);

				/** Check If Private Link Exists And still valid */
				if (!self::checkValidityEventPrivateInvitationLink($_private_link_ID)) :
					return (object)[
						'ERRORS' => true,
						'ERRORS_SCRIPT' => "Your invitation token is no longer valid",
						'ERRORS_STRING' => "Your invitation token is no longer valid -- " . $_private_link_ID
					];
				endif;

				$_private_link_data_ = self::getEventPrivateInvitationLinkDataByID($_private_link_ID);

				/** Compare Private Data With Form Data */
				if (
					$_private_link_data_->event_ID != $eventID ||
					$_private_link_data_->participation_type_ID != $eventParticipationTypeID ||
					$_private_link_data_->participation_sub_type_ID != $eventParticipationSubTypeID
				) :
					return (object)[
						'ERRORS' => true,
						'ERRORS_SCRIPT' => "Invalid data",
						'ERRORS_STRING' => "Invalid data"
					];
				endif;


			endif;

			/** Contact Information */
			$firstname = $str->sanAsName($_EDIT['firstname']);
			$lastname = $str->sanAsName($_EDIT['lastname']);
			$email = $str->data_in($_EDIT['email']);

			$title = !Input::checkInput('title', 'post', 1) ? '' : $str->data_in($_EDIT['title']);

			$telephone = !Input::checkInput('telephone', 'post', 1) ? '' : $str->data_in($_EDIT['telephone']);
			$telephone_2 = !Input::checkInput('full_telephone_2', 'post', 1) ? '' : $str->data_in($_EDIT['full_telephone_2']);

			$gender = !Input::checkInput('gender', 'post', 1) ? '' : $str->data_in($_EDIT['gender']);
			$birthday = !Input::checkInput('birthday', 'post', 1) ? '' : $str->data_in($_EDIT['birthday']);

			$job_title = !Input::checkInput('job_title', 'post', 1) ? '' : $str->data_in($_EDIT['job_title']);
			$job_category = !Input::checkInput('job_category', 'post', 1) ? '' : $str->data_in($_EDIT['job_category']);
			$language = !Input::checkInput('language', 'post', 1) ? '' : $str->data_in($_EDIT['language']);

			/** Participant Password */
			$password = !Input::checkInput('password', 'post', 1) ? '' : $str->data_in($_EDIT['password']);
			$confirm_password = !Input::checkInput('confirm_password', 'post', 1) ? '' : $str->data_in($_EDIT['confirm_password']);

			/** Attending Objective Information */
			$firt_objective = !Input::checkInput('firt_objective', 'post', 1) ? '' : $str->data_in($_EDIT['firt_objective']);
			$second_objective = !Input::checkInput('second_objective', 'post', 1) ? '' : $str->data_in($_EDIT['second_objective']);
			$third_objective = !Input::checkInput('third_objective', 'post', 1) ? '' : $str->data_in($_EDIT['third_objective']);

			/** Source Information */
			$info_source = !Input::checkInput('info_source', 'post', 1) ? '' : $str->data_in($_EDIT['info_source']);

			/** Organization Information */
			$organisation_name = !Input::checkInput('organisation_name', 'post', 1) ? '' : $str->data_in($_EDIT['organisation_name']);
			$organisation_type = !Input::checkInput('organisation_type', 'post', 1) ? '' : $str->data_in($_EDIT['organisation_type']);
			$industry = !Input::checkInput('industry', 'post', 1) ? '' : $str->data_in($_EDIT['industry']);

			$organisation_address = !Input::checkInput('organisation_address', 'post', 1) ? '' : $str->data_in($_EDIT['organisation_address']);
			$line_one = !Input::checkInput('line_one', 'post', 1) ? '' : $str->data_in($_EDIT['line_one']);
			$line_two = !Input::checkInput('line_two', 'post', 1) ? '' : $str->data_in($_EDIT['line_two']);
			$organisation_country = !Input::checkInput('organisation_country', 'post', 1) ? '' : $str->data_in($_EDIT['organisation_country']);
			$organisation_city = !Input::checkInput('organisation_city', 'post', 1) ? '' : $str->data_in($_EDIT['organisation_city']);

			$postal_code = !Input::checkInput('postal_code', 'post', 1) ? '' : $str->data_in($_EDIT['postal_code']);
			$website = !Input::checkInput('website', 'post', 1) ? '' : $str->data_in($_EDIT['website']);

			/** Identification - When In Person Event */
			$residence_country = !Input::checkInput('residence_country', 'post', 1) ? '' : $str->data_in($_EDIT['residence_country']);
			$residence_city = !Input::checkInput('residence_city', 'post', 1) ? '' : $str->data_in($_EDIT['residence_city']);
			$citizenship = !Input::checkInput('citizenship', 'post', 1) ? '' : $str->data_in($_EDIT['citizenship']);
			$id_type = !Input::checkInput('id_type', 'post', 1) ? '' : $str->data_in($_EDIT['id_type']);
			$id_number = !Input::checkInput('id_number', 'post', 1) ? '' : $str->data_in($_EDIT['id_number']);

			/** Media Information */
			$media_card_number = !Input::checkInput('media_card_number', 'post', 1) ? '' : $str->data_in($_EDIT['media_card_number']);
			$media_card_authority = !Input::checkInput('media_card_authority', 'post', 1) ? '' : $str->data_in($_EDIT['media_card_authority']);
			$media_equipment = !Input::checkInput('media_equipment', 'post', 1) ? '' : $str->data_in($_EDIT['media_equipment']);
			$special_request = !Input::checkInput('special_request', 'post', 1) ? '' : $str->data_in($_EDIT['special_request']);
			$delegate_type = !Input::checkInput('delegate_type', 'post', 1) ? '' : $str->data_in($_EDIT['delegate_type']);

			/** Media Information */
			$educacation_institute_name = !Input::checkInput('institute_name', 'post', 1) ? '' : $str->data_in($_EDIT['institute_name']);
			$educacation_institute_category = !Input::checkInput('institute_category', 'post', 1) ? '' : $str->data_in($_EDIT['institute_category']);
			$educacation_institute_industry = !Input::checkInput('institute_industry', 'post', 1) ? '' : $str->data_in($_EDIT['institute_industry']);
			$educacation_institute_website = !Input::checkInput('institute_website', 'post', 1) ? '' : $str->data_in($_EDIT['institute_website']);
			$educacation_institute_country = !Input::checkInput('institute_country', 'post', 1) ? '' : $str->data_in($_EDIT['institute_country']);
			$educacation_institute_city = !Input::checkInput('institute_city', 'post', 1) ? '' : $str->data_in($_EDIT['institute_city']);

			$privacy = !Input::checkInput('privacy', 'post', 1) ? '' : $str->data_in($_EDIT['privacy']);

			/** Student State - When An Youth Or Student regsiters - */
			$student_state = 0;
			if ($educacation_institute_name != '' && $educacation_institute_category != '')
				$student_state = 1;

			/** Upload The ID Document Picture */
			$id_document_picture = '';
			if (isset($_FILES['id_document_picture']))
				if ($_FILES['id_document_picture']['name'] != "")
					$id_document_picture = Functions::fileUpload(DN_IMG_ID_DOC, $_FILES['id_document_picture']);


			/** Upload The ID Document Picture */
			$profile = '';
			if (isset($_FILES['image']))
				if ($_FILES['image']['name'] != "")
					$profile = Functions::fileUpload(DN_IMG_PROFILE, $_FILES['image']);

			/** Upload The vaccination Picture */
			$vaccination_picture = '';
			if (isset($_FILES['vaccination_picture']))
				if ($_FILES['vaccination_picture']['name'] != "")
					$profile = Functions::fileUpload(DN_IMG_VACCINATION, $_FILES['vaccination_picture']);

			/** Check If Email Address not yet used */
			if ($_EvPCode_ != "C006"):
				if (self::checkEmailAlreadyUsed($eventID, $email)) :
					return (object)[
						'ERRORS' => true,
						'ERRORS_SCRIPT' => "This email address has already been used!",
						'ERRORS_STRING' => "This email address has already been used!"
					];
				endif;
			endif;

			/** Check If Password Match */
			// if (strlen($password) < 6 || strlen($confirm_password) < 6) :
			// 	return (object)[
			// 		'ERRORS' => true,
			// 		'ERRORS_SCRIPT' => "Password must have at least 6 characters",
			// 		'ERRORS_STRING' => "Password must have at least 6 characters"
			// 	];
			// endif;

			// if ($password != $confirm_password) :
			// 	return (object)[
			// 		'ERRORS' => true,
			// 		'ERRORS_SCRIPT' => "password don't match",
			// 		'ERRORS_STRING' => "password don't match"
			// 	];
			// endif;

			/** Need Accommodation */
			$needAccommodation = !Input::checkInput('needAccommodation', 'post', 1) ? 0 : $str->data_in($_EDIT['needAccommodation']);
			$hotelId = !Input::checkInput('hotelId', 'post', 1) ? 0 : $str->data_in(Hash::decryptToken($_EDIT['hotelId']));
			$roomId = !Input::checkInput('roomId', 'post', 1) ? 0 : $str->data_in(Hash::decryptToken($_EDIT['roomId']));

			// ADDITIONAL DETAILS
			$participant_data_string = !Input::checkInput('full_details', 'post', 1) ? '' : $str->data_in($_EDIT['full_details']);

			/** Select - Other Option - Specify */
			if ($job_category == 'Other')
				$job_category = !Input::checkInput('job_category1', 'post', 1) ? '' : $str->data_in($_EDIT['job_category1']);
			if ($organisation_type == 'Other')
				$organisation_type = !Input::checkInput('organisation_type1', 'post', 1) ? '' : $str->data_in($_EDIT['organisation_type1']);

			// ADDITIONAL GUEST
			$guest_title = !Input::checkInput('guest_title', 'post', 1) ? '' : $str->data_in($_EDIT['guest_title']);
			$guest_firstname = !Input::checkInput('guest_firstname', 'post', 1) ? '' : $str->data_in($_EDIT['guest_firstname']);
			$guest_lastname = !Input::checkInput('guest_lastname', 'post', 1) ? '' : $str->data_in($_EDIT['guest_lastname']);
			$guest_email = !Input::checkInput('guest_email', 'post', 1) ? '' : $str->data_in($_EDIT['guest_email']);
			$guest_telephone = !Input::checkInput('guest_telephone', 'post', 1) ? '' : $str->data_in($_EDIT['guest_telephone']);
			$guest_organisation_name = !Input::checkInput('guest_organisation_name', 'post', 1) ? '' : $str->data_in($_EDIT['guest_organisation_name']);
			$guest_residence_country = !Input::checkInput('guest_residence_country', 'post', 1) ? '' : $str->data_in($_EDIT['guest_residence_country']);
			$additional_guest = !Input::checkInput('additional_guest', 'post', 1) ? '' : $str->data_in($_EDIT['additional_guest']);


			/** Check Age - [ 10 - ] */
			if ($diagnoArray[0] == 'NO_ERRORS') {

				/** Auto Generate QR For Participant */
				$participantID = self::getLastPacipatantID() + 1;
				$Qr_ = FutureEventController::generateQrID($eventID, $participantID);
				$participant_code = $Qr_->ID;

				$_fields = array(
					'event_id' => $eventID,
					'participant_code' => $participant_code,
					'form_id' => $formID,
					'participation_type_id' => $eventParticipationTypeID,
					'participation_sub_type_id' => $eventParticipationSubTypeID,
					'private_link_id' => $_private_link_ID,

					'hotel_id' => $hotelId,
					'room_id' => $roomId,

					'title' => $title,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'email' => $email,
					'password' => md5($password),
					'salt' => "",

					'telephone' => $telephone,
					'telephone_2' => $telephone_2,

					'gender' => $gender,
					'birthday' => $birthday,
					'organisation_name' => $organisation_name,
					'organisation_type' => $organisation_type,
					'industry' => $industry,
					'job_title' => $job_title,
					'job_category' => $job_category,
					'organisation_address' => $organisation_address,
					'line_one' => $line_one,
					'line_two' => $line_two,
					'organisation_country' => $organisation_country,
					'organisation_city' => $organisation_city,

					'postal_code' => $postal_code,
					'website' => $website,

					'residence_country' => $residence_country,
					'residence_city' => $residence_city,
					'citizenship' => $citizenship,
					'id_type' => $id_type,
					'id_number' => $id_number,
					'id_document_picture' => $id_document_picture,

					'media_card_number' => $media_card_number,
					'media_card_authority' => $media_card_authority,
					'media_equipment' => $media_equipment,
					'special_request' => $special_request,
					'delegate_type' => "",

					'reg_date' => date('Y-m-d H:i:s'),
					'status' => "PENDING",

					'student_state' => $student_state,
					'educacation_institute_name' => $educacation_institute_name,
					'educacation_institute_category' => $educacation_institute_category,
					'educacation_institute_industry' => $educacation_institute_industry,
					'educacation_institute_website' => $educacation_institute_website,
					'educacation_institute_country' => $educacation_institute_country,
					'educacation_institute_city' => $educacation_institute_city,

					'attending_objective_1' => $firt_objective,
					'attending_objective_2' => $second_objective,
					'attending_objective_3' => $third_objective,
					'info_source' => $info_source,

					'profile' => $profile,
					'vaccination_picture' => $vaccination_picture,

					'qrID' => $Qr_->ID,
					'qrCode' => $Qr_->STRING,

					'need_accommodation_state' => $needAccommodation,
					'privacy' => $privacy,
					'full_details' => $participant_data_string,
				);


				try {
					$FutureEventParticipantTable->insertParticipant($_fields);
					/** Get Last Participant ID  */
					$_PID_ = self::getLastPacipatantID();
					/** Generate Auth Token */
					$_AUTH_TOKEN = Hash::encryptAuthToken($_PID_);

					/** Update Private Link Data After Registration */
					if ($_REGISTRATION_STATE_ == 'PRIVATE' && $_private_link_ID != '') :
						$_data_fields_ = array(
							'link_used_time' => time(),
							'link_used_status' => 1,
							'status' => 'USED'
						);
						self::updatePrivateLinkData($_data_fields_, $_private_link_ID);
					endif;

					$_data_ = array(
    					'email' => $email,
    					'firstname' => $firstname.' '.$lastname,
    				);

					/** Send Email To Participant */
				    if($eventID == 23):
        				if($eventParticipationSubTypeID == 154) # Delegate 
                            EmailControllerWorldBank::sendEmailToParticipantAfterSuccessfulRegistration($_data_);
                        else # Media Application 
                            EmailControllerWorldBank::sendEmailToParticipantAfterSuccessfulMediaRegistration($_data_);
                    elseif ($eventID == 24):
                    	$_data_fields_ = array(
							'event_id' => $eventID,
							'participant_id' => $_PID_,
							'hotel_id' => $hotelId,
							'room_id' => $roomId
						);
						$controller = new \Controller();
						$controller->create("hotel_booking_transaction", $_data_fields_);
        			elseif ($eventID == 28):
        				EmailControllerRwandaGov::sendEmailToParticipantAfterSuccessfulRegistration($_data_);
                    elseif ($eventID == 29):
                    	EmailControllerBAL::sendEmailToParticipantAfterSuccessfulRegistration($_data_);
        				if ($additional_guest == 'YES'):
        					/** Auto Generate QR For Participant */
							$participantID = self::getLastPacipatantID() + 1;
							$Qr_ = FutureEventController::generateQrID($eventID, $participantID);
							$participant_code = $Qr_->ID;

        					$_fields = array(
        						'event_id' => $eventID,
        						'participant_code' => $participant_code,
        						'participation_type_id' => 65,
        						'participation_sub_type_id' => 171,
        						'firstname' => $guest_firstname,
        						'lastname' => $guest_lastname,
        						'email' => $guest_email,
        						'telephone' => $guest_telephone,
        						'organisation_name' => $guest_organisation_name,
        						'residence_country' => $guest_residence_country,
        						'emergency_contact_firstname' => $firstname .' '.$lastname,
        						'status' => 'PENDING',
        						'invitation_status' => 'GUEST',
        						'qrID' => $Qr_->ID,
								'qrCode' => $Qr_->STRING,
        						'reg_date' => date('Y-m-d H:i:s')
        					);
        					$FutureEventParticipantTable->insertParticipant($_fields);

        					$_data_ = array(
	        					'email' => $guest_email,
	        					'firstname' => $guest_firstname.' '.$guest_lastname,
	        				);
	        				EmailControllerBAL::sendEmailToParticipantAfterSuccessfulRegistration($_data_);
        				endif;
	                endif;



                    // TEMPLATE EMAIL
                    if ($formID > 0):
                    	$eventDetails = self::getEventDetailsByID($eventID);
                    	$formDetails = FormController::getFormDetails($formID);
                    	$message = htmlspecialchars_decode(stripslashes($formDetails->registration_email_message));
						$w1 = '$firstname';
						$w2 = $title.' '.$firstname;
						$message = str_replace($w1, $w2, $message);
						$general_style = $eventDetails->general_style;
						$logo = $eventDetails->logo;
						$styleArray = json_decode($general_style, true);
						$style = (object)$styleArray;

                    	$_data_ = array(
					        'email' => $email,
        					'firstname' => $title.' '.$firstname,
					        'message' => $message,
					        'subject' => $formDetails->registration_email_subject,
					        'from' => 'registration@torusguru.com',
					        'namefrom' => $eventDetails->event_name,
					        'attachement' => '',
					        'webUrl' => $eventDetails->url_registration,
					        'adminUrl' =>Config::get('server/name'),
					        'logo' => $eventDetails->logo,
					        'primaryColor' => $style->primaryColor,
					    );

                    	EmailController::templateEmail($_data_);
                    endif;
                    	
                    
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'AUTHTOKEN' => $_AUTH_TOKEN,
				// 'PARTICIPATIONPAYMENTTYPE' => $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function updateEventParticipantProfile($_REGISTRATION_STATE_ = 'PUBLIC')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = ' ';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		$_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Session User Data By Token */
			$_user_ID_ = Hash::decryptToken($str->data_in($_EDIT['userToken']));

			if (!is_integer($_user_ID_)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];
			endif;

			$_PID_ = $_user_ID_;

			if(!($_PARTICIPANT_DATA_ = self::getParticipantByID($_PID_))):
    			return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];
			endif;

			$title = !Input::checkInput('title', 'post', 1) ? '' : $str->data_in($_EDIT['title']);
			$firstname = !Input::checkInput('firstname', 'post', 1) ? '' : $str->data_in($_EDIT['firstname']);
			$lastname = !Input::checkInput('lastname', 'post', 1) ? '' : $str->data_in($_EDIT['lastname']);
			$email = !Input::checkInput('email', 'post', 1) ? '' : $str->data_in($_EDIT['email']);
			$telephone = !Input::checkInput('telephone', 'post', 1) ? '' : $str->data_in($_EDIT['telephone']);
			$organisation_name = !Input::checkInput('organisation_name', 'post', 1) ? '' : $str->data_in($_EDIT['organisation_name']);
			$job_title = !Input::checkInput('job_title', 'post', 1) ? '' : $str->data_in($_EDIT['job_title']);
			$job_category = !Input::checkInput('job_category', 'post', 1) ? '' : $str->data_in($_EDIT['job_category']);
			$residence_country = !Input::checkInput('residence_country', 'post', 1) ? '' : $str->data_in($_EDIT['residence_country']);
			$id_type = !Input::checkInput('id_type', 'post', 1) ? '' : $str->data_in($_EDIT['id_type']);
			$id_number = !Input::checkInput('id_number', 'post', 1) ? '' : $str->data_in($_EDIT['id_number']);
			$status = !Input::checkInput('status', 'post', 1) ? '' : $str->data_in($_EDIT['status']);
			$invitation_status = !Input::checkInput('invitation_status', 'post', 1) ? '' : $str->data_in($_EDIT['invitation_status']);

			$guest_firstname = !Input::checkInput('guest_firstname', 'post', 1) ? '' : $str->data_in($_EDIT['guest_firstname']);
			$guest_lastname = !Input::checkInput('guest_lastname', 'post', 1) ? '' : $str->data_in($_EDIT['guest_lastname']);
			$guest_telephone = !Input::checkInput('guest_telephone', 'post', 1) ? '' : $str->data_in($_EDIT['guest_telephone']);
			$additional_guest = !Input::checkInput('additional_guest', 'post', 1) ? '' : $str->data_in($_EDIT['additional_guest']);

			$participant_data_string = !Input::checkInput('full_details', 'post', 1) ? '' : $str->data_in($_EDIT['full_details']);
			$participant_data_string =  html_entity_decode($participant_data_string);

			$eventID = $_PARTICIPANT_DATA_->event_id;
			$fullDetails = html_entity_decode($_PARTICIPANT_DATA_->full_details);
			if(json_decode($fullDetails) != null){
		        $participant_data_string = Functions::json_overwrite($fullDetails, $participant_data_string);
		    }

			if ($_PARTICIPANT_DATA_->status == 'APPROVED' AND $eventID == 27):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Link has expired",
					'ERRORS_STRING' => "Link ha expired"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array('full_details' => $participant_data_string);
				!$title?'':$_fields['title'] = $title;
				!$firstname?'':$_fields['firstname'] = $firstname;
				!$lastname?'':$_fields['lastname'] = $lastname;
				!$email?'':$_fields['email'] = $email;
				!$telephone?'':$_fields['telephone'] = $telephone;
				!$organisation_name?'':$_fields['organisation_name'] = $organisation_name;
				!$job_title?'':$_fields['job_title'] = $job_title;
				!$job_category?'':$_fields['job_category'] = $job_category;
				!$residence_country?'':$_fields['residence_country'] = $residence_country;
				!$id_type?'':$_fields['id_type'] = $id_type;
				!$id_number?'':$_fields['id_number'] = $id_number;
				!$status?'':$_fields['status'] = $status;
				!$invitation_status?'':$_fields['invitation_status'] = $invitation_status;

				try {
					$FutureEventParticipantTable->updateParticipant($_fields, $_PID_);

					if ($eventID == 27 AND $firstname != '' AND $email != ''):
        				if ($additional_guest == 'YES'):
        					$_fields = array(
        						'event_id' => $eventID,
        						'participation_type_id' => 63,
        						'participation_sub_type_id' => 166,
        						'firstname' => $guest_firstname,
        						'lastname' => $guest_lastname,
        						'telephone' => $guest_telephone,
        						'organisation_name' => $firstname .' '.$lastname,
        						'status' => 'APPROVED',
        						'invitation_status' => 'GUEST',
        						'reg_date' => date('Y-m-d H:i:s')
        					);
        					$FutureEventParticipantTable->insertParticipant($_fields);
        				endif;

        				$_data_ = array(
        					'email' => $email,
        					'firstname' => $firstname
        				);
        				EmailControllerOTP::sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApproval($_data_);
                    endif;
				}
				catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		}
		else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		}
		else {

			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function approveParticipantRegistrationStatus($participantID, $transaction_status = 'APPROVED')
	{
		$ParticipantTable = new \FutureEvent();
		$fields = array(
			'status' => $transaction_status
		);
		$ParticipantTable->updateParticipant($fields, $participantID);
	}


	// UPDATE PROMO CODE
	public static function updateParticipantCategory()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));
			$type = Hash::decryptToken($str->data_in($_EDIT['type']));
			$subtype = !Input::checkInput('subtype', 'post', 1) ? '' : Hash::decryptToken($str->data_in($_EDIT['subtype']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Get Participant Data BY ID */
			if (!($_participant_data_ = FutureEventController::getParticipantByID($_ID_))) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;


			if ($diagnoArray[0] == 'NO_ERRORS') {
				$created_by = Session::get(Config::get('sessions/session_name'));

				if($type == 39) {
					$_fields = array('participation_type_id' => $type, 'participation_sub_type_id' => $subtype, 'invitation_status' => 'ACCEPTED');
				} else {
					$_fields = array('participation_type_id' => $type, 'participation_sub_type_id' => $subtype, 'invitation_status' => 'N/A');
				}

				try {
					$FutureEventParticipantTable->updateParticipant($_fields, $_ID_);

					// UPDATING LOGS
					$operation = 'UPDATE-PARTICIPANT CATEGORY';
					$comment = "DELEGATE ID: ".$_ID_." and Category ID: ".$subtype;
					Logs::newLog($operation, $comment, $created_by);

					if ($type == 39) {
						/** Get participant data  By ID */
						$_data_ = array(
							'email' => $_participant_data_->email,
							'firstname' => $_participant_data_->firstname,
							'participant_code' => $_participant_data_->participant_code,
							'cmpd_invite_link' => "https://iff.torusguru.com/cmpd/".Hash::encryptAuthToken($_ID_)."/".Hash::encryptAuthToken($subtype),
						);

						EmailController::sendEmailToParticipantAfterCategoryUpdatedCMPD($_data_);
					}

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function changeStatusParticipantRegistration($status = 'APPROVED')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participant-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Get Participant Data BY ID */
			if (!($_participant_data_ = FutureEventController::getParticipantByID($_ID_))) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Local CBO Code  */
			$_CBO_CODE_ = 'C0015';
			$_RETURNED_MESSAGE_ = "Successfully, participant's registration approved";

			/** Bool IS_CBO? */
			$IS_CBO = false;

			/** If Event ID == 8 And Participant Type Code == CBOCode */
			if ($_participant_data_->event_id == 8 && $_participant_data_->participation_type_code == $_CBO_CODE_) :
				$IS_CBO = true;
				if ($status == 'APPROVED') :
					$status = 'ACCEPTED';
					$_RETURNED_MESSAGE_ = "The Local CBO's application has been accepted and the payment link was sent to his/her email.";
				endif;
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'status' => $status,
					'approved_by' => $created_by
				);

				try {
					$FutureEventParticipantTable->updateParticipant($_fields, $_ID_);

					// UPDATING LOGS
					$operation = $status.' PARTICIPANT REGISTRATION';
					$comment = $status. " ".$_ID_;
					Logs::newLog($operation, $comment, $created_by);

					/** Get Event Private Invitation Link  By ID */
					$_participant_data_ = FutureEventController::getParticipantByID($_ID_);
					$_participant_event_id_ = $_participant_data_->event_id;

					/** Send Email To Participant */
					if ($status == 'APPROVED')
						$status = 'Approved';
					else if ($status == 'DENIED')
						$status = 'Denied';
					else if ($status == 'ACTIVE')
						$status = 'Activated';
					else if ($status == 'ACCEPTED')
						$status = 'Accepted';

					/** Get Payment Link */
					$payment_link = FutureEventController::getPaymentLinkUrl($_participant_event_id_, Hash::encryptAuthToken($_ID_));

					/** Invitation Letter Link */
					$invitation_letter_link = FutureEventController::getInvitationLetterPDFDocumentUrl($_participant_event_id_, Hash::encryptAuthToken($_ID_));

					$participation_type_name = $_participant_data_->participation_type_name;

					if ($participation_type_name == "Media") {
						$participation_type_name = "member of the media";
					}

					$_data_ = array(
						'email' => $_participant_data_->email,
						'firstname' => $_participant_data_->firstname.' '.$_participant_data_->lastname,
						'payment_link' => $payment_link,
						'invitation_letter_link' => $invitation_letter_link,
						'category' => $participation_type_name,
						'participant_code' => $_participant_data_->participant_code,
						'event_url' =>  self::getEventEndPointUrlRegistation($_participant_data_->event_id),
						'approval_status' => $status,
					);

					$sub_type_id = $_participant_data_->participation_sub_type_id;

					if ($_participant_event_id_ == 13) : // For IFF
						if ($IS_CBO) :
							if ($status == 'Approved' || $status == 'Accepted') :
								EmailController::sendEmailToParticipantAfterCBOAcceptanceWithRequestForPayment($_data_);
							elseif ($status == 'Denied') :
								EmailController::sendEmailToParticipantAfterApplicationDecline($_data_);
							endif;
						elseif (($_participant_data_->payment_state == 'FREE' and $status == 'Approved') || ($_participant_data_->group_id > 0 and $status == 'Approved')) :
							if ($sub_type_id == 116 || $sub_type_id == 117 || $sub_type_id == 118 || $sub_type_id == 122) :
								// Gleanin call-to-action to include in Comp Industry Pass
								EmailController::sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApprovalCompIndustry($_data_);
							else :
								EmailController::sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApproval($_data_);
							endif;
						elseif ($status == 'Denied') :
							if ($participation_type_name == 'CMPD') :  // For CMPD
								$_data_['event_url']="https://www.inclusivefintechforum.com/registration";
								EmailController::rejectParticipantApplicationCMPD($_data_);
							else :
								EmailController::rejectParticipantApplication($_data_);
							endif;
						endif;
					elseif ($_participant_event_id_ == 22) : // For ACSSTC
						if ($status == 'Approved') :
							EmailControllerACSSTC::sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApproval($_data_);
						elseif ($status == 'Denied') :
							// EmailControllerACSSTC::sendEmailToParticipantAfterApplicationDecline($_data_);
						endif;
					elseif ($_participant_event_id_ == 29) : // BAL
						if ($status == 'Approved'):
							/** Handle Qr COde */
							require_once('../../config/phpqrcode/qrlib.php');
		                    $_qrID_     = $_participant_data_->qrID;
		                    $_qrEncoded_= $_participant_data_->qrCode;
		                    $_DR_       = DN_IMG_QR;
		                    $_qrFilename_= $_qrID_.".png";
		                    $_qrFile_   = $_DR_.$_qrFilename_;
		                    QRcode::png($_qrEncoded_, $_qrFile_);

		                    $_PARTICIPANT_QR_IMAGE_ = VIEW_QR.$_qrFilename_;

		                    $_data_['participant_qrCode'] = $_PARTICIPANT_QR_IMAGE_;

							EmailControllerBAL::sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApproval($_data_);
						endif;
					elseif ($_participant_event_id_ == 28) : // RWANDA EXPO DOHA
						if ($status == 'Approved')
							EmailControllerRwandaGov::sendEmailToParticipantAfterSuccessfulRegistrationFreeOnApproval($_data_);
					endif;
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'RETURNEDMESSAGE' => $_RETURNED_MESSAGE_,
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function acceptRegistrationInvite()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participant-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'invitation_status' => 'ACCEPTED'
				);

				try {
					$FutureEventParticipantTable->updateParticipant($_fields, $_ID_);

					// UPDATING LOGS
					$operation = 'ACCEPT REGISTRATION INVITATION';
					$comment = 'ACCEPT REGISTRATION INVITATION '.$_ID_;
					Logs::newLog($operation, $comment, $created_by);

				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function createEventParticipantPassword()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = ' ';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		$_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Contact Information */
			$password = $str->data_in($_EDIT['password']);
			$confirm_password = $str->data_in($_EDIT['confirm_password']);

			$eventId = $str->data_in($_EDIT['eventId']);
			$authtoken = $str->data_in($_EDIT['authtoken']);

			$_PID_ = Hash::decryptAuthToken($authtoken);

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_PID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Get Participant Details */
			if (!($_participant_data_ = self::getEventParticipantDataByID($_PID_))) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];
			endif;

			/** Check If Password Match */
			if (strlen($password) < 6 || strlen($confirm_password) < 6) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Password must have at least 6 characters",
					'ERRORS_STRING' => "Password must have at least 6 characters"
				];
			endif;

			if ($password != $confirm_password) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "password don't match",
					'ERRORS_STRING' => "password don't match"
				];
			endif;


			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'password' => md5($password),
					'salt' => "",
				);

				try {
					$FutureEventParticipantTable->updateParticipant($_fields, $_PID_);

					/** Generate Auth Token */
					$_AUTH_TOKEN = $authtoken;
					$_PARTICIPATION_PAYMENT_TYPE_ = $_participant_data_->payment_state;

					/** Send Email To Participant */
					$_data_ = array(
						'email' => $_participant_data_->participant_email,
						'firstname' => $_participant_data_->participant_firstname,
						'fullname' => $_participant_data_->participant_lastname,

						'event' => $_participant_data_->event_name,
						'event_type' => $_participant_data_->event_category,
						'participation_type' => $_participant_data_->participation_type_name,
						'participation_subtype' => $_participant_data_->participation_subtype_name,
						'price' => $_participant_data_->participation_subtype_price,
						'currency' => $_participant_data_->participation_subtype_currency,
					);

					switch ($_participant_data_->payment_state):
						case 'PAYABLE':
							EmailController::sendEmailToParticipantOnRegistrationPayable($_data_);
							break;
						case 'FREE':
							EmailController::sendEmailToParticipantOnRegistrationFree($_data_);
							break;
					endswitch;
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'AUTHTOKEN' => $_AUTH_TOKEN,
				'PARTICIPATIONPAYMENTTYPE' => $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function createEventParticipantPrivateLink()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'private-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Contact Information */
			$firstname = $str->data_in($_EDIT['firstname']);
			$lastname = $str->data_in($_EDIT['lastname']);
			$email = $str->data_in($_EDIT['email']);
			$_participation_sub_type_token = $str->data_in($_EDIT['paticipation_sub_type']);
			$_event_token = $str->data_in($_EDIT['eventId']);
			$_group_token = !Input::checkInput('groupId', 'post', 1) ? '' : $str->data_in($_EDIT['groupId']);
			$group_admin_state = !Input::checkInput('group_admin_state', 'post', 1) ? 0 : $str->data_in($_EDIT['group_admin_state']);

			$plain_text_pwd = !Input::checkInput('plain_text_pwd', 'post', 1) ? '' : $_EDIT['plain_text_pwd'];

			$participation_sub_type_id = Hash::decryptAuthToken($_participation_sub_type_token);
			$event_id = Hash::decryptAuthToken($_event_token);
			$group_id = $_group_token == '' ? 0 : Hash::decryptAuthToken($_group_token);

			/** Check If Participant Email Not yet Used */
			if (self::checkIfPrivateLinkEmailAlreadyUsed($event_id, $participation_sub_type_id, $email)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This E-mail address has been used",
					'ERRORS_STRING' => "This E-mail address has been used"
				];
			endif;

			/** Get Particiption Type Id  */
			if (!($participation_type_data_ = self::getPacipationSubCategoryByID($participation_sub_type_id))) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;
			$participation_type_id = $participation_type_data_->id;

			/** Generated Link */
			$generated_link = '';
			$access_token = '';
			$access_generated_time = time();
			$access_expiry_time = self::getEventPrivateLinkAccessExpirationTime($event_id);

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($participation_sub_type_id) || !is_integer($event_id)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Check If Email Event Exitst  */
			if (self::checkIfEventPrivateLinkExists($event_id, $participation_type_id, $participation_sub_type_id, $email)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This Email was already registered",
					'ERRORS_STRING' => "This Email was already registered"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'event_id' => $event_id,
					'participation_type_id' => $participation_type_id,
					'participation_sub_type_id' => $participation_sub_type_id,
					'group_id' => $group_id,
					'group_admin_state' => $group_admin_state,

					'firstname' => $firstname,
					'lastname' => $lastname,
					'email' => $email,

					'generated_link' => $generated_link,
					'access_token' => $access_token,
					'access_generated_time' => $access_generated_time,
					'access_expiry_time' => $access_expiry_time,
					'link_used_time' => 0,
					'link_used_status' => 0,

					'reusable_state' => 0,
					'status' => 'PENDING' . $plain_text_pwd,
					'creation_date' => time()
				);

				try {
					$FutureEventParticipantTable->insertPrivateLink($_fields);

					/** Get Last Private Link Generated ID */
					$_ID_ = self::getLastID('future_private_links');
					$access_token = Hash::encryptAuthToken($_ID_);
					$generated_link = self::generatePrivateInvitationLink($event_id, $access_token);

					/** Update Entry */
					$_update_fields_ = array(
						'generated_link' => $generated_link,
						'access_token' => $access_token,
						'status' => 'ACTIVE',
					);
					$FutureEventParticipantTable->updatePrivateLink($_update_fields_, $_ID_);

					/** Get Event Private Invitation Link  By ID */
					$_participant_data_ = self::getEventPrivateLinkDataByID($_ID_);

					/** Send Email To Participant */
					$_data_ = array(
						'email' => $_participant_data_->participant_email,
						'firstname' => $_participant_data_->participant_firstname,
						'fullname' => $_participant_data_->participant_lastname,
						'generated_link' => $_participant_data_->generated_link,
						'password' => $plain_text_pwd,
						'system_link' => Config::get('url/group_admin_portal')
					);

					// UPDATING LOGS
					$operation = 'NEW INVITATION LINK';
					$comment = "Send to: ".$email;
					Logs::newLog($operation, $comment, $created_by);

					if($participation_type_id!=39){

						if ($group_admin_state == 1 && $group_id > 0 && $plain_text_pwd != '') {
							EmailController::sendEmailToGroupAdminOnGroupRegistrationRequestAccepted($_data_);
						} else
							EmailController::sendEmailToParticipantOnLinkGenerated($_data_);

					}
					
				} catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'EMAIL' => $email,
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function createGroupParticipantPrivateLink(){
		$diagnoArray[0] = 'NO_ERRORS';
		$validate 		= new \Validate();
		$prfx 			= 'private-';
		foreach($_POST as $index=>$val){
			$ar = explode($prfx,$index);
			if(count($ar)){
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;
		
		$validation = $validate->check($_EDIT, array(
			
		));
		
		
		if($validate->passed()){
			$FutureEventParticipantTable = new \FutureEvent();
			
			$str = new \Str();

			/** Contact Information */
			$firstname = $str->data_in($_EDIT['firstname']);
			$lastname  = $str->data_in($_EDIT['lastname']);
			$email 	   = $str->data_in($_EDIT['email']);
			$slot_ID   = $str->data_in($_EDIT['slotId']);
			$group_ID  = $str->data_in($_EDIT['groupId']);
			$group_name = $str->data_in($_EDIT['group_name']);
			$group_admin_name = $str->data_in($_EDIT['group_admin_name']);
			$event_id  = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));

			$url_registration = self::getEventEndPointUrlRegistation($event_id);

			$generated_link = $url_registration.'/register/group/invitation/'.$slot_ID;

			if($diagnoArray[0] == 'NO_ERRORS'){
				
				/** Send Email To Participant */
				$_data_ = array(
					'email'     => $email, 
					'firstname' => $firstname,
					'fullname'  => $lastname,
					'group_name'  => $group_name,
					'group_admin_name'  => $group_admin_name,
					'generated_link'  => $generated_link
				);

				EmailController::sendEmailToGroupMemberOnLinkGenerated($_data_);
			}
		} else{
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg 	    = ul_array($validation->errors());
		}
		if($diagnoArray[0] == 'ERRORS_FOUND'){
			return (object)[
				'ERRORS'		=> true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		}else{
			return (object)[
				'ERRORS'	    => false,
				'SUCCESS'	    => true,
				'ERRORS_SCRIPT' => "",
				'EMAIL'     	=> $email,
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function updateEventParticipantPrivateLink()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'private-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Contact Information */
			$firstname = $str->data_in($_EDIT['firstname']);
			$lastname = $str->data_in($_EDIT['lastname']);
			$email = $str->data_in($_EDIT['email']);
			$_participation_sub_type_token = $str->data_in($_EDIT['paticipation_sub_type']);
			$_event_token = $str->data_in($_EDIT['eventId']);

			$participation_sub_type_id = Hash::decryptAuthToken($_participation_sub_type_token);
			$event_id = Hash::decryptAuthToken($_event_token);


			/** Get Particiption Type Id  */
			if (!($participation_type_data_ = self::getPacipationSubCategoryByID($participation_sub_type_id))) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;
			$participation_type_id = $participation_type_data_->id;

			/** Generated Link */
			$generated_link = '';
			$access_token = '';
			$access_generated_time = time();
			$access_expiry_time = time();

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($participation_sub_type_id) || !is_integer($event_id)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Check If Email Event Exitst  */
			// if(self::checkIfEventPrivateLinkExists($event_id, $participation_type_id, $participation_sub_type_id, $email)):
			// 	return (object)[
			// 		'ERRORS'		=> true,
			// 		'ERRORS_SCRIPT' => "This Email was already registered",
			// 		'ERRORS_STRING' => "This Email was already registered"
			// 	];
			// endif;

			/** Get Last Private Link Generated ID */
			// $_ID_ 			= self::getLastID('future_private_links');
			$access_token = Hash::encryptAuthToken($_ID_);
			$generated_link = self::generatePrivateInvitationLink($event_id, $access_token);



			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					// 'event_id'             		=> $event_id,
					'participation_type_id' => $participation_type_id,
					'participation_sub_type_id' => $participation_sub_type_id,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'email' => $email,

					'generated_link' => $generated_link,
					'access_token' => $access_token,
					'access_generated_time' => $access_generated_time,
					'access_expiry_time' => $access_expiry_time,
					'link_used_time' => 0,
					'link_used_status' => 0,
					'status' => 'ACTIVE',

				);

				try {
					$FutureEventParticipantTable->updatePrivateLink($_fields, $_ID_);
					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'EMAIL' => $email,
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function resendEventParticipantPrivateLink()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'private-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();
			$str = new \Str();

			$_event_token = $str->data_in($_EDIT['eventId']);
			$_link_token = $str->data_in($_EDIT['Id']);

			$event_id = Hash::decryptAuthToken($_event_token);
			$_ID_ = Hash::decryptAuthToken($_link_token);

			if ($diagnoArray[0] == 'NO_ERRORS') {
				try {
					/** Get Event Private Invitation Link  By ID */
					$_participant_data_ = self::getEventPrivateLinkDataByID($_ID_);

					/** Send Email To Participant */
					$_data_ = array(
						'email' => $_participant_data_->participant_email,
						'firstname' => $_participant_data_->participant_firstname,
						'fullname' => $_participant_data_->participant_lastname,
						'generated_link' => $_participant_data_->generated_link,
					);
					EmailController::sendEmailToParticipantOnLinkGenerated($_data_);
				} catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function changeStatusParticipantPrivateLink($status = 'ACTIVE')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'private-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'status' => $status,

				);

				try {
					$FutureEventParticipantTable->updatePrivateLink($_fields, $_ID_);

					/** Get Event Private Invitation Link  By ID */
					$_participant_data_ = self::getEventPrivateLinkDataByID($_ID_);

					/** Send Email To Participant */
					$status = $status == 'ACTIVE' ? 'Activated' : 'Deactivated';
					$_data_ = array(
						'email' => $_participant_data_->participant_email,
						'firstname' => $_participant_data_->participant_firstname,
						'fullname' => $_participant_data_->participant_lastname,

						'event' => $_participant_data_->event_name,
						'event_type' => $_participant_data_->event_category,
						'participation_type' => $_participant_data_->participation_type_name,
						'participation_subtype' => $_participant_data_->participation_subtype_name,
						'price' => $_participant_data_->participation_subtype_price,
						'currency' => $_participant_data_->participation_subtype_currency,
						'status' => $status,
					);

					EmailController::sendEmailToParticipantOnLinkStatusChanged($_data_);
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				// 'EMAIL'     	=> $email,
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function createEventPromoCode()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** PROMO CODE Information */
			$participation_type = Hash::decryptAuthToken($str->data_in($_EDIT['participation_type']));
			$discount = $str->data_in($_EDIT['discount']);
			$promo_code = $str->data_in($_EDIT['promo_code']);
			$maximum_delegates = $str->data_in($_EDIT['maximum_delegates']);
			$organisation = $str->data_in($_EDIT['organisation']);
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));


			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($event_id)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Check If PROMO CODE Exitst  */
			if (self::checkIfEventPromoCodeExists($event_id, $promo_code)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This promo code was already registered",
					'ERRORS_STRING' => "This promo code already registered"
				];
			endif;


			if ($diagnoArray[0] == 'NO_ERRORS') {
				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'event_id' => $event_id,
					'participation_type_id' => $participation_type,
					'promo_code' => $promo_code,
					'discount' => $discount,
					'maximum_delegates' => $maximum_delegates,
					'organisation' => $organisation,
					'status' => 'ACTIVE',
					'created_by' => $created_by,
					'added_date' => date('Y-m-d h:i:s')
				);

				try {
					$FutureEventParticipantTable->insertPromoCode($_fields);

					// UPDATING LOGS
					$operation = 'NEW PROMO CODE';
					$comment = "Promo code name: ".$promo_code;
					Logs::newLog($operation, $comment, $created_by);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}

	// UPDATE PROMO CODE
	public static function updateEventPromoCode()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** PROMO CODE Information */
			$participation_type = Hash::decryptAuthToken($str->data_in($_EDIT['participation_type']));
			$discount = $str->data_in($_EDIT['discount']);
			$promo_code = $str->data_in($_EDIT['promo_code']);
			$maximum_delegates = $str->data_in($_EDIT['maximum_delegates']);
			$organisation = $str->data_in($_EDIT['organisation']);
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));

			/** Check If PROMO CODE Exitst  */
			if (self::checkIfEventPromoCodeExists($event_id, $promo_code, $_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This promo code was already registered",
					'ERRORS_STRING' => "This promo code already registered"
				];
			endif;


			if ($diagnoArray[0] == 'NO_ERRORS') {
				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'participation_type_id' => $participation_type,
					'promo_code' => $promo_code,
					'discount' => $discount,
					'maximum_delegates' => $maximum_delegates,
					'organisation' => $organisation
				);

				try {
					$FutureEventParticipantTable->updatePromoCode($_fields, $_ID_);

					// UPDATING LOGS
					$operation = 'UPDATE-PROMO CODE';
					$comment = "Category name: ".$promo_code;
					Logs::newLog($operation, $comment, $created_by);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}

	// ACTIVATE / DEACTIVATE PROMO CODE
	public static function changeStatusPromoCode($status = 'ACTIVE')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'promo-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Promo code Table */
			if (!is_integer($_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'status' => $status,
				);

				try {
					$FutureEventParticipantTable->updatePromoCode($_fields, $_ID_);

					// UPDATING LOGS
					$operation = $status.' PROMO CODE';
					$comment = "Promo code name: ".$status;
					Logs::newLog($operation, $comment, $created_by);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function createEventParticipationType()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Contact Information */
			$name = $str->data_in($_EDIT['name']);
			$payment_state = $str->data_in($_EDIT['payment_state']);
			$visibility_state = $str->data_in($_EDIT['visibility_state']);
			$form_order = $str->data_in($_EDIT['form_order']);
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));


			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($event_id)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Check If Email Event Exitst  */
			if (self::checkIfEventParticipationTypeExists($event_id, $name)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This Type was already registered",
					'ERRORS_STRING' => "This Type was already registered"
				];
			endif;


			if ($diagnoArray[0] == 'NO_ERRORS') {
				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'name' => $name,
					'payment_state' => $payment_state,
					'event_level' => 'SPECIFIC',
					'event_id' => $event_id,
					'sub_type_state' => 0,
					'visibility_state' => $visibility_state,

					'form_order' => $form_order,
					'status' => 'ACTIVE',
					'created_by' => $created_by,
					'creation_date' => date('Y-m-d h:i:s')
				);

				try {
					$FutureEventParticipantTable->insertParticipationType($_fields);

					// UPDATING LOGS
					$operation = 'NEW PARTICIPATION CATEGORY';
					$comment = "Category name: ".$name;
					Logs::newLog($operation, $comment, $created_by);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function updateEventParticipationType()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Particiaption Type Information */
			$name = $str->data_in($_EDIT['name']);
			$payment_state = $str->data_in($_EDIT['payment_state']);
			$visibility_state = $str->data_in($_EDIT['visibility_state']);
			$form_order = $str->data_in($_EDIT['form_order']);
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));

			/** Check If Email Event Exitst  */
			if (self::checkIfEventParticipationTypeExists($event_id, $name, $_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This Type was already registered",
					'ERRORS_STRING' => "This Type was already registered"
				];
			endif;


			if ($diagnoArray[0] == 'NO_ERRORS') {
				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'name' => $name,
					'payment_state' => $payment_state,
					'event_id' => $event_id,
					'visibility_state' => $visibility_state,
					'form_order' => $form_order,
				);

				try {
					$FutureEventParticipantTable->updateParticipationType($_fields, $_ID_);

					// UPDATING LOGS
					$operation = 'UPDATE-PARTICIPATION CATEGORY';
					$comment = "Category name: ".$name;
					Logs::newLog($operation, $comment, $created_by);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function changeStatusParticipationType($status = 'ACTIVE')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'status' => $status,
				);

				try {
					$FutureEventParticipantTable->updateParticipationType($_fields, $_ID_);

					// UPDATING LOGS
					$operation = 'CHANGE PARTICIPATION CATEGORY';
					$comment = "Category name: ".$status;
					Logs::newLog($operation, $comment, $created_by);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function createEventParticipationSubType()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());


		if ($validate->passed()) {
			$FutureEventTable = new \FutureEvent();

			$str = new \Str();

			/** Information */
			$name = $str->data_in($_EDIT['name']);
			$payment_state = $str->data_in($_EDIT['payment_state']);
			$price = $str->data_in($_EDIT['price']);
			$currency = $str->data_in($_EDIT['currency']);
			$category = $str->data_in($_EDIT['category']);
			$participation_type_id = Hash::decryptAuthToken($str->data_in($_EDIT['participation_type']));


			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($participation_type_id)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Check If Email Event Exitst  */
			if (self::checkIfEventParticipationSubTypeExists($participation_type_id, $category, $name)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This Sub Type was already registered",
					'ERRORS_STRING' => "This Sub Type was already registered"
				];
			endif;


			if ($diagnoArray[0] == 'NO_ERRORS') {
				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'participation_type_id' => $participation_type_id,
					'payment_state' => $payment_state,
					'name' => $name,
					'price' => $price,
					'category' => $category,
					'currency' => $currency,
					'status' => 'ACTIVE',
					'creation_date' => date('Y-m-d h:i:s')
				);

				try {
					$FutureEventTable->insertParticipationSubType($_fields);

					// UPDATING LOGS
					$operation = 'NEW PARTICIPATION SUB CATEGORY';
					$comment = "Sub Category name: ".$name;
					Logs::newLog($operation, $comment, $created_by);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function updateEventParticipationSubType()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Particiaption Type Information */
			$name = $str->data_in($_EDIT['name']);
			$payment_state = $str->data_in($_EDIT['payment_state']);
			$price = $str->data_in($_EDIT['price']);
			$currency = $str->data_in($_EDIT['currency']);
			$category = $str->data_in($_EDIT['category']);
			$participation_type_id = Hash::decryptAuthToken($str->data_in($_EDIT['participation_type']));

			/** Price is 0 For Free Event */
			if ($payment_state == 'FREE')
				$price = 0;

			/** Check If Email Event Exitst  */
			if (self::checkIfEventParticipationSubTypeExists($participation_type_id, $category, $name, $_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This Sub Type was already registered",
					'ERRORS_STRING' => "This Sub Type was already registered"
				];
			endif;


			if ($diagnoArray[0] == 'NO_ERRORS') {
				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'participation_type_id' => $participation_type_id,
					'payment_state' => $payment_state,
					'name' => $name,
					'price' => $price,
					'category' => $category,
					'currency' => $currency,
				);

				try {
					$FutureEventParticipantTable->updateParticipationSubType($_fields, $_ID_);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}



	public static function getEventParticipantPaymentDataByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT
		future_payment_transaction_entry.id As payment_id, 
		future_payment_transaction_entry.transaction_id As payment_transaction_id, 
		future_payment_transaction_entry.transaction_time As payment_transaction_date, 
		future_payment_transaction_entry.transaction_status As payment_transaction_status, 
		future_payment_transaction_entry.receipt_id As payment_receipt_id, 
		future_payment_transaction_entry.amount As payment_amount, 
		future_participants.id As participant_id, 
		future_participants.qrID As participant_code, 
		future_participants.firstname As participant_firstname, 
		future_participants.lastname As participant_lastname, 
		future_participants.birthday As participant_birthday, 
		future_participants.email As participant_email, 
		future_participants.telephone As participant_telephone, 
		future_participants.organisation_city As participant_city, 
		future_participants.organisation_country As participant_country, 
		future_participation_type.name as participation_type_name, 
		future_participation_sub_type.name As participation_subtype_name,
		future_participation_sub_type.payment_state,
		future_participation_sub_type.category As event_category,
		future_participation_sub_type.price As participation_subtype_price,
		future_participation_sub_type.currency As participation_subtype_currency,
		future_participants.participation_type_id As participation_type_id, 
		future_participants.participation_sub_type_id As participation_sub_type_id,
		future_participants.group_id As participation_group_id,
		future_event.id As event_id, 
		future_event.event_name As event_namegroup_id
		FROM future_payment_transaction_entry INNER JOIN  future_participants ON future_payment_transaction_entry.participant_id = future_participants.id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id 
		INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id 
		INNER JOIN future_event ON future_participants.event_id = future_event.id 
		WHERE future_participants.id = {$ID} ORDER BY future_payment_transaction_entry.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}



	public static function changeStatusParticipationSubType($status = 'ACTIVE')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'participation-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array());

		if ($validate->passed()) {
			$FutureEventParticipantTable = new \FutureEvent();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'status' => $status,
				);

				try {
					$FutureEventParticipantTable->updateParticipationSubType($_fields, $_ID_);

					/** Send Email To Participant */
				} catch (Exeption $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => ""
			];
		} else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function getPacipationTypeyByEventID($eventID, $payment_state = NULL)
	{
		$SQL_Condition_ = ($payment_state == NULL) ? '' : " AND payment_state = '$payment_state' ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_participation_type WHERE event_id = {$eventID} $SQL_Condition_  ORDER BY name ASC ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getPromocodesByEventID($eventID, $status = NULL)
	{
		$SQL_Condition_ = ($status == NULL) ? '' : " AND status = '$status' ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT users.firstname as user_firstname, users.lastname as user_lastname,  future_promo_code.* FROM future_promo_code INNER JOIN users ON users.id = future_promo_code.created_by WHERE event_id = {$eventID} $SQL_Condition_ ORDER BY future_promo_code.id DESC ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getPacipationSubType($eventID, $TypeID = null, $payment_state = NULL)
	{
		$SQL_Condition_ = ($payment_state == NULL) ? '' : " AND future_participation_sub_type.payment_state = '$payment_state' ";
		$SQL_Condition_ .= ($TypeID == null) ? '' : " AND future_participation_sub_type.participation_type_id = {$TypeID}";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participation_type.id as type_ID, future_participation_type.name as type_name, future_participation_type.visibility_state as type_visibility,  future_participation_sub_type.* FROM future_participation_sub_type INNER JOIN future_participation_type ON future_participation_type.id = future_participation_sub_type.participation_type_id WHERE event_id = {$eventID} $SQL_Condition_ ORDER BY future_participation_sub_type.id ASC ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getActivePacipationCategoryByEventID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_participation_type WHERE event_id = {$eventID} AND status = 'ACTIVE' AND visibility_state = 1  ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getAllActivePacipationCategoryByEventID($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_participation_type WHERE event_id = {$eventID} AND status = 'ACTIVE'");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getPacipationCategoryByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM `future_participation_type` WHERE id = {$ID} ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getActivePacipationSubCategoryByPartcipationTypeID($participation_type_Id, $eventType = 'INPERSON')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_participation_sub_type WHERE participation_type_id = {$participation_type_Id} AND category = '{$eventType}' AND status = 'ACTIVE'  ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getActivePacipationSubCategoryByPartcipationTypeIDGroup($participation_type_Id)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_participation_sub_type WHERE participation_type_id = {$participation_type_Id}  AND status = 'ACTIVE'  ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getPacipationCategoryPrice($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM `future_participation_sub_type` WHERE id = {$ID} ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getVisiblePacipationSubCategory($eventID, $eventType = 'INPERSON')
	{
		if (($_participation_type_data_ = self::getActivePacipationCategoryByEventID($eventID))) :
			$_array_data_ = array();
			foreach ($_participation_type_data_ as $_participation_type_) :

				if (($_participation_sub_type_data_ = self::getActivePacipationSubCategoryByPartcipationTypeID($_participation_type_->id, $eventType))) :
					foreach ($_participation_sub_type_data_ as $sub_type_) :
						$_array_data_[] = array(
							'participation_type_name' => $_participation_type_->name,
							'participation_type_payment_state' => $_participation_type_->payment_state,
							'participation_sub_type_id' => $sub_type_->id,
							'participation_sub_type_name' => $sub_type_->name,
							'participation_sub_type_price' => $sub_type_->price,
							'participation_sub_type_currency' => $sub_type_->currency,
							'participation_type_form_order' => $_participation_type_->form_order,
						);
					endforeach;

				endif;
			endforeach;
			return $_array_data_;
		endif;
		return false;
	}

	public static function getPrivatePacipationSubCategory($eventID, $eventType = 'INPERSON')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participation_type.*, future_participation_sub_type.name As sub_type_name , future_participation_sub_type.id As sub_type_id, future_participation_sub_type.category As sub_type_category   FROM `future_participation_type`  INNER JOIN future_participation_sub_type ON future_participation_type.id = future_participation_sub_type.participation_type_id WHERE future_participation_type.event_id = {$eventID} AND future_participation_type.visibility_state =  0 ORDER BY future_participation_type.name DESC");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getGeneratedPrivateLinks($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_private_links.*, future_participation_sub_type.name As sub_type_name,  future_participation_sub_type.category As sub_type_category, future_participation_type.name As type_name 
		FROM future_private_links 
		INNER JOIN future_participation_sub_type ON future_private_links.participation_sub_type_id = future_participation_sub_type.id  
		INNER JOIN `future_participation_type` ON  future_participation_type.id = future_private_links.participation_type_id
		WHERE future_private_links.event_id = {$eventID} ORDER BY future_private_links.id DESC");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getPacipationSubCategoryByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participation_type.*, future_participation_sub_type.name As sub_type_name, future_participation_sub_type.code As sub_type_code, future_participation_sub_type.price as sub_type_price, future_participation_sub_type.currency As sub_type_currency, future_participation_sub_type.payment_state AS sub_type_payment_state  FROM `future_participation_type`  INNER JOIN future_participation_sub_type ON future_participation_type.id = future_participation_sub_type.participation_type_id WHERE future_participation_sub_type.id = {$ID} ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getLastPacipatantID()
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id FROM `future_participants` ORDER BY id DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->id;
		return false;
	}

	public static function getLastID($_table_, $key = 'id')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT $key FROM {$_table_} ORDER BY $key DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->id;
		return false;
	}

	public static function getParticipantDataByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.id, future_participation_type.name as participation_type_name, future_participation_sub_type.payment_state FROM `future_participants` INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.id = {$ID} ORDER BY future_participants.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getParticipantByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.name as participation_type_name, future_participation_type.code as participation_type_code, future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency, form_list.form_name FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id LEFT JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id LEFT JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id LEFT JOIN form_list ON future_participants.form_id = form_list.id WHERE future_participants.id = {$ID} AND (future_participation_type.id IS NULL OR future_participation_type.id IS NOT NULL) AND (future_participation_sub_type.id IS NULL OR future_participation_sub_type.id IS NOT NULL) AND (form_list.id IS NULL OR form_list.id IS NOT NULL) ORDER BY future_participants.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getParticipantByQrID($QrID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.qrID = '{$QrID}' ORDER BY future_participants.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getParticipantDataByQrID($QrID, $event)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.qrID = '{$QrID}' AND future_participants.event_id = '{$event}'  ORDER BY future_participants.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getParticipantByCategoryID($category, $event)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.participation_type_id = '{$category}' AND future_participants.event_id = '{$event}' AND future_participants.issue_badge_status!=1 ORDER BY future_participants.id DESC");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}


	public static function getDelegateCountByCategoryID($event)
	{
		$FutureEventTable = new FutureEvent();
		$category_condition = " future_participants.participation_type_id != 4 AND future_participants.participation_type_id != 6 AND future_participants.participation_type_id != 20 AND future_participants.participation_type_id != 35 AND future_participation_type.name != 'VIP' AND future_participants.status='APPROVED' AND future_participation_sub_type.category='INPERSON'";
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE " . $category_condition . " AND future_participants.event_id = '{$event}' ORDER BY future_participants.id DESC");
		return $FutureEventTable->count();
		return false;
	}

	public static function getVIPCountByCategoryID($event)
	{
		$FutureEventTable = new FutureEvent();
		$category_condition = " future_participation_sub_type.name = 'VIP' AND future_participants.status='APPROVED' AND future_participation_sub_type.category='INPERSON'";
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE " . $category_condition . " AND future_participants.event_id = '{$event}' ORDER BY future_participants.id DESC");
		return $FutureEventTable->count();
		return false;
	}

	public static function getVIPDataByCategoryID($event)
	{
		$FutureEventTable = new FutureEvent();
		$category_condition = " future_participation_sub_type.name = 'VIP' AND future_participants.status='APPROVED' AND future_participation_sub_type.category='INPERSON'";
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE " . $category_condition . " AND future_participants.event_id = '{$event}' ORDER BY future_participants.id DESC");
		return $FutureEventTable->data();
		return false;
	}

	public static function getNonRwandanDelegates($event)
	{
		$FutureEventTable = new FutureEvent();
		$category_condition = " future_participants.organisation_country != 'RW' AND future_participants.status='APPROVED' AND future_participation_sub_type.category='INPERSON'";
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE " . $category_condition . " AND future_participants.event_id = '{$event}' ORDER BY future_participants.id DESC");
		return $FutureEventTable->data();
		return false;
	}

	public static function getParticipantCountByCategoryID($category, $event)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.participation_type_id = '{$category}' AND future_participants.event_id = '{$event}' AND future_participants.status='APPROVED' AND future_participation_sub_type.category='INPERSON' ORDER BY future_participants.id DESC");
		return $FutureEventTable->count();
		return false;
	}

	public static function getParticipantsByEventID($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.*, future_participants.id as participant_ID, future_participation_type.name as participation_type_name, future_participation_type.code as participation_type_code, future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.event_id = {$eventID} $_SQL_Condition_  ORDER BY future_participants.id DESC ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getTotalParticipantsByFilter($eventID, $condition = "", $order = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.*, future_participants.id as participant_ID, future_participation_type.name as participation_type_name, future_participation_type.code as participation_type_code, future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency, form_list.form_name FROM `future_participants` LEFT JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id LEFT JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id LEFT JOIN form_list ON future_participants.form_id = form_list.id WHERE future_participants.event_id = {$eventID} AND (future_participation_type.id IS NULL OR future_participation_type.id IS NOT NULL) AND (future_participation_sub_type.id IS NULL OR future_participation_sub_type.id IS NOT NULL) AND (form_list.id IS NULL OR form_list.id IS NOT NULL) $_SQL_Condition_  $order");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getTotalParticipantsCounterByFilter($eventID, $condition = "", $order = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_participants.*, future_participants.id as participant_ID, future_participation_type.name as participation_type_name, future_participation_type.code as participation_type_code, future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.event_id = {$eventID} $_SQL_Condition_  $order");
		return $FutureEventTable->count();
		return false;
	}

	public static function getRegistrationByDay($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id, firstname participation_type_id, participation_sub_type_id, reg_date 
		FROM future_participants WHERE event_id = {$eventID} $_SQL_Condition_  ORDER BY id DESC ");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	// KIF

	public static function getKIFCParticipantsByEventID($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_rfl_particpants ORDER BY id DESC");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getKIFCParticipantByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM future_rfl_particpants WHERE id = {$ID} ORDER BY id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	// END KIFC


	public static function getPaymentParticipantsByEventID($eventID, $condition = "", $order = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$_SQL_Condition_ .= " AND future_payment_transaction_entry.transaction_status !=  'IGNORED' ";
		// $_SQL_Condition_ .= " AND future_payment_transaction_entry.transaction_status =  'PENDING' AND future_payment_transaction_entry.delayed_payment_reminder_status != 1";
		$FutureEventTable = new FutureEvent();
		$SQL_ = "SELECT 
		future_participants.group_id, 
		future_participants.group_admin_state, 
		future_payment_transaction_entry.id as id, 
		future_payment_transaction_entry.receipt_id as receipt_id, 
		future_payment_transaction_entry.transaction_id as transaction_id, 
		future_payment_transaction_entry.transaction_time as transaction_time, 
		future_payment_transaction_entry.transaction_token as transaction_token, 
		future_payment_transaction_entry.transaction_status as transaction_status, 
		future_payment_transaction_entry.external_transaction_id as external_transaction_id,
		future_payment_transaction_entry.payment_method as payment_method, 
		future_payment_transaction_entry.payment_operator as payment_operator, 
		future_payment_transaction_entry.amount as transaction_amount,
		future_payment_transaction_entry.currency as transaction_currency,
		future_payment_transaction_entry.payment_id as payment_id,
		future_payment_transaction_entry.callback_time as callback_time,
		future_payment_transaction_entry.approval_time as approval_time,
		future_payment_transaction_entry.approval_comment as approval_comment,
		future_payment_transaction_entry.delayed_payment_reminder_status as delayed_payment_reminder_status, 
		
		future_participants.id as participant_ID, 
		future_participants.firstname as participant_firstname, 
		future_participants.lastname as participant_lastname, 
		future_participants.email as participant_email, 
		future_participants.organisation_name as participant_organization_name, 
		future_participants.job_title as participant_job_title, 
		future_participation_type.name as participation_type_name,  
		future_participation_sub_type.name as participation_subtype_name, 
		future_participation_sub_type.payment_state, 
		future_participation_sub_type.category as participation_subtype_category, 
		future_participation_sub_type.price as participation_subtype_price, 
		future_participation_sub_type.currency as participation_subtype_currency 
		FROM future_payment_transaction_entry
		INNER JOIN future_participants ON future_payment_transaction_entry.participant_id = future_participants.id
		INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id 
		INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id 
		WHERE future_participants.event_id = $eventID $_SQL_Condition_ 
		GROUP BY future_payment_transaction_entry.participant_id DESC $order";

		$FutureEventTable->selectQuery($SQL_);
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getPaymentParticipantsCounterByEventID($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$_SQL_Condition_ .= " AND future_payment_transaction_entry.transaction_status !=  'IGNORED' ";
		$FutureEventTable = new FutureEvent();
		$SQL_ = "SELECT 
		future_participants.group_id, 
		future_participants.group_admin_state, 
		future_payment_transaction_entry.id as id, 
		future_payment_transaction_entry.receipt_id as receipt_id, 
		future_payment_transaction_entry.transaction_id as transaction_id, 
		future_payment_transaction_entry.transaction_time as transaction_time, 
		future_payment_transaction_entry.transaction_token as transaction_token, 
		future_payment_transaction_entry.transaction_status as transaction_status, 
		future_payment_transaction_entry.external_transaction_id as external_transaction_id,
		future_payment_transaction_entry.payment_method as payment_method, 
		future_payment_transaction_entry.payment_operator as payment_operator, 
		future_payment_transaction_entry.amount as transaction_amount,
		future_payment_transaction_entry.currency as transaction_currency,
		future_payment_transaction_entry.payment_id as payment_id,
		future_payment_transaction_entry.callback_time as callback_time,
		future_payment_transaction_entry.approval_time as approval_time,
		future_payment_transaction_entry.approval_comment as approval_comment,
		future_payment_transaction_entry.delayed_payment_reminder_status as delayed_payment_reminder_status, 
		
		future_participants.id as participant_ID, 
		future_participants.firstname as participant_firstname, 
		future_participants.lastname as participant_lastname, 
		future_participants.email as participant_email, 
		future_participants.organisation_name as participant_organization_name, 
		future_participants.job_title as participant_job_title, 
		future_participation_type.name as participation_type_name,  
		future_participation_sub_type.name as participation_subtype_name, 
		future_participation_sub_type.payment_state, 
		future_participation_sub_type.category as participation_subtype_category, 
		future_participation_sub_type.price as participation_subtype_price, 
		future_participation_sub_type.currency as participation_subtype_currency 
		FROM future_payment_transaction_entry
		INNER JOIN future_participants ON future_payment_transaction_entry.participant_id = future_participants.id
		INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id 
		INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id 
		WHERE future_participants.event_id = $eventID $_SQL_Condition_ 
		GROUP BY future_payment_transaction_entry.participant_id DESC
		ORDER BY future_payment_transaction_entry.id DESC ";
		$FutureEventTable->selectQuery($SQL_);
		return $FutureEventTable->count();
		return false;
	}

	public static function getEventParticipantDataByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT 
		future_participants.group_id As group_id, 
		future_participants.group_admin_state As group_admin_state, 
		future_participants.id As participant_id, 
		future_participants.firstname As participant_firstname, 
		future_participants.lastname As participant_lastname, 
		future_participants.email As participant_email, 
		future_participation_type.name as participation_type_name, 
		future_participation_type.code as participation_type_code,
		future_participation_sub_type.name As participation_subtype_name,
		future_participation_sub_type.payment_state,
		future_participation_sub_type.category As event_category,
		future_participation_sub_type.price As participation_subtype_price,
		future_participation_sub_type.currency As participation_subtype_currency,
		future_participants.participation_type_id As participation_type_id, 
		future_participants.participation_sub_type_id As participation_sub_type_id,
		future_event.id As event_id, 
		future_event.participant_code As participant_code, 
		future_event.event_name As event_name
		FROM `future_participants` INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id 
		INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id 
		INNER JOIN future_event ON future_participants.event_id = future_event.id 
		WHERE future_participants.id = {$ID} ORDER BY future_participants.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getEventPrivateLinkDataByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT 
		future_private_links.id As participant_id, 
		future_private_links.firstname As participant_firstname, 
		future_private_links.lastname As participant_lastname, 
		future_private_links.email As participant_email, 
		future_private_links.generated_link As generated_link, 
		future_participation_type.name as participation_type_name, 
		future_participation_sub_type.name As participation_subtype_name,
		future_participation_sub_type.payment_state,
		future_participation_sub_type.category As event_category,
		future_participation_sub_type.price As participation_subtype_price,
		future_participation_sub_type.currency As participation_subtype_currency,
		future_event.id As event_id, 
		future_event.event_name As event_name
		FROM `future_private_links` INNER JOIN future_participation_type ON future_private_links.participation_type_id = future_participation_type.id 
		INNER JOIN future_participation_sub_type ON future_private_links.participation_sub_type_id = future_participation_sub_type.id 
		INNER JOIN future_event ON future_private_links.event_id = future_event.id 
		WHERE future_private_links.id = {$ID} ORDER BY future_private_links.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getEventEndPointUrlRegistation($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT url_registration from future_event WHERE id = $eventID ORDER BY id DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->url_registration;
		return false;
	}

	public static function generatePrivateInvitationLink($event_id, $access_token)
	{
		return self::getEventEndPointUrlRegistation($event_id) . '/register/invitation/' . $access_token;
	}

	public static function generatePrivateAdminGroupInvitationLink($event_id, $access_token)
	{
		return self::getEventEndPointUrlRegistation($event_id) . '/register/group/admin/invitation/' . $access_token;
	}

	public static function getPaymentReceiptPDFDocumentUrl($event_id, $paymentauthtoken)
	{
		return self::getEventEndPointUrlRegistation($event_id) . '/pdf/payment/receipt/' . $paymentauthtoken;
	}

	public static function getPaymentInvoicePDFDocumentUrl($event_id, $paymentauthtoken)
	{
		return self::getEventEndPointUrlRegistation($event_id) . '/pdf/payment/invoice/' . $paymentauthtoken;
	}

	public static function getInvitationLetterPDFDocumentUrl($event_id, $paymentauthtoken)
	{
		return self::getEventEndPointUrlRegistation($event_id) . '/pdf/invitation/letter/' . $paymentauthtoken;
	}

	public static function getPaymentLinkUrl($event_id, $authtoken)
	{
		return self::getEventEndPointUrlRegistation($event_id) . '/payment/' . $authtoken;
	}

	public static function checkIfEventPrivateLinkExists($event_id, $participation_type_id, $participation_sub_type_id, $email)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id from future_private_links WHERE event_id =? AND participation_type_id =? AND participation_sub_type_id =?  AND email =?  ORDER BY id DESC LIMIT 1 ", array($event_id, $participation_type_id, $participation_sub_type_id, $email));
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function checkIfEventParticipationTypeExists($event_id, $name, $ID = null)
	{
		$SQL_Condition_ = ($ID == null) ? '' : " AND id != {$ID} ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id from future_participation_type WHERE event_id =? AND name =?  $SQL_Condition_ ORDER BY id DESC LIMIT 1 ", array($event_id, $name));
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function checkIfEventPromoCodeExists($event_id, $name, $ID = null)
	{
		$SQL_Condition_ = ($ID == null) ? '' : " AND id != {$ID} ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id from future_promo_code WHERE event_id =? AND promo_code =?  $SQL_Condition_ ORDER BY id DESC LIMIT 1 ", array($event_id, $name));
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function checkIfEventParticipationSubTypeExists($participation_type_id, $category, $name, $ID = null)
	{
		$SQL_Condition_ = ($ID == null) ? '' : " AND id != {$ID} ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id from future_participation_sub_type WHERE participation_type_id =? AND name =? AND category =?  $SQL_Condition_ ORDER BY id DESC LIMIT 1 ", array($participation_type_id, $name, $category));
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function getEventPrivateInvitationLinkDataByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT  future_private_links.event_id As event_ID, future_private_links.participation_type_id as participation_type_ID, future_private_links.participation_sub_type_id as participation_sub_type_ID, future_private_links.firstname as participant_firstname, future_private_links.lastname as participant_lastname, future_private_links.email as participant_email, future_private_links.status, future_private_links.link_used_status, future_participation_type.name as participation_type_name, future_participation_sub_type.name as participation_sub_type_name, future_participation_sub_type.category as event_type_name FROM `future_private_links` INNER JOIN future_participation_type ON future_private_links.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_private_links.participation_sub_type_id = future_participation_sub_type.id WHERE future_private_links.id = {$ID} ORDER BY future_private_links.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function checkValidityEventPrivateInvitationLink($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT  id  FROM future_private_links  WHERE future_private_links.id = {$ID} AND status = 'ACTIVE' AND link_used_status = 0 ORDER BY future_private_links.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function updatePrivateLinkData($Data, $ID)
	{
		try {
			$FutureEventTable = new FutureEvent();
			$FutureEventTable->updatePrivateLink($Data, $ID);
			return true;
		} catch (Exeption $e) {
			return false;
		}
	}

	public static function getEventEndDate($eventID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT  end_date  FROM future_event  WHERE id = {$eventID} ORDER BY id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->end_date;
		return false;
	}

	public static function getTotalRegisteredByPromoCodeID($event_ID, $promo_code_ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT maximum_delegates, (SELECT COUNT(id) As total FROM future_participants WHERE promo_code_id = $promo_code_ID) As registered_delegates FROM future_promo_code WHERE event_id = ? AND id = ? ORDER BY id DESC LIMIT 1", array($event_ID, $promo_code_ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->registered_delegates;
		return false;
	}

	public static function checkEmailAlreadyUsed($eventID, $email)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT  id  FROM future_participants  WHERE future_participants.event_id = {$eventID} AND future_participants.email = '{$email}' ORDER BY future_participants.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function checkIfPrivateLinkEmailAlreadyUsed($eventID, $participation_sub_type_id, $email)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT  id  FROM future_private_links  WHERE future_private_links.event_id = {$eventID} AND future_private_links.participation_sub_type_id = {$participation_sub_type_id} AND future_private_links.email = '{$email}' ORDER BY future_private_links.id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function checkIfProvateLinkHasExpired($eventID, $private_link_ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT  id  FROM future_private_links  WHERE id = {$private_link_ID} AND event_id = {$eventID} AND link_used_status = 0 AND access_expiry_time < " . time() . " AND status != 'USED' ORDER BY  id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function autoExpirationStatusEventPrivateInvitationLink($eventID)
	{
		if (($_event_private_links_ = self::getGeneratedPrivateLinks($eventID)))
			foreach ($_event_private_links_ as $_private_link_)
				if (self::checkIfProvateLinkHasExpired($eventID, $_private_link_->id))
					self::updatePrivateLinkStatusToExpired($_private_link_->id);
	}

	public static function updatePrivateLinkStatusToExpired($private_link_ID)
	{
		$_expiry_data_ = array(
			'status' => 'EXPIRED'
		);
		self::updatePrivateLinkData($_expiry_data_, $private_link_ID);
	}

	public static function getEventPrivateLinkAccessExpirationTime($eventID)
	{
		return strtotime(self::cleanDateFormat(self::getEventEndDate($eventID)) . ' -12 hours '); # Access Private Link Token Will Expire 12 hours before the event end date
	}

	public static function cleanDateFormat($str_date)
	{
		list($day, $month, $year) = explode('/', $str_date);
		$foramted_date = "$day-$month-$year";
		return (string)$foramted_date;
	}

	public static function generateQrID($eventID, $participantID)
	{
		$_Qr_ID_ = "TRS" . date('m') . date('y') . $eventID . "00" . $participantID;
		$_Qr_string_ = Hash::encryptSecToken($_Qr_ID_);
		return (object)[
			'ID' => $_Qr_ID_,
			'STRING' => $_Qr_string_
		];
	}

	public static function decodeQrString($QrString)
	{
		return Hash::decryptSecToken($QrString);
	}

	public static function participationTypeDiscountDescription($type_, $sub_type_)
	{
		$description = "";
		$_id_ = 1;
		if ($type_ == 'Africa based participants') :
			$_id_ = 1;
			$description = "Early bird discounted rate
			Valid till 31st December 2021 <br>
			$450 from 1st January 2021 - 5th March 2022";

		elseif ($type_ == 'Non-Africa based participants') :
			$_id_ = 2;
			$description = "Early bird discounted rate
			Valid till 31st December 2021
			$600 from 1st January 2021 - 5th March 2022";

		elseif ($type_ == 'Students / Youth participants') :
			$_id_ = 3;
			$description = "Early bird discounted rate
			Valid till 31st December 2021 <br>
			$200 from 1st January 2021 - 5th March 2022";

		elseif ($type_ == 'Media') :
			$_id_ = 4;
			$description = "Local & international meda are invited to attend the congress. <br>
			Apply for media accreditation here.";
		endif;
	}

	public static function checkDelayedPendingTransaction($transaction_datetime, $limit_max_days = 7)
	{
		$_TRX_DATETIME_UPTO_7_DAYS = strtotime(date('Y-m-d', $transaction_datetime) . '+ 7 days');
		$_CURRENT_DATETIME = time();
		return ($_TRX_DATETIME_UPTO_7_DAYS < $_CURRENT_DATETIME) ? true : false;
	}
}