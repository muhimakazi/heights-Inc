<?php
class PaymentController
{
	public static function paymentTransactionRequest()
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'pay';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_PAY[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_PAY, array(

		));

		if ($validate->passed()) {
			$PaymentTable = new \Payment();
			$str = new \Str();

			/** Information */
			$eventtoken = $str->data_in($_PAY['eventId']);
			$authtoken = $str->data_in($_PAY['authtoken']);
			$DefaultPayment = strtoupper($str->data_in($_PAY['defaultMethod']));

			if ($DefaultPayment != 'CC' && $DefaultPayment != 'BT'):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			$participation_id = Hash::decryptAuthToken($authtoken);
			$event_id = Hash::decryptToken($eventtoken);

			/** Check If Valid $participation_id And Exists In Participant Table */
			if (!is_integer($participation_id)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid Data",
					'ERRORS_STRING' => "Invalid Data"
				];
			endif;

			/** Get Participant Details */
			if (!($_participant_data_ = FutureEventController::getEventParticipantDataByID($participation_id))):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];
			endif;

			if (($event_id != $_participant_data_->event_id)):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];
			endif;

			/** Get Participant Details */
			if (!(self::checkIfEventParticipantHasAlreadySuccessfullyPaid($event_id, $participation_id))):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "You have already paid",
					'ERRORS_STRING' => "You have already paid"
				];
			endif;

			$participant_token = Hash::encryptAuthToken($_participant_data_->id);
			$participation_type = $_participant_data_->participant_pass_type;

			$payment_method = '';
			$payment_operator = '';

			/** Payment Information */
			$amount = $_participant_data_->participation_subtype_price;
			$currency = $_participant_data_->participation_subtype_currency;

			$transaction_id = self::generateTransactionID($event_id, $participation_id);
			$transaction_source = 'WEB';
			$transaction_type = 'PAY_EVENT';
			$transaction_time = time();
			$transaction_token = self::generateTransationToken($transaction_id);
			$transaction_status = 'PENDING';

			if ($diagnoArray[0] == 'NO_ERRORS') {

				/** Initiate Payment Request - Create Payment Token */
				$PAY_REQ_DATA = array(
					'pay_amount' => $amount,
					'pay_currency' => $currency,
					'pay_transactionID' => $transaction_id,

					'customer_token' => $participant_token,

					'customer_token' => $participant_token,
					'customer_email' => $_participant_data_->participant_email,
					'customer_firstname' => $_participant_data_->participant_firstname,
					'customer_lastname' => $_participant_data_->participant_lastname,

					'service_description' => 'Payment to participate to IUCN Africa Protected Areas Congress (APAC) Event.',
					'service_date' => date('Y/m/d h:i', time()),
				);


				$PaymentHandler = new \PaymentHandler();
				$PAYMENT_REQ = $PaymentHandler->createToken($PAY_REQ_DATA, $DefaultPayment);

				if (!$PAYMENT_REQ):
					return (object)[
						'ERRORS' => true,
						'ERRORS_SCRIPT' => "Failed to initiate your payment request",
						'ERRORS_STRING' => "Failed to initiate your payment request"
					];
				endif;
				$PAYMENT_REQ = (object)$PAYMENT_REQ;

				$external_transaction_id = '';
				$external_transaction_token = '';

				if ($PAYMENT_REQ->Success):
					$external_transaction_id = $PAYMENT_REQ->TransRef;
					$external_transaction_token = $PAYMENT_REQ->TransToken;
				endif;

				$external_transaction_status = $PAYMENT_REQ->Result;

				$payment_request_cmd = '';
				$payment_request_time = time();

				$payment_id = '';
				$callback_cmd = '';
				$callback_time = 0;

				$_fields = array(
					'event_id' => $event_id,
					'participation_id' => $participation_id,
					'participant_token' => $participant_token,
					'participation_type_id' => 0,
					'participation_sub_type_id' => 0,

					'transaction_id' => $transaction_id,
					'transaction_source' => $transaction_source,
					'transaction_type' => $transaction_type,
					'transaction_time' => $transaction_time,
					'transaction_token' => $transaction_token,
					'transaction_status' => $transaction_status,

					'external_transaction_id' => $external_transaction_id,
					'external_transaction_token' => $external_transaction_token,
					'external_transaction_status' => $external_transaction_status,

					'payment_method' => $payment_method,
					'payment_operator' => $payment_operator,
					'amount' => $amount,
					'currency' => $currency,

					'payment_id' => $payment_id,
					'payment_request_cmd' => $payment_request_cmd,
					'payment_request_time' => $payment_request_time,
					'callback_cmd' => $callback_cmd,
					'callback_time' => $callback_time,

					'c_date' => time(),
				);

				$_payURL_ = NULL;
				try {
					$PaymentTable->insert($_fields);

					if ($PAYMENT_REQ->Success)
						$_payURL_ = $PaymentHandler->getInitiatedPaymentRequestUrl();

				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		}
		else {
			$diagnoArray[0] = 'ERRORS_FOUND';
			
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->errors(),
				'ERRORS_STRING' => ""
			];
		}
		else {
			return (object)[
				'ERRORS' => false,
				'SUCCESS' => true,
				'ERRORS_SCRIPT' => "",
				'AUTHTOKEN' => $authtoken,
				'PAYURL' => $_payURL_,
				'ERRORS_STRING' => ""
			];
		}
	}

	public static function updateBankTransferPaymentTransactionEntry($transaction_status = 'APPROVED')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'pay';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_PAY[end($ar)] = $val; 
			}
		}

		$validation = $validate->check($_PAY, array(

		));

		if ($validate->passed()) {
			$PaymentTable = new \Payment();
			$str = new \Str();

			/** Information */
			$payment_entry_id = Hash::decryptToken($str->data_in($_PAY['Id']));
			$participant_id = Hash::decryptAuthToken($str->data_in($_PAY['participant']));
			$receipt_id = NULL;
			$approval_comment = $str->data_in($_PAY['approval_comment']);


			/** Get Participant Details */
			if (!($_participant_data_ = FutureEventController::getEventParticipantDataByID($participant_id))):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];
			endif;

			/** Get Event ID */
			$event_id = $_participant_data_->event_id;
			$group_id = $_participant_data_->group_id;
			$group_admin_state = $_participant_data_->group_admin_state;

			/** When Approve - Receipt ID is required */
			if ($transaction_status == 'APPROVED'):
				$receipt_id = $str->data_in($_PAY['receipt']);

				if ($receipt_id == '' && strlen($receipt_id) < 5):
					return (object)[
						'ERRORS' => true,
						'ERRORS_SCRIPT' => "Provide valid receipt to approve bank transfer",
						'ERRORS_STRING' => "Provide valid receipt to approve bank transfer"
					];
				endif;

				/** Check If Receipt Not yet used */
				if (self::checkIfBankTransferReceiptAlreadyUsed($event_id, $participant_id, $receipt_id)):
					return (object)[
						'ERRORS' => true,
						'ERRORS_SCRIPT' => "Provide valid receipt to approve bank transfer",
						'ERRORS_STRING' => "Provide valid receipt to approve bank transfer"
					];
				endif;
			endif;

			$approval_state = $transaction_status;
			$approval_time = time();

			/** When transaction status :: APPROVED -- COMPLETED */
			$transactionstatus = $transaction_status == 'APPROVED' ? 'COMPLETED' : $transaction_status;

			if ($diagnoArray[0] == 'NO_ERRORS') {
				$PaymentHandler = new \PaymentHandler();

				$_fields = array(
					'receipt_id' => $receipt_id,
					'transaction_status' => $transactionstatus,
					'approval_time' => $approval_time,
					'approval_comment' => $approval_comment,
				);

				try {
					/** Update Participant Payment Transaction Entry */
					$PaymentTable->update($_fields, $payment_entry_id);

					/** Update Participant Registration Staus - Approved */
					FutureEventController::approveParticipantRegistrationStatus($participant_id, $transaction_status);

					/** Payment Invoice Link */
					$payment_receipt_link = FutureEventController::getPaymentReceiptPDFDocumentUrl($event_id, Hash::encryptAuthToken($payment_entry_id));

					/** Invitation Letter Link */
					$invitation_letter_link = FutureEventController::getInvitationLetterPDFDocumentUrl($event_id, Hash::encryptAuthToken($participant_id));

					/** Send Email */
					$_data_ = array(
						'email' => $_participant_data_->participant_email,
						'firstname' => $_participant_data_->participant_firstname,
						'payment_receipt_link' => $payment_receipt_link,
						'invitation_letter_link' => $invitation_letter_link,
						'payment_link' => Config::get('server/name') . '/payment/' . Hash::encryptAuthToken($participant_id)
					);

					if ($transactionstatus == 'COMPLETED'):
						if ($group_id > 0 && $group_admin_state == 1):
							/** Approve Group Registration And Group Admin Account With Credentials */
							$_POST = array(
								'eventId' => Hash::encryptAuthToken($event_id),
								'Id' => Hash::encryptAuthToken($group_id),
							);

							$_GROUP_REGISTRATION_APPROVAL_2_ = FutureEventGroupController::approval2GroupRegistration('APPROVED');
							if ($_GROUP_REGISTRATION_APPROVAL_2_->ERRORS == true):
								return (object)[
									'ERRORS' => true,
									'ERRORS_SCRIPT' => $_GROUP_REGISTRATION_APPROVAL_2_->ERRORS_STRING,
									'ERRORS_STRING' => $_GROUP_REGISTRATION_APPROVAL_2_->ERRORS_STRING
								];
							endif;

							/** Send Email To Group Admin For Confirmation */
							EmailController::sendEmailToGroupAdminOnGroupRegistrationPaymentCompleted($_data_);
						else:
							EmailController::sendEmailToParticipantAfterFullyCompletedRegistrationAndSuccessfulPayment($_data_);
						endif;
					elseif ($transactionstatus == 'REFUNDED'):
						EmailController::sendEmailToParticipantAfterPaymentRefunded($_data_);
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
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->errors(),
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

	public static function sendPaymentReminder($transaction_status = 'APPROVED')
	{
		$diagnoArray[0] = 'NO_ERRORS';
		$validate = new \Validate();
		$prfx = 'pay';
		foreach ($_POST as $index => $val) {
			$ar = explode($prfx, $index);
			if (count($ar)) {
				$_PAY[end($ar)] = $val;
			}
		}

		$validation = $validate->check($_PAY, array(

		));

		if ($validate->passed()) {
			$PaymentTable = new \Payment();
			$str = new \Str();

			/** Information */
			$payment_entry_id = Hash::decryptToken($str->data_in($_PAY['Id']));
			$participant_id = Hash::decryptAuthToken($str->data_in($_PAY['participant']));

			/** Get Participant Details */
			if (!($_participant_data_ = FutureEventController::getEventParticipantDataByID($participant_id))):
				return (object)[
					'ERRORS' => true,
					'ERRORS_SCRIPT' => "Invalid data",
					'ERRORS_STRING' => "Invalid data"
				];
			endif;

			/** Get Event ID */
			$event_id = $_participant_data_->event_id;

			/** When transaction status :: APPROVED -- COMPLETED */
			$transactionstatus = $transaction_status == 'APPROVED' ? 'COMPLETED' : $transaction_status;

			if ($diagnoArray[0] == 'NO_ERRORS') {
				$PaymentHandler = new \PaymentHandler();

				$_fields = array(
					'delayed_payment_reminder_status' => 1,
				);

				try {
					/** Update Participant Payment Transaction Entry */
					$PaymentTable->update($_fields, $payment_entry_id);

					/** Send Email */
					$_data_ = array(
						'email' => $_participant_data_->participant_email,
						'firstname' => $_participant_data_->participant_firstname,
					);
					EmailController::sendEmailToParticipantWhenBankTransferNotAppearedInIUCNAccount7DaysAfterRegistration($_data_);
				}
				catch (Exception $e) {
					$diagnoArray[0] = "ERRORS_FOUND";
					$diagnoArray[] = $e->getMessage();
				}
			}
		}
		else {
			$diagnoArray[0] = 'ERRORS_FOUND';
		}
		if ($diagnoArray[0] == 'ERRORS_FOUND') {
			return (object)[
				'ERRORS' => true,
				'ERRORS_SCRIPT' => $validate->errors(),
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

	public static function checkIfBankTransferReceiptAlreadyUsed($eventID, $participantID, $receipt_id)
	{
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT * FROM future_payment_transaction_entry WHERE event_id = {$eventID} AND  participant_id != {$participantID} AND receipt_id = '$receipt_id' ORDER BY id DESC ");
		if ($PaymentTable->count())
			return true;
		return false;
	}

	public static function checkIfEventParticipantHasAlreadySuccessfullyPaid($eventID, $participantID)
	{
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT * FROM future_payment_transaction_entry WHERE event_id = {$eventID} AND  participant_id = {$participantID} AND transaction_status = 'COMPLETED' ORDER BY id DESC ");
		if ($PaymentTable->count())
			return true;
		return false;
	}

	public static function generateTransactionID($event_id, $participation_id)
	{
		return "KIFC" . $event_id . "00-" . date("y") . $participation_id . self::getIncrCountEntries($event_id) . date("m") . date("d") . date("H") . date("i");
	}

	public static function generateTransationToken($transaction_id)
	{
		return md5(sha1($transaction_id . date('yh')));
	}

	public static function getIncrCountEntries($eventID)
	{
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT COUNT(id) As count_total FROM future_payment_transaction_entry WHERE event_id = {$eventID}  ORDER BY id DESC  ");
		if ($PaymentTable->count())
			return $PaymentTable->first()->count_total + 1;
		return 1;
	}


	public static function getPaymentStatsRegistrationByPaymentChannelCount($eventID, $payment_channel = '', $transaction_status = '', $condition = '')
	{
		$SQL_CONDITION_ = " future_payment_transaction_entry.event_id = {$eventID} ";
		$SQL_CONDITION_ .= " AND future_payment_transaction_entry.transaction_status !=  'IGNORED' ";
		if ($payment_channel == 'BANK_TRANSFER')
			$SQL_CONDITION_ .= " AND future_payment_transaction_entry.payment_method = 'BANK_TRANSFER' ";
		if ($payment_channel == 'ONLINE_PAYMENT')
			$SQL_CONDITION_ .= " AND future_payment_transaction_entry.payment_method != 'BANK_TRANSFER' ";
		if ($transaction_status != '')
			$SQL_CONDITION_ .= " AND future_payment_transaction_entry.transaction_status = '$transaction_status' ";
		if ($condition != '')
			$SQL_CONDITION_ .= " $condition ";

		// echo $SQL_CONDITION_;
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT COUNT(future_payment_transaction_entry.id) as total_count FROM future_payment_transaction_entry INNER JOIN future_participants ON future_participants.id = future_payment_transaction_entry.participant_id WHERE $SQL_CONDITION_ GROUP BY future_payment_transaction_entry.participant_id DESC ORDER BY future_payment_transaction_entry.id DESC ");
		if ($PaymentTable->count())
			return $PaymentTable->count();
		return 0;
	}

	public static function getPaymentStatsRegistrationByPaymentChannelAmount($eventID, $payment_channel = '', $transaction_status = '', $condition = '')
	{
		$SQL_CONDITION_ = " future_payment_transaction_entry.event_id = {$eventID} ";
		$SQL_CONDITION_ .= " AND future_payment_transaction_entry.transaction_status !=  'IGNORED' ";
		$SQL_CONDITION_ .= " AND future_payment_transaction_entry.transaction_status = 'COMPLETED' ";
		if ($payment_channel == 'BANK_TRANSFER')
			$SQL_CONDITION_ .= " AND future_payment_transaction_entry.payment_method = 'BANK_TRANSFER' ";
		if ($payment_channel == 'ONLINE_PAYMENT')
			$SQL_CONDITION_ .= " AND future_payment_transaction_entry.payment_method != 'BANK_TRANSFER' ";
		if ($transaction_status != '')
			$SQL_CONDITION_ .= " AND future_payment_transaction_entry.transaction_status = '$transaction_status' ";
		if ($condition != '')
			$SQL_CONDITION_ .= " $condition ";

		// echo $SQL_CONDITION_;
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT SUM(future_payment_transaction_entry.amount) as total_amount FROM future_payment_transaction_entry INNER JOIN future_participants ON future_participants.id = future_payment_transaction_entry.participant_id WHERE $SQL_CONDITION_  ORDER BY future_payment_transaction_entry.id DESC ");
		if ($PaymentTable->count())
			return $PaymentTable->first()->total_amount;
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

	public static function getPaymentTransactionEntryDataByParticipantID($eventID, $_participant_id_)
	{
		$PaymentTable = new Payment();
		$PaymentTable->selectQuery("SELECT future_payment_transaction_entry.payment_method,  future_payment_transaction_entry.amount, future_payment_transaction_entry.currency, future_payment_transaction_entry.transaction_status, future_payment_transaction_entry.receipt_id, future_payment_transaction_entry.transaction_id, future_payment_transaction_entry.external_transaction_id, future_payment_transaction_entry.transaction_time, future_payment_transaction_entry.approval_time, future_payment_transaction_entry.approval_comment, future_payment_transaction_entry.callback_time FROM `future_payment_transaction_entry` INNER JOIN future_participants ON future_participants.id = future_payment_transaction_entry.participant_id  WHERE future_payment_transaction_entry.event_id = {$eventID} AND  future_payment_transaction_entry.participant_id = {$_participant_id_} AND transaction_status != 'IGNORED'  ORDER BY future_payment_transaction_entry.id DESC LIMIT 1");
		if ($PaymentTable->count())
			return $PaymentTable->first();
		return false;
	}

}