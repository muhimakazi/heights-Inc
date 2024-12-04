<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('admin/login');
    }

    $page = "content";
    $link = "exhibitors";
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
                    <li><a href="<?php linkto('admin/'); ?>">Home</a></li>
                    <li><a href="<?php linkto('admin/pages/events/events_list'); ?>">Events</a></li>
                    <li><a>Website content</a></li>
                    <li class="active"><strong>Exhibitors</strong></li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addExhibitorModal" id="addExhibitor"><i class="fa fa-plus-circle"></i> Add Exhibitor</button>
                            <div class="modal inmodal fade" id="addExhibitorModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <h4 class="modal-title">Add new exhibitor</h4>
                                        </div>
                                        <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" method="post" class="formCustom modal-form" id="addExhibitorForm">
                                            <div class="modal-body">
                                                <div id="add-exhibitor-messages"></div>
                                                <p>All <small class="red-color">*</small> fields are mandatory</p>
                                                <div class="row">
                                                    <div class="col-sm-12" style="padding: 0;">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Name<small class="red-color">*</small></label>
                                                                <input type="text" name="name" id="name" placeholder="Name" class="form-control" data-rule="required" data-msg="Please enter name"/>
                                                                <div class="validate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6" style="padding: 0;">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Email<small class="red-color">*</small></label>
                                                                <input type="text" name="email" id="email" placeholder="Email" class="form-control" data-rule="email" data-msg="Please enter a valid email">
                                                                <div class="validate"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>City<small class="red-color">*</small></label>
                                                                <input type="text" name="city" id="city" placeholder="City" class="form-control" data-rule="required" data-msg="Please enter city">
                                                                <div class="validate"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Industry<small class="red-color">*</small></label>
                                                                <select class="form-control" name="industry" id="industry" data-rule="required" data-msg="Please select industry">
                                                                    <option value="" selected="">[--Select industry--]</option>
                                                                    <?php $controller->industry();?>
                                                                </select>
                                                                <div class="validate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6" style="padding: 0;">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Website</label>
                                                                <input type="text" name="website" id="website" placeholder="eg: https://example.com" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Country<small class="red-color">*</small></label>
                                                                <select class="form-control" name="country" id="country" data-rule="required" data-msg="Please select country">
                                                                    <option value="" selected="">[--Select country--]</option>
                                                                    <?php $controller->country();?>
                                                                </select>
                                                                <div class="validate"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Placement order<small class="red-color">*</small></label>
                                                                <input type="number" name="order" id="order" placeholder="Placement order" class="form-control" data-rule="required" value="1" data-msg="Please enter placement order"/>
                                                                <div class="validate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12" style="margin-top: 30px;">
                                                        <div class="form-group">
                                                            <label for="image" class="col-lg-4 control-label">
                                                                Upload exhibitor logo<small class="red-color">*</small><br><br>
                                                                <p style="color: red;">Image size: 400 × 142<br>
                                                                Format: jpg or png file</p>
                                                            </label>
                                                            <div class="col-lg-8">
                                                                <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                                <div class="kv-avatar center-block">                            
                                                                    <input type="file" name="image" class="form-control" id="image" placeholder="Exhibitor picture"  class="file-loading" style="width:auto;" data-rule="required" data-msg="Please select exhibitor picture"/>
                                                                    <div class="validate"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="request" value="addExhibitor"/> 
                                                <input type="hidden" name="eventId" value="<?php echo $eventId; ?>"/>
                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                <button type="submit" id="addExhibitorButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <h5>Exhibitors</h5>
                        </div>

                        <div class="ibox-content" id="exhibitor-table"></div>

                        <div class="modal inmodal fade" id="editExhibitorModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title">Edit exhibitor</h4>
                                    </div>
                                    <div class="panel blank-panel">
                                        <div class="panel-heading">
                                            <div class="panel-options">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-list-alt"></i> Exhibitor name</a></li>
                                                    <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-picture-o"></i> Exhibitor logo</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="tab-content">
                                                <div id="tab-1" class="tab-pane active">
                                                    <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" method="post" class="formCustom modal-form" id="editExhibitorForm">
                                                        <div class="modal-body">
                                                            <div id="edit-exhibitor-messages"></div>
                                                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                                                            <div class="row">
                                                                <div class="col-sm-12" style="padding: 0;">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Name<small class="red-color">*</small></label>
                                                                            <input type="text" name="ename" id="ename" placeholder="Name" class="form-control" data-rule="required" data-msg="Please enter name"/>
                                                                            <div class="validate"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6" style="padding: 0;">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Email<small class="red-color">*</small></label>
                                                                            <input type="text" name="eemail" id="eemail" placeholder="Email" class="form-control" data-rule="email" data-msg="Please enter a valid email">
                                                                            <div class="validate"></div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>City<small class="red-color">*</small></label>
                                                                            <input type="text" name="ecity" id="ecity" placeholder="City" class="form-control" data-rule="required" data-msg="Please enter city">
                                                                            <div class="validate"></div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Industry<small class="red-color">*</small></label>
                                                                            <select class="form-control" name="eindustry" id="eindustry" data-rule="required" data-msg="Please select industry">
                                                                                <option value="" selected="">[--Select industry--]</option>
                                                                                <?php $controller->industry();?>
                                                                            </select>
                                                                            <div class="validate"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6" style="padding: 0;">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Website</label>
                                                                            <input type="text" name="ewebsite" id="ewebsite" placeholder="eg: https://example.com" class="form-control">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Country<small class="red-color">*</small></label>
                                                                            <select class="form-control" name="ecountry" id="ecountry" data-rule="required" data-msg="Please select country">
                                                                                <option value="" selected="">[--Select country--]</option>
                                                                                <?php $controller->country();?>
                                                                            </select>
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
                                                        </div>
                                                        <div class="modal-footer editExhibitorFooter">
                                                            <input type="hidden" name="request" value="editExhibitor" /> 
                                                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                            <button type="submit" id="editExhibitorButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div id="tab-2" class="tab-pane">
                                                    <form action="<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>" method="post" class="formCustom modal-form" id="editExhibitorImageForm" enctype="multipart/form-data">
                                                        <p>All <small class="red-color">*</small> fields are mandatory</p>
                                                        <div class="modal-body">
                                                            <div id="edit-exhibitorImage-messages"></div>
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <label for="editExhibitorImage" class="col-sm-3 control-label">Exhibitor logo<small class="red-color">*</small></label>
                                                                    <label class="col-sm-1 control-label">: </label>
                                                                    <div class="col-sm-8">
                                                                        <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                                        <div class="kv-avatar center-block">                            
                                                                            <input type="file" class="form-control" id="editExhibitorImage" placeholder="Exhibitor logo" name="editExhibitorImage" class="file-loading" style="width:auto;"/>
                                                                        </div>
                                                                        <p style="color: red; margin-top: 10px;">Image size: 400 × 142<br>
                                                                        Format: jpg or png file</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer editExhibitorImageFooter">
                                                            <input type="hidden" name="request" value="editExhibitorImage" />
                                                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                            <!-- <button type="submit" id="editExhibitorImageButton" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</button> -->
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
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var eventId = '<?php echo $eventId; ?>';
            var linkto  = '<?php linkto("admin/pages/exhibitors/exhibitors_action.php"); ?>';
        </script>
        <script src="<?php linkto('admin/pages/exhibitors/exhibitors.js'); ?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
