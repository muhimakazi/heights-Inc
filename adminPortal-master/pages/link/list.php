<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "content";
    $link = "link";
?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <style>
        .DTTT_container {
            display: none;
        }
        input[type="search"]  {
            width: 300px!important;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Events</h2>
                <ol class="breadcrumb">
                    <li><a href="<?=DN?>">Home</a></li>
                    <li class="active"><strong>Registration Invites</strong></li>
                </ol>
            </div>
            <div class="col-lg-2">
                <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addPartnerModal" id="addClient" style="margin-top: 50px;"><i class="fa fa fa-external-link"></i> Generate pivite link</button> -->
                <!-- Generate Link modal -->
                <div class="modal inmodal fade" id="generateLinkModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Create private link</h4>
                            </div>
                            <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post"
                                class="formCustom modal-form" id="addLinkForm">
                                <div class="modal-body">
                                    <div id="add-link-messages"></div>
                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group col-md-6">
                                                <label>First name<small class="red-color">*</small></label>
                                                <input type="text" name="firstname" id="firstname"
                                                    placeholder="First name" class="form-control" data-rule="required"
                                                    data-msg="Please enter first name" />
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Last name<small class="red-color">*</small></label>
                                                <input type="text" name="lastname" id="lastname" placeholder="Last name"
                                                    class="form-control" data-rule="required"
                                                    data-msg="Please enter last name" />
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Email<small class="red-color">*</small></label>
                                                <input type="text" name="email" id="email" placeholder="Email address"
                                                    class="form-control" data-rule="required"
                                                    data-msg="Please enter email address" />
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Select Participation Type<small
                                                        class="red-color">*</small></label>
                                                <select class="form-control" name="paticipation_sub_type"
                                                    id="paticipant_type" data-rule="required"
                                                    data-msg="Please select  Category">
                                                    <option value="" selected="">[--Select--]</option>
                                                    <?php
$_SUB_CATEGORIES_DATA_ = FutureEventController::getPrivatePacipationSubCategory($eventId);
if($_SUB_CATEGORIES_DATA_):
    foreach($_SUB_CATEGORIES_DATA_ As $_sub_type_ ):
        if ($_sub_type_->sub_type_id != 113 AND $_sub_type_->sub_type_id != 114 AND $_sub_type_->sub_type_id != 116 AND $_sub_type_->sub_type_id != 117 AND $_sub_type_->sub_type_id != 118 AND $_sub_type_->sub_type_id != 119 AND $_sub_type_->sub_type_id != 120 AND $_sub_type_->sub_type_id != 128):
?>
                                                    <!-- <option value="<?=Hash::encryptAuthToken($_sub_type_->sub_type_id)?>" ><?=$_sub_type_->name?> - <?=$_sub_type_->sub_type_name?> - <?=Functions::getEventCategory($_sub_type_->sub_type_category) ?></option> -->
                                                    <option
                                                        value="<?=Hash::encryptAuthToken($_sub_type_->sub_type_id)?>">
                                                        <?=$_sub_type_->name=="Complimentary"?"":$_sub_type_->name." - " ?>
                                                        <?=$_sub_type_->sub_type_name?></option>
                                                    <?php
        endif;
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
                                    <input type="hidden" name="request" value="sendPrivateLink" />
                                    <input type="hidden" name="eventId"
                                        value="<?=Hash::encryptAuthToken($eventId) ?>" />
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i
                                            class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="addLinkButton" class="btn btn-primary"
                                        data-loading-text="Loading..." autocomplete="off"><i
                                            class="fa fa fa-external-link"></i> Create link</button>
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
                        <button class="btn btn-sm btn-primary pull-right" data-toggle="modal"
                            data-target="#generateLinkModal"><i class="fa fa-paper-plane"></i> Create Registration
                            Link</button>
                        <h5>Registration Invites</h5>
                    </div>
                    <div class="ibox-content" id="list-generated-links">

                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Link Modal  -->
        <div class="modal inmodal fade" id="editLinkModal" tabindex="-1"
            role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit Private Link </h4>
                    </div>
                    <form method="post"
                        class="formCustom modal-form" id="editLinkForm">
                        <div class="modal-body">
                            <div id="edit-link-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group col-md-6">
                                        <label>First name<small class="red-color">*</small></label>
                                        <input type="text" name="firstname" id="efirstname" placeholder="First name" class="form-control" data-rule="required" data-msg="Please enter first name" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Last name<small class="red-color">*</small></label>
                                        <input type="text" name="lastname" id="elastname" placeholder="Last name" class="form-control" data-rule="required" data-msg="Please enter last name" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Email<small class="red-color">*</small></label>
                                        <input type="email" name="email" id="eemail" placeholder="Email address" class="form-control" data-rule="required" data-msg="Please enter email address" />
                                        <div class="validate"></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Select Pass Type<small class="red-color">*</small></label>
                                        <select class="form-control" name="paticipation_sub_type" id="epaticipant_type"
                                            data-rule="required" data-msg="Please select  Category">
                                            <option value="" selected="">[--Select--]</option>
                                            <?php
$_SUB_CATEGORIES_DATA_ = FutureEventController::getPrivatePacipationSubCategory($eventId);
if($_SUB_CATEGORIES_DATA_):
    foreach($_SUB_CATEGORIES_DATA_ As $_sub_type_ ):
?>
                                            <option value="<?=Hash::encryptAuthToken($_sub_type_->sub_type_id)?>">
                                                <?=$_sub_type_->name?> -
                                                <?=Functions::getEventCategory($_sub_type_->sub_type_category) ?>
                                            </option>
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
                            <input type="hidden" name="request" value="editAndSendPrivateLink" />
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>" />
                            <input type="hidden" name="Id" id="linkId" value="" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i
                                    class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="editLinkButton" class="btn btn-primary editButtonDynamic"
                                data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
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
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId) ?>" />
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="confirmButton" class="btn btn-primary confirmButtonDynamic" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="text/javascript">
        var linkto = '<?=DN?>/pages/link/link_action.php';

        function copyToClipboard(element, linkID) {
            var error = $('#alert_' + linkID).empty();
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).data('link')).select();
            document.execCommand("copy");
            error.html('Copied');
            $temp.remove();

            window.setTimeout(function() {
                error.html('');
            }, 2000);
        }
        </script>
        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        <script src="<?=DN?>/pages/link/link.js?v=<?=date('Y-m-d H:i:s')?>'); ?>"></script>

        <?php include $INC_DIR . "footer.php"; ?>

    </div>
    </div>
</body>

</html>