<?php
require_once 'core/init.php';
require_once 'config/phpqrcode/qrlib.php';

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

// if(!Input::checkInput('authtoken_', 'get', 1))
// 	Redirect::to('dashboard');


$_QR_CODE_ = Input::get('authtoken_', 'get');
$_QR_ID_   = FutureEventController::decodeQrString($_QR_CODE_);

/** Find Participant By Qr ID */
// if(!($_participant_data_ = FutureEventController::getParticipantByQrID($_QR_ID_)))
// 	Redirect::to('404');

/** Find Participant By Category */
// if (!($_participants_data_ = FutureEventController::getParticipantByCategoryID(4, $activeEventId)))
// 	Redirect::to('404');

/** Find Participant By Category */
// if (!($_participants_data_ = FutureEventController::getVIPDataByCategoryID($activeEventId)))
// Redirect::to('404'); getNonRwandanDelegates

/** Find Non Rwanda delegates */
// if (!($_participants_data_ = FutureEventController::getNonRwandanDelegates($activeEventId)))
// 	Redirect::to('404');

$_filter_condition_ = "";
$limit = "";
$event_id = 17;

$today = date("Y-m-d");
$date_time1 = $today. ' 00:00:00';
$date_time2 = $today. ' 23:59:00';

$_filter_condition_ .= " AND future_participants.participation_sub_type_id = 134";
$_filter_condition_ .= " AND future_participants.status = 'APPROVED'";
$_filter_condition_ .= " AND `reg_date` BETWEEN '".$date_time1."' AND  '".$date_time2."'";
$_filter_condition_ .= " AND future_participants.print_badge_status = 0";

if (!($_participants_data_ = AccreditationController::getKGDParticipants($event_id, $_filter_condition_, $limit)))
    Redirect::to('404');
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <?php include 'includes/head.php';?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet"> 
	<style>
body {
	background-color: #c2d4d79e;
	font-size: 13px;
	overflow-x: hidden;
}
.card-container {
	box-shadow: 0px 0px 0px 0px #f1f1f1;
}
.card_profile_container {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%,-50%);
}
.card {
    background-color: #fff;
    border: 0 solid #eee;
    border-radius: 0;
    overflow: auto;
}
.card {
    margin-bottom: 30px;
    -webkit-box-shadow: 2px 2px 2px rgba(0,0,0,0.1), -1px 0 2px rgba(0,0,0,0.05);
    box-shadow: 2px 2px 2px rgba(0,0,0,0.1), -1px 0 2px rgba(0,0,0,0.05);
}
.card-profile .card-header {
    background-size: cover;
    background-position: center center
}
.card-header:first-child {
    border-radius: 0 0 0 0;
}
.card-header:first-child {
    border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
}
.card-header {
    padding-top: 90px;
    background-color: #fff;
    text-align: center;
    min-height: 680px;
}
.card-header h2{
	margin-top: 50px;
	font-size: 40px;
	line-height: 1.5;
	font-family: 'Montserrat', sans-serif;
	margin-right: 20px;
	margin-left: 20px;
}

	</style>
</head>

