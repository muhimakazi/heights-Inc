<?php
require_once "../../core/init.php";

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

$page = "reports";
$link = "attendance";

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

    b {
        font: bold;
    }
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        
            <div class="wrapper wrapper-content" style="padding: 25px 0;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-content" style="padding: 15px 20px 0px 20px;">

                                    <h4>Filter By:</h4>
                                    <form action="" id="filterForm" class="row" method='post'>

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
                                                <label for="">Date:</label>
                                                <select id="added_date" name="added_date" class="form-control" onchange="showParticipantsList()">
            <?php
            $eventDate  = DB::getInstance()->query("SELECT `start_date`, `end_date` FROM `future_event` WHERE `id` = $eventId");
            $start_date = dateFormat($eventDate->first()->start_date);
            $end_date   = dateFormat($eventDate->first()->end_date);
            $format = 'Y-m-d';
            $interval = new DateInterval('P1D');
            $realEnd = new DateTime($end_date);
            $realEnd->add($interval);
            $period = new DatePeriod(new DateTime($start_date), $interval, $realEnd);
            foreach($period as $date):
                $_TODAY = $date->format($format);
            ?>
                                                    <option value="<?=$_TODAY?>"><?= date("j F Y", strtotime($_TODAY))?></option>
            <?php
            endforeach;
            ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2" style="padding-top: 22px;">
                                            <input type="hidden" name="eventId" id="eventId" value="<?php echo $event_key; ?>">
                                            <input type="hidden" name="request" id="request" value="fetchParticitants">
                                            <button type="submit" style="border-radius: 0px; padding: 6px 70px 6px 26px;"
                                                autocomplete="off" class="btn btn-md btn-primary col-md-3"> <i
                                                    class=" fa fa-filter"></i> Filter </button>
                                        </div>
                                        <?php if ($user->hasPermission('admin') || $user->hasPermission('event-admin')) { ?>
                                        <div class="col-md-1">
                                            <label for="" style="visibility: hidden;">Export</label>
                                            <a class="btn btn-primary exportBtn" style="border-radius: 0px; padding: 6px 30px"><i class="fa fa-download"></i> Export</a>
                                        </div>
                                        <?php } ?>
                                    </form>

                                    <!-- <br><br> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">

                                <div class="ibox-title" style="height: auto; overflow: auto;">
                                    <h5>Attendance by selected date</h5>
                                </div>

                                <div class="ibox-content">
                                    <table id="attendanceTable" class='table table-hover customTable'>
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Full name</th>
                                            <th>Pass type</th>
                                            <th>Pass subtype</th>
                                            <th>Job title</th>
                                            <th>Organisation</th>
                                            <th>Country</th>
                                            <th>Location</th>
                                            <th>Time</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                var linkto = '<?=DN?>/pages/attendance/attendance_action.php';
            </script>

            <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
            <script src="<?=DN?>/pages/attendance/attendance.js?v=<?=date('Y-m-d H:i:s')?>"></script>

            <?php include $INC_DIR . "footer.php"; ?>

        </div>
    </div>
</body>

</html>