<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "content";
    $link = "partners";
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
                    <li class="active"><strong>Partners / Sponsors </strong></li>
                </ol>
            </div>
            <div class="col-lg-2">
                <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addPartnerModal" id="addClient" style="margin-top: 50px;"><i class="fa fa-plus-circle"></i> Add partner</button> -->

                <div class="modal inmodal fade" id="addPartnerModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Add new partner</h4>
                            </div>
                            <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="addPartnerForm">
                                <div class="modal-body">
                                    <div id="add-partner-messages"></div>
                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Name<small class="red-color">*</small></label>
                                                <input type="text" name="name" id="name" placeholder="Name" class="form-control" data-rule="required" data-msg="Please enter name"/>
                                                <div class="validate"></div>
                                            </div>
                                            <div class="form-group">
                                                <label>Placement order<small class="red-color">*</small></label>
                                                <input type="number" name="order" id="order" placeholder="Placement order" class="form-control" data-rule="required" value="1" data-msg="Please enter placement order"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 30px;">
                                            <div class="form-group">
                                                <label for="image" class="col-lg-4 control-label">
                                                    Upload partner logo<small class="red-color">*</small><br><br>
                                                    <p style="color: red;">Image size: 400 × 142<br>
                                                    Format: jpg or png file</p>
                                                </label>
                                                <div class="col-lg-8">
                                                    <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                    <div class="kv-avatar center-block">                            
                                                        <input type="file" name="image" class="form-control" id="image" placeholder="Partner picture"  class="file-loading" style="width:auto;" data-rule="required" data-msg="Please select partner picture"/>
                                                        <div class="validate"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="request" value="addPartner"/> 
                                    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="addPartnerButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row-flex" id="partners-list"></div>
            <div class="modal inmodal fade" id="editPartnerModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Edit partner</h4>
                        </div>
                        <div class="panel blank-panel">
                            <div class="panel-heading">
                                <div class="panel-options">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-list-alt"></i> Partner name</a></li>
                                        <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-picture-o"></i> Partner logo</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div id="tab-1" class="tab-pane active">
                                        <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="editPartnerForm">
                                            <div class="modal-body">
                                                <div id="edit-partner-messages"></div>
                                                <p>All <small class="red-color">*</small> fields are mandatory</p>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Name<small class="red-color">*</small></label>
                                                            <input type="text" name="ename" id="ename" placeholder="Name" class="form-control" data-rule="required" data-msg="Please enter name"/>
                                                            <div class="validate"></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Placement order<small class="red-color">*</small></label>
                                                            <input type="number" name="eorder" id="eorder" placeholder="Placement order" class="form-control" data-rule="required" value="1" data-msg="Please enter placement order"/>
                                                            <div class="validate"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer editPartnerFooter">
                                                <input type="hidden" name="request" value="editPartner" /> 
                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                <button type="submit" id="editPartnerButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="tab-2" class="tab-pane">
                                        <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="editPartnerImageForm" enctype="multipart/form-data">
                                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                                            <div class="modal-body">
                                                <div id="edit-partnerImage-messages"></div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="editPartnerImage" class="col-sm-3 control-label">Partner logo<small class="red-color">*</small></label>
                                                        <label class="col-sm-1 control-label">: </label>
                                                        <div class="col-sm-8">
                                                            <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                            <div class="kv-avatar center-block">                            
                                                                <input type="file" class="form-control" id="editPartnerImage" placeholder="Partner logo" name="editPartnerImage" class="file-loading" style="width:auto;"/>
                                                            </div>
                                                            <p style="color: red; margin-top: 10px;">Image size: 400 × 142<br>
                                                            Format: jpg or png file</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer editPartnerImageFooter">
                                                <input type="hidden" name="request" value="editPartnerImage" />
                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                <!-- <button type="submit" id="editPartnerImageButton" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</button> -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var eventId = '<?php echo $eventId; ?>';
            var linkto  = '<?php linkto("pages/content/content_action.php"); ?>';
        </script>
        <script src="<?php linkto('pages/content/partners.js'); ?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
