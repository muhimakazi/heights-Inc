<?php
require_once "../../core/init.php"; 

$page = "home";
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
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <a href="" class="btn btn-xs btn-primary pull-right"><i class="fa fa-eye"></i> View</a>
                            <h5>Maximum Slots</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">
                                <?=FutureEventGroupController::getStatsTotalMaxDelegateSlots($eventId, $_GROUP_ID_)?>
                                <span class="pull-right"><i class="fa fa-calendar"></i></span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <a href="" class="btn btn-xs btn-primary pull-right"><i class="fa fa-eye"></i> View</a>
                            <h5>Delegates Registered</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">
                                <?=FutureEventGroupController::getStatsTotalDelegateRegistered($eventId, $_GROUP_ID_, 'ALL')?>
                                <span class="pull-right"><i class="fa fa-calendar"></i></span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <a href="" class="btn btn-xs btn-primary pull-right"><i class="fa fa-eye"></i> View</a>
                            <h5>Delegates Approved</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">
                                <?=FutureEventGroupController::getStatsTotalDelegateRegistered($eventId, $_GROUP_ID_, 'APPROVED')?>
                                <span class="pull-right"><i class="fa fa-calendar"></i></span>
                            </h1>
                        </div>
                    </div>
                </div>
<?php
if($_GROUP_DATA_->status == 'ACCEPTED' ):
    if(!$_PARTICIPANT_DATA_ ):
?>
                <!-- Group Status Accepted And Registration and Payment Process Not yet completed -->
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" id="card-profile">
                        <div class="ibox-title" style="height: auto;">
                        
                        </div>

                        <div class="ibox-content">
                            <div class="row gutters-sm">
                                <div class="col-md-12 col-ld-12 col-xs-12 col-sm-12 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <div class="mt-3">
                                                    <h4>The Group Profile will be accessible once the Group Registration and  Payment process completed. Use the link provided in the group accceptance email sent to you or <br> </h4>
                                                    <p class="text-secondary mb-1 display_status_" style="color: <?=$_status_color_?>;">  <a href="<?=$_GROUP_DATA_->generated_link?>" target="_blank"> <i class="fa fa-link"></i> Click here</a> to proceed to complete the Group Registration and Payment </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div> 
<?php
    endif;
endif;
?>
       

                <!-- Group List Of Slots -->
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" id="card-profile">
                        <div class="ibox-title" style="height: auto;">
                            <div class="row">
                            <h3 class="col-md-8"><?= $_GROUP_NAME_?> Group Registered Members</h3>
                            <!-- <span class="col-md-4 pull-right" style="text-align: right;">  <h3>Total Amount: <?=number_format($_GROUP_DATA_->total_group_amount)?> <small><?= FutureEventGroupController::getEventPaymentCurrency ($eventId) ?></small>  </h3> </span> -->
                            <span class="col-md-4 pull-right"><a href="<?php linkto("group/register/$encodedEventId"); ?>" class="btn btn-sm btn-primary pull-right"><i class="fa fa fa-plus"></i> Add new member</a></span>
                            </div>
                        </div>

                        <div class="ibox-content" id="participants-table"></div>

                    </div>
                </div> 

            </div>
        </div>
        

<!-- <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
         
        </div>
    </div> -->

        <script type="text/javascript">
            var linkto  = '<?=DN?>/pages/groups/controller_action.php';
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
        <script src="<?=DN?>/pages/groups/group_admin_delegates.js?v=<?=date('Y-m-d H:i:s')?>"></script>
                
        <?php include $INC_DIR . "footer.php"; ?>

        </div>
    </div>

   

</body>
</html>