<?php
require_once "../../core/init.php";
if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

$link = "report";
$eventId = Hash::decryptToken(Input::get('eventId'));
$auth_token = $user->data()->token;

/** Filter By Participation Type */
$_PARTICIPATION_TYPE_TOKEN_ = Input::get('participationTypeToken', 'get');
?>
<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>

        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Total registered</h5>
                            <span class="label label-warning pull-right">Delegates</span>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = "";
                                        echo ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                        ?>
                                    </h1>
                                    <small>Total</small>
                                </div>
                                <div class="col-md-4">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND gender = 'Male'";
                                        echo ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                        ?>
                                    </h1>
                                    <small>Male</small>
                                </div>
                                <div class="col-md-4">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND gender = 'Female'";
                                        echo ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                        ?>
                                    </h1>
                                    <small>Female</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Total approved</h5>
                            <span class="label label-success pull-right">Delegates</span>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND status = 'APPROVED'";
                                        echo ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                        ?>
                                    </h1>
                                    <small>Total</small>
                                </div>
                                <div class="col-md-4">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND status = 'APPROVED'";
                                        $_filter_condition_ .= " AND gender = 'Male'";
                                        echo ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                        ?>
                                    </h1>
                                    <small>Male</small>
                                </div>
                                <div class="col-md-4">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND status = 'APPROVED'";
                                        $_filter_condition_ .= " AND gender = 'Female'";
                                        echo ReportController::getTotalParticipantsByEventID($eventId, $_filter_condition_);
                                        ?>
                                    </h1>
                                    <small>Female</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Accreditation</h5>
                            <span class="label label-warning pull-right">Badges</span>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-2">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = "";
                                        echo FutureEventController::getVIPCountByCategoryID($eventId);
                                        ?>
                                    </h1>
                                    <small>VIP</small>
                                </div>
                                <div class="col-md-2">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND gender = 'Male'";
                                        echo FutureEventController::getParticipantCountByCategoryID(20, $eventId);
                                        ?>
                                    </h1>
                                    <small>Secretariat Staff</small>
                                </div>
                                <div class="col-md-2">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND gender = 'Female'";
                                        echo FutureEventController::getParticipantCountByCategoryID(4, $eventId);
                                        ?>
                                    </h1>
                                    <small>Press & Media</small>
                                </div>

                                <div class="col-md-2">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = "";
                                        echo FutureEventController::getParticipantCountByCategoryID(35, $eventId);
                                        ?>
                                    </h1>
                                    <small>Volunteer</small>
                                </div>
                                <div class="col-md-2">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND gender = 'Male'";
                                        echo FutureEventController::getParticipantCountByCategoryID(6, $eventId);
                                        ?>
                                    </h1>
                                    <small>Supplier</small>
                                </div>
                                <div class="col-md-2">
                                    <h1 class="no-margins">
                                        <?php
                                        $_filter_condition_ = " AND gender = 'Female'";
                                        echo FutureEventController::getDelegateCountByCategoryID($eventId);
                                        ?>
                                    </h1>
                                    <small>Delegate</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div>
                                <h3 class="font-bold no-margins">
                                    Number of delegates filtered by:
                                </h3>
                            </div>

                            <div class="m-t-sm">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="" id="filterForm" class="row" method='post'>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select id="type" name="type" onchange="filterOptionsSubtype(this);" class="form-control" data-rule="required" data-msg="Please select type">
                                                        <option value="">- Select Participation Type -</option>
                                                        <option value="">All</option>
                                                        <?php
                                                        $_TYPE_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId, 'PAYABLE');
                                                        if ($_TYPE_DATA_) :
                                                            foreach ($_TYPE_DATA_ as $type_) :
                                                        ?>
                                                                <option value="<?= Hash::encryptToken($type_->id) ?>"><?= $type_->name ?></option>
                                                        <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select id="subtype" name="subtype" class="form-control" data-rule="required" data-msg="Please select subtype">
                                                        <option value="">- Select Participation Subtype -</option>
                                                        <option value="">All</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select id="status" name="status" class="form-control" data-rule="required" data-msg="Please select status">
                                                        <option value="">- Select Payment Status -</option>
                                                        <option value="">All</option>
                                                        <option value="PENDING">Pending</option>
                                                        <option value="APPROVED">Approved</option>
                                                        <option value="DENIED">Denied</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-top: 0;">
                                                <input type="hidden" name="eventId" id="eventId" value="<?php echo $eventId; ?>">
                                                <input type="hidden" name="request" id="request" value="fetchParticitants">
                                                <button type="submit" style="border-radius: 0px; padding: 6px 70px 6px 26px;" autocomplete="off" class="btn btn-md btn-primary col-md-3"> <i class=" fa fa-filter"></i> Filter </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-lg-9">
                                        <div class="flot-chart">
                                            <div class="flot-chart-content" id="flot-dashboard-chart"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <ul class="stat-list m-t-lg">
                                            <li>
                                                <h2 class="no-margins" id="participants-table">0</h2>
                                                <small>Delegates</small>
                                                <div class="progress progress-mini">
                                                    <div class="progress-bar" style="width: 48%;"></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Payment</span>
                            <h5>Via Payment Online Platform</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-xs-4">
                                    <small class="stats-label">Registration</small>
                                    <h4><?= number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelCount($eventId, "ONLINE_PAYMENT", "")); ?></h4>
                                </div>

                                <div class="col-xs-4">
                                    <small class="stats-label">Completed Payment</small>
                                    <h4><?= number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelCount($eventId, "ONLINE_PAYMENT", "COMPLETED")); ?></h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">Total Paid</small>
                                    <h4>$ <?= number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelAmount($eventId, "ONLINE_PAYMENT", "")); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Payment</span>
                            <h5>Via Bank Transfer</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-xs-4">
                                    <small class="stats-label">Registration</small>
                                    <h4><?= number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelCount($eventId, "BANK_TRANSFER", "")); ?></h4>
                                </div>

                                <div class="col-xs-4">
                                    <small class="stats-label">Completed Payment</small>
                                    <h4><?= number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelCount($eventId, "BANK_TRANSFER", "COMPLETED")); ?></h4>
                                </div>
                                <div class="col-xs-4">
                                    <small class="stats-label">Total Paid</small>
                                    <h4>$ <?= number_format(PaymentController::getPaymentStatsRegistrationByPaymentChannelAmount($eventId, "BANK_TRANSFER", "")); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paid Registered Participants by contienet -->
            <?php
            $Africa = ReportController::getTotalPayingParticipantsByContinent($eventId, "Africa");
            $Asia = ReportController::getTotalPayingParticipantsByContinent($eventId, "Asia");
            $Europe = ReportController::getTotalPayingParticipantsByContinent($eventId, "Europe");
            $NorthAmerica =  ReportController::getTotalPayingParticipantsByContinent($eventId, "North America");
            $SouthAmerica = ReportController::getTotalPayingParticipantsByContinent($eventId, "South America");
            $Oceania = ReportController::getTotalPayingParticipantsByContinent($eventId, "Oceania");
            ?>


            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5> Total paying participants by continent</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">

                            <div class="row">
                                <div class="col-lg-6">
                                    <table class="table table-hover margin bottom">
                                        <thead>
                                            <tr>
                                                <th style="width: 1%" class="text-center">No.</th>
                                                <th>Continent</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td>Africa</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= $Africa ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>Europe</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= $Europe ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>North America</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= $NorthAmerica ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>South America</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= $SouthAmerica ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td>Asia</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= $Asia ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td>Oceania</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= $Oceania ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6">
                                    <div id="world-map-1" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Paid Participants by continent -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Registered participants by continent</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="ibox-content">

                            <div class="row">
                                <div class="col-lg-6">
                                    <table class="table table-hover margin bottom">
                                        <thead>
                                            <tr>
                                                <th style="width: 1%" class="text-center">No.</th>
                                                <th>Continent</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td>Africa</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= ReportController::getTotalParticipantsByContinent($eventId, "Africa"); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>Europe</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= ReportController::getTotalParticipantsByContinent($eventId, "Europe"); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>North America</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= ReportController::getTotalParticipantsByContinent($eventId, "North America"); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">2</td>
                                                <td>South America</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= ReportController::getTotalParticipantsByContinent($eventId, "South America"); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td>Asia</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= ReportController::getTotalParticipantsByContinent($eventId, "Asia"); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">3</td>
                                                <td>Oceania</td>
                                                <td class="text-center">
                                                    <span class="label label-primary">
                                                        <?= ReportController::getTotalParticipantsByContinent($eventId, "Oceania"); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6">
                                    <div id="world-map" style="height: 300px;"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span hidden="" id="map-regions"></span>
        </div>

        <script type="text/javascript">
            var eventId = '<?php echo $eventId; ?>';
            var participationTypeToken = '<?php echo $_PARTICIPATION_TYPE_TOKEN_; ?>';
            var linkto = '<?php linkto("pages/reports/reports_action.php"); ?>';
            var mapDataURL = '<?php linkto("pages/reports/reports_action.php"); ?>';
        </script>
        <script src="<?php linkto("pages/reports/reports.js"); ?>"></script>

        <?php include $INC_DIR . "footer.php"; ?>

    </div>
    </div>

    <!-- Flot -->
    <script src="<?php linkto('js/plugins/flot/jquery.flot.js'); ?>"></script>
    <script src="<?php linkto('js/plugins/flot/jquery.flot.tooltip.min.js'); ?>"></script>
    <script src="<?php linkto('js/plugins/flot/jquery.flot.spline.js'); ?>"></script>
    <script src="<?php linkto('js/plugins/flot/jquery.flot.resize.js'); ?>"></script>
    <script src="<?php linkto('js/plugins/flot/jquery.flot.pie.js'); ?>"></script>
    <script src="<?php linkto('js/plugins/flot/jquery.flot.symbol.js'); ?>"></script>
    <script src="<?php linkto('js/plugins/flot/jquery.flot.time.js'); ?>"></script>

    <!-- Jvectormap -->
    <script src="<?php linkto('js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>"></script>
    <script src="<?php linkto('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>"></script>

    <!-- EayPIE -->
    <script src="<?php linkto('js/plugins/easypiechart/jquery.easypiechart.js'); ?>"></script>

    <script>
        $(document).ready(function() {
            $('.chart').easyPieChart({
                barColor: '#f8ac59',
                //                scaleColor: false,
                scaleLength: 5,
                lineWidth: 4,
                size: 80
            });

            $('.chart2').easyPieChart({
                barColor: '#1c84c6',
                //                scaleColor: false,
                scaleLength: 5,
                lineWidth: 4,
                size: 80
            });

            var data2 = [
                [gd(2012, 1, 1), 7],
                [gd(2012, 1, 2), 6],
                [gd(2012, 1, 3), 4],
                [gd(2012, 1, 4), 8],
                [gd(2012, 1, 5), 9],
                [gd(2012, 1, 6), 7],
                [gd(2012, 1, 7), 5],
                [gd(2012, 1, 8), 4],
                [gd(2012, 1, 9), 7],
                [gd(2012, 1, 10), 8],
                [gd(2012, 1, 11), 9],
                [gd(2012, 1, 12), 6],
                [gd(2012, 1, 13), 4],
                [gd(2012, 1, 14), 5],
                [gd(2012, 1, 15), 11],
                [gd(2012, 1, 16), 8],
                [gd(2012, 1, 17), 8],
                [gd(2012, 1, 18), 11],
                [gd(2012, 1, 19), 11],
                [gd(2012, 1, 20), 6],
                [gd(2012, 1, 21), 6],
                [gd(2012, 1, 22), 8],
                [gd(2012, 1, 23), 11],
                [gd(2012, 1, 24), 13],
                [gd(2012, 1, 25), 7],
                [gd(2012, 1, 26), 9],
                [gd(2012, 1, 27), 9],
                [gd(2012, 1, 28), 8],
                [gd(2012, 1, 29), 5],
                [gd(2012, 1, 30), 8],
                [gd(2012, 1, 31), 25]
            ];

            var data3 = [
                [gd(2012, 1, 1), 800],
                [gd(2012, 1, 2), 500],
                [gd(2012, 1, 3), 600],
                [gd(2012, 1, 4), 700],
                [gd(2012, 1, 5), 500],
                [gd(2012, 1, 6), 456],
                [gd(2012, 1, 7), 800],
                [gd(2012, 1, 8), 589],
                [gd(2012, 1, 9), 467],
                [gd(2012, 1, 10), 876],
                [gd(2012, 1, 11), 689],
                [gd(2012, 1, 12), 700],
                [gd(2012, 1, 13), 500],
                [gd(2012, 1, 14), 600],
                [gd(2012, 1, 15), 700],
                [gd(2012, 1, 16), 786],
                [gd(2012, 1, 17), 345],
                [gd(2012, 1, 18), 888],
                [gd(2012, 1, 19), 888],
                [gd(2012, 1, 20), 888],
                [gd(2012, 1, 21), 987],
                [gd(2012, 1, 22), 444],
                [gd(2012, 1, 23), 999],
                [gd(2012, 1, 24), 567],
                [gd(2012, 1, 25), 786],
                [gd(2012, 1, 26), 666],
                [gd(2012, 1, 27), 888],
                [gd(2012, 1, 28), 900],
                [gd(2012, 1, 29), 178],
                [gd(2012, 1, 30), 555],
                [gd(2012, 1, 31), 993]
            ];


            var dataset = [{
                label: "Number of orders",
                data: data3,
                color: "#1ab394",
                bars: {
                    show: true,
                    align: "center",
                    barWidth: 24 * 60 * 60 * 600,
                    lineWidth: 0
                }

            }, {
                label: "Payments",
                data: data2,
                yaxis: 2,
                color: "#464f88",
                lines: {
                    lineWidth: 1,
                    show: true,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.2
                        }, {
                            opacity: 0.2
                        }]
                    }
                },
                splines: {
                    show: false,
                    tension: 0.6,
                    lineWidth: 1,
                    fill: 0.1
                },
            }];


            var options = {
                xaxis: {
                    mode: "time",
                    tickSize: [3, "day"],
                    tickLength: 0,
                    axisLabel: "Date",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Arial',
                    axisLabelPadding: 10,
                    color: "#d5d5d5"
                },
                yaxes: [{
                    position: "left",
                    max: 1070,
                    color: "#d5d5d5",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Arial',
                    axisLabelPadding: 3
                }, {
                    position: "right",
                    clolor: "#d5d5d5",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: ' Arial',
                    axisLabelPadding: 67
                }],
                legend: {
                    noColumns: 1,
                    labelBoxBorderColor: "#000000",
                    position: "nw"
                },
                grid: {
                    hoverable: false,
                    borderWidth: 0
                }
            };

            function gd(year, month, day) {
                return new Date(year, month - 1, day).getTime();
            }

            var previousPoint = null,
                previousLabel = null;

            $.plot($("#flot-dashboard-chart"), dataset, options);

            var mapData = {
                "US": 298,
                "SA": 200,
                "DE": 220,
                "FR": 540,
                "CN": 120,
                "AU": 760,
                "BR": 550,
                "IN": 200,
                "GB": 120,
            };

            getActiveRegions();

            window.setTimeout(function() {
                let mapPaidData = $("#map-regions").html();
                console.log(mapPaidData);
                mapPaidData = JSON.parse(mapPaidData);
                $('#world-map-1').vectorMap({
                    map: 'world_mill_en',
                    backgroundColor: "transparent",
                    regionStyle: {
                        initial: {
                            fill: '#e4e4e4',
                            "fill-opacity": 0.9,
                            stroke: 'none',
                            "stroke-width": 0,
                            "stroke-opacity": 0
                        }
                    },

                    series: {
                        regions: [{
                            values: mapPaidData,
                            scale: ["#1ab394", "#22d6b1"],
                            normalizeFunction: 'polynomial'
                        }]
                    },
                    onRegionOver: function(e, el, code) {
                        this.html(mapPaidData[code]);
                    }
                });
            }, 1000);






            $('#world-map').vectorMap({
                map: 'world_mill_en',
                backgroundColor: "transparent",
                regionStyle: {
                    initial: {
                        fill: '#e4e4e4',
                        "fill-opacity": 0.9,
                        stroke: 'none',
                        "stroke-width": 0,
                        "stroke-opacity": 0
                    }
                },

                series: {
                    regions: [{
                        values: mapData,
                        scale: ["#1ab394", "#22d6b1"],
                        normalizeFunction: 'polynomial'
                    }]
                },
            });

        });
    </script>
</body>

</html>