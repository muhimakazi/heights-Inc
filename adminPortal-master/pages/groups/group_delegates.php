<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) {
    Redirect::to('login');
}

$page = "groups";
$link = "group_delegates";
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
                <h2>Registration Groups</h2>
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
                        <form action="" id="filterForm" class="row" method='post'>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select id="type" name="type" onchange="filterOptionsSubtype(this);" required  class="form-control" data-rule="required" data-msg="Please select type">
                                        <option value="">- Select Group -</option>
                                        <option value="">All</option>
    <?php
    $_LIST_DATA_ = FutureEventGroupController::getGroupList($eventId, $_filter_condition_);
    if($_LIST_DATA_):
        foreach($_LIST_DATA_ As $list_):
    ?>  
                                        <option value="<?=Hash::encryptToken($list_->id)?>"><?=$list_->group_name?></option>
    <?php
        endforeach;
    endif;
    ?>
                                    </select>
                                </div>  
                            </div>
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
        <script src="<?=DN?>/pages/groups/group_delegates.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        </div>
    </div>
</body>

</html>
