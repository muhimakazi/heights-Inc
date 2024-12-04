<?php
class HotelController

{
	public static function addHotel() // ADD HOTEL
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'hotel-';
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

			$name = $str->data_in($_EDIT['name']);
			$email = $str->data_in($_EDIT['email']);
			$telephone = $str->data_in($_EDIT['telephone_full']);
			$country = $str->data_in($_EDIT['country']);
			$city = $str->data_in($_EDIT['city']);
			$address = $str->data_in($_EDIT['address']);
			$rate = $str->data_in($_EDIT['rate']);

			// ADDITIONAL DETAILS IFF
			$hotel_data_string = !Input::checkInput('full_details', 'post', 1) ? '' : $str->data_in($_EDIT['full_details']);

			/** Upload photo */
			$photo = '';
			if (isset($_FILES['photo']))
				if ($_FILES['photo']['name'] != "")
					$photo = Functions::fileUpload(Config::get('filepath/image').'hotel/', $_FILES['photo']);

			$created_by = Session::get(Config::get('sessions/session_name'));

			$_ID = self::getLastID('hotel') + 1;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
	                'name'         => $name,
	                'email'        => $email,
	                'telephone'    => $telephone,
	                'country'      => $country,
	                'city'         => $city,
	                'address'      => $address,
	                'rate'         => $rate,
	                'full_details' => $hotel_data_string,
	                'rate'         => $rate,
	                'photo'        => $photo,
	                'added_date'   => date('Y-m-d H:i:s')
	            );

				try {
					$controller->create("hotel", $_fields);
					// UPDATING LOGS
					$operation = 'NEW HOTEL';
					$comment = "HOTEL ID: ".$_ID;
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
				'SUCCESS_STRING' => "Hotel successfully created",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function updateHotel() // EDIT HOTEL
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'hotel-';
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

			$_ID = Hash::decryptToken($str->data_in($_EDIT['Id']));
			$name = $str->data_in($_EDIT['name']);
			$email = $str->data_in($_EDIT['email']);
			$telephone = $str->data_in($_EDIT['telephone']);
			$country = $str->data_in($_EDIT['country']);
			$city = $str->data_in($_EDIT['city']);
			$address = $str->data_in($_EDIT['address']);
			$rate = $str->data_in($_EDIT['rate']);
			$hotel_data_string = !Input::checkInput('full_details', 'post', 1) ? '' : $str->data_in($_EDIT['full_details']);

			$created_by = Session::get(Config::get('sessions/session_name'));

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
	                'name'         => $name,
	                'email'        => $email,
	                'telephone'    => $telephone,
	                'country'      => $country,
	                'city'         => $city,
	                'address'      => $address,
	                'rate'         => $rate,
	                'full_details' => $hotel_data_string,
	                'rate'         => $rate
	            );

				try {
					$controller->update("hotel", $_fields, $_ID);
					// UPDATING LOGS
					$operation = 'UPDATE_HOTEL';
					$comment = "HOTEL ID: ".$_ID;
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
				'SUCCESS_STRING' => "Hotel successfully updated",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function changeHotelStatus($status = 'ACTIVE')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'hotel-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_EDIT, array(

		));

		$created_by = Session::get(Config::get('sessions/session_name'));

		if ($validate->passed()) {

			$controller = new \Controller();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			$_RETURNED_MESSAGE_ = "Successfully activated";

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'status' => $status
				);

				try {
					$controller->update("hotel", $_fields, $_ID_);

					// UPDATING LOGS
					$operation = $status.' HOTEL';
					$comment = "HOTEL ID: ".$_ID_;
					Logs::newLog($operation, $comment, $created_by);
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
				'RETURNEDMESSAGE' => $_RETURNED_MESSAGE_,
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function getLastID($_table_, $key = 'id') // GET TABLE LAST ID
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT $key FROM {$_table_} ORDER BY $key DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->id;
		return false;
	}

	public static function getHotels($_filter_condition_, $order = "") // FETCH HOTELS
	{
		$FutureEventTable = new \FutureEvent();
		$FutureEventTable->selectQuery("SELECT * FROM hotel WHERE status != 'DELETED' $_filter_condition_ $order");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getHotelCount($_filter_condition_ = "")
	{
		$FutureEventTable = new \FutureEvent();
		$FutureEventTable->selectQuery("SELECT COUNT(id) as total_count FROM hotel WHERE status != 'DELETED' $_filter_condition_");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->total_count;
		return false;
	}


	// ADD ROOM
	public static function addRoom()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'hotel-';
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

			$hotel_id = Hash::decryptToken($str->data_in($_EDIT['hotel_id']));
			$room_type = $str->data_in($_EDIT['room_type']);
			$room_count = $str->data_in($_EDIT['room_count']);
			$room_price = $str->data_in($_EDIT['room_price']);
			$currency = $str->data_in($_EDIT['currency']);
			$room_occupancy = !Input::checkInput('room_occupancy', 'post', 1) ? '' : $str->data_in($_EDIT['room_occupancy']);
			$adults = !Input::checkInput('adults', 'post', 1) ? '' : $str->data_in($_EDIT['adults']);
			$children = !Input::checkInput('children', 'post', 1) ? '' : $str->data_in($_EDIT['children']);
			$bed_type = !Input::checkInput('bed_type', 'post', 1) ? '' : $str->data_in($_EDIT['bed_type']);
			$room_description = !Input::checkInput('room_description', 'post', 1) ? '' : $str->data_in($_EDIT['room_description']);
			$room_data_string = !Input::checkInput('full_details', 'post', 1) ? '' : $str->data_in($_EDIT['full_details']);

			/** Upload photo */
			$photo = '';
			if (isset($_FILES['photo']))
				if ($_FILES['photo']['name'] != "")
					$photo = Functions::fileUpload(Config::get('filepath/image').'hotel/room/', $_FILES['photo']);

			$created_by = Session::get(Config::get('sessions/session_name'));

			$_ID = self::getLastID('hotel_room') + 1;

			/** Check If Room Exitst  */
			if (self::checkIfRoomExists($hotel_id, $room_type)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This room is already registered",
					'ERRORS_STRING' => "This room is already registered"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
	                'hotel_id'         => $hotel_id,
	                'room_type'        => $room_type,
	                'room_count'       => $room_count,
	                'room_price'       => $room_price,
	                'currency'         => $currency,
	                'room_occupancy'   => $room_occupancy,
	                'adults'           => $adults,
	                'children'         => $children,
	                'bed_type'         => $bed_type,
	                'room_description' => $room_description,
	                'room_photo'       => $photo,
	                'full_details'     => $room_data_string,
	                'added_date'       => date('Y-m-d H:i:s')
	            );

				try {
					$controller->create("hotel_room", $_fields);
					// UPDATING LOGS
					$operation = 'NEW HOTEL ROOM';
					$comment = "HOTEL ROOM ID: ".$_ID;
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
				'SUCCESS_STRING' => "Hotel successfully created",
				'ERRORS_STRING' => ""
			];
		}
	}


	// EDIT ROOM
	public static function updateRoom()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'hotel-';
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

			$_ID = Hash::decryptToken($str->data_in($_EDIT['Id']));
			$hotel_id = Hash::decryptToken($str->data_in($_EDIT['hotel_id']));
			$room_type = $str->data_in($_EDIT['room_type']);
			$room_count = $str->data_in($_EDIT['room_count']);
			$room_price = $str->data_in($_EDIT['room_price']);
			$currency = $str->data_in($_EDIT['currency']);
			$room_occupancy = !Input::checkInput('room_occupancy', 'post', 1) ? '' : $str->data_in($_EDIT['room_occupancy']);
			$adults = !Input::checkInput('adults', 'post', 1) ? '' : $str->data_in($_EDIT['adults']);
			$children = !Input::checkInput('children', 'post', 1) ? '' : $str->data_in($_EDIT['children']);
			$bed_type = !Input::checkInput('bed_type', 'post', 1) ? '' : $str->data_in($_EDIT['bed_type']);
			$room_description = !Input::checkInput('room_description', 'post', 1) ? '' : $str->data_in($_EDIT['room_description']);
			$room_data_string = !Input::checkInput('full_details', 'post', 1) ? '' : $str->data_in($_EDIT['full_details']);

			$created_by = Session::get(Config::get('sessions/session_name'));

			/** Check If Room Exitst  */
			if (self::checkIfRoomExists($hotel_id, $room_type, $_ID)) :
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This room is already registered",
					'ERRORS_STRING' => "This room is already registered"
				];
			endif;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
	                'room_type'        => $room_type,
	                'room_count'       => $room_count,
	                'room_price'       => $room_price,
	                'currency'         => $currency,
	                'room_occupancy'   => $room_occupancy,
	                'adults'           => $adults,
	                'children'         => $children,
	                'bed_type'         => $bed_type,
	                'room_description' => $room_description,
	                'full_details'     => $room_data_string,
	            );

				try {
					$controller->update("hotel_room", $_fields, $_ID);
					// UPDATING LOGS
					$operation = 'UPDATE_ROOM';
					$comment = "ROOM ID: ".$_ID;
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
				'SUCCESS_STRING' => "Room updated",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function changeRoomStatus($status = 'ACTIVE')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'hotel-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_EDIT, array(

		));

		$created_by = Session::get(Config::get('sessions/session_name'));

		if ($validate->passed()) {

			$controller = new \Controller();

			$str = new \Str();

			/** Get Id */
			$_ID_ = Hash::decryptToken($str->data_in($_EDIT['Id']));

			/** Check If Valid $_PID_ And Exists In Participant Table */
			if (!is_integer($_ID_)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			$_RETURNED_MESSAGE_ = "Successfully activated";

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'status' => $status
				);

				try {
					$controller->update("hotel_room", $_fields, $_ID_);

					// UPDATING LOGS
					$operation = $status.' ROOM';
					$comment = "ROOM ID: ".$_ID_;
					Logs::newLog($operation, $comment, $created_by);
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
				'RETURNEDMESSAGE' => $_RETURNED_MESSAGE_,
				// 'PARTICIPATIONPAYMENTTYPE'=> $_PARTICIPATION_PAYMENT_TYPE_,
				'ERRORS_STRING' => ""
			];
		}
	}


	public static function checkIfRoomExists($hotel_id, $room_type, $ID = null)
	{
		$SQL_Condition_ = ($ID == null) ? '' : " AND id != {$ID} ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id FROM hotel_room WHERE hotel_id =? AND room_type =?  $SQL_Condition_ ORDER BY id DESC LIMIT 1 ", array($hotel_id, $room_type));
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	// FETCH ROOMS
	public static function getRooms($_filter_condition_, $order = "")
	{
		$FutureEventTable = new \FutureEvent();
		$FutureEventTable->selectQuery("SELECT hotel_room.*, hotel.name as hotel_name FROM hotel_room INNER JOIN hotel ON hotel_room.hotel_id = hotel.id WHERE hotel_room.status != 'DELETED'  $_filter_condition_ $order");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getRoomsCount($_filter_condition_ = "")
	{
		$FutureEventTable = new \FutureAccount();
		$FutureEventTable->selectQuery("SELECT COUNT(hotel_room.id) as total_count FROM hotel_room INNER JOIN hotel ON hotel_room.hotel_id = hotel.id WHERE hotel_room.status != 'DELETED' $_filter_condition_ ORDER BY hotel_room.id DESC");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->total_count;
		return false;
	}


	// BOOKING
	public static function getBookingParticipantsByEventID($eventID, $condition = "", $order = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$_SQL_Condition_ .= " AND hotel_booking_transaction.transaction_status !=  'IGNORED' ";
		// $_SQL_Condition_ .= " AND hotel_booking_transaction.transaction_status =  'PENDING' AND hotel_booking_transaction.delayed_payment_reminder_status != 1";
		$FutureEventTable = new FutureEvent();
		$SQL_ = "SELECT 
		future_participants.group_id, 
		future_participants.group_admin_state, 
		hotel_booking_transaction.id as id, 
		hotel_booking_transaction.receipt_id as receipt_id, 
		hotel_booking_transaction.transaction_id as transaction_id, 
		hotel_booking_transaction.transaction_time as transaction_time, 
		hotel_booking_transaction.transaction_token as transaction_token, 
		hotel_booking_transaction.transaction_status as transaction_status, 
		hotel_booking_transaction.external_transaction_id as external_transaction_id,
		hotel_booking_transaction.payment_method as payment_method, 
		hotel_booking_transaction.payment_operator as payment_operator, 
		hotel_booking_transaction.amount as transaction_amount,
		hotel_booking_transaction.currency as transaction_currency,
		hotel_booking_transaction.payment_id as payment_id,
		hotel_booking_transaction.callback_time as callback_time,
		hotel_booking_transaction.approval_time as approval_time,
		hotel_booking_transaction.approval_comment as approval_comment,
		hotel_booking_transaction.delayed_payment_reminder_status as delayed_payment_reminder_status, 
		
		future_participants.id as participant_ID, 
		future_participants.firstname as participant_firstname, 
		future_participants.lastname as participant_lastname, 
		future_participants.email as participant_email, 
		future_participants.organisation_name as participant_organization_name, 
		future_participants.job_title as participant_job_title, 
		hotel.name as hotel_name,  
		hotel_room.room_type as room_type, 
		hotel_room.room_price as room_price, 
		hotel_room.currency as room_currency 
		FROM hotel_booking_transaction
		INNER JOIN future_participants ON hotel_booking_transaction.participant_id = future_participants.id
		INNER JOIN hotel ON future_participants.hotel_id = hotel.id 
		INNER JOIN hotel_room ON future_participants.room_id = hotel_room.id 
		WHERE future_participants.event_id = $eventID $_SQL_Condition_ 
		GROUP BY hotel_booking_transaction.participant_id DESC $order";

		$FutureEventTable->selectQuery($SQL_);
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getBookingParticipantsCounterByEventID($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$_SQL_Condition_ .= " AND hotel_booking_transaction.transaction_status !=  'IGNORED' ";
		$FutureEventTable = new FutureEvent();
		$SQL_ = "SELECT 
		future_participants.group_id, 
		future_participants.group_admin_state, 
		hotel_booking_transaction.id as id, 
		hotel_booking_transaction.receipt_id as receipt_id, 
		hotel_booking_transaction.transaction_id as transaction_id, 
		hotel_booking_transaction.transaction_time as transaction_time, 
		hotel_booking_transaction.transaction_token as transaction_token, 
		hotel_booking_transaction.transaction_status as transaction_status, 
		hotel_booking_transaction.external_transaction_id as external_transaction_id,
		hotel_booking_transaction.payment_method as payment_method, 
		hotel_booking_transaction.payment_operator as payment_operator, 
		hotel_booking_transaction.amount as transaction_amount,
		hotel_booking_transaction.currency as transaction_currency,
		hotel_booking_transaction.payment_id as payment_id,
		hotel_booking_transaction.callback_time as callback_time,
		hotel_booking_transaction.approval_time as approval_time,
		hotel_booking_transaction.approval_comment as approval_comment,
		hotel_booking_transaction.delayed_payment_reminder_status as delayed_payment_reminder_status, 
		
		future_participants.id as participant_ID, 
		future_participants.firstname as participant_firstname, 
		future_participants.lastname as participant_lastname, 
		future_participants.email as participant_email, 
		future_participants.organisation_name as participant_organization_name, 
		future_participants.job_title as participant_job_title, 
		hotel.name as hotel_name,  
		hotel_room.room_type as room_type, 
		hotel_room.room_price as room_price, 
		hotel_room.currency as room_currency 
		FROM hotel_booking_transaction
		INNER JOIN future_participants ON hotel_booking_transaction.participant_id = future_participants.id
		INNER JOIN hotel ON future_participants.hotel_id = hotel.id 
		INNER JOIN hotel_room ON future_participants.room_id = hotel_room.id 
		WHERE future_participants.event_id = $eventID $_SQL_Condition_ 
		GROUP BY hotel_booking_transaction.participant_id DESC
		ORDER BY hotel_booking_transaction.id DESC ";
		$FutureEventTable->selectQuery($SQL_);
		return $FutureEventTable->count();
		return false;
	}

}
?>