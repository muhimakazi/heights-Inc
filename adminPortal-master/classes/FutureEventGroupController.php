<?php
class FutureEventGroupController
{
	public static function requestEventRegistrationGroup()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'group-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(

		));


		if ($validate->passed()) {
			$FutureEventGroupParticipantTable = new \FutureEventGroup();

			$str = new \Str();

			/** Contact Information */
			$firstname = $str->data_in($_EDIT['firstname']);
			$lastname = $str->data_in($_EDIT['lastname']);
			$email = $str->data_in($_EDIT['email']);
			$telephone = $str->data_in($_EDIT['full_telephone']);
			$subTotal = $str->data_in($_EDIT['subTotal']);

			$group_name = $str->data_in($_EDIT['group_name']);
			$group_description = !Input::checkInput('group_description', 'post', 1) ? '' : $str->data_in($_EDIT['group_description']);

			$request_auth_token = !Input::checkInput('request_auth_token', 'post', 1) ? '' : Hash::decryptAuthToken($str->data_in($_EDIT['request_auth_token']));
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventAuth']));

			/** Check If User Email Not yet Used */
			if (self::checkIfEmailAlreadyExists($email)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This Email address has been used",
					'ERRORS_STRING' => "This Email address has been used"
				];
			endif;

			if (self::checkIfEmailAlreadyUsed($event_id, $email)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "This Email address has been used",
					'ERRORS_STRING' => "This Email address has been used"
				];
			endif;

			/** Controller Slots */
			if (!Input::checkInput('maximum_delegates', 'post', 1) && !Input::checkInput('participation_category', 'post', 1) && !Input::checkInput('participation_sub_category', 'post', 1))
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Please provide at least on group slot",
					'ERRORS_STRING' => "Please provide at least on group slot"
				];

			$_slot_maximum_delegates_array_ = !Input::checkInput('maximum_delegates', 'post', 1) ? 0 : $_EDIT['maximum_delegates'];
			$_slot_participation_category_array_ = !Input::checkInput('participation_category', 'post', 1) ? 0 : $_EDIT['participation_category'];
			$_slot_participation_subcategory_array_ = !Input::checkInput('participation_sub_category', 'post', 1) ? 0 : $_EDIT['participation_sub_category'];

			if (count($_slot_maximum_delegates_array_) < 1 || count($_slot_participation_category_array_) < 1 || count($_slot_participation_subcategory_array_) < 1)
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Please provide at least one group slot",
					'ERRORS_STRING' => "Please provide at least one group slot"
				];

			if ($subTotal > 0){
				$payment_state = 'PAYABLE';
				$status = 'PENDING';
			} else {
				$payment_state = 'FREE';
				$status = 'APPROVED';
			}

			if ($diagnoArray[0] == 'NO_ERRORS') {
				$group_code = self::generateGroupCode($event_id);
				$created_by = Session::get(Config::get('sessions/session_name'));

				$_fields = array(
					'event_id' => $event_id,
					'group_code' => $group_code,
					'group_name' => $group_name,
					'firstname' => $firstname,
					'lastname' => $lastname,
					'email' => $email,
					'telephone' => $telephone,
					'payment_state' => $payment_state,
					'group_description' => json_encode($_POST),
					'token' => self::generateCode($event_id . time() . $group_name),
					'status' => $status,
					'request_datetime' => time(),
					'approval1_datetime' => time(),
					'approval1_by' => $created_by,
				);

				try {
					$FutureEventGroupParticipantTable->insert($_fields);

					/** Get Last ID */
					$_group_ID = self::getLastID('future_group_registration');
					$_group_ID_auth_token_ = Hash::encryptAuthToken($_group_ID);

					$_REGISTER_SLOT_RECORD_['slot-event_id'] = $event_id;
					$_REGISTER_SLOT_RECORD_['slot-group_id'] = $_group_ID;

					$pass_details = "";

					/** Fetch Array Slot */
					$count_slot = 0;
					foreach ($_slot_maximum_delegates_array_ as $key => $value):
						if ($_slot_maximum_delegates_array_[$key] && $_slot_participation_category_array_[$key] && $_slot_participation_subcategory_array_[$key]):

							/** Registration for Slot */
							$_REGISTER_SLOT_RECORD_['slot-maximum_delegates'] = $_slot_maximum_delegates_array_[$key];
							$_REGISTER_SLOT_RECORD_['slot-participation_type_id'] = $_slot_participation_category_array_[$key];
							$_REGISTER_SLOT_RECORD_['slot-participation_sub_type_id'] = $_slot_participation_subcategory_array_[$key];

							$PARTICIPATION_TYPE_DATA_ = FutureEventController::getPacipationCategoryByID($_slot_participation_category_array_[$key]);
							$pass_name = $PARTICIPATION_TYPE_DATA_->name;

							$pass_details .= $_slot_maximum_delegates_array_[$key] .' '. $pass_name. '<br>';

							self::addRegistrationGroupSlot($_REGISTER_SLOT_RECORD_);
							/** Registration for Slot */
							$count_slot++;
						endif;
					endforeach;

					// UPDATING LOGS
					$operation = 'NEW GROUP';
					$comment = "Group for: ".$group_name;
					Logs::newLog($operation, $comment, $created_by);

					$salt = Hash::salt(32);
					// $generate_password = Hash::randomPassword(8);
					$generate_password = md5(uniqid(rand()));
					$password = Hash::make($generate_password, $salt);

					if ($payment_state == "FREE") {
						$user = new \User();
						$user->create(array(
							'client_id'  => self::getClientID($event_id),
			                'group_id'   => $_group_ID,
			                'username'   => $email,
			                'firstname'  => $firstname,
			                'lastname'   => $lastname,
			                'password'   => $password,
			                'salt'       => $salt,
			                'joined'     => date('Y-m-d H:i:s'),
			                'group'      => 6,
			                'token'      => $generate_password,
			                'permission' => "Admin",
			                'status'     => "ACTIVE"
			            ));

			            $_data_ = array(
							'email' => $email,
							'firstname' => $firstname,
							'group_name' => $group_name,
							'group_code' => $group_code,
							'full_name' => $firstname . ' ' . $lastname,
							'telephone' => $telephone,
							'password' => $generate_password,
							'system_link' => DN,
						);

			            /** Send Email To Free Group Admin */
						EmailController::sendEmailToGroupAdminOnSubmitGroupRegistrationFree($_data_);
					} else {
						/** Auto Generate QR For Participant */
						$participantID = FutureEventController::getLastPacipatantID() + 1;
						$Qr_ = FutureEventController::generateQrID($event_id, $participantID);
						$participant_code = $Qr_->ID;

						/** Get First Group Slot */
						$_group_slot_data_ = self::getFirstSlotGroupDataByID($_group_ID);

						$participant_fields = array(
							'event_id' => $event_id,
							'participant_code' => $participant_code,
							'participation_type_id' => $_group_slot_data_->participation_type_id,
							'participation_sub_type_id' => $_group_slot_data_->participation_sub_type_id,
							'group_id' => $_group_ID,
							'group_admin_state' => 1,
							'firstname' => $firstname,
							'lastname' => $lastname,
							'email' => $email,
							'telephone' => $telephone,
							'organisation_name' => $group_name,
							'reg_date' => date('Y-m-d H:i:s'),
							'status' => 'PENDING',
							'qrID' => $Qr_->ID,
							'qrCode' => $Qr_->STRING,
						);

						try {
							$FutureEventParticipantTable = new \FutureEvent();
							$FutureEventParticipantTable->insertParticipant($participant_fields);
							/** Get Last Participant ID - Generate Auth Token */
							$_PID_ = FutureEventController::getLastPacipatantID();
							$_AUTH_TOKEN = Hash::encryptAuthToken($_PID_);

							/** Send Email To Payable Group Admin */
							$event_url =  FutureEventController::getEventEndPointUrlRegistation($event_id);

							$_data_ = array(
								'email' => $email,
								'firstname' => $firstname,
								'pass_details' => $pass_details,
								'payment_link' => $event_url."/payment/".$_AUTH_TOKEN,
							);

							/** Send Email To Free Group Admin */
							EmailController::sendEmailToGroupAdminOnSubmitGroupRegistrationPayable($_data_);
						}
						catch (Exception $e) {
							$diagnoArray[0] = "ERRORS_FOUND";
							$diagnoArray[] = $e->getMessage();
						}
					}
				}
				catch (Exception $e) {
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
				'ERRORS_STRING' => $diagnoArray
			];
		}
		else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'AUTHTOKEN' => $_group_ID_auth_token_,
				'ERRORS_SCRIPT' => "",
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function addRegistrationGroupSlot($_POST_)
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'slot-';
		foreach ($_POST_ as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$FutureEventGroupParticipantTable = new \FutureEventGroup();

			$str = new \Str();

			/** Contact Information */
			$event_id = $str->data_in($_EDIT['event_id']);
			$group_id = $str->data_in($_EDIT['group_id']);
			$participation_type_id = $str->data_in($_EDIT['participation_type_id']);
			$participation_sub_type_id = $str->data_in($_EDIT['participation_sub_type_id']);
			$maximum_delegates = $str->data_in($_EDIT['maximum_delegates']);

			/** Get Participation Sub Category  */
			$_EVENT_PARTICIPATION_TYPE_DATA_ = FutureEventController::getPacipationSubCategoryByID($participation_sub_type_id);

			/** Calculate Total Price */
			$currency = $_EVENT_PARTICIPATION_TYPE_DATA_->sub_type_currency;
			$unit_price = $_EVENT_PARTICIPATION_TYPE_DATA_->sub_type_price;
			$total_price = $unit_price * $maximum_delegates;

			$payment_state = $_EVENT_PARTICIPATION_TYPE_DATA_->sub_type_payment_state;

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'event_id' => $event_id,
					'group_id' => $group_id,
					'participation_type_id' => $participation_type_id,
					'participation_sub_type_id' => $participation_sub_type_id,
					'maximum_delegates' => $maximum_delegates,

					'payment_state' => $payment_state,
					'unit_price' => $unit_price,
					'total_price' => $total_price,
					'currency' => $currency,

					'token' => self::generateCode($event_id . $group_id . time()),
					'status' => 'ACTIVE',
					'creation_datetime' => time(),
				);

				try {
					$FutureEventGroupParticipantTable->insertSlot($_fields);
				}
				catch (Exception $e) {
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

	public static function approvalGroupRegistration($_STATUS_)
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'approval-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$FutureEventGroupParticipantTable = new \FutureEventGroup();

			$str = new \Str();

			/** Contact Information */
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));
			$group_id = Hash::decryptAuthToken($str->data_in($_EDIT['Id']));

			/** Get Group Details */
			$_group_data_ = self::getGroupDataByID($group_id);
			if (!$_group_data_)
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];

			/** Get First Group Slot */
			$_group_slot_data_ = self::getFirstSlotGroupDataByID($group_id);
			if (!$_group_slot_data_)
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];

			if ($_STATUS_ != 'ACCEPTED' and $_STATUS_ != 'DECLINED')
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data 22",
					'ERRORS_STRING' => "Invalid data 22"
				];

			$_session_admin_ID = Session::get(Config::get('sessions/session_name'));
			if ($diagnoArray[0] == 'NO_ERRORS') {

				$salt = Hash::salt(32);
				$generate_password = Functions::generateStrongPassword(6);
				$password = Hash::make($generate_password, $salt);

				$_fields = array(
					'password' => $password,
					'salt' => $salt,
					'token' => $generate_password,
					'status' => $_STATUS_,
					'approval1_datetime' => time(),
					'approval1_by' => $_session_admin_ID
				);

				try {
					$FutureEventGroupParticipantTable->update($_fields, $group_id);

					/** Create private Link for group admin */
					if ($_STATUS_ == 'ACCEPTED'):
						$_POST = array(
							'eventId' => Hash::encryptAuthToken($_group_data_->event_id),
							'paticipation_sub_type' => Hash::encryptAuthToken($_group_slot_data_->participation_sub_type_id),
							'groupId' => Hash::encryptAuthToken($_group_data_->id),
							'group_admin_state' => 1,

							'firstname' => $_group_data_->firstname,
							'lastname' => $_group_data_->lastname,
							'email' => $_group_data_->email,

							'plain_text_pwd' => $generate_password,
						);

						$_CREATE_PRIVATE_LINK_ = FutureEventController::createEventParticipantPrivateLink();
						if ($_CREATE_PRIVATE_LINK_->ERRORS == true)
							return (object)[
								'ERRORS' => true,
								'ERRORS_SCRIPT' => $_CREATE_PRIVATE_LINK_->ERRORS_STRING,
								'ERRORS_STRING' => $_CREATE_PRIVATE_LINK_->ERRORS_STRING
							];
					endif;
				}
				catch (Exception $e) {
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

	public static function approval2GroupRegistration($_STATUS_ = 'APPROVED')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'approval-';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_EDIT[end($ar)] = $val;
			}
		}
		// $_EDIT = $_POST;

		$validation = $validate->check($_EDIT, array(

		));

		if ($validate->passed()) {
			$FutureEventGroupParticipantTable = new \FutureEventGroup();

			$str = new \Str();

			/** Contact Information */
			$event_id = Hash::decryptAuthToken($str->data_in($_EDIT['eventId']));
			$group_id = Hash::decryptAuthToken($str->data_in($_EDIT['Id']));

			/** Get Group Details */
			$_group_data_ = self::getGroupDataByID($group_id);
			if (!$_group_data_)
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];

			if ($_STATUS_ != 'APPROVED')
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];

			if ($diagnoArray[0] == 'NO_ERRORS') {

				$_fields = array(
					'status' => $_STATUS_,
					'approval2_datetime' => time(),
				);

				try {
					$FutureEventGroupParticipantTable->update($_fields, $group_id);
				}
				catch (Exception $e) {
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

	public static function generateCode($STR)
	{
		$seconds = time();
		$token_hash = md5($seconds . sha1($STR));
		return $token_hash;
	}

	public static function generateGroupCode($event_ID)
	{
		$token_hash = 'EG00' . rand(20, 80) . $event_ID . (self::getLastID('future_group_registration') + 1) . '00' . rand(2, 8);
		return $token_hash;
	}

	public static function getLastID($_table_, $key = 'id')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT $key FROM {$_table_} ORDER BY $key DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->id;
		return false;
	}

	public static function checkIfEmailAlreadyUsed($event_ID, $email)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id FROM future_group_registration WHERE event_id = {$event_ID} AND email = '{$email}' ORDER BY id DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function checkIfEmailAlreadyExists($email)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id FROM `users` WHERE `username` = '{$email}' ORDER BY id DESC LIMIT 1 ");
		if ($FutureEventTable->count())
			return true;
		return false;
	}

	public static function getGroupByID($event_ID, $group_ID, $condition = '')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_group_registration.*, future_private_links.generated_link, SUM(future_group_registration_slots.maximum_delegates) As total_max_delegates, future_group_registration_slots.currency As currency, SUM(future_group_registration_slots.total_price) As total_group_amount FROM future_group_registration INNER JOIN future_group_registration_slots ON future_group_registration.id = future_group_registration_slots.group_id INNER JOIN future_private_links ON future_private_links.group_id = future_group_registration.id WHERE future_group_registration.id = ? AND future_group_registration.event_id = ? AND future_private_links.group_admin_state = 1 ORDER BY future_group_registration.id DESC LIMIT 1;", array($group_ID, $event_ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getGroupList($event_ID, $condition = '')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_group_registration.*   FROM future_group_registration WHERE future_group_registration.event_id = ? $condition ", array($event_ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getGroupDataByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_group_registration.*   FROM future_group_registration WHERE future_group_registration.id = ? ORDER BY id DESC LIMIT 1 ", array($ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getFirstSlotGroupDataByID($ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id, participation_sub_type_id, participation_type_id  FROM future_group_registration_slots WHERE future_group_registration_slots.group_id = ? ORDER BY id ASC LIMIT 1 ", array($ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}

	public static function getGroupGeneratedPrivateLinks($eventID, $search = '')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT 
		future_private_links.*, future_participation_sub_type.name As sub_type_name,  future_participation_sub_type.category As sub_type_category, 
		future_participation_type.name As type_name, future_group_registration.group_name As group_name, future_group_registration.group_code as group_code
		FROM future_private_links 
		INNER JOIN future_participation_sub_type ON future_private_links.participation_sub_type_id = future_participation_sub_type.id  
		INNER JOIN `future_participation_type` ON  future_participation_type.id = future_private_links.participation_type_id 
		INNER JOIN `future_group_registration` ON  future_group_registration.id = future_private_links.group_id
		WHERE future_private_links.event_id = {$eventID} ORDER BY future_private_links.id DESC");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}
	
	public static function getGroupName($event_ID, $group_ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT group_name FROM future_group_registration WHERE event_id = ? AND id = ? ", array($event_ID, $group_ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->group_name;
		return false;
	}

	public static function getClientID($event_ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT client_id FROM future_event WHERE id = ?", array($event_ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->client_id;
		return false;
	}

	public static function getStatsTotalMaxDelegateSlots($event_ID, $group_ID, $condition = '')
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT SUM(future_group_registration_slots.maximum_delegates) As total_max_delegates FROM future_group_registration_slots WHERE event_id = ? AND group_id = ? AND status = 'ACTIVE' ", array($event_ID, $group_ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->total_max_delegates;
		return 0;
	}

	public static function getStatsTotalDelegateRegistered($event_ID, $group_ID, $status = 'ALL')
	{
		$condition = ($status == 'APPROVED')? " AND status = 'APPROVED' ": "";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT COUNT(future_participants.id) As total_max_delegates FROM future_participants WHERE event_id = ? AND group_id = ? $condition ", array($event_ID, $group_ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->first()->total_max_delegates;
		return 0;
	}

	public static function getEventPaymentCurrency($eventID)
	{
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT future_participation_sub_type.currency FROM `future_participation_sub_type` INNER JOIN future_participation_type ON future_participation_sub_type.participation_type_id = future_participation_type.id WHERE future_participation_sub_type.payment_state = 'PAYABLE' AND future_participation_type.event_id = {$eventID} ORDER BY `future_participation_sub_type`.`id` ASC LIMIT 1");
		if ($PaymentTable->count())
			return $PaymentTable->first()->currency;
		return 'USD';
	}

	public static function getGroupSlotsByGroupID($event_ID, $group_ID)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_group_registration_slots.*, future_participation_type.name as participation_type_name, future_participation_sub_type.name as participation_sub_type_name, future_participation_sub_type.category as participation_sub_type_category, (SELECT COUNT(id) As total FROM future_participants WHERE group_id = $group_ID AND participation_sub_type_id = future_participation_sub_type.id  ) As registered_delegates  FROM future_group_registration_slots INNER JOIN future_participation_type ON future_participation_type.id = future_group_registration_slots.participation_type_id INNER JOIN future_participation_sub_type ON future_participation_sub_type.id = future_group_registration_slots.participation_sub_type_id WHERE future_group_registration_slots.event_id = ? AND future_group_registration_slots.group_id = ? AND future_group_registration_slots.status = 'ACTIVE' ", array($event_ID, $group_ID));
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getGrouSlotByID($event_ID, $group_ID, $slot_id)
	{
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT future_group_registration_slots.*, future_participation_type.name as participation_type_name, future_participation_sub_type.name as participation_sub_type_name, future_participation_sub_type.category as participation_sub_type_category FROM future_group_registration_slots INNER JOIN future_participation_type ON future_participation_type.id = future_group_registration_slots.participation_type_id INNER JOIN future_participation_sub_type ON future_participation_sub_type.id = future_group_registration_slots.participation_sub_type_id WHERE future_group_registration_slots.event_id = ? AND future_group_registration_slots.group_id = ? AND future_group_registration_slots.id = ? AND future_group_registration_slots.status = 'ACTIVE' ", array($event_ID, $group_ID, $slot_id));
		if ($FutureEventTable->count())
			return $FutureEventTable->first();
		return false;
	}
}