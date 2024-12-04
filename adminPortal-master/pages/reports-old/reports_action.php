<?php
require_once "../../core/init.php";
if (!$user->isLoggedIn())
	// Redirect::to('login');

	$valid['success'] = array('success' => false, 'messages' => array());


// $_POST = array(
//     'Id'              => "37393835343534353735",
//     'participant'     => "575466473478665933714e305743476f4c4d3674446a4341657a5a346a4178657748644c7250354f615463",
//     'eventId'   	  => "386452724a46643151372f64686f714e5a4a496d6454644f5231566e6376455446732f724c424363563741",

// 	'receipt'	=> '111111',
// 	'approval_comment' => 'ok',

//     'request' => 'approveBankTransferTransaction',
// );


/** Load all participants table */
if (Input::checkInput('request', 'post', 1)) :
	$_post_request_ = Input::get('request', 'post');
	switch ($_post_request_):

			/** Table - Display the list of Participant Registered */
		case 'fetchParticitants':
			/** Filter Condition */
			$_filter_condition_ = "";

			/** Filter By Participation Type */
			$_EVENT_ID_ 				   = Input::get('eventId', 'post');
			$_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
			$_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');

			$_STATUS_  = Input::get('status', 'post');


			if ($_PARTICIPATION_TYPE_TOKEN_ != '') :
				$_TYPE_ID_	 				= Hash::decryptToken($_PARTICIPATION_TYPE_TOKEN_);
				if (is_integer($_TYPE_ID_))
					$_filter_condition_    .= " AND future_participants.participation_type_id = $_TYPE_ID_ ";
			endif;

			if ($_PARTICIPATION_SUBTYPE_TOKEN_ != '') :
				$_SUBTYPE_ID_   	 	    = Hash::decryptToken($_PARTICIPATION_SUBTYPE_TOKEN_);
				if (is_integer($_SUBTYPE_ID_))
					$_filter_condition_    .= " AND future_participants.participation_sub_type_id = $_SUBTYPE_ID_ ";
			endif;

			if ($_STATUS_ != '') :
				$_filter_condition_    .= " AND future_participants.status = '$_STATUS_' ";
			endif;

			echo $_LIST_DATA_ = ReportController::getTotalParticipantsByFilter($_EVENT_ID_, $_filter_condition_);

			break;

			/** Filter Subtype By Type */
		case 'filterParticipationSubType':
			$_EVENT_ID_  = Input::get('eventId', 'post');
			$_TYPE_ID_   = Hash::decryptToken(Input::get('type', 'post'));
			$_TYPE_DATA_ = FutureEventController::getPacipationSubType($_EVENT_ID_, $_TYPE_ID_, 'PAYABLE');
?>
			<option value="">- Select Participation Subtype -</option>
			<option value="">All</option>
			<?php
			if ($_TYPE_DATA_) :
				foreach ($_TYPE_DATA_ as $type_) :
			?>
					<option value="<?= Hash::encryptToken($type_->id) ?>"><?= $type_->name . ' ' . ($type_->name == '' ? '' : '/ ') ?> <?= $type_->category ?></option>
<?php
				endforeach;
			endif;

			break;

		case 'fetchMapData':
			$event_id = Input::get('eventId', 'post');

			$resPayCountryArray = array();

			$sql = "SELECT future_participants.id, future_participants.organisation_country FROM future_participants INNER JOIN future_payment_transaction_entry 
				ON future_participants.id=future_payment_transaction_entry.participant_id 
				WHERE future_participants.status='APPROVED' AND future_payment_transaction_entry.transaction_status='COMPLETED' AND future_participants.event_id=" . $event_id . "";
			$number_in_country = 0;
			$i = 0;
			try {
				$delegateData = DB::getInstance()->query($sql);

				foreach ($delegateData->results() as $resPayCountries) {
					$country = $resPayCountries->organisation_country;
					$number_in_country = ReportController::getTotalParticipantsByCountry($event_id, $country);
					$resPayCountryArray[$country] = $number_in_country;
					// echo $number_in_country;
				}
			} catch (Exception $e) {
			}

			echo json_encode($resPayCountryArray);

			break;
	endswitch;
endif;

?>