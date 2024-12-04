<?php
require_once 'core/init.php';
require_once 'config/phpqrcode/qrlib.php';

// if(!isset($_SESSION['username']))
//     Redirect::to('login');

if (!Input::checkInput('authtoken_', 'get', 1))
	Redirect::to('dashboarrd');


$_QR_CODE_ = Input::get('authtoken_', 'get');
$_QR_ID_   = FutureEventController::decodeQrString($_QR_CODE_, $activeEventId);

/** Find Participant By Qr ID */
if (!($_participants_data_ = FutureEventController::getParticipantByQrID($_QR_ID_)))
	Redirect::to('404');

/** Find Participant By Category */
// if (!($_participants_data_ = FutureEventController::getParticipantByCategoryID(4, $activeEventId)))
// 	Redirect::to('404');

/** Find Participant By Category */
// if (!($_participants_data_ = FutureEventController::getVIPDataByCategoryID($activeEventId)))
// Redirect::to('404'); getNonRwandanDelegates

/** Find Non Rwanda delegates */
// if (!($_participants_data_ = FutureEventController::getNonRwandanDelegates($activeEventId)))
// 	Redirect::to('404');


$getContent   = DB::getInstance()->get('future_event', array('id', '=', $activeEventId));
$banner       = $getContent->first()->banner;
$event_name   = $getContent->first()->event_name;
$start_date   = date('j', strtotime(dateFormat($getContent->first()->start_date)));
$end_date     = date("j F Y", strtotime(dateFormat($getContent->first()->end_date)));
$event_date   = $start_date . " - " . $end_date;



