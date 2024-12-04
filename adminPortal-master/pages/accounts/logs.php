<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/agrf/admin/core/init.php";
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "users";
    $link = "logs";
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
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Activity Logs</h5>
                        </div>
                        <div class="ibox-content">
                            <?php 
                            $user->get('logs', '*', NULL, "", 'log_ID DESC');
                            $i = 0;
                            if (!$user->count()) {
                                Danger("No recent logs");
                            } else {
                            ?>
                            <table class="table dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Item</th>
                                        <th>Action</th>
                                        <th class="text-center">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($user->data() as $resLog) { 
                                        $i ++;
                                    ?>
                                    <tr class="gradeX" style="background: #f8f8f8; border-bottom: 2px solid #fff;">
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $resLog->username; ?></td>
                                        <td><?php echo $resLog->item_name; ?></td>
                                        <td><small><?php echo $resLog->action; ?></small></td>
                                        <td class="text-center"><small class="label label-primary lbl-status"><i class="fa fa-clock-o"></i> <?php echo $resLog->log_date; ?></small></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>
        
        <?php include $INC_DIR . "footer.php"; ?>

        </div>
        </div>
</body>

</html>
