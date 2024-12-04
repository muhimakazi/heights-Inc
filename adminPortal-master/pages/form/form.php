<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "form";
    $link = "form";

    $eventDetails = FutureEventController::getEventDetailsByID($eventId);
    $event_name = $eventDetails->event_name;
    $event_venue = $eventDetails->venue;
    $start_date = date('j', strtotime(dateFormat($eventDetails->start_date)));
    $end_date   = date("j F Y", strtotime(dateFormat($eventDetails->end_date)));
    $event_date = $start_date." - ".$end_date;

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
        .form-group { margin-bottom: 0px;}
        /*.btn-group {width: 100%;}
        .btn-group button {border-radius: 3px!important}
        .btn-group button:first-child {margin-right: 10px}*/
        .text-green {color: green;}
        .text-red {color: red;}
        .note-editor {
            height: auto;
            min-height: 200px;
            border: 1px solid #e5e6e7;
        }
        .mt-2 {margin-top: 20px!important}
        .mb-2 {margin-bottom: 20px!important}
    </style>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Forms</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php linkto(''); ?>">Home</a></li>
                    <li><a>Forms</a></li>
                    <li class="active"><strong>List</strong></li>
                </ol>
            </div>
            <div class="col-lg-2">
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <?php if ($user->hasPermission('admin') || $user->hasPermission('client')) { ?>
                            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#addModal" ><i class="fa fa-plus"></i> New Form</button>
                            <?php } ?>
                        </div>
                        <div class="ibox-content">
                            <table id="formTable" class='table table-stripped table-hover customTable'>
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Form name</th>
                                    <th>Publish type</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD FORM -->
        <div class="modal inmodal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">New Form</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="addForm" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="add-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row" style="display: flex; flex-flow: row wrap;">
                                <div class="form-group control-group col-md-12">
                                    <label>Form name<small class="red-color">*</small></label>
                                    <input type="text" name="form_name" id="form_name" class="form-control" required="required" placeholder="Form name" data-validation-required-message="Please enter form name"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Publish type<small class="red-color">*</small></label>
                                    <select class="form-control" name="publish_type" id="publish_type" required="required" data-validation-required-message="Please select">
                                        <option value="1">Public</option>
                                        <option value="0">Private</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Form order<small class="red-color">*</small></label>
                                    <input type="number" name="form_order" id="form_order" class="form-control" required="required" placeholder="Form order" min="1" value="1" data-validation-required-message="Please enter form order"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Form note</label>
                                    <textarea name="form_note" id="form_note" class="form-control" placeholder="Form note" style="height: 90px;"></textarea>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12 mt-2">
                                    <h4>Registration autoresponse email</h4>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Subject<small class="red-color">*</small></label>
                                    <input type="text" name="registration_email_subject" id="registration_email_subject" class="form-control" required="required" placeholder="Subject" value="Registration for the <?=$event_name?>"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Message<small class="red-color">*</small></label>
                                    <textarea class="summernote" name="registration_email_message" id="registration_email_message" required="required">
                                        Dear $firstname,<br>
                                        Thank you for applying to attend the <?=$event_name?> that will be held from <?=$event_date?> in <?=$event_venue?>.<br><br>
                                        Your registration application will be reviewed, and a response will be received within 3 working days.<br><br>
                                        Regards,<br>
                                        <?=$event_name?> Team
                                    </textarea>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12 mt-2">
                                    <h4>Approval autoresponse email</h4>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Subject<small class="red-color">*</small></label>
                                    <input type="text" name="approval_email_subject" id="approval_email_subject" class="form-control" required="required" placeholder="Subject" value="Registration for the <?=$event_name?>" />
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Message<small class="red-color">*</small></label>
                                    <textarea class="summernote" name="approval_email_message" id="approval_email_message" required="required">
                                        Dear $firstname,<br>
                                        Thank you for applying to attend the <?=$event_name?> that will be held from <?=$event_date?> in <?=$event_venue?>.<br><br>
                                        Your application has been approved.<br><br>
                                        Regards,<br>
                                        <?=$event_name?> Team
                                    </textarea>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="createForm"/>
                            <input type="hidden" name="eventId" value="<?=Hash::encryptAuthToken($eventId)?>"/>
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="addButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT FORM -->
        <div class="modal inmodal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Edit Form</h4>
                    </div>
                    <form method="post" class="formCustom modal-form" id="editForm" novalidate="novalidate">
                        <div class="modal-body">
                            <div id="edit-messages"></div>
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                            <div class="row" style="display: flex; flex-flow: row wrap;">
                                <div class="form-group control-group col-md-12">
                                    <label>Form name<small class="red-color">*</small></label>
                                    <input type="text" name="form_name" id="eform_name" class="form-control" required="required" placeholder="Form name" data-validation-required-message="Please enter form name"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Publish type<small class="red-color">*</small></label>
                                    <select class="form-control" name="publish_type" id="epublish_type" required="required" data-validation-required-message="Please select">
                                        <option value="1">Public</option>
                                        <option value="0">Private</option>
                                    </select>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Form order<small class="red-color">*</small></label>
                                    <input type="number" name="form_order" id="eform_order" class="form-control" required="required" placeholder="Form order" min="1" value="1" data-validation-required-message="Please enter form order"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Form note</label>
                                    <textarea name="form_note" id="eform_note" class="form-control" placeholder="Form note" style="height: 90px;"></textarea>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12 mt-2">
                                    <h4>Registration autoresponse email</h4>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Subject<small class="red-color">*</small></label>
                                    <input type="text" name="registration_email_subject" id="eregistration_email_subject" class="form-control" required="required" placeholder="Subject"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12" id="eregistration_email_message">
                                    <label>Message<small class="red-color">*</small></label>
                                    <textarea class="summernote" name="registration_email_message" required="required"></textarea>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12 mt-2">
                                    <h4>Approval autoresponse email</h4>
                                </div>
                                <div class="form-group control-group col-md-12">
                                    <label>Subject<small class="red-color">*</small></label>
                                    <input type="text" name="approval_email_subject" id="eapproval_email_subject" class="form-control" required="required" placeholder="Subject"/>
                                    <p class="help-block"></p>
                                </div>
                                <div class="form-group control-group col-md-12" id="eapproval_email_message">
                                    <label>Message<small class="red-color">*</small></label>
                                    <textarea class="summernote" name="approval_email_message" required="required"></textarea>
                                    <p class="help-block"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="request" value="editForm"/>
                            <input type="hidden" name="Id" id="editId" value=""/>
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="editButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- CONFIRM MODAL  -->
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
                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                            <button type="submit" id="confirmButton" class="btn btn-primary confirmButtonDynamic" data-loading-text="Loading..." autocomplete="off"><i class="fa fa fa-external-link"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php include $INC_DIR . "footer.php"; ?>

        <script type="text/javascript">
            var DN = '<?=DN?>';
            var linkto  = '<?=DN?>/pages/form/form_action.php';
        </script>
        <script src="<?=DN?>/js/jqBootstrapValidation.min.js"></script>
        <script src="<?=DN?>/pages/form/form.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        </div>
        </div>
</body>

</html>
