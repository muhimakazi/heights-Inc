<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page    = "content";
    $link    = "program";
    $sublink = $progDay;
?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <style>
        .note-editor {
        height: auto;
        min-height: 200px;
        border: 1px solid #e5e6e7;
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
                    <li><a href="<?php linkto(''); ?>">Home</a></li>
                    <li><a href="<?php linkto('pages/events/events_list'); ?>">Events</a></li>
                    <li><a>Website content</a></li>
                    <li><a>Program section</a></li>
                    <li class="active"><strong><?php echo $progDay; ?></strong></li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="ibox-content forum-post-container">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addSessionModal"><i class="fa fa-plus-circle"></i> Add session</button>
                        <div class="modal inmodal fade" id="addSessionModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title">Add session</h4>
                                    </div>
                                    <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="addSessionForm">
                                        <div class="modal-body">
                                            <div id="add-session-messages"></div>
                                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="bootstrap-timepicker">
                                                        <div class="form-group">
                                                            <label>Start time</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </div>
                                                                <input type="text" name="start_time" id="start_time" class="form-control timepicker" data-rule="required" data-msg="Please enter start time  "/>
                                                                <div class="validate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="bootstrap-timepicker">
                                                        <div class="form-group">
                                                            <label>End time</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </div>
                                                                <input type="text" name="end_time" id="end_time" class="form-control timepicker" data-rule="required" data-msg="Please enter end time  "/>
                                                                <div class="validate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Session name  <small class="red-color">*</small></label>
                                                        <input type="text" name="session_name" id="session_name" class="form-control" placeholder="Enter session name" data-rule="required" data-msg="Please enter session name  "/>
                                                        <div class="validate"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Session type  <small class="red-color">*</small></label>
                                                        <select class="form-control" name="session_type" id="session_type" data-rule="required" data-msg="Please select session type">
                                                            <option value="" selected="">[--Select session type--]</option>
                                                            <option value="Plenary">Plenary</option>
                                                            <option value="Breakout">Breakout</option>
                                                            <option value="Workshop">Workshop</option>
                                                            <option value="Roundtable">Roundtable</option>
                                                            <option value="Masterclass">Masterclass</option>
                                                            <option value="Spotlight conversation">Spotlight conversation</option>
                                                            <option value="Debate">Debate</option>
                                                            <option value="Dinner">Dinner</option>
                                                            <option value="Cocktail">Cocktail </option>
                                                            <option value="Lunch">Lunch</option>
                                                            <option value="Keynote presentation">Keynote presentation</option>
                                                            <option value="Breakfast">Breakfast</option>
                                                        </select>
                                                        <div class="validate"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Attendance<small class="red-color">*</small></label>
                                                        <select class="form-control" name="attendance" id="attendance" data-rule="required" data-msg="Please select attendance">
                                                            <option value="" selected="">[--Select attendance--]</option>
                                                            <option value="Invitation only">Invitation only</option>
                                                            <option value="Open to all delegates">Open to all delegates</option>
                                                            <option value="Media only">Media only</option>
                                                        </select>
                                                        <div class="validate"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Description<small class="red-color">*</small></label>
                                                        <textarea class="summernote" name="description" id="description"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Room</label>
                                                        <input type="text" name="room" id="room" class="form-control" placeholder="e.g: Room MH1 | Kigali Convention Center"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Session video link</label>
                                                        <input type="text" name="video" id="video" class="form-control" placeholder="Video link"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer addSessionFooter">
                                            <input type="hidden" name="request" value="addSession"/>
                                            <input type="hidden" name="eventId" value="<?php echo $eventId; ?>"/> 
                                            <input type="hidden" name="day" value="<?php echo $progDay; ?>"/> 
                                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                            <button type="submit" id="addSessionButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div id="sessions-list" class="program-admin"></div>

                        <div class="modal inmodal fade" id="editSessionModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title">Edit session</h4>
                                    </div>
                                    <form action="<?php linkto("pages/content/content_action.php"); ?>" method="post" class="formCustom modal-form" id="editSessionForm">
                                        <div class="modal-body">
                                            <div id="edit-session-messages"></div>
                                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="bootstrap-timepicker">
                                                        <div class="form-group">
                                                            <label>Start time</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </div>
                                                                <input type="text" name="estart_time" id="estart_time" class="form-control timepicker" data-rule="required" data-msg="Please enter start time  "/>
                                                                <div class="validate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="bootstrap-timepicker">
                                                        <div class="form-group">
                                                            <label>End time</label>
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-clock-o"></i>
                                                                </div>
                                                                <input type="text" name="eend_time" id="eend_time" class="form-control timepicker" data-rule="required" data-msg="Please enter end time  "/>
                                                                <div class="validate"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Session name  <small class="red-color">*</small></label>
                                                        <input type="text" name="esession_name" id="esession_name" class="form-control" placeholder="Enter session name" data-rule="required" data-msg="Please enter session name  "/>
                                                        <div class="validate"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Session type  <small class="red-color">*</small></label>
                                                        <select class="form-control" name="esession_type" id="esession_type" data-rule="required" data-msg="Please select session type">
                                                            <option value="" selected="">[--Select session type--]</option>
                                                            <option value="Plenary">Plenary</option>
                                                            <option value="Breakout">Breakout</option>
                                                            <option value="Workshop">Workshop</option>
                                                            <option value="Roundtable">Roundtable</option>
                                                            <option value="Masterclass">Masterclass</option>
                                                            <option value="Spotlight conversation">Spotlight conversation</option>
                                                            <option value="Debate">Debate</option>
                                                            <option value="Dinner">Dinner</option>
                                                            <option value="Cocktail">Cocktail </option>
                                                            <option value="Lunch">Lunch</option>
                                                            <option value="Keynote presentation">Keynote presentation</option>
                                                            <option value="Breakfast">Breakfast</option>
                                                        </select>
                                                        <div class="validate"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Attendance<small class="red-color">*</small></label>
                                                        <select class="form-control" name="eattendance" id="eattendance" data-rule="required" data-msg="Please select attendance">
                                                            <option value="" selected="">[--Select attendance--]</option>
                                                            <option value="Invitation only">Invitation only</option>
                                                            <option value="Open to all delegates">Open to all delegates</option>
                                                            <option value="Media only">Media only</option>
                                                        </select>
                                                        <div class="validate"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Description<small class="red-color">*</small></label>
                                                        <textarea class="summernote" name="edescription" id="edescription"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Room</label>
                                                        <input type="text" name="eroom" id="eroom" class="form-control" placeholder="e.g: Room MH1 | Kigali Convention Center"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Session video link</label>
                                                        <input type="text" name="evideo" id="evideo" class="form-control" placeholder="Video link"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer editSessionFooter">
                                            <input type="hidden" name="request" value="editSession"/> 
                                            <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                                            <button type="submit" id="editSessionButton" class="btn btn-primary" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var eventId = '<?php echo $eventId; ?>';
            var day     = '<?php echo $progDay; ?>';
            var linkto  = '<?php linkto("pages/content/content_action.php"); ?>';
        </script>
        <script src="<?php linkto('pages/content/program.js'); ?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
