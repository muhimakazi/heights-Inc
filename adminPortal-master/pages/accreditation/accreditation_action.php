<?php
require_once "../../core/init.php";
// if (!Session::exists('user')) {
//     Redirect::to('login');
// }
if (!$user->isLoggedIn()) {
	Redirect::to('login');
}

$valid['success'] = array('success' => false, 'messages' => array());

/** Load all participants table */
if (Input::checkInput('request', 'post', 1)) :
	$_post_request_ = Input::get('request', 'post');
	switch ($_post_request_):

		/** Action - Confirm badge print */
		/** UPDATE PARTICIPANT CATEGORY */
		case 'confirmPrint':
			$_form_ = AccreditationController::updatePrintBadgeStatus(1);
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
		break;

		/** Action - Unconfirm badge print  */
		case 'unConfirmPrint':
			$_form_ = AccreditationController::updatePrintBadgeStatus(0);
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
		break;

			/** Action - Issue badge */
		case 'issueBadge':
			$_form_ = AccreditationController::updateIssueBadgeStatus(1);
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
		break;

			/** Action - Unissue badge */
		case 'unIssueBadge':
			$_form_ = AccreditationController::updateIssueBadgeStatus(0);
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
		break;

		/** Action - Check in attendance */
		case 'checkIn':
			$_form_ = AttendanceController::checkInAttendance();
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "$_form_->ERRORS_STRING";
            endif;
            echo json_encode($valid);
		break;

			/** Action - Confirm covid print */
		case 'confirmCovid':
			try {
				$participant_ID = Hash::decryptToken(Input::get('Id', 'post'));
				$controller->update('future_participants', array('covid_status' => 'Negative', 'issue_badge_status' => 0), $participant_ID);
				$valid['success'] = true;
				$valid['messages'] = "Successfully Updated";
			} catch (Exception $error) {
				$valid['success'] = false;
				$valid['messages'] = "Error while updating";
			}
			echo json_encode($valid);
			break;

			/** Action - Unconfirm covid print  */
		case 'unConfirmCovid':
			try {
				$participant_ID = Hash::decryptToken(Input::get('Id', 'post'));
				$controller->update('future_participants', array('covid_status' => 'Unknown'), $participant_ID);
				$valid['success'] = true;
				$valid['messages'] = "Successfully Updated";
			} catch (Exception $error) {
				$valid['success'] = false;
				$valid['messages'] = "Error while updating";
			}
			echo json_encode($valid);
			break;

		/** Action - Add a new participant */
		case 'registration':
			$_form_ = FutureEventController::registerEventParticipant();
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "$_form_->ERRORS_STRING";
            endif;
            echo json_encode($valid);
		break;

			/** Table - Display the list of Participant Registered */
		case 'fetchParticitants':
			/** Filter Condition */
			$_filter_condition_ = "";

			$_filter_condition_    .= " AND future_participants.status = 'APPROVED'";
			$_filter_condition_    .= " AND future_participation_sub_type.category = 'INPERSON'";
			// $_filter_condition_    .= " AND future_participants.reg_date BETWEEN '2023-05-25 00:00:00' AND  '2023-05-25 23:59:00'";

			## Read value
			$draw            = Input::get('draw', 'post');
			$row             = Input::get('start', 'post');
			$rowperpage      = Input::get('length', 'post'); // Rows display per page
			$columnIndex     = Input::get('order', 'post')[0]['column']; // Column index
			$columnName      = Input::get('columns', 'post')[$columnIndex]['data']; // Column name
			$columnSortOrder = Input::get('order', 'post')[0]['dir']; // asc or desc
			$searchValue     = Input::get('search', 'post')['value']; // Search value

			## Search 
			if($searchValue != ''){
				$_filter_condition_ .= " AND (future_participants.firstname LIKE '%".$searchValue."%' 
				OR future_participants.lastname LIKE '%".$searchValue."%' 
				OR future_participants.organisation_name LIKE'%".$searchValue."%' 
				OR future_participation_type.name LIKE'%".$searchValue."%' 
				OR future_participation_sub_type.name LIKE'%".$searchValue."%')";
			}

			## Total number of records without filtering
            $totalRecords = FutureEventController::getTotalParticipantsCounterByFilter($eventId);

            ## Total number of records with filtering
            $totalRecordwithFilter = FutureEventController::getTotalParticipantsCounterByFilter($eventId, $_filter_condition_);

            ## Fetch records
			$order = "ORDER BY future_participants.".$columnName." DESC LIMIT ".$row.",".$rowperpage;
			$partRecords = FutureEventController::getTotalParticipantsByFilter($eventId, $_filter_condition_, $order);

			if ($partRecords) {
				$count_ = 0;
				foreach($partRecords as $participant_) {
					$count_++;

					// STATUS
					$print_status_ = $participant_->print_badge_status;
					$issue_status_ = $participant_->issue_badge_status;
					$participation_type_id = $participant_->participation_type_id;
					$participation_sub_type_id = $participant_->participation_sub_type_id;
			
					if($print_status_ == 1) {
						$printStatus = "PRINTED";
						$print_status_label_ = 'badge-success';
					} else {
						$printStatus = "PENDING";
						$print_status_label_ = 'badge-warning';
					}
					if($issue_status_ == 1) {
						$issueStatus = "ISSUED";
						$issue_status_label_ = 'badge-success';
					} else {
						$issueStatus = "PENDING";
						$issue_status_label_ = 'badge-warning';
					}
					if (AttendanceController::hasAlreadyAttendedToday($eventId, $participant_->id, Time::getDate())) {
						$attendStatus = "CHECKEDIN";
						$attend_status_label_ = 'badge-success';
					} else {
						$attendStatus = "N/A";
						$attend_status_label_ = 'badge-default';
					}

					$type_name = $participant_->participation_type_name;
					$subtype_name = $participant_->participation_subtype_name;

					// PASS TYPE
					if ($type_name == 'CMPD') {
						$pass_type = $type_name." ".$subtype_name;
					} elseif ($type_name == 'Industry' || $type_name == 'FinTech Start-up' || $subtype_name == 'Application' || $subtype_name == 'Research' || $subtype_name == 'The Careers Forum') {
						$pass_type = $type_name;
					} elseif ($subtype_name == 'Government Official (Organising Team)') {
						$pass_type = 'Government Official';
					} else {
						$pass_type = $subtype_name;
					}

					// COUNTRY
					$participant_country_ = countryCodeToCountry($participant_->residence_country);

					// ACTION
					$noGuestPermission = $user->hasPermission('guest')?'none' :''; // FOR GUEST
					$edit_key = Hash::encryptToken($participant_->id);
					$part_name = $participant_->firstname .' '. $participant_->lastname;
					$profile_link = DN.'/participants/profile/'.$edit_key;
					$badge_link = DN.'/accreditation/badge/'.$participant_->qrCode;
					$action = "
					<div class='ibox-tools'>
	                    <a class='dropdown-toggle' data-toggle='dropdown' href='#' style='color: #3c8dbc;'>More</a>
	                    <ul class='dropdown-menu dropdown-user popover-menu-list' style='display: $noGuestPermission;'>";
							$action .= "<li><a class='menu confirm_modal' data-id='$edit_key' data-name='$part_name' data-request='issueBadge' style='color: green;'><i class='fa fa-check-circle'></i> Issue badge </a></li>";
							$action .= "<li><a class='menu' href='$badge_link'><i class='fa fa-id-badge'></i> Generate badge</a></li>"; // Badge
	                	$action .= 
	                    "</ul>
                	</div>";

                	$action = "
                    <div class='btn-group pull-right'>";
                    	if ($attendStatus != "CHECKEDIN"):
                        // $action .= "<button class='btn-white btn btn-xs confirm_modal text-red' data-id='$edit_key' data-name='$part_name' data-request='checkIn'>Checkin</button>";
                    		$action .= "<a class='confirm_modal text-red' data-id='$edit_key' data-name='$part_name' data-request='checkIn'>Checkin</a>";
                    	endif;
                        $action .= "
                    </div>";

	                $data[] = array(
	                	"id" => $count_,
	                	"reg_date" => date("j F Y", strtotime($participant_->reg_date)),
	                    "firstname" => $part_name,
	                    "pass_type" => $pass_type,
	                    "job_title" => $participant_->job_title,
	                    "organisation_name" => $participant_->organisation_name,
	                    "residence_country"  => $participant_country_,
	                   	// "print_status"  => "<span class='badge {$print_status_label_}' style='display: block;'>{$printStatus}</span>",
	                   	// "issue_status"  => "<span class='badge {$issue_status_label_}' style='display: block;'>{$issueStatus}</span>",
	                   	"attend_status"  => "<span class='badge {$attend_status_label_}' style='display: block;'>{$attendStatus}</span>",
	                   	"action"  => $action
	                );
	            }
	        } else {
	        	 $data = array();
	        }

			## Response
			$response = array(
			    "draw" => intval($draw),
			    "iTotalRecords" => $totalRecords,
			    "iTotalDisplayRecords" => $totalRecordwithFilter,
			    "aaData" => $data
			);

			echo json_encode($response);

			
			break;

			/** Filter Subtype By Type */
		case 'filterParticipationSubType':
			$_TYPE_ID_   = Hash::decryptToken(Input::get('type', 'post'));
			$_TYPE_DATA_ = FutureEventController::getPacipationSubType($eventId, $_TYPE_ID_);
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
	endswitch;
endif;

?>