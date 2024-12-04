<?php
require_once 'core/init.php';
require_once 'config/phpqrcode/qrlib.php';

// if(!isset($_SESSION['username']))
//     Redirect::to('login');

if(!Input::checkInput('authtoken_', 'get', 1))
	Redirect::to('dashboarrd');


$_QR_CODE_ = Input::get('authtoken_', 'get');
$_QR_ID_   = FutureEventController::decodeQrString($_QR_CODE_);

/** Find Participant By Qr ID */
if(!($_participant_data_ = FutureEventController::getParticipantByQrID($_QR_ID_)))
	Redirect::to('404');



$getContent   = DB::getInstance()->get('future_event', array('id', '=', $activeEventId));
$banner       = $getContent->first()->banner;
$event_name   = $getContent->first()->event_name;
$start_date   = date('j', strtotime(dateFormat($getContent->first()->start_date)));
$end_date     = date("j F Y", strtotime(dateFormat($getContent->first()->end_date)));
$event_date   = $start_date." - ".$end_date;

$_EVENT_NAME_ 		       = $_participant_data_->event_name;
$_PARTICIPANT_FULL_NAME_   = $_participant_data_->firstname.' '.$_participant_data_->lastname;
$_COMPANY_NAME_			   = $_participant_data_->organisation_name; //'Cube communication Ltd';
$_EVENT_START_END_DATE_    = $start_date.' - '.$end_date;
$_EVENT_ADDRESS_	       = 'Kigali Rwanda';
$_PARTICIPANT_PROFILE_     = $_participant_data_->profile != null? VIEW_PROFILE.$_participant_data_->profile: "https://bootdey.com/img/Content/avatar/avatar7.png";

if($_participant_data_->student_state == 1)
    $_COMPANY_NAME_		   = $_participant_data_->educacation_institute_name;

$_PARTICIPATION_TYPE_NAME_ = $_participant_data_->participation_type_name;

/** Handle Qr COde */
$_qrID_		= $_participant_data_->qrID;
$_qrEncoded_= "https://system.torusguru.com/ebadge/$_participant_data_->qrCode";
$_DR_		= DN_IMG_QR;
$_qrFilename_= $_qrID_.".png";
$_qrFile_ 	= $_DR_.$_qrFilename_;
QRcode::png($_qrEncoded_, $_qrFile_);

$_PARTICIPANT_QR_IMAGE_    = VIEW_QR.$_qrFilename_;
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <?php include 'includes/head.php';?>
	<style>
		body {
			font-family: ubuntu-light;
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
            /* padding: 8px 0; */
            background: #37af47;
             bottom: 0; 
/*            margin-top: 49px;*/
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


/*MC*/
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
    height: 9rem;
    background-size: cover;
    background-position: center center
}

.card-profile-img {
    /*max-width: 10rem;*/
    margin-bottom: 1rem;
    border-radius: 100%;
    width: 150px!important;
	height: 150px;
	object-fit: cover;
	border: 0px solid #fff;
	/*margin: auto;*/
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
	min-height: 220px;
    padding: 3rem 1.25rem;
    background-color: #194925;
    /*border-bottom: 1px solid #eee;*/
}
.card-header {
    /*-webkit-box-shadow: 2px 2px 2px rgba(0,0,0,0.05);
    box-shadow: 2px 2px 2px rgba(0,0,0,0.05);*/
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
	color: #8ec54a;
	font-family: ubuntu-Bold;
}
/*.card-body {overflow: hidden;}*/
.card-body h2 {
	color: #4d4d4c;
	font-family: ubuntu-Bold;
}
.card-body h3 {
	color: #777776;
	font-family: ubuntu-regular;
	margin-left: 20px;
	margin-right: 20px;
}
.partners img {
	margin-bottom: 30px;
}

	</style>
</head>

