<?php
class AttendanceController
{
	// CHECK IN ATTENDANCE
	public static function checkInAttendance($status = 1)
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'attendance-';
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
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));
			$location = !Input::checkInput('location', 'post', 1)?'KCC':$str->data_in($_EDIT['location']);
            $status = "CHECKEDIN";
            // $device = $_SERVER['HTTP_USER_AGENT'];
            $device = Session::get(Config::get('sessions/session_name'));
            $added_date = Time::getDate();
			$added_time = Time::getTime();

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

			if (self::hasAlreadyAttendedToday($event_id, $_ID_, $added_date)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Delegate already checkedin",
					'ERRORS_STRING' => "Delegate already checkedin",
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {
				if(Session::exists(Config::get('sessions/session_name'))) {
					$scanned_by = Session::get(Config::get('sessions/session_name'));
				} else {
					$scanned_by = $device;
				}

				$_fields = array(
					'event_id' => $event_id,
					'participant_id' => $_ID_,
					'location' => $location,
					'status' => $status,
					'added_date' => $added_date,
					'added_time' => $added_time,
					'scanned_by' => $scanned_by
				);

				try {
					$controller = new \Controller();

					$controller->create("future_attendance", $_fields);

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
				'ERRORS_STRING' => json_encode($diagnoArray)
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

	// CHECK IN SESSION
	public static function checkInSessionAttendance()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'attendance-';
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
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));
			$location = !Input::checkInput('location', 'post', 1)?'KCC':$str->data_in($_EDIT['location']);
			$session_name = !Input::checkInput('session_name', 'post', 1)?'KCC':$str->data_in($_EDIT['session_name']);
			$session_room = !Input::checkInput('session_room', 'post', 1)?'KCC':$str->data_in($_EDIT['session_room']);
            // $device = $_SERVER['HTTP_USER_AGENT'];
            $device = Session::get(Config::get('sessions/session_name'));
            $added_date = Time::getDate();
			$added_time = Time::getTime();

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

				if ($session_data = self::hasAlreadyAttendedSessionToday($event_id, $_ID_, $added_date)):
					$session_details = html_entity_decode($session_data->session_details);
					$session_details_id = $session_data->id;

					$sessionDetailsArray = json_decode($session_details, true);
					$arrayLength         = count($sessionDetailsArray) / 2;

					$session_room_key = 'session_room_'.$arrayLength;
					$session_name_key = 'session_name_'.$arrayLength;

					$response[$session_room_key] = $session_room;
					$response[$session_name_key] = $session_name;

					$attendance_info = json_encode($response);

					if(json_decode($session_details) != null){
				        $attendance_info = Functions::json_overwrite($session_details, $attendance_info);
				    }

					$_fields = array('session_details' => $attendance_info);

					try {
						$controller = new \Controller();
						$controller->update("future_attendance", $_fields, $session_details_id);
					} catch (Exeption $e) {
						$diagnoArray[0] = "ERRORS_FOUND";
						$diagnoArray[] = $e->getMessage();
					}
				else:
					$_POST = array('eventId' => Hash::encryptAuthToken($event_id), 'location' => $location, 'Id' => Hash::encryptToken($_ID_));
					self::checkInAttendance();
					if ($session_data = self::hasAlreadyAttendedSessionToday($event_id, $_ID_, $added_date)):
						$session_details_id = $session_data->id;

						$response['session_room'] = $session_room;
						$response['session_name'] = $session_name;
						$attendance_info = json_encode($response);

						$_fields = array('session_details' => $attendance_info);

						try {
							$controller = new \Controller();
							$controller->update("future_attendance", $_fields, $session_details_id);
						} catch (Exeption $e) {
							$diagnoArray[0] = "ERRORS_FOUND";
							$diagnoArray[] = $e->getMessage();
						}
						
					endif;
				endif;
			}
		} else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			$error_msg = ul_array($validation->errors());
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->getErrorLocation(),
				'ERRORS_STRING' => json_encode($diagnoArray)
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

	public static function hasAlreadyAttendedToday($eventID, $participantID, $today)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT  id  FROM future_attendance  WHERE participant_id = $participantID AND event_id = $eventID AND added_date = '$today' ORDER BY id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function hasAlreadyAttendedSessionToday($eventID, $participantID, $today)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT  id, session_details  FROM future_attendance  WHERE participant_id = $participantID AND event_id = $eventID AND added_date = '$today' ORDER BY id DESC LIMIT 1");
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}


	//GET ATTENDANCE LIST
	public static function getParticipantsAttendance($eventID, $condition = "", $order = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$SQL_ = "SELECT 
		future_attendance.id as id, 
		future_attendance.location as location, 
		future_attendance.status as status, 
		future_attendance.added_date as added_date, 
		future_attendance.added_time as added_time, 
		
		future_participants.id as participant_ID, 
		future_participants.participation_type_id as participation_type_id, 
		future_participants.participation_sub_type_id as participation_sub_type_id,
		future_participants.title as title, 
		future_participants.participant_code as participant_code,
		future_participants.firstname as firstname, 
		future_participants.lastname as lastname, 
		future_participants.telephone as telephone, 
		future_participants.email as email,
		future_participants.full_details as full_details,
		future_participants.organisation_name as organisation_name,
		future_participants.residence_country as residence_country,
		future_participants.organisation_city as organisation_city,
		future_participants.job_title as job_title, 
		future_participation_type.name as participation_type_name, 
		future_participation_sub_type.name as participation_subtype_name 
		FROM future_attendance
		INNER JOIN future_participants ON future_attendance.participant_id = future_participants.id
		INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id 
		INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id 
		WHERE future_participants.event_id = $eventID $_SQL_Condition_ 
		GROUP BY future_attendance.participant_id DESC $order";

		$FutureEventTable->selectQuery($SQL_);
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getParticipantsCountAttendance($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$SQL_ = "SELECT 
		future_attendance.id as id, 
		future_attendance.location as location, 
		future_attendance.status as status, 
		future_attendance.added_date as added_date, 
		future_attendance.added_time as added_time, 
		
		future_participants.id as participant_ID, 
		future_participants.participation_type_id as participation_type_id, 
		future_participants.participation_sub_type_id as participation_sub_type_id,
		future_participants.title as title, 
		future_participants.participant_code as participant_code,
		future_participants.firstname as participant_firstname, 
		future_participants.lastname as participant_lastname, 
		future_participants.email as participant_email, 
		future_participants.organisation_name as organisation_name,
		future_participants.residence_country as residence_country,
		future_participants.job_title as participant_job_title, 
		future_participation_type.name as participation_type_name, 
		future_participation_sub_type.name as participation_subtype_name, 
		future_participation_sub_type.category as participation_subtype_category, 
		future_participation_sub_type.price as participation_subtype_price, 
		future_participation_sub_type.currency as participation_subtype_currency 
		FROM future_attendance
		INNER JOIN future_participants ON future_attendance.participant_id = future_participants.id
		INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id 
		INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id 
		WHERE future_participants.event_id = $eventID $_SQL_Condition_ 
		GROUP BY future_attendance.participant_id DESC
		ORDER BY future_attendance.id DESC";

		$FutureEventTable->selectQuery($SQL_);
		return $FutureEventTable->count();
		return false;
	}

}