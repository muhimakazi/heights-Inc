<?php
    require_once "../../core/init.php";
    require_once '../../config/phpqrcode/qrlib.php';

    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "accreditation";
    $link = "accreditation";
?>

<!DOCTYPE html>
<html>
<head>
<?php include $INC_DIR . "head.php"; ?>
</head>
<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="col-md-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <?php if ($user->hasPermission('admin') || $user->hasPermission('client')) { ?>
                        <button class="btn btn-sm btn-primary pull-right" onclick='myApp.printDiv()'><i class="fa fa-print"></i> Print badge</button>
                        <?php } ?>
                        <h5>Generate badges</h5>
                    </div>
                    <div class="row ibox-content">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-5">
                            <div id="parent" class="parentDiv">
            <?php
            $cat_bg = "#7557d3!important;";
            $banner_title = "data_system/img/banner/badge-header.jpeg?v=1.0";
            $logo = "data_system/img/banner/logo-wash.png?v=1.0";

            $cat_color = '#fff';

            $_filter_condition_ = "";
            $limit = " LIMIT 1";
            $limit = "";
            $today = date("Y-m-d");
            $date_time1 = $today. ' 00:00:00';
            $date_time2 = $today. ' 23:59:00';

            // $_filter_condition_ .= " AND future_participants.participation_sub_type_id = 156";
            $_filter_condition_ .= " AND future_participants.status = 'APPROVED'";
            // $_filter_condition_ .= " AND future_participants.id = 17081 || future_participants.id = 17083 || future_participants.id = 17084";

            // FOR SINGLE BADGE
            if (Input::checkInput('authtoken_', 'get', 1)):
                $_QR_CODE_ = Input::get('authtoken_', 'get');
                $_QR_ID_   = FutureEventController::decodeQrString($_QR_CODE_, $eventId);
                $participant_data = FutureEventController::getParticipantByQrID($_QR_ID_);
                $_ID_ = $participant_data->id;
                $_filter_condition_ .= " AND future_participants.id = $_ID_";
            else:
                $_filter_condition_ .= " AND future_participants.print_badge_status = 0";
            endif;

            if (!($_participants_data_ = AccreditationController::getIFFParticipants($eventId, $_filter_condition_, $limit))):
                Danger("No participant recorded");
            else:
                $_participants_data_ = json_encode($_participants_data_);
                $_participants_data_ = json_decode($_participants_data_);
                $counter = 0;
                $name_font = '35px';
                foreach ($_participants_data_ as $_participant_data_) {

                    //Update badge print status
                    $_ID_ = $_participant_data_->id;
                    AccreditationController::updatePrintBadgesStatus($_ID_);

                    $_PARTICIPANT_FULL_NAME_   = $_participant_data_->title.' '.$_participant_data_->firstname.' '.$_participant_data_->lastname;
                    $_JOB_TITLE_               = $_participant_data_->job_title;
                    // $_PARTICIPANT_PROFILE_     = $_participant_data_->profile != null? VIEW_PROFILE.$_participant_data_->profile:"https://bootdey.com/img/Content/avatar/avatar7.png";

                    $_PART_TYPE_ID_ = $_participant_data_->participation_type_id;
                    $_PART_SUB_TYPE_ID_ = $_participant_data_->participation_sub_type_id;
                    $_PART_SUB_TYPE_NAME_ = $_participant_data_->participation_subtype_name;
                    $_GROUP_ID_ = $_participant_data_->group_id;
                    $_ORGANISATION_NAME_ = $_participant_data_->organisation_name;
                    $_COUNTRY_ = $_participant_data_->residence_country;
                    if (strlen($_COUNTRY_) > 2) {
                    } else {
                        $_COUNTRY_ = countryCodeToCountry($_COUNTRY_);
                    }
                    $_LINE_ONE_ = $_participant_data_->line_one;
                    $_LINE_TWO_ = $_participant_data_->line_two;

                    /** Handle Qr COde */
                    // $_qrID_     = $_participant_data_->qrID;
                    // $_qrEncoded_= $_participant_data_->qrCode;
                    // $_DR_       = DN_IMG_QR;
                    // $_qrFilename_= $_qrID_.".png";
                    // $_qrFile_   = $_DR_.$_qrFilename_;
                    // QRcode::png($_qrEncoded_, $_qrFile_);
                    // $_PARTICIPANT_QR_IMAGE_    = VIEW_QR.$_qrFilename_;


                    if ($_PART_SUB_TYPE_NAME_ == 'Crew') {
                        $category_background_color = "#000000!important;";
                        $color = "#dc5f02;";
                    } elseif ($_PART_SUB_TYPE_NAME_ == 'Media') {
                        $category_background_color = "#a2c93a!important;";
                        $color = "#2b2e4b;";
                    } else {
                        $category_background_color = "#3fc3e7!important;";
                        $_PART_SUB_TYPE_NAME_ = 'Delegate';
                        $color = "#2b2e4b;";
                    }


                    // $_PARTICIPANT_FULL_NAME_ = 'Gerville Jinky Bitrics R. Luistro Mohamed Chanfiou';
                    ?>
                                    <div class="card-content">
                                        <div class="card card-profile" style="position:relative;margin:-8px;background-color:#fff;border:0 solid #eee;border-radius:0;overflow:auto;margin-bottom:30px;box-shadow: 0 4px 8px 0 rgba(0,0,0,.08);">
                                            <div class="card-body" style="background-color:#000;overflow:auto;min-height:419.5px;display:flex;align-items:center;justify-content:center;background: #2b2e4b;">
                                                <div class="del_names" style="padding:10px;text-align:center;width:100%;">
                                                    <img src="<?php linkto($logo);?>" style="width:80%; margin-bottom:50px;">
                                                    <span style="color:#fff;line-height:1.1;display:block;font-family:'Poppins', sans-serif;font-size:27px;margin-bottom:10px"><?=$_PARTICIPANT_FULL_NAME_?></span>
                                                    <span style="font-size:22px;color:#fff;line-height:1.1;display:block;font-family:'Poppins', sans-serif;margin-bottom:50px;"><?=$_ORGANISATION_NAME_?></span>
                                                    <span style="font-size:22px;color:#a2c93a;line-height:1.1;display:block;font-family:'Poppins', sans-serif;"><?=$_COUNTRY_?></span>
                                                </div>
                                            </div>
                                            <div class="card-header">
                                                <img src="<?php linkto($banner_title);?>" style="width:100%;">
                                            </div>
                                            <div class="card-body" style="background-color:#000;overflow:auto;min-height:90px;display:flex;align-items:center;justify-content:center;background:<?=$category_background_color?>">
                                                <h2 style="font-size:50px;color:#fff;margin-top:0;margin-bottom:0px;text-transform:uppercase;letter-spacing:2px;font-family:League Spartan, sans-serif;"><?=$_PART_SUB_TYPE_NAME_?></h2>
                                            </div>
                                        </div>
                                    </div>
                    <?php
                    $counter++;
                }
            endif;
            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

<script>
    var myApp = new function () {
        this.printDiv = function () {
            var div = document.getElementById('parent');
            var win = window.open('', '', 'height=720,width=1200');
            win.document.write('<link rel="preconnect" href="https://fonts.googleapis.com">');
            win.document.write('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>');
            win.document.write('<link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@700&display=swap" rel="stylesheet">');
            win.document.write('<link href="https://fonts.googleapis.com/css2?family=League+Spartan&family=Poppins&display=swap" rel="stylesheet">');
            win.document.write(div.outerHTML);
            win.document.close();
            win.focus();
            win.print();
            win.close();
        }
    }
</script>

</html>