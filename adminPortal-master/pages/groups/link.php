<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "groups";
    $link = "group_delegates_registration_link";
    $eventId = base64_decode(Input::get('eventId'));
?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Events</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php linkto(''); ?>">Home</a></li>
                    <li><a href="<?php linkto('pages/events/events_list'); ?>">Events</a></li>
                    <li><a>Website content</a></li>
                    <li class="active"><strong>Registration link</strong></li>
                </ol>
            </div>
            <div class="col-lg-2">
                <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addPartnerModal" id="addClient" style="margin-top: 50px;"><i class="fa fa fa-external-link"></i> Generate pivite link</button> -->
                <!-- Generate Link modal -->
                <div class="modal inmodal fade" id="generateLinkModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Send private link</h4>
                            </div>
                            <form  action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="addLinkForm">
                                <div class="modal-body">
                                    <div id="add-link-messages"></div>
                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group col-md-6">
                                                <label>First name<small class="red-color">*</small></label>
                                                <input type="text" name="firstname" id="firstname" placeholder="First name" class="form-control" data-rule="required" data-msg="Please enter first name"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Last name<small class="red-color">*</small></label>
                                                <input type="text" name="lastname" id="lastname" placeholder="Last name" class="form-control" data-rule="required" data-msg="Please enter last name"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Email<small class="red-color">*</small></label>
                                                <input type="text" name="email" id="email" placeholder="Email address" class="form-control" data-rule="required" data-msg="Please enter email address"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Select Participation Type<small class="red-color">*</small></label>
                                                <select class="form-control" name="paticipation_sub_type" id="paticipant_type" data-rule="required" data-msg="Please select  Category"> 
                                                    <option value="" selected="">[--Select--]</option>
<?php
$_SUB_CATEGORIES_DATA_ = FutureEventController::getPrivatePacipationSubCategory($eventId);
if($_SUB_CATEGORIES_DATA_):
    foreach($_SUB_CATEGORIES_DATA_ As $_sub_type_ ):
?>
                                                    <option value="<?=Hash::encryptAuthToken($_sub_type_->sub_type_id)?>" ><?=$_sub_type_->name?> - <?=$_sub_type_->sub_type_name?> - <?=Functions::getEventCategory($_sub_type_->sub_type_category) ?></option>
<?php
    endforeach;
endif;
?>      
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="request" value="sendPrivateLink"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="addLinkButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Send link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row-flex" id="partners-list"></div>
            <div class="col-md-12">
                <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#generateLinkModal" ><i class="fa fa-plus-circle"></i> Add Registration Link</button>
                            <h5>Registration Link</h5> -->
                        </div>
                    <div class="ibox-content" id="list-generated-links">
                
                    </div>
                </div>
            </div>
        </div>

<?php
$_LIST_DATA_ = FutureEventController::getGeneratedPrivateLinks($eventId);
if($_LIST_DATA_): $count_ = 0;
    foreach($_LIST_DATA_  As $_link_): $count_++;
?>
                  <!-- Edit Link Modal  -->
                  <div class="modal inmodal fade" id="editLinkModal<?=Hash::encryptToken($_link_->id)?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Edit  Private Link </h4>
                            </div>
                            <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="editLinkForm">
                                <div class="modal-body">
                                    <div id="edit-link-messages"></div>
                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group col-md-6">
                                                <label>First name<small class="red-color">*</small></label>
                                                <input type="text" name="firstname" id="firtname" value="<?=$_link_->firstname?>" placeholder="First name" class="form-control" data-rule="required" data-msg="Please enter first name"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Last name<small class="red-color">*</small></label>
                                                <input type="text" name="lastname" id="lastname" value="<?=$_link_->lastname?>"  placeholder="Last name" class="form-control" data-rule="required" data-msg="Please enter last name"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Email<small class="red-color">*</small></label>
                                                <input type="email" name="email" id="email" value="<?=$_link_->email?>"  placeholder="Email address" class="form-control" data-rule="required" data-msg="Please enter email address"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Select Participation Type<small class="red-color">*</small></label>
                                                <select class="form-control" name="paticipation_sub_type" id="paticipant_type" data-rule="required" data-msg="Please select  Category"> 
                                                    <option value="" selected="">[--Select--]</option>
<?php
$_SUB_CATEGORIES_DATA_ = FutureEventController::getPrivatePacipationSubCategory($eventId);
if($_SUB_CATEGORIES_DATA_):
    foreach($_SUB_CATEGORIES_DATA_ As $_sub_type_ ):
?>
                                                    <option value="<?=Hash::encryptAuthToken($_sub_type_->sub_type_id)?>" <?=$_link_->participation_sub_type_id == $_sub_type_->sub_type_id? 'selected':'' ?> ><?=$_sub_type_->name?> - <?=Functions::getEventCategory($_sub_type_->sub_type_category) ?></option>
<?php
    endforeach;
endif;
?>      
                                                </select>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer"> 
                                    <input type="hidden" name="request" value="editAndSendPrivateLink"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <input type="hidden" name="Id" value="<?=Hash::encryptToken($_link_->id) ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="button" id="editLinkButton" class="btn btn-primary editLinkButtonDynamic" data-loading-text="Loading..." data-key = "<?=Hash::encryptToken($_link_->id)?>" autocomplete="off"><i class="fa fa fa-external-link"></i> Send link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Link Modal  -->
                <div class="modal inmodal fade" id="activateModal<?=Hash::encryptToken($_link_->id)?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Activate  Private Link </h4>
                            </div>
                            <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="activateForm">
                                <div class="modal-body">
                                    <div id="activate-messages"></div>
                                    <p class="text-center">Do you really want to Activate this link For <strong> <?=$_link_->firstname?> </strong> ?</p>
                                    <div class="row">
                                        
                                    </div>
                                </div>
                                <div class="modal-footer"> 
                                    <input type="hidden" name="request" value="activatePrivate"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <input type="hidden" name="Id" value="<?=Hash::encryptToken($_link_->id) ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="button" id="activateButton" class="btn btn-primary activateButtonDynamic" data-loading-text="Loading..." data-key = "<?=Hash::encryptToken($_link_->id)?>" autocomplete="off"><i class="fa fa fa-external-link"></i> Activate Link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Link Modal  -->
                <div class="modal inmodal fade" id="deactivateModal<?=Hash::encryptToken($_link_->id)?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Deactivate  Private Link </h4>
                            </div>
                            <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="deactivateForm">
                                <div class="modal-body">
                                    <div id="deactivate-messages"></div>
                                    <p class="text-center">Do you really want to Deactivate this link For <strong> <?=$_link_->firstname?> </strong> ?</p>
                                    <div class="row">
                                        
                                    </div>
                                </div>
                                <div class="modal-footer"> 
                                    <input type="hidden" name="request" value="deactivatePrivateLink"/> 
                                    <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>"/>
                                    <input type="hidden" name="Id" value="<?=Hash::encryptToken($_link_->id) ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="button" id="deactivateButton" class="btn btn-primary deactivateButtonDynamic" data-loading-text="Loading..." data-key = "<?=Hash::encryptToken($_link_->id)?>" autocomplete="off"><i class="fa fa fa-external-link"></i> Deactivate Link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
<?php
    endforeach;
endif;
?>
        <script type="text/javascript">
            var eventId = '<?php echo $eventId; ?>';
            var linkto  = '<?php linkto("pages/groups/controller_action.php"); ?>';
        </script>
        <script src="<?php linkto('pages/groups/link.js'); ?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
