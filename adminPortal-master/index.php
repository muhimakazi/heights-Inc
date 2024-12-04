<?php
require_once "core/init.php";

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

if ($user->hasPermission('guest')) {
    Redirect::to("participants/list");
}

if ($user->hasPermission('group-admin')) {
    Redirect::to("group/dashboard");
}

if ($user->hasPermission('accreditation')) {
    Redirect::to("accreditation/list");
}


$page = "home";

// Event details
$eventDetails = ReportController::getEventDetailsByID($eventId);
$ticket_type  = $eventDetails->ticket_type;
$start_date   = dateFormat($eventDetails->start_date);
$end_date     = dateFormat($eventDetails->end_date);
$today        = date("Y-m-d");

//Quick registration numbers
$totalRegistration = $approvedRegistration = $pendingRegistration = $rejectedRegistration = $approvedRegistrationPercentage = $pendingRegistrationPercentage = $rejectedRegistrationPercentage = 0;

$_filter_condition_ = "";
$totalRegistration = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
if ($totalRegistration > 0) {
    $_filter_condition_ = " AND future_participants.status = 'APPROVED'";
    $approvedRegistration = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $_filter_condition_ = "AND future_participants.status = 'PENDING'";
    $pendingRegistration = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $_filter_condition_ = "AND future_participants.status = 'DENIED'";
    $rejectedRegistration = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);

    $approvedRegistrationPercentage = ($approvedRegistration*100)/$totalRegistration;
    $pendingRegistrationPercentage = ($pendingRegistration*100)/$totalRegistration;
    $rejectedRegistrationPercentage = ($rejectedRegistration*100)/$totalRegistration;
}

//Quick payment numbers
$totalPayment = $totalPaymentAmount = $completedPayment = $completedPaymentAmount = $pendingPayment = $pendingPaymentAmount = $refundedPayment = $refundedPaymentAmount = $completedPaymentPercentage = $completedPaymentPercentage = $refundedPaymentPercentage = $pendingPaymentPercentage = 0;

$_filter_condition_ = "";
$totalPayment = ReportController::getPaymentStatsRegistrationCount($eventId, $_filter_condition_);

if ($totalPayment > 0) {
    $_filter_condition_ = "";
    $totalPaymentAmount = ReportController::getPaymentStatsRegistrationAmount($eventId, $_filter_condition_);

    $_filter_condition_     = " AND future_payment_transaction_entry.transaction_status = 'COMPLETED'";
    $completedPayment       = ReportController::getPaymentStatsRegistrationCount($eventId, $_filter_condition_);
    $completedPaymentAmount = ReportController::getPaymentStatsRegistrationAmount($eventId, $_filter_condition_);

    $_filter_condition_   = "AND future_payment_transaction_entry.transaction_status = 'PENDING'";
    $pendingPayment       = ReportController::getPaymentStatsRegistrationCount($eventId, $_filter_condition_);
    $pendingPaymentAmount = ReportController::getPaymentStatsRegistrationAmount($eventId, $_filter_condition_);

    $_filter_condition_    = "AND future_payment_transaction_entry.transaction_status = 'REFUNDED'";
    $refundedPayment       = ReportController::getPaymentStatsRegistrationCount($eventId, $_filter_condition_);
    $refundedPaymentAmount = ReportController::getPaymentStatsRegistrationAmount($eventId, $_filter_condition_);

    $completedPaymentPercentage = ($completedPayment*100)/$totalPayment;
    $pendingPaymentPercentage   = ($pendingPayment*100)/$totalPayment;
    $refundedPaymentPercentage  = ($refundedPayment*100)/$totalPayment;
}

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="<?=DN?>/css/select2.min.css">
    <link rel="stylesheet" href="<?=DN?>/css/flag-icon.min.css">
    <!-- Data Tables -->
    <link href="<?=DN?>/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?=DN?>/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
    <link href="<?=DN?>/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">

    <?php include  "includes/head-index.php"; ?>


    <script src="<?=DN?>/js/jquery-2.1.1.js"></script>

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
    b , strong {
        font-family: Lato-Regular;
    }
    </style>
</head>

