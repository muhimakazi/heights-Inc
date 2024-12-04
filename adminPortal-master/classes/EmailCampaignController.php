<?php
class EmailCampaignController
{
	public static function getParticipantsByEventID($eventID, $condition = "", $limit = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$FutureEventTable = new FutureEvent();
		$FutureEventTable->selectQuery("SELECT id, firstname, lastname, email FROM future_participants WHERE event_id = {$eventID} $_SQL_Condition_  ORDER BY id DESC $limit");
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	public static function getPaymentParticipantsByEventID($eventID, $condition = "")
	{
		$_SQL_Condition_ = $condition == "" ? "" : " $condition ";
		$_SQL_Condition_ .= " AND future_payment_transaction_entry.transaction_status !=  'IGNORED' ";
		$_SQL_Condition_ .= " AND future_payment_transaction_entry.transaction_status =  'PENDING' AND future_payment_transaction_entry.delayed_payment_reminder_status != 1";
		$_SQL_Condition_ .= " AND future_participants.status != 'APPROVED'";
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
		if ($FutureEventTable->count())
			return $FutureEventTable->data();
		return false;
	}

	//SEND CAMPAIGN
	public static function processEmailQueue ($eventID, $condition = "", $limit = "") 
	{
	    $_LIST_DATA_        = self::getParticipantsByEventID($eventID, $condition, $limit);
	    if (!$_LIST_DATA_):
	      Danger("No participant recorded");
	    else:
	      $count_ = 0;
	      $url_registration = FutureEventController::getEventEndPointUrlRegistation($eventID);

	      foreach( $_LIST_DATA_ as $participant_): $count_++;
	        $_ID_      = $participant_->id;
	        $firstname = $participant_->firstname;
	        $lastname  = $participant_->lastname;
	        $email     = $participant_->email;

	        echo 'Email sent to '.$firstname.' '.$lastname.'<br>';

    		$accommodation_link = 'https://iff.torusguru.com/update/profile/'.Hash::encryptAuthToken($_ID_);

	        $_data_ = array(
	          'email' => $email,
	          'firstname' => $firstname,
	          'payment_link' => Config::get('server/name')."/payment/72537830355561353569524159752f34305335434e5058483930383941466869446c30794759395841566f",
	          'payment_receipt_link' => '',
	          'invitation_letter_link' => '',
	          'participant_code' => 'TRS052313007671',
	          'generated_link' => 'iff.torusguru.com/register',
	          'password' => 'QwerTyu',
	          'system_link' => Config::get('url/group_admin_portal'),
	          'category' => 'CMPD',
	          'event_url' =>  Config::get('url/group_admin_portal'),
	          'group_name' => 'GROUP NAME',
	          'group_admin_name' => 'ADMIN NAME',
	          'approval_status' => 'Approved',
	          'accommodation_link' => $accommodation_link,
	          'cmpd_invite_link' => "https://iff.torusguru.com/cmpd/".Hash::encryptAuthToken(5628)."/".Hash::encryptAuthToken(99),
	        );

	        // IFF
	        // EmailController::sendEmailToParticipantForAccreditation($_data_);
	        // EmailController::sendEmailToParticipantForEventOpen($_data_);
	        // EmailController::sendEmailToParticipantForWorkshops($_data_);
	        // EmailController::sendEmailToParticipantForThankYou($_data_);
	        // EmailController::sendEmailToParticipantForPostForumSurvey($_data_);

	        // RICA
	        // EmailControllerRICA::sendCommunique($_data_);

	        // WASH
	        EmailControllerWorldBank::sendAgenda($_data_);

	        self::updateEmailStatus($_ID_);

	        sleep(3);
	      endforeach;

	    endif;
  	}

  	public static function processEmailQueueCareers ($eventID, $condition = "", $limit = "") 
	{
	    $_LIST_DATA_        = self::getParticipantsByEventID($eventID, $condition, $limit);
	    if (!$_LIST_DATA_):
	      Danger("No participant recorded");
	    else:
	      $count_ = 0;
	      $url_registration = FutureEventController::getEventEndPointUrlRegistation($eventID);

	      foreach( $_LIST_DATA_ as $participant_): $count_++;
	        $_ID_      = $participant_->id;
	        $firstname = $participant_->firstname;
	        $lastname  = $participant_->lastname;
	        $email     = $participant_->email;

	        echo 'Email sent to '.$firstname.' '.$lastname.'<br>';

    		$accommodation_link = 'https://iff.torusguru.com/update/profile/'.Hash::encryptAuthToken($_ID_);

	        $_data_ = array(
	          'email' => $email,
	          'firstname' => $firstname,
	          'payment_link' => Config::get('server/name')."/payment/72537830355561353569524159752f34305335434e5058483930383941466869446c30794759395841566f",
	          'payment_receipt_link' => '',
	          'invitation_letter_link' => '',
	          'participant_code' => 'TRS052313007671',
	          'generated_link' => 'iff.torusguru.com/register',
	          'password' => 'QwerTyu',
	          'system_link' => Config::get('url/group_admin_portal'),
	          'category' => 'CMPD',
	          'event_url' =>  Config::get('url/group_admin_portal'),
	          'group_name' => 'GROUP NAME',
	          'group_admin_name' => 'ADMIN NAME',
	          'approval_status' => 'Approved',
	          'accommodation_link' => $accommodation_link,
	          'cmpd_invite_link' => "https://iff.torusguru.com/cmpd/".Hash::encryptAuthToken(5628)."/".Hash::encryptAuthToken(99),
	        );

	        // EmailController::sendEmailToParticipantForAccreditation($_data_);
	        // EmailController::sendEmailToParticipantForEventOpen($_data_);
	        EmailController::sendEmailToParticipantCareersForum($_data_);

	        self::updateEmailStatus($_ID_);

	        sleep(2);
	      endforeach;

	    endif;
  	}


  	public static function processEmailQueuePaymentReminder ($eventID, $condition = "") 
	{
	    $_LIST_DATA_        = self::getPaymentParticipantsByEventID($eventID, $condition);
	    if (!$_LIST_DATA_):
	      Danger("No participant recorded");
	    else:
	      $count_ = 0;

	      foreach( $_LIST_DATA_ as $payment_transaction_): $count_++;
	        $_ID_      = $payment_transaction_->id;
	        $firstname = $payment_transaction_->participant_firstname;
	        $lastname  = $payment_transaction_->participant_lastname;
	        $email     = $payment_transaction_->participant_email;
	        $participant_id = $payment_transaction_->participant_ID;

	        $_AUTH_TOKEN = Hash::encryptAuthToken($participant_id);

	        echo 'Email sent to '.$firstname.' '.$lastname.'<br>';

	        $_data_ = array(
				'email' => $email,
				'firstname' => $firstname,
				'payment_link' => "https://iff.torusguru.com/payment/".$_AUTH_TOKEN,
				'payment_receipt_link' => '',
				'invitation_letter_link' => '',
				'participant_code' => '',
			);

			//   echo "https://iff.torusguru.com/payment/".$_AUTH_TOKEN,
			EmailController::sendIFFPaymentReminder($_data_);

	        self::updateEmailPaymentReminderStatus($_ID_);

	        sleep(2);
	      endforeach;

	      echo $count_;

	    endif;
  	}

  	public static function updateEmailStatus($ID)
	{
		$ParticipantTable = new \FutureEvent();
		$fields = array(
			'urgent_status' => 1
		);
		$ParticipantTable->updateParticipant($fields, $ID);
	}

	public static function updateEmailPaymentReminderStatus($ID)
	{
		$PaymentTable = new \Payment();
		$_fields = array(
			'delayed_payment_reminder_status' => 1,
		);
		$PaymentTable->update($_fields, $ID);
	}
}