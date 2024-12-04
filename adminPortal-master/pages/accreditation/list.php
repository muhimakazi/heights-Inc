<?php
require_once "../../core/init.php";

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

$page = "accreditation";
$link = "accreditation";

$_EvCode_  = 'TORUS001';
$_EvPCode_ = 'DELGR001';

$eventVenue = DB::getInstance()->query("SELECT `venue` FROM `future_event` WHERE `id` = $eventId");
$venue      = $eventVenue->first()->venue;

//Quick registration numbers
$total = $printed = $pending = $issued = $printedPercentage = $pendingPercentage = $issuedPercentage = 0;
$_filter_condition_ = " AND future_participants.status = 'APPROVED'";
$total = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
if ($total > 0) {
    $_filter_condition_ = " AND future_participants.print_badge_status = 1 AND future_participants.status = 'APPROVED'";
    $printed = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $_filter_condition_ = " AND future_participants.print_badge_status = 1 AND future_participants.issue_badge_status = 0 AND future_participants.status = 'APPROVED'";
    $pending = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $_filter_condition_ = " AND future_participants.issue_badge_status = 1 AND future_participants.status = 'APPROVED'";
    $issued = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $printedPercentage = ($printed*100)/$total;
    $pendingPercentage = ($pending*100)/$total;
    $issuedPercentage = ($issued*100)/$total;
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="<?=DN?>/css/select2.min.css">
    <link rel="stylesheet" href="<?=DN?>/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?=DN?>/build/css/intlTelInput.css">
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?php linkto('js/jquery-2.1.1.js'); ?>"></script>
    <style>
    .label-dark {
        background-color: #2c4897;
        color: #FFFFFF;
    }

    .DTTT_container {
        display: none;
    }

    input[type="search"] {
        width: 300px !important;
        /*            height: 40px;*/
    }
    .select2-container {
        display: inline;
    }
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        
            <div class="wrapper wrapper-content" style="padding: 25px 0;">
                <div class="container-fluid">
                    <div class="row" style="display: none;">
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-info pull-right">Approved</span>
                                    <h5>Approved participants</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?=$total?></h1>
                                    <div class="stat-percent font-bold text-default">100% <i class="fa fa-bolt"></i></div>
                                    <small>Participants</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-success pull-right">Printed</span>
                                    <h5>Printed badges</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?=$printed?></h1>
                                    <div class="stat-percent font-bold text-success"><?=number_format($printedPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                    <small>Badges</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-success pull-right">Issued</span>
                                    <h5>Issued badges</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?=$issued?></h1>
                                    <div class="stat-percent font-bold text-success"><?=number_format($issuedPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                    <small>Badges</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-warning pull-right">Pending</span>
                                    <h5>Pending badges</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?=$pending?></h1>
                                    <div class="stat-percent font-bold text-info"><?=number_format($pendingPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                    <small>Badges</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">

                                <div class="ibox-title" style="height: auto; overflow: auto;">
                                    <?php if ($user->hasPermission('admin')) { ?>
                                    <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#registerModal"><i class="fa fa-pencil"></i> Register new participant</button>
                                    <?php } ?>
                                    <h5>Accreditation</h5>
                                </div>
                                <div class="ibox-content">
                                    <table id="participantsTable" class='table table-hover customTable'>
                                        <thead>
                                        <tr>
                                            <th> </th>
                                            <th>Reg. date</th>
                                            <th>Full name</th>
                                            <th>Pass type</th>
                                            <th>Job title</th>
                                            <th>Organisation</th>
                                            <th>Country</th>
                                            <!-- <th>Print badge</th> -->
                                            <!-- <th>Issue badge</th> -->
                                            <th>Attendance</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Confirm Modal  -->
            <div class="modal inmodal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="confirmTitle"></h4>
                        </div>
                        <form method="post" class="formCustom modal-form" id="confirmForm" novalidate="novalidate">
                            <div class="modal-body">
                                <div id="confirm-messages"></div>
                                <p class="text-center" id="confirmQuestion"></p>
                                <div class="row">

                                </div>
                            </div>
                            <div class="modal-footer confirmFooter">
                                <input type="hidden" id="request" name="request" value="" />
                                <input type="hidden" name="Id" id="confirmId" value="" />
                                <input type="hidden" name="eventId" value="<?= Hash::encryptAuthToken($eventId) ?>" />
                                <input type="hidden" name="location" value="<?=$venue ?>" />
                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                <button type="submit" id="confirmButton" class="btn btn-primary confirmButtonDynamic" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- REGISTER NEW PARTICIPANT -->
            <div class="modal inmodal fade" id="registerModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span
                                    aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Register new participant</h4>
                        </div>
                        <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post"
                            class="formCustom modal-form" id="registerForm">
                            <div class="modal-body">
                                <div id="register-messages"></div>
                                <p>All <small class="red-color">*</small> fields are mandatory</p>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Pass type<small class="red-color">*</small></label>
                                        <select class="form-control" name="eventParticipation" id="eventParticipation" data-rule="required" data-msg="Please select pass">
                                            <option value="" selected="">Please select</option>
                                            <?php
$_DATA_PARTICIPATION_CATEGORY_  = FutureEventController::getVisiblePacipationSubCategory($eventId, 'INPERSON');
if($_DATA_PARTICIPATION_CATEGORY_ ):
foreach($_DATA_PARTICIPATION_CATEGORY_ As $_event_participation_category_ ):
$_event_participation_category_ = (Object) $_event_participation_category_ ;
?>
                                            <option value="<?=Hash::encryptToken($_event_participation_category_->participation_sub_type_id)?>"><?=$_event_participation_category_->participation_sub_type_name?></option>
                                                <?php
endforeach;
endif;
?>
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Title<small class="red-color">*</small></label>
                                        <select class="form-control" name="title" id="title" data-msg="Please select title">
                                            <option value="" selected="">Please select</option>
                                            <?php $array = array("Mr.","Mrs.","H.E.","Hon.","Prof.","Amb.","Dr.","Ms.")?>
                                            <?php 
                                            for($i=0;$i<count($array);$i++){?>
                                            <option value="<?=$array[$i];?>"><?=$array[$i];?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>First name<small class="red-color">*</small></label>
                                        <input type="text" name="firstname" id="firstname" placeholder="First name" class="form-control" data-rule="required" data-msg="Please enter first name" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Last name<small class="red-color">*</small></label>
                                        <input type="text" name="lastname" id="lastname" placeholder="Last name" class="form-control" data-rule="required" data-msg="Please enter last name" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Email<small class="red-color">*</small></label>
                                        <input type="text" name="email" id="email" placeholder="Email address" class="form-control" data-msg="Please enter email address" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Telephone</label>
                                        <input type="text" name="telephone" id="telephone" placeholder="Telephone" class="form-control"/>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Organisation</label>
                                        <input type="text" name="organisation_name" id="organisation_name" placeholder="Organisation" class="form-control"/>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Job title</label>
                                        <input type="text" name="job_title" id="job_title" placeholder="Job title" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Country</label>
                                        <select class="form-control" name="residence_country">
                                            <option value="" selected="">Please select</option>
                                            <?php $controller->country();?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>City</label>
                                        <input type="text" name="organisation_city" id="organisation_city" placeholder="City" class="form-control"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Nationality</label>
                                        <select class="form-control" name="citizenship" id="citizenship">
                                            <option value="" selected="">Please select</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>ID type</label>
                                        <select class="form-control" name="id_type" id="id_type">
                                            <option value="">Please select</option>
                                            <option value="ID Card">ID Card</option>
                                            <option value="Passport">Passport</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>ID number</label>
                                        <input type="text" name="id_number" id="id_number" placeholder="ID number" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="request" value="registration" />
                                <input type="hidden" name="eventId" value="<?=Hash::encryptToken($eventId)?>">
                                <input type="hidden" name="_EvPCode_" value="C006" />
                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                <button type="submit" id="registerButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                var linkto = '<?=DN?>/pages/accreditation/accreditation_action.php';
            </script>

            <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
            <script src="<?=DN?>/pages/accreditation/accreditation.js?v=<?=date('Y-m-d H:i:s')?>"></script>

            <?php include $INC_DIR . "footer.php"; ?>

            <script src="<?=DN?>/js/select2.min.js"></script>
            <script src="<?=DN?>/js/countries.js"></script>
            <script src="<?=DN?>/build/js/intlTelInput.js"></script>
            <script>
            var phone_number = window.intlTelInput(document.querySelector("#telephone"), {
                autoPlaceholder: "off",
                separateDialCode: true,
                preferredCountries: ["rw"],
                // initialCountry: "rw",
                hiddenInput: "full",
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
            </script>

        </div>
    </div>
</body>

</html>