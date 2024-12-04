<?php
require_once 'core/init.php';
require_once 'config/phpqrcode/qrlib.php';

$activeEventId = 10;

// if(!isset($_SESSION['username']))
//     Redirect::to('login');

// if (!Input::checkInput('authtoken_', 'get', 1))
//     Redirect::to('dashboarrd');


$_QR_CODE_ = Input::get('authtoken_', 'get');
$_QR_ID_   = FutureEventController::decodeQrString($_QR_CODE_, $activeEventId);

/** Find Participant By Qr ID */
// if (!($_participants_data_ = FutureEventController::getParticipantByQrID($_QR_ID_)))
// 	Redirect::to('404');

/** Find Participant By Category */
// if (!($_participants_data_ = FutureEventController::getParticipantByCategoryID(4, $activeEventId)))
// 	Redirect::to('404');

/** Find Participant By Category */
// if (!($_participants_data_ = FutureEventController::getVIPDataByCategoryID($activeEventId)))
// Redirect::to('404'); getNonRwandanDelegates

/** Find Non Rwanda delegates */
if (!($_participants_data_ = FutureEventController::getKGDParticipants(10)))
    Redirect::to('404');

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
            text-align: center;
            background: #37af47;
            bottom: 0;
            position: absolute;
            width: 100%;
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
            height: 45rem;
            background-size: cover;
            background-position: center center !important;
        }

        .card-header {
            min-height: 200px;
            padding-top: 20% !important;
            padding-bottom: 20% !important;
            /* background-position: fixed !important; */
        }

        .card-profile img {
            width: 100%;
        }


        .partners img {
            margin-bottom: 30px;
        }

        .header-text h1 {
            color: #fff !important;
            font-family: gotham-bold !important;
            font-size: 76px !important;
            margin: 0 !important;
            margin-bottom: -10% !important;
        }

        .header-text h4 {
            color: #fff !important;
            font-family: DIN-Alternate-Light !important;
            font-size: 26px !important;
            margin: 0 !important;
            font-weight: 700 !important;

        }

        .header-text p {
            color: #fff !important;
            font-family: DIN-Alternate-Light !important;
            font-size: 18px !important;
            margin-top: 3% !important;
            font-weight: 700 !important;

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

    // $_participants_data_ = array(
    //     array("firstname" => "Amizero", "lastname" => "Aime Samuel", "organisation_name" => "", "participation_type_name" => "DELEGATE", "profile" => NULL),
    //     array("firstname" => "Lucien", "lastname" => "M. Meru", "organisation_name" => "", "participation_type_name" => "CONTRACTOR", "profile" => NULL),
    //     array("firstname" => "Mikindi", "lastname" => "Colombe", "organisation_name" => "", "participation_type_name" => "MEDIA", "profile" => NULL),
    //     array("firstname" => "Manzi", "lastname" => "Anderson", "organisation_name" => "", "participation_type_name" => "HOST", "profile" => NULL),
    //     // array("firstname" => "Amizero", "lastname" => "Aime Samuel", "organisation_name" => "", "participation_type_name" => "DELEGATE", "profile" => NULL),
    //     // array("firstname" => "Lucien", "lastname" => "M. Meru", "organisation_name" => "", "participation_type_name" => "CONTRACTOR", "profile" => NULL),
    //     // array("firstname" => "Mikindi", "lastname" => "Colombe", "organisation_name" => "", "participation_type_name" => "MEDIA", "profile" => NULL),
    //     // array("firstname" => "Manzi", "lastname" => "Anderson", "organisation_name" => "", "participation_type_name" => "HOST", "profile" => NULL),
    //     // array("firstname" => "Amizero", "lastname" => "Aime Samuel", "organisation_name" => "", "participation_type_name" => "DELEGATE", "profile" => NULL),
    //     // array("firstname" => "Lucien", "lastname" => "M. Meru", "organisation_name" => "", "participation_type_name" => "CONTRACTOR", "profile" => NULL),
    //     // array("firstname" => "Mikindi", "lastname" => "Colombe", "organisation_name" => "", "participation_type_name" => "MEDIA", "profile" => NULL),
    //     // array("firstname" => "Manzi", "lastname" => "Anderson", "organisation_name" => "", "participation_type_name" => "HOST", "profile" => NULL),
    //     // array("firstname" => "Amizero", "lastname" => "Aime Samuel", "organisation_name" => "", "participation_type_name" => "DELEGATE", "profile" => NULL),
    //     // array("firstname" => "Lucien", "lastname" => "M. Meru", "organisation_name" => "", "participation_type_name" => "CONTRACTOR", "profile" => NULL),
    //     // array("firstname" => "Mikindi", "lastname" => "Colombe", "organisation_name" => "", "participation_type_name" => "MEDIA", "profile" => NULL),
    //     // array("firstname" => "Manzi", "lastname" => "Anderson", "organisation_name" => "", "participation_type_name" => "HOST", "profile" => NULL),

    // );

    ?>

    <div class="container card_profile_container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="printable" style="max-height: 560px!important; width: 401px!important;">

                <?php
                $_participants_data_ = json_encode($_participants_data_);
                $_participants_data_ = json_decode($_participants_data_);
                $counter = 0;
                foreach ($_participants_data_ as $_participant_data_) {

                    // LOOPING THROUGH PARTICIPANTS DATA
                    $participant_id = $_participant_data_->id;
                    $_PARTICIPANT_FULL_NAME_   = $_participant_data_->firstname . ' ' . $_participant_data_->lastname;
                    $_COMPANY_NAME_               = '';
                    $_EVENT_START_END_DATE_    = $start_date . ' - ' . $end_date;
                    $_EVENT_ADDRESS_           = 'Kigali Rwanda';
                    // $_PARTICIPANT_PROFILE_     = $_participant_data_->profile != null ? VIEW_PROFILE . $_participant_data_->profile : "https://bootdey.com/img/Content/avatar/avatar7.png";


                    $_PARTICIPATION_TYPE_NAME_ = $_participant_data_->delegate_type;

                    /** Handle Qr COde */
                    $_qrID_        = $_participant_data_->id;
                    // $_qrID_        = "meru";
                    // $_qrEncoded_ = "https://admin.torusguru.com/kgd_issue?delegate=".$participant_id;
                    $_qrEncoded_ = "http://192.168.1.159/thefuture/adminPortal/kgd_issue?delegate=" . $participant_id;
                    $_DR_        = DN_IMG_QR;
                    $_qrFilename_ = $_qrID_ . ".png";
                    $_qrFile_     = $_DR_ . $_qrFilename_;
                    QRcode::png($_qrEncoded_, $_qrFile_);

                    $_PARTICIPANT_QR_IMAGE_    = VIEW_QR . $_qrFilename_;

                    // $_EVENT_NAME_                = $_participant_data_->event_name;


                    $title_color = "#ffffff!important;";
                    $title_1_color = "#8ec54a!important;";
                    $title_2_color = "#ffffff!important;";
                    $category_color = "#194925!important;";
                    $delegate_fit = "";
                    $category_name = "";
                    $CATEGORY_NAME = "";
                    // echo "categories: " . $_PARTICIPATION_TYPE_NAME_;
                    $logo = "data_system/img/banner/APAC-LOGO-01.png";
                    if ($_PARTICIPATION_TYPE_NAME_ == "CONTRACTOR") {
                        $category_color = "#fff!important;";
                        $category_background_color = "#212121!important;";
                        $category_name  = "Technical Team";
                        $card_header    = "data_system/img/banner/BG-C.png";
                    } elseif ($_PARTICIPATION_TYPE_NAME_ == "HOST") {
                        $category_color = "#fff!important;";
                        $category_background_color = "#CF4232!important;";
                        $category_name  = "Host";
                        $title_1_color  = "#ffffff!important;";
                        $card_header    = "data_system/img/banner/BG-VIP.png";
                    } elseif ($_PARTICIPATION_TYPE_NAME_ == "MEDIA") {
                        $category_color = "#fff!important;";
                        $category_background_color = "#66AC73!important;";
                        $category_name  = "Media";
                        $title_2_color  = "#194925!important;";
                        $title_1_color  = "#194925!important;";
                        $title_color    = "#194925!important;";
                        $card_header    = "data_system/img/banner/BG-M.png";
                        $logo = "data_system/img/banner/APAC LOGO-02.png";
                    } else if ($_PARTICIPATION_TYPE_NAME_ == "DELEGATE") {
                        $category_color = "#fff!important;";
                        $category_background_color = "#3B6BB1!important;";
                        $category_name  =  "";
                        $delegate_fit = "padding-top: 5%; padding-bottom: 5%;";
                        $card_header    = "data_system/img/banner/BG-D.png";
                    }

                    $CATEGORY_NAME = $category_name;
                ?>
                    <div id="contain">

                        <div class=" card-profile text-center" style="background-color: <?= $category_background_color ?>; ">
                            <div class="card-header" style="background-image: url(data_system/img/background.png)!important;">

                                <div class="header-text">
                                    <h1>KIGALI</h1>
                                    <h4>GLOBAL DIALOGUE</h4>
                                    <p>10-12 Aug 2022</p>
                                </div>
                                <div style="margin-top: 20px!important;">
                                    <img src="<?= $_PARTICIPANT_QR_IMAGE_ ?>" style="width: 30%;">
                                </div>
                                <div class="partners" style="margin-top: 32px!important;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><img src="<?php linkto('data_system/img/kgd_partners/host_logos.png'); ?>"></div>

                                </div>
                            </div>
                            <div class="card-body" style="padding-bottom: 13.5px!important; padding-top: 4px!important; background-color: <?= $category_background_color ?>">
                                <!-- <span style="padding-top: 40px!important;"></span> -->
                                <h2 class="" style="color: <?= $category_color ?>; font-family: helvetica-neue!important; font-weight: 900!important; font-size: 30px!important; <?= $delegate_fit ?>"><?= substr($_PARTICIPANT_FULL_NAME_, 0, 55) ?></h2>
                                <h2 style="text-transform: uppercase; color: <?= $category_color ?>; font-family: helvetica-neue!important; font-weight: 900!important; font-size: 25px!important;"><?= $category_name ?></h2>
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