<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) {
    Redirect::to('login');
}

$page = "groups";
$link = "group_list";

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
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Registration Group</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php linkto(''); ?>">Home</a>
                    </li>
                    <li>
                        <a> Groups</a>
                    </li>
                    <li class="active">
                        <strong>All Groups</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <!-- <div class="col-lg-2"></div> -->
                
               

                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        
                        <!-- <div class="ibox-title" style="height: auto;">
                            Filter By:
                        </div> -->

                        <div class="ibox-content" style="padding: 15px 20px 0px 20px;">
                        <!-- <a href="<?php linkto("pages/participants/export_delegates/$exportId"); ?>" class="btn btn-primary pull-right" style="border-radius: 0px;"><i class="fa fa-download"></i> Export participants</a> -->
                        <form action="" id="filterForm" class="row" method='post'>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select id="status" name="status"  class="form-control" data-rule="required" data-msg="Please select status">
                                        <option value="">- Select Status -</option>
                                        <option value="">All</option>
                                        <option value="PENDING">Pending</option>
                                        <!-- <option value="ACTIVE">Activated</option>
                                        <option value="DEACTIVE">Deactivated</option> -->
                                        <option value="ACCEPTED">Accepted</option>
                                        <option value="APPROVED">Approved</option>
                                        <option value="REJECTED">Rejected</option>

                                    </select>
                                </div>  
                            </div>
                            <!-- <div class="col-md-1"> -->
                                <button type="submit" style="border-radius: 0px; padding: 6px 70px 6px 26px;"  autocomplete="off" class="btn btn-md btn-primary col-md-1"> <i class=" fa fa-filter"></i> Filter</button>
                            <!-- </div> -->
                        </form>

                <!-- <br><br> -->
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-title" style="height: auto;">
                          
                        </div>

                        <div class="ibox-content" id="participants-table"></div>
                        
                    </div>
                </div>
                <!-- <div class="col-lg-2"></div> -->
            </div>
        </div>

        <script type="text/javascript">
            var linkto  = '<?=DN?>/pages/groups/controller_action.php';
        </script>
        <script src="<?=DN?>/pages/groups/group_list.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        </div>
    </div>
</body>

</html>
