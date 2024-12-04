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
$event_id = 16;

// $_filter_condition_ .= " AND future_participants.participation_sub_type_id = 131";
$_filter_condition_ .= " AND future_participants.status = 'APPROVED'";
$_filter_condition_ .= " AND future_participants.print_badge_status = 0";

if (!($_participants_data_ = AccreditationController::getKGDParticipants($event_id, $_filter_condition_, $limit)))
    Redirect::to('404');
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <?php include 'includes/head.php';?>
	<style>
		body {
			font-family: League Spartan, sans-serif;
			background-color: #c2d4d79e;
			font-size: 13px;
			overflow-x: hidden;
		}
		.card-container {
			box-shadow: 0px 0px 0px 0px #f1f1f1;
		}
		.p-category {
			margin: auto; */
             text-align: center; 
            background: #37af47;
             bottom: 0; 
            position: absolute;
            width: 100%;
        }
		.inner-img {
			width: 106px;
			height: 100px;
			border-radius: 100%;
			border: 0px solid #fff;
			margin: auto;
			text-align: center;
			margin-top: -50px;
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
.card-profile-img {
    margin-bottom: 1rem;
    border-radius: 100%;
    width: 150px!important;
	height: 150px;
	object-fit: cover;
	border: 0px solid #fff;
	text-align: center;
	margin-top: -70px;
}
.card-header:first-child {
    border-radius: 0 0 0 0;
}
.card-header:first-child {
    border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
}
.card-header {
    padding: 0;
    background-color: #194925;
}
.card-profile img {width: 100%;}
.border_left {
	border-right: 1px solid #fff;
}
.card-profile .title h4 {
	color: #fff;
	font-family: ubuntu-light;
	font-weight: 300;
	margin: 0;
	line-height: 1.4;
	margin-bottom: 0;
}
.card-profile .dates p {
	color: #fff;
	font-size: 12px;
	margin-bottom: 0;
}
.card-profile .dates h5 {
	margin-bottom: 0;
	color: #8ec54a;
}
.card-profile .separator {
	border-top: 1px solid #fff;
	margin-left: 15px;
	margin-top: 5px;
	padding-left: 0;
}
.card-profile .separator p {margin: 0;}
.card-profile .separator h3 {
	margin: 0;
	color: #fff;
	font-family: League Spartan, sans-serif;
}
.card-body {
	background-color: #7557d3;
	padding: 20px;
	overflow: auto;
	min-height: 180px
}
.card-body h2 {
	font-size: 35px;
	color: #fff;
	font-weight: 700;
	font-family: League Spartan, sans-serif;
	margin-bottom: 0;
}
.card-body h3 {
	font-size: 25px;
	color: #fff;
	font-family: League Spartan, sans-serif;
	text-transform: uppercase;
}
.card-body .divider {
	background: #fff;
	height: 1px;
	width: 100%;
}
.partners img {
	margin-bottom: 30px;
}
.del_names {
	float: left;
	width: 70%;
}
.del_qr {
	float: right;
	width: 20%;
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
    $banner_title = "data_system/img/banner/KGD-badge.jpeg";

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
    	$_ID_ = $_participant_data_->id;
    	// AccreditationController::updatePrintBadgesStatus($_ID_);

	    $_PARTICIPANT_FULL_NAME_   = $_participant_data_->firstname.' '.$_participant_data_->lastname;
		$_COMPANY_NAME_			   = $_participant_data_->organisation_name;
		$_PARTICIPANT_PROFILE_     = $_participant_data_->profile != null? VIEW_PROFILE.$_participant_data_->profile: "https://bootdey.com/img/Content/avatar/avatar7.png";

		$_PARTICIPATION_SUB_TYPE_NAME_ = $_participant_data_->participation_subtype_name;


		/** Handle Qr COde */
		// $_qrID_		= $_participant_data_->qrID;
		// $_qrEncoded_= "https://system.torusguru.com/ebadge/$_participant_data_->qrCode";
		// $_DR_		= DN_IMG_QR;
		// $_qrFilename_= $_qrID_.".png";
		// $_qrFile_ 	= $_DR_.$_qrFilename_;
		// QRcode::png($_qrEncoded_, $_qrFile_);

		$_PARTICIPANT_QR_IMAGE_    = VIEW_QR.'kGD_QR.png';

	    if ($_PARTICIPATION_SUB_TYPE_NAME_ == 'Delegate') {
	        $category_background_color = "#7557d3!important;";
	        $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	        $_color = '#fff';
	    } elseif ($_PARTICIPATION_SUB_TYPE_NAME_ == "Host") {
	        $category_background_color = "#d75033!important;";
	        $_color = '#fff';
	        $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	    }  elseif ($_PARTICIPATION_SUB_TYPE_NAME_ == "Media") {
	        $category_background_color = "#e8c513!important;";
	        $_color = '#000';
	        $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	    } elseif ($_PARTICIPATION_SUB_TYPE_NAME_ == "Team Kigali") {
	        $category_background_color = "#08b768!important;";
	        $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	        $_color = '#fff';
	    } else {
	        $category_background_color = "#000000!important;";
	        $category_name  = $_PARTICIPATION_SUB_TYPE_NAME_;
	        $_color = '#fff';
	    }

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
							</div>
							<div class="card-body" style="background: <?=$category_background_color?>; min-height: 180px">
								<div class="del_names">
									<h3 style="color: <?=$_color?>"><?=$category_name?></h3>
									<div class="divider" style="background: <?=$_color?>"></div>
									<h2 style="color: <?=$_color?>; font-size: <?=$name_font?>"><?=$_PARTICIPANT_FULL_NAME_?></h2>
								</div>
								<div class="del_qr">
									<img src="<?=$_PARTICIPANT_QR_IMAGE_?>" style="width: 100%;">
								</div>
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
            win.document.write('<link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@700&display=swap" rel="stylesheet">');
            win.document.write("<style>.card-profile .separator h3,.card-profile .separator p{margin:0}body{font-family: 'League Spartan', sans-serif!important;background-color:#c2d4d79e;font-size:13px;overflow-x:hidden}.card-container{box-shadow:0 0 0 0 #f1f1f1}.p-category{margin:auto;text-align:center;background:#37af47;bottom:0;position:absolute;width:100%}.inner-img{width:106px;height:100px;border-radius:100%;border:0 solid #fff;margin:-50px auto auto;text-align:center}.card,.partners img{margin-bottom:30px}.card_profile_container{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%)}.card{background-color:#fff;border:0 solid #eee;border-radius:0;overflow:auto;-webkit-box-shadow:2px 2px 2px rgba(0,0,0,.1),-1px 0 2px rgba(0,0,0,.05);box-shadow:2px 2px 2px rgba(0,0,0,.1),-1px 0 2px rgba(0,0,0,.05)}.card-profile .card-header{background-size:cover;background-position:center center}.card-profile-img{margin-bottom:1rem;border-radius:100%;width:150px!important;height:150px;object-fit:cover;border:0 solid #fff;text-align:center;margin-top:-70px}.card-header:first-child{border-radius:0;border-radius:calc(.25rem - 1px) calc(.25rem - 1px) 0 0}.card-header{padding:0;background-color:#194925}.card-profile img{width:100%}.border_left{border-right:1px solid #fff}.card-profile .title h4{color:#fff;font-family:ubuntu-light;font-weight:300;margin:0;line-height:1.4}.card-profile .dates p{color:#fff;font-size:12px;margin-bottom:0}.card-profile .dates h5{margin-bottom:0;color:#8ec54a}.card-body h2,.card-body h3,.card-profile .separator h3{color:#fff;font-family: 'League Spartan', sans-serif!important;margin-bottom: 10px}.card-profile .separator{border-top:1px solid #fff;margin-left:15px;margin-top:5px;padding-left:0}.card-body{background-color:#7557d3;padding:20px;overflow:auto;min-height: 180px;}.card-body h2{font-size:38px;font-weight:700;margin-top:10px}.card-body h3{font-size:28px;text-transform:uppercase}.card-body .divider{background:#fff;height:1px;width:100%}.del_names{float:left;width:65%}.del_qr{float:right;width:30%}</style>");

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