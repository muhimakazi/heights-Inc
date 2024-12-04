<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) {
    Redirect::to('login');
}

$page = "participants";
$link = "list";

//Quick registration numbers
$total = $approved = $pending = $rejected = $approvedPercentage = $pendingPercentage = $rejectedPercentage = 0;
$exportBtn = 'disabled';
$_filter_condition_ = "";
$total = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
if ($total > 0) {
    $exportBtn = '';
    $_filter_condition_ = " AND future_participants.status = 'APPROVED'";
    $approved = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $_filter_condition_ = " AND future_participants.status = 'PENDING'";
    $pending = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $_filter_condition_ = " AND future_participants.status = 'DENIED'";
    $rejected = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $approvedPercentage = ($approved*100)/$total;
    $pendingPercentage = ($pending*100)/$total;
    $rejectedPercentage = ($rejected*100)/$total;
}
?>

<!DOCTYPE html>
<html>

<head>
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
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        
        <div class="wrapper wrapper-content" style="padding: 25px 0;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">All</span>
                                <h5>Total registrations</h5>
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
                                <span class="label label-warning pull-right">Pending</span>
                                <h5>Pending registrations</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$pending?></h1>
                                <div class="stat-percent font-bold text-info"><?=number_format($pendingPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small>Participants</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-success pull-right">Approved</span>
                                <h5>Approved registrations</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$approved?></h1>
                                <div class="stat-percent font-bold text-success"><?=number_format($approvedPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small>Participants</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-danger pull-right">Rejected</span>
                                <h5>Rejected registrations</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$rejected?></h1>
                                <div class="stat-percent font-bold text-danger"><?=number_format($rejectedPercentage, 2)?>% <i class="fa fa-level-down"></i></div>
                                <small>Participants</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content" style="padding: 15px 20px 0px 20px;">
                                <!-- <a href="<?php linkto("pages/participants/export_delegates/$exportId"); ?>" class="btn btn-primary pull-right" style="border-radius: 0px;"><i class="fa fa-download"></i> Export participants</a> -->
                                <form action="" id="filterForm" method='post'>
                                    <div class="row">
                                        <?php
                                        $passCount = ReportController::getAllPacipationCategoryCountByEventID($eventId);
                                        if($passCount):
                                        ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <!-- <select id="type" name="type" onchange="filterOptionsSubtype(this);" required  class="form-control" data-rule="required" data-msg="Please select type"> -->
                                                <select id="type" name="type" onchange="filterOptionsSubtype(this);"
                                                    class="form-control">
                                                    <!-- <option value="">- Select Participation Type -</option> -->
                                                    <option value="">All</option>
                                                    <?php
                                                    $_TYPE_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId);
                                                    if($_TYPE_DATA_):
                                                        foreach($_TYPE_DATA_ As $type_):
                                                    ?>
                                                    <option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name?>
                                                    </option>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select id="subtype" name="subtype" class="form-control" data-rule="required"
                                                    data-msg="Please select subtype">
                                                    <option value="">- Select Participation Subtype -</option>
                                                    <option value="">All</option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        else:
                                        ?>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select id="formtype" name="formtype" class="form-control">
                                                    <option value="">All</option>
                                                    <?php
                                                    $_TYPE_DATA_ = ReportController::getAllFormPassByEventID($eventId);
                                                    if($_TYPE_DATA_):
                                                        foreach($_TYPE_DATA_ As $type_):
                                                    ?>
                                                    <option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->form_name?>
                                                    </option>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        endif;
                                        ?>


                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select id="country" name="country" class="form-control" data-rule="required"
                                                    data-msg="Please select status">
                                                    <option value="">- Select Country -</option>
                                                    <option value="">All</option>
                                                    <option value="Local">Local</option>
                                                    <option value="International">International</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select id="status" name="status" class="form-control" data-rule="required"
                                                    data-msg="Please select status">
                                                    <option value="">- Select Status -</option>
                                                    <option value="">All</option>
                                                    <option value="PENDING">Pending</option>
                                                    <!-- <option value="ACTIVE">Activated</option>
                                                    <option value="DEACTIVE">Deactivated</option> -->
                                                    <option value="APPROVED">Approved</option>
                                                    <!-- <option value="REJECTED">Rejected</option> -->
                                                    <option value="DENIED">Rejected</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">From:</label>
                                                <input type="date" class="form-control" name="datefrom" id="datefrom">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="">To:</label>
                                                    <input type="date" class="form-control" name="dateto" id="dateto">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <input type="hidden" name="eventId" id="eventId" value="<?=Hash::encryptToken($eventId)?>">
                                            <input type="hidden" name="request" id="request" value="fetchParticitants">
                                            <label for="" style="visibility: hidden;">Submit</label>
                                            <button type="submit" style="border-radius: 0px; padding: 6px 70px 6px 26px;"
                                            autocomplete="off" class="btn btn-md btn-primary col-md-1"> <i
                                                class=" fa fa-filter"></i> Filter</button>
                                        </div>
                                        <div class="col-md-1"></div>
                                        <?php if ($user->hasPermission('admin') || $user->hasPermission('event-admin')) { ?>
                                        <div class="col-md-1">
                                            <label for="" style="visibility: hidden;">Export</label>
                                            <button class="btn btn-primary exportBtn" style="border-radius: 0px; padding: 6px 30px" <?=$exportBtn?>><i class="fa fa-download"></i> Export</button>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </form>

                                <!-- <br><br> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <!-- Participants Table -->
                            <div class="ibox-content">
                                <table id="participantsTable" class='table table-hover customTable'>
                                    <thead>
                                    <tr>
                                        <th> </th>
                                        <th>Reg. date</th>
                                        <th>Full name</th>
                                        <!-- <th>Referral person</th> -->
                                        <th>Pass type</th>
                                        <th>Job title</th>
                                        <th>Organisation</th>
                                        <th>Job Category</th>
                                        <th>Country</th>
                                        <th>Status</th>
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


        <!-- APPROVE REJECT Modal  -->
        <div class="modal inmodal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true"
            novalidate="novalidate">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="approve-modal-title"></h4>
                    </div>
                    <form action="" method="post" class="formCustom modal-form" id="approveForm"
                        novalidate="novalidate">
                        <div class="modal-body">
                            <div id="approve-messages"></div>
                            <p class="text-center" id="approve-modal-confirm"></p>
                            <div class="row">

                            </div>
                        </div>
                        <div class="modal-footer approveFooter">
                            <input type="hidden" id="approveRequest" name="request" value="" />
                            <input type="hidden" name="page" id="page" value="list" />
                            <input type="hidden" name="Id" id="approveId" value="" />
                            <input type="hidden" name="eventId" value="<?= Hash::encryptAuthToken($eventId) ?>" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="approveButton" class="btn btn-primary approveButtonDynamic"
                                data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i>
                                Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update delegate category  -->
        <div class="modal inmodal fade" id="categoryModal" tabindex="-1" role="dialog" aria-hidden="true"
            novalidate="novalidate">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Update participant Category</h4>
                    </div>
                    <form action="" method="post" class="formCustom modal-form" id="categoryForm">
                        <div class="modal-body">
                            <div id="category-messages"></div>
                            <p class="text-center" id="category-modal-confirm"></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group control-group">
                                        <select id="type" name="type" onchange="getOptionsSubtype(this);"
                                            class="form-control" data-rule="required" data-msg="Please select">
                                            <option value="">Select participation type</option>
                                            <?php
        $_TYPE_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId);
        if($_TYPE_DATA_):
            foreach($_TYPE_DATA_ As $type_):
                // if($type_->id != 45) {
        ?>
                                            <option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name?>
                                            </option>
                                            <?php
                // }
            endforeach;
        endif;
        ?>
                                        </select>
                                        <p class="validate"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group control-group">
                                        <select id="update_subtype" name="subtype" class="form-control"
                                            data-rule="required" data-msg="Please select">
                                            <option value="">Select participation subtype</option>
                                        </select>
                                        <p class="validate"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer categoryFooter">
                            <input type="hidden" name="request" value="changeCategory" />
                            <input type="hidden" name="Id" id="updateCategoryPartId" value="" />
                            <input type="hidden" name="eventId" value="<?= Hash::encryptAuthToken($eventId) ?>" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="categoryButton" class="btn btn-primary categoryButtonDynamic"
                                data-loading-text="Loading..." autocomplete="off"><i class="fa fa-external-link"></i>
                                Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- CMPD  -->
        <div class="modal inmodal fade" id="cmpdModal" tabindex="-1" role="dialog" aria-hidden="true"
            novalidate="novalidate">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Update CMPD Pass</h4>
                    </div>
                    <form action="" method="post" class="formCustom modal-form" id="cmpdForm">
                        <div class="modal-body">
                            <div id="cmpd-messages"></div>
                            <!-- <p class="text-center">Do you really want to upgrade participant?</p> -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group control-group">
                                        <select id="subtype" name="subtype" class="form-control" data-rule="required" data-msg="Please select">
                                            <option value="">Select CMPD Type</option>
                                            <?php
        $_TYPE_DATA_ = FutureEventController::getPacipationSubType($eventId, 39);
        if($_TYPE_DATA_):
            foreach($_TYPE_DATA_ As $type_):
                if($type_->id != 113) {
        ?>
                                            <option value="<?=Hash::encryptToken($type_->id)?>"><?=$type_->name?>
                                            </option>
                                            <?php
                }
            endforeach;
        endif;
        ?>
                                        </select>
                                        <p class="validate"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer cmpdFooter">
                            <input type="hidden" name="request" value="changeCategory" />
                            <input type="hidden" name="Id" id="updatecmpdPartId" value="" />
                            <input type="hidden" name="type" value="<?=Hash::encryptToken(39)?>" />
                            <input type="hidden" name="eventId" value="<?= Hash::encryptAuthToken($eventId) ?>" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="cmpdButton" class="btn btn-primary cmpdButtonDynamic"
                                data-loading-text="Loading..." autocomplete="off"><i class="fa fa-external-link"></i>
                                Upgrade</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal inmodal fade" id="paymentLinkModal" tabindex="-1" role="dialog" aria-hidden="true"
            novalidate="novalidate">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="confirm-messages"></div>
                        <p class="text-center" style="margin-bottom: 0;"><a id="payment_link" href="" target="_blank">Click here</a> to copy <span id="participant_name"></span>'s payment link</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT PARTICIPANT -->
        <div class="modal inmodal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit participant</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="updateProfileForm" novalidate="novalidate">
                        <div class="modal-body">
                            <p class="text-center" id="edit-profile-confirm"></p>
                            <div id="update-profile-message"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Title<small class="red-color">*</small></label>
                                    <select class="form-control" name="title" id="title" required="required">
                                        <option value="" selected="">Please select</option>
                                        <?php $array = array("Mr.","Mrs.","H.E.","Hon.","Prof.","Dr.","Ms.")?>
                                        <?php 
                                        for($i=0;$i<count($array);$i++){?>
                                        <option value="<?=$array[$i];?>"><?=$array[$i];?></option>
                                        <?php } ?>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-3">
                                    <label>First name<small class="red-color">*</small></label>
                                    <input type="text" name="firstname" id="firstname" placeholder="First name" class="form-control" required="required"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-3">
                                    <label>Last name<small class="red-color">*</small></label>
                                    <input type="text" name="lastname" id="lastname" placeholder="Last name" class="form-control" required="required"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-3">
                                    <label>Email<small class="red-color">*</small></label>
                                    <input type="text" name="email" id="email" placeholder="Email address" class="form-control"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Telephone</label>
                                    <input type="text" name="telephone" id="telephone" placeholder="Telephone" class="form-control"/>
                                </div>
                                <div class="form-group control-group col-md-3">
                                    <label>Organisation</label>
                                    <input type="text" name="organisation_name" id="organisation_name" placeholder="Organisation" class="form-control"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Job title</label>
                                    <input type="text" name="job_title" id="job_title" placeholder="Job title" class="form-control"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-3">
                                    <label>Country</label>
                                    <select class="form-control" name="residence_country" id="residence_country">
                                        <option value="" selected="">Please select</option>
                                        <?php $controller->country();?>
                                    </select>
                                </div>
                                <div class="form-group control-group col-md-3">
                                    <label>ID type</label>
                                    <select class="form-control" name="id_type" id="id_type">
                                        <option value="">Please select</option>
                                        <option value="ID Card">ID Card</option>
                                        <option value="Passport">Passport</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-3">
                                    <label>ID number</label>
                                    <input type="text" name="id_number" id="id_number" class="form-control"/>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="registrationUpdate" />
                            <input type="hidden" id="userToken" name="userToken">
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="updateProfileButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var linkto = '<?=DN?>/pages/participants/participants_action.php';
        </script>

        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        <script src="<?=DN?>/pages/participants/participants.js?v=<?=date('Y-m-d H:i:s')?>"></script>

        <script>
            function copyToClipboard(element, linkID) {
                var error = $('#alert_'+linkID).empty();
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).data('link')).select();
                document.execCommand("copy");
                error.html('Copied');
                $temp.remove();

                window.setTimeout(function () {
                    error.html('');
                }, 2000);
            }
        </script>

        <?php include $INC_DIR . "footer.php"; ?>

    </div>
    </div>
</body>

</html>