?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
	<?php include 'includes/head.php'; ?>
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
			margin: auto;
			/* text-align: center;
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
			transform: translate(-50%, -50%);
		}

		.card {
			background-color: #fff;
			border: 0 solid #eee;
			border-radius: 0;
			overflow: auto;
		}

		.card {
			margin-bottom: 30px;
			-webkit-box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1), -1px 0 2px rgba(0, 0, 0, 0.05);
			box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1), -1px 0 2px rgba(0, 0, 0, 0.05);
		}

		.card-profile .card-header {
			height: 9rem;
			background-size: cover;
			background-position: center center
		}

		.card-profile-img {
			/*max-width: 10rem;*/
			margin-bottom: 1rem;
			/* border-radius: 100%; */
			width: 150px !important;
			object-fit: cover;
			max-height: 200px;
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

		.card-profile img {
			width: 100%;
		}

		.border_left {
			border-right: 1px solid #fff;
		}

		.card-profile .title h4 {
			color: #fff;
			font-family: ubuntu-light !important;
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

		.card-profile .separator p {
			margin: 0;
		}

		.card-profile .separator h3 {
			margin: 0;
			color: #8ec54a;
			font-family: ubuntu-Bold !important;
		}

		/*.card-body {overflow: hidden;}*/
		.card-body h2 {
			color: #4d4d4c;
			font-family: ubuntu-Bold !important;
		}

		.card-body h3 {
			color: #777776;
			font-family: ubuntu-regular !important;
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
			<h3> <?= $_EVENT_NAME_ ?> </h3>
			<p>  <?= $_EVENT_START_END_DATE_ ?> | <?= $_EVENT_ADDRESS_ ?> </p>
		</div>
		<div class="inner-img">
			<img src="<?= $_PARTICIPANT_PROFILE_ ?>">
		</div>
		<div class="reg-datails">
			<div class="names">
				<h4> <?= $_PARTICIPANT_FULL_NAME_ ?> </h4>
				<h5> <?= $_COMPANY_NAME_ ?></h5>
				<hr>
			</div>
		</div>
		<div class="qr-code">
			<img src="<?= $_PARTICIPANT_QR_IMAGE_ ?>">
		</div>
		<div class="p-category">
			<h4><?= $_PARTICIPATION_TYPE_NAME_ ?></h4>
		</div>

	</div> -->
	<?php

	$_participants_data_ = array(
		// array("firstname" => "Beatrice", "lastname" => "Cyzia", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Patrick", "lastname" => "Karera", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "James", "lastname" => "Shema", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Augustin", "lastname" => "Kalisa", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "William", "lastname" => "Mugambo", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Hon. Dr. Jeanne d'Arc", "lastname" => "Mujawamariya", "organisation_name" => "Minister of Environment, Rwanda", "participation_type_name" => "VVIP", "profile" => NULL),
		// array("firstname" => "Philippe", "lastname" => "KWITONDA", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Alain Michel", "lastname" => "DORICYUSA", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "William", "lastname" => "MUGABO", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Basile", "lastname" => "UWIMANA", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Alice", "lastname" => "NAMBAJE", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "David", "lastname" => "TOOVEY", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Basile", "lastname" => "UWIMANA", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Theophile", "lastname" => "DUSENGIMANA", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VVIP", "profile" => NULL),
		// array("firstname" => "Augustin", "lastname" => "Kalisa", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Anabella", "lastname" => "UMUHOZA", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Brenda", "lastname" => "NTAGANDA", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Sylien", "lastname" => "Gasangwa", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Diane", "lastname" => "DUSABE BUCYANA", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Billy Michel", "lastname" => "Migambi", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Shema", "lastname" => "James", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Athanase", "lastname" => "Akumuntu", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Roger", "lastname" => "MIZERO", "organisation_name" => "Ministry of Environment, Rwanda", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Clare", "lastname" => "AKAMANZI", "organisation_name" => "Chief Executive Officer, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Zephanie", "lastname" => "Niyonkuru", "organisation_name" => "Deputy Chief Executive Officer, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Michaella", "lastname" => "Rugwizangoga", "organisation_name" => "Chief Tourism Officer, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Ariella", "lastname" => "Kageruka", "organisation_name" => "Head of Tourism and Conservation Department, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Eugene", "lastname" => "Mutangana", "organisation_name" => "Conservation management Expert, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Telesphore", "lastname" => "Ngoga", "organisation_name" => "Conservation Analyst, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Prosper", "lastname" => "Uwingeli", "organisation_name" => "Chief Park Warden, Volcanoes National Park, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Anaclet", "lastname" => "Budahera", "organisation_name" => "Chief Park Warden, Gishwati-Mukura National Park, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Linda", "lastname" => "Mutesi", "organisation_name" => "Head of Marketing Division, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Dr. Richard", "lastname" => "Muvunyi", "organisation_name" => "Veterinary Analyst, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Himani", "lastname" => "Nautiyal", "organisation_name" => "Intern, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Rui Boliqueime", "lastname" => "Martins Diogo", "organisation_name" => "Intern, RDB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Cate", "lastname" => "Twining-Ward", "organisation_name" => "Intern, RDB", "participation_type_name" => "VIP", "profile" => NULL),


		// array("firstname" => "Bernardin", "lastname" => "UZAYISABA", "organisation_name" => "Head of sustainable growth Unit, UNDP", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Immaculee", "lastname" => "UWIMANA", "organisation_name" => "NDC Program coordinator, UNDP", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Mireille", "lastname" => "UWERA", "organisation_name" => "Programme Analyst, Environment , UNDP", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Varsha Redkar", "lastname" => "Palepu", "organisation_name" => "Deputy Resident Representative, UNDP", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Spridio", "lastname" => "NSHIMIYIMANA", "organisation_name" => "Ag DG , RFA", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Ivan", "lastname" => "Gasangwa", "organisation_name" => "Forestry research Division manager, RFA", "participation_type_name" => "VIP", "profile" => NULL),


		// array("firstname" => "Teddy", "lastname" => "Mugabo", "organisation_name" => "Chief Executive Officer, FONERWA", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Hitimana", "lastname" => "Augustin", "organisation_name" => "CFO, FONERWA", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Emmanuella", "lastname" => "Murekatete", "organisation_name" => "Division Manager, FONERWA", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Natacha", "lastname" => "Ndahiro", "organisation_name" => "DProject analysis Specialist, FONERWA", "participation_type_name" => "VIP", "profile" => NULL),


		// array("firstname" => "Juliet", "lastname" => "Kabera", "organisation_name" => "REMA", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Faustin", "lastname" => "Munyazikwiye", "organisation_name" => "REMA", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Innocent", "lastname" => "Rugema", "organisation_name" => "MINAFET", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Olivier", "lastname" => "Rutaganira", "organisation_name" => "Senior Officer, MINAFET", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Valence", "lastname" => "Gitera", "organisation_name" => "RCB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Sylvia", "lastname" => "Gasana", "organisation_name" => "RCB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Magnifique", "lastname" => "Karake", "organisation_name" => "RCB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Dr Jean Luc", "lastname" => "Benimana", "organisation_name" => "RCB", "participation_type_name" => "VIP", "profile" => NULL),


		// array("firstname" => "Hakizimana", "lastname" => "Jean Claude", "organisation_name" => "OGS", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Claude", "lastname" => "Kabengera", "organisation_name" => "OGS", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Emma Claudine", "lastname" => "Ntirenganya", "organisation_name" => "OGS", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Olivier", "lastname" => "Hakizimana", "organisation_name" => "OGS", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Fiston", "lastname" => "Nyirishema", "organisation_name" => "OGS", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Fidele", "lastname" => "Kamanzi", "organisation_name" => "METEO RWANDA", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Aimable", "lastname" => "Gahigi", "organisation_name" => "METEO RWANDA", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Dr. Emmanuel", "lastname" => "RUKUNDO", "organisation_name" => "RWB", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Bernard Segatagara", "lastname" => "MUSANA", "organisation_name" => "RWB", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Caroline Rwivanga", "lastname" => "Kayonga", "organisation_name" => "FCDO", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Charles", "lastname" => "Uwiragiye", "organisation_name" => "CCA", "participation_type_name" => "VIP", "profile" => "charles.jpeg"),
		// array("firstname" => "BFred", "lastname" => "CYUSA", "organisation_name" => "CCA", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Antonio Herman", "lastname" => "Benjamin", "organisation_name" => "Justice, High Court of Brazil, IUCN World Commission", "participation_type_name" => "VVIP", "profile" => NULL),
		// array("firstname" => "Muguni", "lastname" => "Maggie", "organisation_name" => "Ministry of Environment, Zimbabwe", "participation_type_name" => "VIP", "profile" => NULL),


		// array("firstname" => "Muhire", "lastname" => "Patrick", "organisation_name" => "Ministry of Envirnment", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Beatrice", "lastname" => "Nafula", "organisation_name" => "USAID", "participation_type_name" => "VIP", "profile" => NULL),


		// array("firstname" => "Ms. Josephine", "lastname" => "Marealle-Ulimwengu", "organisation_name" => "Strategic Planning and Team leader, RCO", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Ms. Maureen", "lastname" => "Twahirwa", "organisation_name" => "Communications and Advocacy, RCO", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Ms. Angela", "lastname" => "Zeleza", "organisation_name" => "Economist & Development Coordination Office, RCO", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Mr. Muhire", "lastname" => "Aristide", "organisation_name" => "Videographer, RCO", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Grace", "lastname" => "Vanderpuye", "organisation_name" => "SDG Accelerator Fellow, UNDP", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Stella", "lastname" => "Tushabe", "organisation_name" => "Project Communication & External relations, UNDP", "participation_type_name" => "VIP", "profile" => NULL),
		// array("firstname" => "Munyana", "lastname" => "Yvette", "organisation_name" => "MSU Associate, UNDP", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Alphonse Didier", "lastname" => "Mayinguidi", "organisation_name" => "Ministère de l'Economie Forestière, République du Congo", "participation_type_name" => "VIP", "profile" => NULL),

		// array("firstname" => "Delvi Delvi", "lastname" => "Mayinguidi", "organisation_name" => "Ministère de l'Economie Forestière, République du Congo", "participation_type_name" => "VIP", "profile" => NULL),


	);

	?>

	<div class="container card_profile_container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
			<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" id="printable">
				<?php
				$_participants_data_ = json_encode($_participants_data_);
				$_participants_data_ = json_decode($_participants_data_);
				$counter = 0;
				foreach ($_participants_data_ as $_participant_data_) {

					// LOOPING THROUGH PARTICIPANTS DATA

					$_PARTICIPANT_FULL_NAME_   = $_participant_data_->firstname . ' ' . $_participant_data_->lastname;
					$_COMPANY_NAME_               = $_participant_data_->organisation_name; //'Cube communication Ltd';
					$_EVENT_START_END_DATE_    = $start_date . ' - ' . $end_date;
					$_EVENT_ADDRESS_           = 'Kigali Rwanda';
					$_PARTICIPANT_PROFILE_     = $_participant_data_->profile != null ? VIEW_PROFILE . $_participant_data_->profile : "https://bootdey.com/img/Content/avatar/avatar7.png";


					$_PARTICIPATION_TYPE_NAME_ = $_participant_data_->participation_type_name;

					/** Handle Qr COde */
					// $_qrID_        = $_participant_data_->qrID;
					// $_qrEncoded_ = "https://system.torusguru.com/ebadge/$_participant_data_->qrCode";
					// $_qrEncoded_ = "" . DN . "/api/v1/attendance/new/" . Hash::encryptAuthToken($activeEventId) . "/" . Hash::encryptAuthToken($_participant_data_->id);
					// $_DR_        = DN_IMG_QR;
					// $_qrFilename_ = $_qrID_ . ".png";
					// $_qrFile_     = $_DR_ . $_qrFilename_;
					// QRcode::png($_qrEncoded_, $_qrFile_);

					// $_PARTICIPANT_QR_IMAGE_    = VIEW_QR . $_qrFilename_;

					// $_EVENT_NAME_                = $_participant_data_->event_name;


					$title_color = "#ffffff!important;";
					$title_1_color = "#8ec54a!important;";
					$title_2_color = "#ffffff!important;";
					$category_color = "#194925!important;";
					$logo = "data_system/img/banner/APAC-LOGO-01.png";
					if (false) {
						$category_color = "#343434!important;";
						$category_name  = "Contractor";
						$card_header    = "data_system/img/banner/BG-C.png";
					} elseif ($_participant_data_->participation_type_name == "VIP") {
						$category_color = "#a13122!important;";
						$category_name  = "VIP";
						$title_1_color  = "#ffffff!important;";
						$card_header    = "data_system/img/banner/BG-VIP.png";
					} elseif ($_participant_data_->participation_type_name == "VVIP") {
						$category_color = "#a13122!important;";
						$category_name  = "VVIP";
						$title_1_color  = "#ffffff!important;";
						$card_header    = "data_system/img/banner/BG-VIP.png";
					} elseif (false) {
						$category_color = "#308fb8!important;";
						$category_name  = "Volunteer";
						$title_1_color  = "#ffffff!important;";
						$card_header    = "data_system/img/banner/BG-V.png";
					} elseif (false) {
						$category_color = "#d6dc3c!important;";
						$category_name  = "Press & Media";
						$title_2_color  = "#194925!important;";
						$title_1_color  = "#194925!important;";
						$title_color    = "#194925!important;";
						$card_header    = "data_system/img/banner/BG-M.png";
						$logo = "data_system/img/banner/APAC LOGO-02.png";
					} elseif ($_participant_data_->participation_type_name == "SECRETARIAT") {
						$category_color = "#351a15!important;";
						$category_name  = "Secretariat";
						$card_header    = "data_system/img/banner/BG-S.png";
					} else {
						$category_color = "#194925!important;";
						$category_name  =  "DELEGATE";
						$card_header    = "data_system/img/banner/BG-D.png";
					}



				?>
					<div class="card card-profile">
						<div style="background-image: url(<?= linkto($card_header) ?>)!important;" class="card-header">
							<!-- <div class="card-header" style="background:<?= $category_color ?>"> -->
							<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
								<img src="<?php linkto($logo); ?>">
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 title border_left">
								<h4 style="color: <?= $title_color ?>;">IUCN Africa<br> Protected<br> Areas<br> Congress</h4>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 dates" style="padding-left: 0;">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 border_left">
									<h5 style="color: <?= $title_1_color ?>;">18-23</h5>
									<p style="color: <?= $title_2_color ?>;">July</p>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<h5 style="color: <?= $title_1_color ?>;">Kigali</h5>
									<p style="color: <?= $title_2_color ?>;">Rwanda</p>
								</div>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 separator">
									<p style="color: <?= $title_2_color ?>;">apaccongress.africa</p>
									<h3 style="color: <?= $title_1_color ?>;">#APAC2022</h3>
								</div>
							</div>
						</div>
						<div class="card-body text-center">
							<!-- <img src="/thefuture/adminPortal/charles.jpeg" class="card-profile-img"> -->
							<span style="padding-top: 140px!important;"></span>
							<h2 class="mb-3"><?= substr($_PARTICIPANT_FULL_NAME_, 0, 35) ?></h2>
							<h3><?= substr($_COMPANY_NAME_, 0, 54) ?></h3>
							<img src="<?= $_PARTICIPANT_QR_IMAGE_ ?>" style="width: 30%;">
							<h2 style="text-transform: uppercase; color: <?= $category_color ?>; font-family: ubuntu-Bold!important;"><?= $category_name ?></h2>
							<div class="partners" style="margin-top: 40px;">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><img src="<?php linkto('data_system/img/Untitled-design-33.png'); ?>"></div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><img src="<?php linkto('data_system/img/Untitled-design-35.png'); ?>"></div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><img src="<?php linkto('data_system/img/awf-logo-white-01.png'); ?>"></div>
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

	<script type="module" src="<?= linkto("client/modules/pages/Badge.js") ?>"></script>
</body>

</html>