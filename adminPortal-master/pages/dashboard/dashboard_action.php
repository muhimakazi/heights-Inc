<?php
require_once "../../core/init.php"; 

if(!$user->isLoggedIn()) 
    Redirect::to('login');
  
$valid['success'] = array('success' => false, 'messages' => array());

/** Load all participants table */ 
if(Input::checkInput('request', 'post', 1)):
	$_post_request_ = Input::get('request', 'post');

    $StatefulParticipantData=array();

	switch($_post_request_):

		/** Table - Display the list of Participant Registered */
		case 'fetchRegistrationByDay':
	?>
<table class="table dataTables-example customTable">
    <thead>
        <tr>
            <th>Day</th>
            <th>Date</th>
            <th>Indutry</th>
            <th>FinTech</th>
            <th>CMPD</th>
            <th>Government</th>
            <th>Elev. Industry</th>
            <th>KIFC Industry</th>
            <th>FTS Industry</th>
            <th>Careers Forum</th>
            <th>Speakers</th>
            <th>Others</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
         	$start = '2023-03-16';
         	$end = '2023-06-22';
         	// $end = date("Y-m-d");
         	$format = 'Y-m-d';

		    $interval = new DateInterval('P1D');

		    $realEnd = new DateTime($end);
		    $realEnd->add($interval);

		    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
		    $count_ = 0;

		    foreach($period as $date) { 
		    	$count_++;
		        $date1 = $date->format($format); 
		        $date_time1 = $date1. ' 00:00:00';
                $date_time2 = $date1. ' 23:59:00';

                $industry = 0;
				$fintech  = 0;
				$cmpd  = 0;
				$government  = 0;
				$elev_industry = 0;
				$kifc_industry = 0;
				$fts_industry = 0;
				$career_forum = 0;
				$speaker = 0;
				$other   = 0;
				$total = 0;

                $getData = DB::getInstance()->query("SELECT `participation_type_id`, `participation_sub_type_id` FROM `future_participants` WHERE `event_id` = '$eventId' AND `reg_date` BETWEEN '".$date_time1."' AND  '".$date_time2."' ORDER BY DATE(reg_date) ASC");

                if (!$getData->count()) {
                 
                } else {
                    foreach($getData->results() as $participant_) {
                    	$total++;
                    	if ($participant_->participation_type_id == 36) {
                    		$industry++;
                    	} elseif ($participant_->participation_type_id == 37) {
                    		$fintech++;
                    	} elseif ($participant_->participation_type_id == 39) {
                    		$cmpd++;
                    	} elseif ($participant_->participation_type_id == 38) {
                    		$government++;
                    	} elseif ($participant_->participation_sub_type_id == 116) {
                    		$elev_industry++;
                    	} elseif ($participant_->participation_sub_type_id == 117) {
                    		$kifc_industry++;
                    	} elseif ($participant_->participation_sub_type_id == 118) {
                    		$fts_industry++;
                    	} elseif ($participant_->participation_sub_type_id == 122) {
                    		$career_forum++;
                    	} elseif ($participant_->participation_sub_type_id == 119 || $participant_->participation_sub_type_id == 128) {
                    		$speaker++;
                    	} else {
                    		$other++;
                    	}
                    }
                }       
	?>

        <tr class="gradeX">
            <td>
                <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                    <?=$count_;?> </span>
            </td>
            <td><?= date("j F Y", strtotime($date1))?> </td>
            <td><?= $industry?> </td>
            <td><?= $fintech?> </td>
            <td><?= $cmpd?> </td>
            <td><?= $government?> </td>
            <td><?= $elev_industry?> </td>
            <td><?= $kifc_industry?> </td>
            <td><?= $fts_industry?> </td>
            <td><?= $career_forum?> </td>
            <td><?= $speaker?> </td>
            <td><?= $other?> </td>
            <td><b style="color: #000;"><?= $total?></b></td>

        </tr>
        <?php 
			}
		?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    $('.dataTables-example').dataTable({
        responsive: true,
        order: [[1, 'desc']],
        "dom": 'T<"clear">lfrtip',
        "tableTools": {
            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
        }
    });
});
</script>
<?php
		break;

		/** Table - Display the list of Participant Registered */
		case 'fetchRegistrationByCountry':
			/** Filter Condition */
			$_filter_condition_ = "";

			$_PARTICIPATION_TYPE_TOKEN_    = Input::get('type', 'post');
			$_PARTICIPATION_SUBTYPE_TOKEN_ = Input::get('subtype', 'post');

			// $getCountryToatal = DB::getInstance()->query("SELECT `id` FROM `future_participants` WHERE `event_id` = '$eventId' $_filter_condition_  ORDER BY id ASC");

			// if($_COUNTRY_ != '' ):
            // 	echo $_COUNTRY_.': '.$getCountryToatal->count();
            // endif;


            // $_STATUS_  = Input::get('status', 'post');
            $_STATUS_  = 'APPROVED';

			$_COUNTRY_ 			           = Input::get('residence_country', 'post');
			
			$_DATE_1_  = Input::get('datefrom', 'post');
			$_DATE_2_  = Input::get('dateto', 'post');

            $_TODAY= date("Y-m-d H:i:s");

			$_DATETIME_1_ = $_DATE_1_." 00:00:00";
			$_DATETIME_2_ = $_DATE_2_." 23:59:00";

			$_DATE_ = 'All';

			if($_DATE_1_ != '' OR $_DATE_2_ != ''):
				if($_DATE_1_ != '' AND $_DATE_2_ != ''):
					$_filter_condition_    .= " AND future_participants.reg_date BETWEEN '$_DATETIME_1_' AND  '$_DATETIME_2_'";
					$_DATE_ = 'From '.date("j F Y", strtotime($_DATE_1_)).' to '.date("j F Y", strtotime($_DATE_2_));
				elseif($_DATE_1_ != '' AND $_DATE_2_ == ''):
					$_filter_condition_    .= " AND future_participants.reg_date BETWEEN '$_DATETIME_1_' AND '$_TODAY'";
					$_DATE_ = 'From '.date("j F Y", strtotime($_DATE_1_));
				
				elseif($_DATE_1_ == '' AND $_DATE_2_ != ''):
					$_filter_condition_    .= " AND future_participants.reg_date BETWEEN '$_TODAY' AND '$_DATETIME_2_'";
					$_DATE_ = 'Up to '.date("j F Y", strtotime($_DATE_2_));
				endif;
			endif;

			if($_STATUS_ != ''):
				$_filter_condition_    .= " AND future_participants.status = '$_STATUS_' ";
			endif;

			if($_PARTICIPATION_TYPE_TOKEN_ != '' ):
				$_TYPE_ID_	 				= Hash::decryptToken($_PARTICIPATION_TYPE_TOKEN_);
				if(is_integer($_TYPE_ID_))
					$_filter_condition_    .= " AND future_participants.participation_type_id = $_TYPE_ID_ ";
			endif;
			
			if($_PARTICIPATION_SUBTYPE_TOKEN_ != '' ):
				$_SUBTYPE_ID_   	 	    = Hash::decryptToken($_PARTICIPATION_SUBTYPE_TOKEN_);
				if(is_integer($_SUBTYPE_ID_))
					$_filter_condition_    .= " AND future_participants.participation_sub_type_id = $_SUBTYPE_ID_ ";
			endif;

			if($_COUNTRY_ != '' ):
				if ($_COUNTRY_ == 'Local') {
					$_filter_condition_    .= " AND future_participants.residence_country = 'RW' ";
				} else{
					$_filter_condition_    .= " AND future_participants.residence_country != 'RW' ";
				}
			endif;


            ?>
			<table class="table table-bordered  customTable">
			    <thead>
			        <tr>
			            <th>Date</th>
			            <th class="text-center">Rwanda</th>
			            <th class="text-center">Rest of the World</th>
			            <th class="text-center">Total</th>
			        </tr>
			    </thead>
			    <tbody>
			        <?php
			        	//REGISTRATION
						$rwanda = 0;
						$rwandaPercentage = 0;
						$world = 0;
						$worldPercentage = 0;
						$total = 0;

						//PRINTED
						$rwandaPrinted = 0;
						$rwandaPercentagePrinted = 0;
						$worldPrinted = 0;
						$worldPercentagePrinted = 0;
						$totalPrinted = 0;

						//ISSUED
						$rwandaIssued = 0;
						$rwandaPercentageIssued = 0;
						$worldIssued = 0;
						$worldPercentageIssued = 0;
						$totalIssued = 0;



		                $getData = DB::getInstance()->query("SELECT `id`, `residence_country`, `print_badge_status`, `issue_badge_status` FROM `future_participants` WHERE `event_id` = $eventId $_filter_condition_  ORDER BY id ASC");

		                if (!$getData->count()) {
		                 
		                } else {
		                    foreach($getData->results() as $participant_) {
		                    	$country = $participant_->residence_country;
		                    	$print_badge_status = $participant_->print_badge_status;
		                    	$issue_badge_status = $participant_->issue_badge_status;

		                    	if ($country == 'RW') {
		                    		$rwanda++;
		                    		$total++;
		                    		if ($print_badge_status == 1) {
		                    			$rwandaPrinted++;
		                    			$totalPrinted++;
		                    		}
		                    		if ($issue_badge_status == 1) {
		                    			$rwandaIssued++;
		                    			$totalIssued ++;
		                    		}
		                    	} else {
		                    		$world++;
		                    		$total++;
		                    		if ($print_badge_status == 1) {
		                    			$worldPrinted++;
		                    			$totalPrinted++;
		                    		}
		                    		if ($issue_badge_status == 1) {
		                    			$worldIssued++;
		                    			$totalIssued ++;
		                    		}
		                    	}
		                    }

		                    $rwandaPercentage = ($rwanda*100)/$total;
		                    $worldPercentage = ($world*100)/$total;
		                    $rwandaPercentagePrinted = ($rwandaPrinted*100)/$total;
		                    $worldPercentagePrinted = ($worldPrinted*100)/$total;
		                    $rwandaPercentageIssued = ($rwandaIssued*100)/$total;
		                    $worldPercentageIssued = ($worldIssued*100)/$total;
		                }       
				?>

			        <tr class="gradeX">
			            <td><b>APPROVED</b></td>
			            <td class="text-center"><?= $rwanda?> <span class="stat-percent text-info pull-right"><?=number_format($rwandaPercentage, 2)?>%</span></td>
			            <td class="text-center"><?= $world?> <span class="stat-percent text-info pull-right"><?=number_format($worldPercentage, 2)?>%</span></td>
			            <td class="text-center"><b style="color: #000;"><?= $total?></b></td>
			        </tr>

			        <tr class="gradeX">
			            <td><b>PRINTED</b></td>
			            <td class="text-center"><?= $rwandaPrinted ?>  <span class="stat-percent text-info pull-right"><?=number_format($rwandaPercentagePrinted, 2)?>%</span></td>
			            <td class="text-center"><?= $worldPrinted ?>  <span class="stat-percent text-info pull-right"><?=number_format($worldPercentagePrinted, 2)?>%</span></td>
			            <td class="text-center"><b style="color: #000;"><?= $totalPrinted ?></b></td>
			        </tr>

			        <tr class="gradeX">
			            <td><b>ISSUED</b></td>
			            <td class="text-center"><?= $rwandaIssued ?>  <span class="stat-percent text-info pull-right"><?=number_format($rwandaPercentageIssued, 2)?>%</span></td>
			            <td class="text-center"><?= $worldIssued ?>  <span class="stat-percent text-info pull-right"><?=number_format($worldPercentageIssued, 2)?>%</span></td>
			            <td class="text-center"><b style="color: #000;"><?= $totalIssued ?></b></td>
			        </tr>
			    </tbody>
			</table>

			<!-- <script>
			$(document).ready(function() {
			    $('.dataTables-example').dataTable({
			        responsive: true,
			        order: [[1, 'desc']],
			        "dom": 'T<"clear">lfrtip',
			        "tableTools": {
			            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
			        }
			    });
			});
			</script> -->
			<?php


		break;

		/** Table - Display the list of Participant Registered */
		case 'fetchAttendanceByCountry':
		?>
		<table class="table table-bordered customTable">
		    <thead>
		        <tr>
		            <th class="text-center">Day</th>
		            <th class="text-center">Date</th>
		            <th class="text-center">Rwanda</th>
		            <th class="text-center">Rest of the world</th>
		            <th class="text-center">Total</th>
		        </tr>
		    </thead>
		    <tbody>
        <?php 
	        $eventDate  = DB::getInstance()->query("SELECT `start_date`, `end_date` FROM `future_event` WHERE `id` = $eventId");
			$start_date = dateFormat($eventDate->first()->start_date);
			$end_date   = dateFormat($eventDate->first()->end_date);

         	$format = 'Y-m-d';
         	$_filter_condition_ = '';

		    $interval = new DateInterval('P1D');

		    $realEnd = new DateTime($end_date);
		    $realEnd->add($interval);

		    $period = new DatePeriod(new DateTime($start_date), $interval, $realEnd);
		    $count_ = 0;

		    foreach($period as $date) { 
		    	$count_++;
		        $_TODAY = $date->format($format); 

                //REGISTRATION
				$rwanda = 0;
				$rwandaPercentage = 0;
				$world = 0;
				$worldPercentage = 0;
				$total = 0;

				$_filter_condition_ = " AND future_attendance.added_date = '$_TODAY' ";

                $_LIST_DATA_ = AttendanceController::getParticipantsAttendance($eventId, $_filter_condition_);

                if (!$_LIST_DATA_) {
                 
                } else {
                    foreach($_LIST_DATA_ as $participant_) {
                			$country = $participant_->residence_country;
                    	if ($country == 'RW') {
                    		$rwanda++;
                    		$total++;
                    	} else {
                    		$world++;
                    		$total++;
                    	}
                    }

                    $rwandaPercentage = ($rwanda*100)/$total;
                    $worldPercentage = ($world*100)/$total;
                }       
		?>

		        <tr class="gradeX">
		            <td class="text-center">
		                <span>
		                    <?=$count_;?> </span>
		            </td>
		            <td class="text-center"><?= date("j F Y", strtotime($_TODAY))?> </td>
		            <td class="text-center"><?= $rwanda?> <b class="stat-percent text-success pull-right"><i class="fa fa-level-up pull-right"><?=number_format($rwandaPercentage, 2)?>%</i></b></td>
		            <td class="text-center"><?= $world?> <b class="stat-percent text-info pull-right"><i class="fa fa-level-up pull-right"><?=number_format($worldPercentage, 2)?>%</i></b></td>
		            <td class="text-center"><b style="color: #000;"><?= $total?></b></td>

		        </tr>
        <?php 
			}
		?>
		    </tbody>
		</table>
		<?php
		break;

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

	endswitch;
endif;		
	
?>