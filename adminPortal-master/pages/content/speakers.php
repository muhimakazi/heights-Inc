<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "content";
    $link = "speakers";
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
                    <li><a>Home</a></li>
                    <li><a>Events</a></li>
                    <li><a>Website content</a></li>
                    <li class="active"><strong>Speakers</strong></li>
                </ol>
            </div>
            <div class="col-lg-2">
                <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#addSpeakerModal" id="addClient" style="margin-top: 50px;"><i class="fa fa-plus-circle"></i> Add speaker</button> -->

                <div class="modal inmodal fade" id="addSpeakerModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Add new speaker</h4>
                            </div>
                            <form method="post" class="formCustom modal-form" id="addSpeakerForm">
                                <div class="modal-body">
                                    <div id="add-speaker-messages"></div>
                                    <p>All <small class="red-color">*</small> fields are mandatory</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Full name<small class="red-color">*</small></label>
                                                <input type="text" name="name" id="name" placeholder="Full name" class="form-control" data-rule="required" data-msg="Please enter name"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Organisation<small class="red-color">*</small></label>
                                                <input type="text" name="organisation" id="organisation" placeholder="Organisation" class="form-control" data-rule="required" data-msg="Please enter organisation"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Job title<small class="red-color">*</small></label>
                                                <input type="text" name="job_title" id="job_title" placeholder="Job title" class="form-control" data-rule="required" data-msg="Please enter job title"/>
                                                <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 30px;">
                                            <div class="form-group">
                                                <label for="image" class="col-lg-4 control-label">
                                                    Upload speaker picture<small class="red-color">*</small><br><br>
                                                    <p style="color: red;">Image size: 160 × 160<br>
                                                    Format: jpg or png file</p>
                                                </label>
                                                <div class="col-lg-8">
                                                    <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                    <div class="kv-avatar center-block">                            
                                                        <input type="file" name="image" class="form-control" id="image" placeholder="speaker picture"  class="file-loading" style="width:auto;" data-rule="required" data-msg="Please select speaker picture"/>
                                                        <div class="validate"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="request" value="addSpeaker"/> 
                                    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>"/>
                                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                    <button type="submit" id="addSpeakerButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <?php
            $getStyle  = TemplateController::getSpeakerStyle($eventId);
            if ($getStyle) {
                $general_style = $getStyle->general_style;
                $styleArray = json_decode($general_style, true);
                $style = (object)$styleArray;
                $titleColor = $style->titleColor;
                $textColor = $style->textColor;
                $bgColor = $style->bgColor;
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12 responseMsg" id="add-about-messages"></div>
                                <form method="post" class="formCustom" id="addAboutForm">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Section title<small class="red-color">*</small></label>
                                            <input type="text" name="title" id="title" placeholder="Title" class="form-control" data-rule="required" data-msg="Please enter title" value="<?=$getStyle->title?>">
                                            <div class="validate"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Title color (HEX code) <small class="red-color">*</small></label>
                                        <div class="form-group control-group">
                                            <input type="text" name="title_color" id="title_color" class="form-control" value="<?=$titleColor?>" data-rule="required" data-msg="This is required"/>
                                            <p class="validate"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Text color (HEX code) <small class="red-color">*</small></label>
                                        <div class="form-group control-group">
                                            <input type="text" name="text_color" id="text_color" class="form-control" value="<?=$textColor?>" data-rule="required" data-msg="This is required"/>
                                            <p class="validate"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Background color (HEX code) <small class="red-color">*</small></label>
                                        <div class="form-group control-group">
                                            <input type="text" name="bg_color" id="bg_color" class="form-control" value="<?=$bgColor?>" data-rule="required" data-msg="This is required"/>
                                            <p class="validate"></p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-1">
                                        <label></label>
                                        <input type="hidden" name="request" value="addAbout" />
                                        <input type="hidden" name="contentId" id="contentId" value="<?=$getStyle->id?>" />
                                        <button type="submit" id="addAboutButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="row-flex" id="speakers-list"></div>
            <div class="modal inmodal fade" id="editSpeakerModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Edit speaker</h4>
                        </div>
                        <div class="panel blank-panel">
                            <div class="panel-heading">
                                <div class="panel-options">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-list-alt"></i> Speaker details</a></li>
                                        <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-picture-o"></i> Speaker picture</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div id="tab-1" class="tab-pane active">
                                        <form method="post" class="formCustom modal-form" id="editSpeakerForm">
                                            <div class="modal-body">
                                                <div id="edit-speaker-messages"></div>
                                                <p>All <small class="red-color">*</small> fields are mandatory</p>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label>Full name<small class="red-color">*</small></label>
                                                            <input type="text" name="ename" id="ename" placeholder="Full name" class="form-control" data-rule="required" data-msg="Please enter name"/>
                                                            <div class="validate"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Organisation<small class="red-color">*</small></label>
                                                            <input type="text" name="eorganisation" id="eorganisation" placeholder="Organisation" class="form-control" data-rule="required" data-msg="Please enter organisation"/>
                                                            <div class="validate"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Job title<small class="red-color">*</small></label>
                                                            <input type="text" name="ejob_title" id="ejob_title" placeholder="Job title" class="form-control" data-rule="required" data-msg="Please enter job title"/>
                                                            <div class="validate"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer editSpeakerFooter">
                                                <input type="hidden" name="request" value="editSpeaker" /> 
                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                <button type="submit" id="editSpeakerButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div id="tab-2" class="tab-pane">
                                        <form method="post" class="formCustom modal-form" id="editSpeakerImageForm" enctype="multipart/form-data">
                                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                                            <div class="modal-body">
                                                <div id="edit-speakerImage-messages"></div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="editSpeakerImage" class="col-sm-3 control-label">Speaker picture<small class="red-color">*</small></label>
                                                        <label class="col-sm-1 control-label">: </label>
                                                        <div class="col-sm-8">
                                                            <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
                                                            <div class="kv-avatar center-block">                            
                                                                <input type="file" class="form-control" id="editSpeakerImage" placeholder="Speaker picture" name="editSpeakerImage" class="file-loading" style="width:auto;"/>
                                                            </div>
                                                            <p style="color: red; margin-top: 10px;">Image size: 160 × 160<br>
                                                            Format: jpg or png file</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer editSpeakerImageFooter">
                                                <input type="hidden" name="request" value="editSpeakerImage" />
                                                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                                <!-- <button type="submit" id="editSpeakerImageButton" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</button> -->
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
            var linkto  = '<?=DN?>/pages/content/content_action.php';
        </script>
        <script src="<?=DN?>/pages/content/speakers.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        <script src="<?=DN?>/js/plugins/switchery/switchery.js"></script>
        <script src="<?=DN?>/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
        <script>
            var elem = document.querySelector('.about-status');
            var switchery = new Switchery(elem, { color: '#1AB394' });
            $('#title_color').colorpicker();
            $('#text_color').colorpicker();
            $('#bg_color').colorpicker();
        </script>
    
        </div>
        </div>
</body>

</html>