<body>
	<?php
	$title_color = "#ffffff!important;";
    $title_1_color = "#8ec54a!important;";
    $title_2_color = "#ffffff!important;";
    $category_background_color = "#7557d3!important;";
    $logo = "data_system/img/banner/APAC-LOGO-01.png";
    $banner_title = "data_system/img/banner/ACC-2023-01.jpeg";

    $_color = '#fff';

    
		?>

		<div class="container card_profile_container">
			<div class="row">
				<div class="col-lg-3"></div>
				<div class="col-lg-5">
					<div id="parent">
	<?php

	$_participants_data_ = json_encode($_participants_data_);
    $_participants_data_ = json_decode($_participants_data_);
    $counter = 0;
    $name_font = '35px';
    foreach ($_participants_data_ as $_participant_data_) {

    	// Update badge print status
    	// $_ID_ = $_participant_data_->id;
    	// AccreditationController::updatePrintBadgesStatus($_ID_);

	    // $_PARTICIPANT_FULL_NAME_   = $_participant_data_->firstname.' '.$_participant_data_->lastname;
		// $_COMPANY_NAME_			   = $_participant_data_->organisation_name;
		// $_PARTICIPANT_PROFILE_     = $_participant_data_->profile != null? VIEW_PROFILE.$_participant_data_->profile: "https://bootdey.com/img/Content/avatar/avatar7.png";

		// $_PARTICIPATION_SUB_TYPE_NAME_ = $_participant_data_->participation_subtype_name;


		/** Handle Qr COde */
		// $_qrID_		= $_participant_data_->qrID;
		// $_qrEncoded_= "https://system.torusguru.com/ebadge/$_participant_data_->qrCode";
		// $_DR_		= DN_IMG_QR;
		// $_qrFilename_= $_qrID_.".png";
		// $_qrFile_ 	= $_DR_.$_qrFilename_;
		// QRcode::png($_qrEncoded_, $_qrFile_);

		// $_PARTICIPANT_QR_IMAGE_    = VIEW_QR.'kGD_QR.png';

	    // if ($_PARTICIPATION_SUB_TYPE_NAME_ == 'Delegate') {
	    //     $category_background_color = "#7557d3!important;";
	    //     $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	    //     $_color = '#fff';
	    // } elseif ($_PARTICIPATION_SUB_TYPE_NAME_ == "Host") {
	    //     $category_background_color = "#d75033!important;";
	    //     $_color = '#fff';
	    //     $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	    // }  elseif ($_PARTICIPATION_SUB_TYPE_NAME_ == "Media") {
	    //     $category_background_color = "#e8c513!important;";
	    //     $_color = '#000';
	    //     $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	    // } elseif ($_PARTICIPATION_SUB_TYPE_NAME_ == "Team Kigali") {
	    //     $category_background_color = "#08b768!important;";
	    //     $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	    //     $_color = '#fff';
	    // } else {
	    //     $category_background_color = "#000000!important;";
	    //     $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	    //     $_color = '#fff';
	    // }

	    // $_PARTICIPANT_FULL_NAME_ = 'Gerville Jinky Bitrics R. Luistro';

	    // if (strlen($_PARTICIPANT_FULL_NAME_) > 13) {
	    // 	$name_font = '28px';
	    // }
	    // if (strlen($_PARTICIPANT_FULL_NAME_) > 20) {
	    // 	$name_font = '20px';
	    // }
	    ?>
						<div class="card card-profile">
							<div class="card-header">
								<img src="<?php linkto($banner_title); ?>" style="width: 100%;">
								<h2><?=$_participant_data_->firstname?>
								</h2>
							</div>
						</div>
		<?php
        $counter++;
    }
    ?>
    				</div>
				</div>
			</div>
		</div>

	<div class="col-lg-12" style="padding: 40px;">
        <input class="btn btn-primary pull-right btn-lg" type='button' value='PRINT BADGE' onclick='myApp.printDiv()' />
        <input class="btn btn-primary btn-lg" type='button' value='PRINT BADGE' onclick='myApp.printDiv()' />
    </div>
    
</body>

<script>
	var name_font = '<?=$name_font?>';
  	var myApp = new function () {
        this.printDiv = function () {
            var div = document.getElementById('parent');
            var win = window.open('', '', 'height=720,width=1200');

            // Define and style for the elements and store it in a vairable.
            // win.document.write('<link href="css/custom.css?v=5" rel="stylesheet">');
            win.document.write('<link rel="preconnect" href="https://fonts.googleapis.com">');
            win.document.write('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>');
            win.document.write('<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">');
            win.document.write("<style>.card,.card-header{background-color:#fff}body{background-color:#c2d4d79e;font-size:13px;overflow-x:hidden}.card-container{box-shadow:0 0 0 0 #f1f1f1}.card_profile_container{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%)}.card{border:0 solid #eee;border-radius:0;overflow:auto;margin-bottom:30px;-webkit-box-shadow:2px 2px 2px rgba(0,0,0,.1),-1px 0 2px rgba(0,0,0,.05);box-shadow:2px 2px 2px rgba(0,0,0,.1),-1px 0 2px rgba(0,0,0,.05)}.card-profile .card-header{background-size:cover;background-position:center center}.card-header:first-child{border-radius:0;border-radius:calc(.25rem - 1px) calc(.25rem - 1px) 0 0}.card-header{padding-top:90px;text-align:center;min-height:690px}.card-header h2{margin-top:70px;margin-right:20px;margin-left:20px;font-size:50px!important;line-height:1.5;font-family:Montserrat,sans-serif}</style>");

            // CUSTOMIZE NAME FONT
            var style = '<style>';
            style = style + "h2 { font-size:"+name_font+"!important;}";
            style = style + '</style>';

            // Now, write the DIV contents with CSS styles and print it.
            win.document.write(style);
            win.document.write(div.outerHTML);
            win.document.close();
		    win.focus();
		    win.print();
		    win.close();
        }
    }
</script>

</html>