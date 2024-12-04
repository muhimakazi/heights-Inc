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
    <style>
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
            border: 0 solid #eee;
            border-radius: 0;
            overflow: auto;
        }
        .card {
            margin-bottom: 30px;
            -webkit-box-shadow: 2px 2px 2px rgba(0,0,0,0.1), -1px 0 2px rgba(0,0,0,0.05);
            box-shadow: 2px 2px 2px rgba(0,0,0,0.1), -1px 0 2px rgba(0,0,0,0.05);
        }
        .card-content {
            min-height: 700px;
            font-family:colfax, helvetica neue, arial, verdana, sans-serif;
        }
        .card-profile {
            min-height: 226px;
            background: rgba(225, 225, 225, 1);
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
            color: #000;
            font-weight: 700;
            margin: 0 10px;
        }
        .card-body h3 {
            font-size: 25px;
            line-height: 1.2;
            margin-left: 10px;
            margin-right: 10px;
            margin-top: 10px;
        }
        .card-footer {
            width: 100%;
            text-align: center;
        }
        .card-footer h3 {
            text-align: left;
            margin-top: 0;
            line-height: normal;
            margin: 20px;
            font-size: 25px;
        }
        .card-footer h3 span {
            margin-left: 20px
        }
        .card-footer {
            text-align: center;
        }
    </style>
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

            $titlecat_color = "#ffffff!important;";
            $title_1cat_color = "#8ec54a!important;";
            $title_2cat_color = "#ffffff!important;";
            $cat_bg = "#7557d3!important;";
            $banner_header = "data_system/img/banner/IFF-header.png";
            $banner_footer = "data_system/img/banner/IFF-footer.png";

            $cat_color = '#fff';

            $_filter_condition_ = "";
            $limit = " LIMIT 1";
            $limit = "";
            $today = date("Y-m-d");
            $date_time1 = $today. ' 00:00:00';
            $date_time2 = $today. ' 23:59:00';

            // $_filter_condition_ .= " AND future_participants.participation_sub_type_id = 134";
            $_filter_condition_ .= " AND future_participants.status = 'APPROVED'";
            // $_filter_condition_ .= " AND future_participants.print_badge_status = 0";
            // $_filter_condition_ .= " AND future_participants.id = 9591 AND future_participants.id = 12528";

            // FOR SINGLE BADGE
            if (Input::checkInput('authtoken_', 'get', 1)):
                $_QR_CODE_ = Input::get('authtoken_', 'get');
                $_QR_ID_   = FutureEventController::decodeQrString($_QR_CODE_, $eventId);
                $participant_data = FutureEventController::getParticipantByQrID($_QR_ID_);
                $_ID_ = $participant_data->id;
                $_filter_condition_ .= " AND future_participants.id = $_ID_";
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

                    $_PARTICIPANT_FULL_NAME_   = $_participant_data_->firstname.' '.$_participant_data_->lastname;
                    $_JOB_TITLE_               = $_participant_data_->job_title;
                    // $_PARTICIPANT_PROFILE_     = $_participant_data_->profile != null? VIEW_PROFILE.$_participant_data_->profile: "https://bootdey.com/img/Content/avatar/avatar7.png";

                    $_PART_TYPE_ID_ = $_participant_data_->participation_type_id;
                    $_PART_SUB_TYPE_ID_ = $_participant_data_->participation_sub_type_id;
                    $_PART_SUB_TYPE_NAME_ = $_participant_data_->participation_subtype_name;
                    $_GROUP_ID_ = $_participant_data_->group_id;
                    $_ORGANISATION_NAME_ = $_participant_data_->organisation_name;
                    $_COUNTRY_ = $_participant_data_->residence_country;
                    $_LINE_ONE_ = $_participant_data_->line_one;
                    $_LINE_TWO_ = $_participant_data_->line_two;

                    if ($_GROUP_ID_ == 44 || $_GROUP_ID_ == 78) {
                        // $_ORGANISATION_NAME_ = 'Cube Communications Ltd';
                    }


                    /** Handle Qr COde */
                    $_qrID_     = $_participant_data_->qrID;
                    $_qrEncoded_= $_participant_data_->qrCode;
                    $_DR_       = DN_IMG_QR;
                    $_qrFilename_= $_qrID_.".png";
                    $_qrFile_   = $_DR_.$_qrFilename_;
                    QRcode::png($_qrEncoded_, $_qrFile_);

                    $_PARTICIPANT_QR_IMAGE_    = VIEW_QR.$_qrFilename_;


                    // $_PARTICIPANT_FULL_NAME_ = 'Gerville Jinky Bitrics R. Luistro';
                    ?>
                                    <div class="card-content">
                                        <div class="card card-profile" style="position: relative;">
                                            
                                            <div class="card-body" style="margin-bottom: 300px">
                                                <h2><?=$_PARTICIPANT_FULL_NAME_?></h2>
                                                <h3><?=$_ORGANISATION_NAME_?></h3>
                                            </div>
                                            <div class="card-footer" style="position: absolute;  bottom: 0;">
                                                <img src="<?=$_PARTICIPANT_QR_IMAGE_?>" style="width: 30%;">
                                                <h3><?=$_LINE_ONE_?> <span><?=$_LINE_TWO_?></span></h3>
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

            // Define and style for the elements and store it in a vairable.
            // win.document.write('<link href="css/custom.css?v=5" rel="stylesheet">');
            win.document.write('<link rel="preconnect" href="https://fonts.googleapis.com">');
            win.document.write('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>');
            // win.document.write('<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">');
            win.document.write("<style>.card-body h2,.card-content,.card-footer h2{font-family:colfax,helvetica neue,arial,verdana,sans-serif}.card-container{box-shadow:0 0 0 0 #f1f1f1}.card_profile_container{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%)}.card{border:0 solid #eee;border-radius:0;overflow:auto;margin-bottom:30px;-webkit-box-shadow:2px 2px 2px rgba(0,0,0,.1),-1px 0 2px rgba(0,0,0,.05);box-shadow:2px 2px 2px rgba(0,0,0,.1),-1px 0 2px rgba(0,0,0,.05)}.card-content{min-height:700px}.card-profile{min-height:226px;background:#e1e1e1}.card-header:first-child{border-radius:0;border-radius:calc(.25rem - 1px) calc(.25rem - 1px) 0 0}.card-header{padding-left:30px;text-align:center}.card-body{margin-top:50px;text-align:center}.card-body h2,.card-footer h2{font-size:40px;line-height:1.1;color:#000;font-weight:700;margin:0 10px}.card-body h3{font-size:25px;line-height:1.2;margin-left:10px;margin-right:10px;margin-top:10px}.card-footer{width:100%;text-align:center}.card-footer h3{text-align:left;line-height:normal;margin:20px;font-size:25px}.card-footer h3 span{margin-left:20px}</style>");

            win.document.write(div.outerHTML);
            win.document.close();
            win.focus();
            win.print();
            win.close();
        }
    }
</script>

</html>