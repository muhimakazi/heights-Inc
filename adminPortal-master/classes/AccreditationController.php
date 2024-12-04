<?php
class AccreditationController
{

	// UPDATE PRINT BADGE STATUS
	public static function updatePrintBadgeStatus($status = 1)
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'accreditation-';
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

			if ($diagnoArray[0] == 'NO_ERRORS') {
				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array('print_badge_status' => $status);

				try {
					$FutureEventParticipantTable->updateParticipant($_fields, $_ID_);

					// UPDATING LOGS
					if ($status == 1) {
						$operation = 'PRINT BADGE';
					} else {
						$operation = 'UNPRINT BADGE';
					}

					$comment = "DELEGATE ID: ".$_ID_;
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
				'ERRORS_STRING' => ""
			];
		}
	}


	// UPDATE PRINTED BADGE STATUS
	public static function updatePrintedBadgeStatus($_ID_)
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'accreditation-';
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

				$_fields = array('print_badge_status2' => $status);

				try {
					$FutureEventParticipantTable->updateParticipant($_fields, $_ID_);

					$operation = 'CONFIRM PRINTED BADGE';

					$comment = "DELEGATE ID: ".$_ID_;
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
				'ERRORS_STRING' => ""
			];
		}
	}


	// UPDATE ISSUE BADGE STATUS
	public static function updateIssueBadgeStatus($status = 1)
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'accreditation-';
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
			$location = $str->data_in($_EDIT['location']);

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

				$_fields = array(
					'issue_badge_status' => $status,
					'issue_badge_location' => $location,
					'issue_badge_time' => date('Y-m-d H:i:s')
				);

				try {
					$FutureEventParticipantTable->updateParticipant($_fields, $_ID_);

					// UPDATING LOGS
					if ($status == 1) {
						$operation = 'ISSUE BADGE';
					} else {
						$operation = 'UNISSUE BADGE';
					}

					$comment = "DELEGATE ID: ".$_ID_;
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
				'ERRORS_STRING' => ""
			];
		}
	}


	//GET BADGES LIST
	public static function getKGDParticipantsOld($event)
	{
		$FutureEventTable = new FutureEvent();
		$selection = "";
		$FutureEventTable->selectQuery("select id, firstname, lastname, delegate_type, issue_badge_status, status from future_participants where event_id = '{$event}' ORDER BY id ASC LIMIT 1");
		return $FutureEventTable->data();
		return false;
	}

	//CORPS
	public static function getCORPSParticipants($event, $condition = "", $limit = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$selection = "";
		$FutureEventTable->selectQuery("SELECT DISTINCT firstname FROM future_participants where event_id = '{$event}' $_SQL_Condition_ ORDER BY firstname ASC $limit");
		return $FutureEventTable->data();
		return false;
	}

	//KGD
	public static function getKGDParticipants($event, $condition = "", $limit = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$selection = "";

		$FutureEventTable->selectQuery("SELECT DISTINCT firstname FROM future_participants where event_id = '{$event}' $_SQL_Condition_ ORDER BY firstname ASC $limit");

		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.event_id = '{$event}' $_SQL_Condition_ ORDER BY future_participants.firstname ASC $limit");
		return $FutureEventTable->data();
		return false;
	}

	//IFF
	public static function getIFFParticipants($event, $condition = "", $limit = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$selection = "";

		$FutureEventTable->selectQuery("SELECT DISTINCT firstname FROM future_participants where event_id = '{$event}' $_SQL_Condition_ ORDER BY firstname ASC $limit");

		$FutureEventTable->selectQuery("SELECT future_participants.*,  future_event.event_name as event_name,  future_participants.id as participant_ID, future_participation_type.id as future_participation_type_id, future_participation_type.name as participation_type_name,  future_participation_sub_type.name as participation_subtype_name , future_participation_sub_type.payment_state, future_participation_sub_type.category as participation_subtype_category, future_participation_sub_type.price as participation_subtype_price, future_participation_sub_type.currency as participation_subtype_currency FROM `future_participants` INNER JOIN future_event ON future_event.id = future_participants.event_id INNER JOIN future_participation_type ON future_participants.participation_type_id = future_participation_type.id INNER JOIN future_participation_sub_type ON future_participants.participation_sub_type_id = future_participation_sub_type.id WHERE future_participants.event_id = '{$event}' $_SQL_Condition_ ORDER BY future_participants.firstname ASC $limit");
		return $FutureEventTable->data();
		return false;
	}

	//UPDATE BADGE PRINT STATUS
	public static function updatePrintBadgesStatus($ID)
	{
		$ParticipantTable = new \FutureEvent();
		$fields = array(
			'print_badge_status' => 1
		);
		$ParticipantTable->updateParticipant($fields, $ID);

		// UPDATING LOGS
		$created_by = Session::get(Config::get('sessions/session_name'));
		$operation = 'PRINT BADGES';
		$comment = "DELEGATE ID: ".$ID;
		Logs::newLog($operation, $comment, $created_by);
	}



}