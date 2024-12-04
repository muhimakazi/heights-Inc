<?php
require_once 'core/init.php';
require_once 'config/phpqrcode/qrlib.php';

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

// if(!Input::checkInput('authtoken_', 'get', 1))
// 	Redirect::to('dashboard');


// $_QR_CODE_ = Input::get('authtoken_', 'get');
// $_QR_ID_   = FutureEventController::decodeQrString($_QR_CODE_);

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
$event_id = 13;

$today = date("Y-m-d");
$date_time1 = $today. ' 00:00:00';
$date_time2 = $today. ' 23:59:00';

// $_filter_condition_ .= " AND future_participants.participation_sub_type_id = 134";
$_filter_condition_ .= " AND future_participants.status = 'APPROVED'";
$_filter_condition_ .= " AND future_participants.print_badge_status = 0";
// $_filter_condition_ .= " AND future_participants.id = 9591 AND future_participants.id = 12528";

if (!($_participants_data_ = AccreditationController::getIFFParticipants($event_id, $_filter_condition_, $limit)))
    Danger("No participant recorded");
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
	font-family:colfax, helvetica neue, arial, verdana, sans-serif;
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
    background-color: #ebdcd7;
    border: 0 solid #eee;
    border-radius: 0;
    overflow: auto;
}
.card {
    margin-bottom: 30px;
    -webkit-box-shadow: 2px 2px 2px rgba(0,0,0,0.1), -1px 0 2px rgba(0,0,0,0.05);
    box-shadow: 2px 2px 2px rgba(0,0,0,0.1), -1px 0 2px rgba(0,0,0,0.05);
}
.card-profile {
	min-height: 700px;
	background: #ebdcd7;
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
	padding-left: 30px;
    text-align: center;
}
.card-body {
	margin-top: 50px;
	text-align: center;
}
.card-body h2, .card-footer h2 {
	font-size: 40px;
	line-height: 1.1;
	font-family:colfax, helvetica neue, arial, verdana, sans-serif;
	color: #820364;
	font-weight: 700;
	margin: 0 10px;
}
.card-body p {
	font-size: 30px;
	line-height: 1.2;
	font-weight: 300;
	margin-left: 10px;
	margin-right: 10px;
}
.card-body img {
	margin-top: 10px;
	margin-bottom: 30px;
}
.card-footer .del_name {
	padding: 15px 0;
}
.card-footer .del_name h2 {
	letter-spacing: 2px;
	margin-top: 0;
	line-height: normal;
	margin-bottom: 0;
}
.card-footer {
	text-align: center;
}
.card-footer .partners {
	margin: 20px 30px;
}
.card-footer .divider {
	background: #000;
	height: 1px;
	margin-bottom: 15px;
}

	</style>
</head>