<body>
    <div id="wrapper">
        <input type="hidden" id="active-event" value=<?=$encodedEventId?> />
        <?php include "includes/nav.php"; ?>

        <!-- Main content -->
        <section class="content mt-5" style="margin-top: 1.6%;">
            <div class="container-fluid">
                <div class="row" style="margin-bottom: 1%;">
                    <div class="col-md-12">
                        <h2 style="font-size: 150%; margin-top: 0;">Registration overview</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">All</span>
                                <h5>Total registrations</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$totalRegistration?></h1>
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
                                <h1 class="no-margins"><?=$pendingRegistration?></h1>
                                <div class="stat-percent font-bold text-info"><?=number_format($pendingRegistrationPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
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
                                <h1 class="no-margins"><?=$approvedRegistration?></h1>
                                <div class="stat-percent font-bold text-success"><?=number_format($approvedRegistrationPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
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
                                <h1 class="no-margins"><?=$rejectedRegistration?></h1>
                                <div class="stat-percent font-bold text-danger"><?=number_format($rejectedRegistrationPercentage, 2)?>% <i class="fa fa-level-down"></i></div>
                                <small>Participants</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->


        <!-- Main content -->
        <section class="content mt-5" style="margin-top: 0%;">
            <div class="container-fluid">
                <div class="row" style="margin-bottom: 1%;">
                    <div class="col-md-12">
                        <h2 style="font-size: 150%; margin-top: 0;">Registration by category</h2>
                    </div>
                </div>
                <div class="row">
                <?php
                    $passCount = ReportController::getAllPacipationCategoryCountByEventID($eventId);
                    if($passCount > 1):
                        $catRecords = ReportController::getAllPacipationCategoryByEventID($eventId);
                        if ($catRecords):
                            $count_ = 0;
                            foreach($catRecords as $category_):$count_++;
                                $type_id = $category_->id;
                                $type_name = $category_->name;
                                $_filter_condition_ = " AND future_participants.participation_type_id = '$type_id'";
                                $totalByCat = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                if ($totalRegistration != 0) {
                                    $totalByCatPercentage = ($totalByCat*100)/$totalRegistration;
                                } else {
                                    $totalByCatPercentage = 0;
                                }
                ?>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">All</span>
                                <h5><?=$type_name?></h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$totalByCat?></h1>
                                <div class="stat-percent font-bold text-success"><?=number_format($totalByCatPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small>Participants</small>
                            </div>
                        </div>
                    </div>

                <?php
                            endforeach;
                        endif;
                    elseif ($passCount == 1):
                        $passID = ReportController::getOnePacipationCategoryByEventID($eventId);
                        $passID = $passID->id;
                        $passSubTypesRecords = ReportController::getPacipationSubCategoryByID($passID);
                        if ($passSubTypesRecords):
                            foreach($passSubTypesRecords as $sub_type):
                                $sub_type_id = $sub_type->id;
                                $sub_type_name = $sub_type->name;
                                $_filter_condition_ = " AND future_participants.participation_sub_type_id = '$sub_type_id'";
                                $totalByCat = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                if ($totalRegistration != 0) {
                                    $totalByCatPercentage = ($totalByCat*100)/$totalRegistration;
                                } else {
                                    $totalByCatPercentage = 0;
                                }
                ?>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">All</span>
                                <h5><?=$sub_type_name?></h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?=$totalByCat?></h1>
                                <div class="stat-percent font-bold text-success"><?=number_format($totalByCatPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small>Participants</small>
                            </div>
                        </div>
                    </div>

                <?php
                            endforeach;
                        endif;
                    else:
                        $passCount = ReportController::getAllFormPassCountByEventID($eventId);
                        if($passCount):
                            $catRecords = ReportController::getAllFormPassByEventID($eventId);
                            if ($catRecords):
                                $count_ = 0;
                                foreach($catRecords as $category_):$count_++;
                                    $type_id = $category_->id;
                                    $type_name = $category_->form_name;
                                    $_filter_condition_ = " AND future_participants.form_id = '$type_id'";
                                    $totalByCat = ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                    if ($totalRegistration != 0) {
                                        $totalByCatPercentage = ($totalByCat*100)/$totalRegistration;
                                    } else {
                                        $totalByCatPercentage = 0;
                                    }
                    ?>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-info pull-right">All</span>
                                    <h5><?=$type_name?></h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?=$totalByCat?></h1>
                                    <div class="stat-percent font-bold text-success"><?=number_format($totalByCatPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                    <small>Participants</small>
                                </div>
                            </div>
                        </div>

                    <?php
                                endforeach;
                            endif;

                        endif;
                    endif;
                ?>

                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

<?php if($ticket_type != 'Free attendance'){ ?>
        <!-- Main content -->
        <section class="content mt-5" style="margin-top: 0%;">
            <div class="container-fluid">
                <div class="row" style="margin-bottom: 1%;">
                    <div class="col-md-12">
                        <h2 style="font-size: 150%;">Payment overview</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">All</span>
                                <h5>Total initiated payments</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins">$<?=$totalPaymentAmount ?></h1>
                                <div class="stat-percent font-bold text-default">100% <i class="fa fa-bolt"></i></div>
                                <small><b><?=$totalPayment ?></b> Participants</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-warning pull-right">Pending</span>
                                <h5>Pending payments</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins">$<?=$pendingPaymentAmount ?></h1>
                                <div class="stat-percent font-bold text-info">$ <?=number_format($pendingPaymentPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small><b><?=$pendingPayment ?></b> Participants</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-success pull-right">Revenue</span>
                                <h5>Completed payments</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins">$<?=$completedPaymentAmount ?></h1>
                                <div class="stat-percent font-bold text-success"><?=number_format($completedPaymentPercentage, 2)?>% <i class="fa fa-level-up"></i></div>
                                <small><b><?=$completedPayment ?></b> Participants</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-danger pull-right">Refunded</span>
                                <h5>Refunded payments</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins">$<?=$refundedPaymentAmount ?></h1>
                                <div class="stat-percent font-bold text-danger"><?=number_format($refundedPaymentPercentage, 2)?>% <i class="fa fa-level-down"></i></div>
                                <small><b><?=$refundedPayment ?></b> Participants</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
<?php } ?>


<?php if($start_date < $today) { ?>
        <!-- Main content -->
        <section class="content mt-5" style="margin-top: 0%;">
            <div class="container-fluid">
                <div class="row" style="display: none;">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="height: auto; overflow: auto;">
                                <h5>Registrations by day</h5>
                            </div>
                            <div class="ibox-content" id="participants-table"></div>

                        </div>
                    </div>
                </div>

                <div class="row" id="registration-by-category"></div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="height: auto; overflow: auto; padding-bottom: 0;">
                                <h5>ATTENDANCE REPORT</h5>
                            </div>
                            <div class="ibox-content" id="attendance-table"></div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title" style="height: auto; overflow: auto;">
                                <h5>REGISTRATION / ACCREDITATION</h5>
                            </div>
                            <div class="ibox-content" style="padding: 15px 20px 0px 20px; overflow: auto;">
                                <form action="" id="filterForm" class="row" method='post'>
                                    <!-- <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="">Country:</label>
                                            <select id="residence_country" name="residence_country" class="form-control"
                                                data-rule="required" data-title="Select country" data-msg="Please select country">
                                                <option></option>
                                            </select>
                                            <div class="validate" id="residence_country_error"></div>
                                        </div>
                                    </div> -->

                                    <div class="col-md-4">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="subtype" name="subtype" class="form-control" data-rule="required"
                                                data-msg="Please select subtype">
                                                <option value="">- Select Participation Subtype -</option>
                                                <option value="">All</option>
                                            </select>
                                        </div>
                                    </div>
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

                                    <!-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <select id="status" name="status" class="form-control" data-rule="required"
                                                data-msg="Please select status">
                                                <option value="">- Select Status -</option>
                                                <option value="">All</option>
                                                <option value="APPROVED">Approved</option>
                                                <option value="PENDING">Pending</option>
                                                <option value="DENIED">Rejected</option>

                                            </select>
                                        </div>
                                    </div> -->

                                    <div class="col-md-2" style="padding-top: 22px;">
                                        <input type="hidden" name="eventId" id="eventId" value="<?php echo $encodedEventId; ?>">
                                        <input type="hidden" name="request" id="request" value="fetchRegistrationByCountry">
                                        <button type="submit" style="border-radius: 0px; padding: 6px 70px 6px 26px;"
                                            autocomplete="off" class="btn btn-md btn-primary col-md-3"> <i
                                                class=" fa fa-filter"></i> Filter </button>
                                    </div>
                                </form>
                                <div style="margin-bottom: 20px;">
                                     <h5 id="country-data">Total: 0</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
<?php } ?>

        <script type="text/javascript">
            var linkto = '<?=DN?>/pages/dashboard/dashboard_action.php';
        </script>

        <script src="<?=DN?>/pages/dashboard/dashboard.js?v=<?=date('Y-m-d H:i:s')?>"></script>

        <?php include $INC_DIR . "footer-index.php"; ?>

        <!-- Mainly scripts -->

        <!-- Data Tables -->
        <script src="<?=DN?>/js/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="<?=DN?>/js/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="<?=DN?>/js/plugins/dataTables/dataTables.responsive.js"></script>
        <script src="<?=DN?>/js/plugins/dataTables/dataTables.tableTools.min.js"></script>

        <script src="<?=DN?>/js/select2.min.js"></script>
        <script src="<?=DN?>/js/countries.js?v=1.1"></script>
        
    </div>
    </div>

</body>

</html>