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

		/** Filter Subtype By Type */
		case 'filterParticipationSubType':
			$_TYPE_ID_   = Hash::decryptToken(Input::get('type', 'post'));
			$_TYPE_DATA_ = FutureEventController::getPacipationSubType($eventId, $_TYPE_ID_);
			?>
			<option value="">- Select Participation Subtype -</option>
			<option value="">All</option>
			<?php
			if($_TYPE_DATA_):
				foreach($_TYPE_DATA_ As $type_):
			?>
			<option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name.' '.($type_->name == ''?'':' ')?>
			</option>
			<?php
				endforeach;
			endif;

		break;

		/** Table - Display the list of Participant Registered */
		case 'fetchParticitants':
			/** Filter Condition */
			$_filter_condition_ = "";

			/** Local CBO Code  */
			$_CBO_CODE_ = 'C0015';

			/** Filter By Participation Type */
			$_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
			$_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');
			$_APPROVAL_STATUS_ 			   = Input::get('status', 'post');

			if ($_APPROVAL_STATUS_ != '') :
				$_filter_condition_    .= " AND future_participants.status = '$_APPROVAL_STATUS_' ";
			endif;

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

			$_COUNTRY_ 			           = Input::get('country', 'post');
			
			$_DATE_ = Input::get('added_date', 'post');

			$_DATE_1_  = Input::get('datefrom', 'post');
			$_DATE_2_  = Input::get('dateto', 'post');

            $_TODAY= date("Y-m-d");

			$_DATETIME_1_ = $_TODAY." 00:00:00";
			$_DATETIME_2_ = $_TODAY." 23:59:00";

			if($_DATE_ != ''):
				$_filter_condition_    .= " AND future_attendance.added_date = '$_DATE_' ";
			else:
				// $_filter_condition_    .= " AND future_participants.reg_date BETWEEN '$_DATETIME_1_' AND  '$_DATETIME_2_'";
				$_filter_condition_    .= " AND future_attendance.added_date = '$_TODAY' ";

			endif;

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
      		$totalRecords = AttendanceController::getParticipantsCountAttendance($eventId);

      		## Total number of records with filtering
      		$totalRecordwithFilter = AttendanceController::getParticipantsCountAttendance($eventId, $_filter_condition_);

      		## Fetch records
			$order = "ORDER BY future_participants.".$columnName." DESC LIMIT ".$row.",".$rowperpage;
			$partRecords = AttendanceController::getParticipantsAttendance($eventId, $_filter_condition_, $order);

			// Fetch records for data export
			$order = "ORDER BY future_participants.".$columnName." DESC";
			$exportRecords = AttendanceController::getParticipantsAttendance($eventId, $_filter_condition_, $order);
            Session::put('attendanceExportData', $exportRecords);


			if ($partRecords) {
				$count_ = 0;
				foreach($partRecords as $participant_) {
					$count_++;

					$partProfile = DN.'/participants/profile/'.Hash::encryptToken($participant_->id);
					$type_name = $participant_->participation_type_name;
					$subtype_name = $participant_->participation_subtype_name;

					// COUNTRY
					$participant_country_ = countryCodeToCountry($participant_->residence_country);

					// ACTION
					$part_name = $participant_->firstname . ' ' . $participant_->lastname;

		          	$data[] = array(
			          	"id" => $count_,
			            "firstname" => "<span style='color:#428bca'>$part_name</span>",
			            "pass_type" => $participant_->participation_type_name,
			            "pass_sub_type" => $participant_->participation_subtype_name,
			            "job_title" => $participant_->job_title,
			            "organisation_name" => $participant_->organisation_name,
			            "residence_country"  => $participant_country_,
			           	"location"  => $participant_->location,
			           	"added_time"  => $participant_->added_time
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

		/** Action - Export Attendance data */
		case 'exportAttendanceData':
			try{
                FutureDataExport::attendanceCSVReport(Session::get('attendanceExportData'));
            } catch(Exception $e){
                throw new Exception("An error occured while exporting this report");
            }
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