<body>
    <!-- <div class="card-container">
		<div class="event-details">
			<h3> <?=$_EVENT_NAME_?> </h3>
			<p>  <?=$_EVENT_START_END_DATE_?> | <?=$_EVENT_ADDRESS_?> </p>
		</div>
		<div class="inner-img">
			<img src="<?=$_PARTICIPANT_PROFILE_?>">
		</div>
		<div class="reg-datails">
			<div class="names">
				<h4> <?=$_PARTICIPANT_FULL_NAME_?> </h4>
				<h5> <?=$_COMPANY_NAME_ ?></h5>
				<hr>
			</div>
		</div>
		<div class="qr-code">
			<img src="<?=$_PARTICIPANT_QR_IMAGE_?>">
		</div>
		<div class="p-category">
			<h4><?=$_PARTICIPATION_TYPE_NAME_?></h4>
		</div>

	</div> -->
	<?php
	$title_color = "#ffffff!important;";
    $title_1_color = "#8ec54a!important;";
    $title_2_color = "#ffffff!important;";
    $category_color = "#194925!important;";
    $logo = "data_system/img/banner/APAC-LOGO-01.png";
    if ($_participant_data_->participation_type_id == 6) {
        $category_color = "#343434!important;";
        $category_name  = "Contractor";
        $card_header    = "data_system/img/banner/BG-C.png";
    } elseif ($_participant_data_->participation_type_name == "VIP") {
        $category_color = "#a13122!important;";
        $category_name  = "VIP";
        $title_1_color  = "#ffffff!important;";
        $card_header    = "data_system/img/banner/BG-VIP.png";
    } elseif ($_participant_data_->participation_type_id == 35) {
        $category_color = "#308fb8!important;";
        $category_name  = "Volunteer";
        $title_1_color  = "#ffffff!important;";
        $card_header    = "data_system/img/banner/BG-V.png";
    } elseif ($_participant_data_->participation_type_id == 4) {
        $category_color = "#d6dc3c!important;";
        $category_name  = "Press & Media";
        $title_2_color  = "#194925!important;";
        $title_1_color  = "#194925!important;";
        $title_color    = "#194925!important;";
        $card_header    = "data_system/img/banner/BG-M.png";
        $logo = "data_system/img/banner/APAC LOGO-02.png";
    } elseif ($_participant_data_->participation_type_id == 20) {
        $category_color = "#351a15!important;";
        $category_name  = "Secretariat";
        $card_header    = "data_system/img/banner/BG-S.png";
    } else {
        $category_color = "#194925!important;";
        $category_name  =  "DELEGATE";
        $card_header    = "data_system/img/banner/BG-D.png";
    }
	?>

	<div class="container card_profile_container">
		<div class="row">
			<div class="col-lg-3"></div>
			<div class="col-lg-5">
				<div class="card card-profile">
					<div style="background-image: url(<?=linkto($card_header)?>)!important" class="card-header">
						<!-- <div class="card-header" style="background:<?= $category_color ?>"> -->
						<div class="col-lg-5">
							<img src="<?php linkto($logo); ?>">
						</div>
						<div class="col-lg-3 title border_left">
							<h4 style="color: <?=$title_color?>;">IUNC Africa<br> Protected<br> Areas<br> Congress</h4>
						</div>
						<div class="col-lg-4 dates" style="padding-left: 0;">
							<div class="col-lg-6 border_left">
								<h5 style="color: <?=$title_1_color?>;">18-23</h5>
								<p style="color: <?=$title_2_color?>;">July</p>
							</div>
							<div class="col-lg-6">
								<h5 style="color: <?=$title_1_color?>;">Kigali</h5>
								<p style="color: <?=$title_2_color?>;">Rwanda</p>
							</div>
							<div class="col-lg-10 separator">
								<p style="color: <?=$title_2_color?>;">apaccongress.africa</p>
								<h3 style="color: <?=$title_1_color?>;">#APAC2022</h3>
							</div>
						</div>
					</div>
					<div class="card-body text-center">
						<img src="<?=$_PARTICIPANT_PROFILE_?>" class="card-profile-img">
					  	<h2 class="mb-3"><?=$_PARTICIPANT_FULL_NAME_?></h2>
					  	<h3><?=$_COMPANY_NAME_ ?></h3>
					  	<img src="<?=$_PARTICIPANT_QR_IMAGE_?>" style="width: 30%;">
					  	<h2 style="text-transform: uppercase; color: <?=$category_color?>; font-family: ubuntu-Bold;"><?=$category_name?></h2>
					  	<div class="partners" style="margin-top: 40px;">
							<div class="col-lg-4"><img src="<?php linkto('data_system/img/Untitled-design-33.png'); ?>"></div>
							<div class="col-lg-4"><img src="<?php linkto('data_system/img/Untitled-design-35.png'); ?>"></div>
							<div class="col-lg-4"><img src="<?php linkto('data_system/img/awf-logo-white.jpg'); ?>"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
</body>

</html>