<body>
	<?php
	$titlecat_color = "#ffffff!important;";
    $title_1cat_color = "#8ec54a!important;";
    $title_2cat_color = "#ffffff!important;";
    $cat_bg = "#7557d3!important;";
    $banner_header = "data_system/img/banner/IFF-header.png";
    $banner_footer = "data_system/img/banner/IFF-footer.png";

    $cat_color = '#fff';

    
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

    	//Update badge print status
    	$_ID_ = $_participant_data_->id;
    	AccreditationController::updatePrintBadgesStatus($_ID_);

	    $_PARTICIPANT_FULL_NAME_   = $_participant_data_->firstname.' '.$_participant_data_->lastname;
		$_JOB_TITLE_			   = $_participant_data_->job_title;
		// $_PARTICIPANT_PROFILE_     = $_participant_data_->profile != null? VIEW_PROFILE.$_participant_data_->profile: "https://bootdey.com/img/Content/avatar/avatar7.png";

		$_PART_TYPE_ID_ = $_participant_data_->participation_type_id;
		$_PART_SUB_TYPE_ID_ = $_participant_data_->participation_sub_type_id;
		$_PART_SUB_TYPE_NAME_ = $_participant_data_->participation_subtype_name;
		$_GROUP_ID_ = $_participant_data_->group_id;
		$_ORGANISATION_NAME_ = $_participant_data_->organisation_name;
		$_COUNTRY_ = $_participant_data_->residence_country;

		if ($_GROUP_ID_ == 44 || $_GROUP_ID_ == 78) {
			// $_ORGANISATION_NAME_ = 'Cube Communications Ltd';
		}


		/** Handle Qr COde */
		$_qrID_		= $_participant_data_->qrID;
		$_qrEncoded_= $_participant_data_->qrCode;
		$_DR_		= DN_IMG_QR;
		$_qrFilename_= $_qrID_.".png";
		$_qrFile_ 	= $_DR_.$_qrFilename_;
		QRcode::png($_qrEncoded_, $_qrFile_);

		$_PARTICIPANT_QR_IMAGE_    = VIEW_QR.$_qrFilename_;

		//INDUSTRY
	    if ($_PART_TYPE_ID_ == 36 || $_PART_TYPE_ID_ == 38 || $_PART_SUB_TYPE_ID_ == 106 || $_PART_SUB_TYPE_ID_ == 106 
	    	|| $_PART_SUB_TYPE_ID_ == 116 || $_PART_SUB_TYPE_ID_ == 117 || $_PART_SUB_TYPE_ID_ == 118)
	    {
	        $cat_bg = "#820364!important;";
	        $cat_name  = 'Industry Pass';
	        $cat_color = '#fff!important';
	    }

	    //FINTECH
	    elseif ($_PART_TYPE_ID_ == 37 || $_PART_SUB_TYPE_ID_ == 107 || $_PART_SUB_TYPE_ID_ == 120)
	    {
	        $cat_bg = "#ffd401!important;";
	        $cat_name  = 'FinTech Pass';
	        $cat_color = '#820364';
	    } 

	    //VIP
	    elseif ($_PART_SUB_TYPE_ID_ == 110 || $_PART_SUB_TYPE_ID_ == 119 || $_PART_SUB_TYPE_ID_ == 128 || $_PART_SUB_TYPE_ID_ == 137)
	    {
	        $cat_bg = "#fff!important;";
	        $cat_name  = 'VIP';
	        $cat_color = '#820364!important';
	    }

	    //CMPD-INDUSTRY
	    elseif ($_PART_SUB_TYPE_ID_ == 99 || $_PART_SUB_TYPE_ID_ == 114)
	    {
	        $cat_bg = "#820364!important;";
	        $cat_name  = 'Industry Pass - CMPD';
	        $cat_color = '#fff!important';
	    }

	    //CMPD-FINTECH
	    elseif ($_PART_SUB_TYPE_ID_ == 100)
	    {
	        $cat_bg = "#ffd401!important;";
	        $cat_name  = 'FinTech Pass - CMPD';
	        $cat_color = '#820364!important';
	    }

	    //CMPD VIP
	    elseif ($_PART_SUB_TYPE_ID_ == 101 || $_PART_SUB_TYPE_ID_ == 102)
	    {
	        $cat_bg = "#fff!important;";
	        $cat_name  = 'VIP - CMPD';
	        $cat_color = '#820364!important';
	    }

	    //ORGANISER
	    elseif ($_PART_SUB_TYPE_ID_ == 115)
	    {
	        $cat_bg = "#fe7503!important;";
	        $cat_name  = 'Organiser';
	        $cat_color = '#fff!important';
	    }

	    //MEDIA
	    elseif ($_PART_TYPE_ID_ == 40 || $_PART_SUB_TYPE_ID_ == 103 || $_PART_SUB_TYPE_ID_ == 108)
	    {
	        $cat_bg = "#38bc2a!important;";
	        $cat_name  = 'Media';
	        $cat_color = '#fff!important';
	    }

	    //CAREERS FORUM
	    elseif ($_PART_TYPE_ID_ == 47 || $_PART_TYPE_ID_ == 46)
	    {
	        $cat_bg = "#000!important;";
	        $cat_name  = 'Careers Forum';
	        $cat_color = '#fff!important';
	    }

	    //CREW - Entertainers
	    elseif ($_PART_SUB_TYPE_ID_ == 111 || $_PART_SUB_TYPE_ID_ == 136)
	    {
	        $cat_bg = "#4766fe!important;";
	        $cat_name  = 'Crew';
	        $cat_color = '#fff!important';
	    } 

	    //PROTOCOL
	    elseif ($_PART_SUB_TYPE_ID_ == 135)
	    {
	        $cat_bg = "#4766fe!important;";
	        $cat_name  = 'Protocol';
	        $cat_color = '#fff!important';
	    }
	    else {
	    	$cat_bg = "#4766fe!important;";
	        $cat_name  = 'Crew';
	        $cat_color = '#fff!important';
	    }


	    // $_PARTICIPANT_FULL_NAME_ = 'Gerville Jinky Bitrics R. Luistro';
	    ?>
						<div class="card card-profile" style="position: relative;">
							<div class="card-header">
								<img src="<?php linkto($banner_header); ?>" style="width: 100%;">
							</div>
							<div class="card-body">
								<h2><?=$_PARTICIPANT_FULL_NAME_?></h2>
								<p>
									<?=$_ORGANISATION_NAME_?><br>
									<?=$_COUNTRY_?>
								</p>
								<img src="<?=$_PARTICIPANT_QR_IMAGE_?>" style="width: 30%;">
							</div>
							<div class="card-footer" style="position: absolute; 
                    bottom: 0; 
                   ">
								<div class="del_name" style="background: <?=$cat_bg?>">
									<h2 style="color: <?=$cat_color?>;"><?=$cat_name?></h2>
								</div>
								<div class="partners">
									<div class="divider"></div>
									<img src="<?php linkto($banner_footer); ?>" style="width: 100%;">
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
            // win.document.write('<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">');
            win.document.write("<style>.card-body h2,.card-footer h2,body{font-family:colfax,helvetica neue,arial,verdana,sans-serif}.card-body,.card-footer,.card-header{text-align:center}body{background-color:#c2d4d79e;font-size:13px;overflow-x:hidden}.card-container{box-shadow:0 0 0 0 #f1f1f1}.card_profile_container{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%)}.card{background-color:#ebdcd7;border:0 solid #eee;border-radius:0;overflow:auto;margin-bottom:30px;-webkit-box-shadow:2px 2px 2px rgba(0,0,0,.1),-1px 0 2px rgba(0,0,0,.05);box-shadow:2px 2px 2px rgba(0,0,0,.1),-1px 0 2px rgba(0,0,0,.05)}.card-profile{min-height:780px;background:#ebdcd7}.card-profile .card-header{background-size:cover;background-position:center center}.card-header:first-child{border-radius:0;border-radius:calc(.25rem - 1px) calc(.25rem - 1px) 0 0}.card-header{padding-left:30px}.card-body{margin-top:50px}.card-body h2,.card-footer h2{font-size:40px;line-height:1.1;color:#820364;font-weight:700;margin:0 10px}.card-body p{font-size:30px;line-height:1.2;font-weight:300;margin-left:10px;margin-right:10px}.card-body img{margin-top:10px;margin-bottom:30px}.card-footer .del_name{padding:15px 0}.card-footer .del_name h2{letter-spacing:2px;margin-top:0;line-height:normal;margin-bottom:0}.card-footer .partners{margin:20px 30px}.card-footer .divider{background:#000;height:1px;margin-bottom:15px}</style>");

            // CUSTOMIZE NAME FONT
            var style = '<style>';
            style = style + "h2 { font-size:"+name_font+"!important;}";
            style = style + '</style>';

            // Now, write the DIV contents with CSS styles and print it.
            // win.document.write(style);
            win.document.write(div.outerHTML);
            win.document.close();
		    win.focus();
		    win.print();
		    win.close();
        }
    }
</script>

</html>