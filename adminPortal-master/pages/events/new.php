<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }
    if (!$user->hasPermission('admin')) {
        Redirect::to('index');
    }

    $page = "events";
    $link = "list";
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
                    <li>
                        <a href="<?php linkto(''); ?>">Home</a>
                    </li>
                    <li>
                        <a>Events</a>
                    </li>
                    <li class="active">
                        <strong>New Event</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <p>All <small class="red-color">*</small> fields are mandatory</p>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <form action="<?php linkto("pages/events/events_action.php"); ?>" method="post" class="formCustom" id="addEventForm">
                                    <div class="col-lg-12" id="add-event-messages"></div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Event name<small class="red-color">*</small></label>
                                            <input type="text" name="event_name" id="event_name" placeholder="Event name" class="form-control" data-rule="required" data-msg="Please enter event name"/>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Client<small class="red-color">*</small></label>
                                            <select class="form-control" name="client" id="client" data-rule="required" data-msg="Please select client">
                                                <option value="" selected="">[--Select client--]</option>
                                                <?php 
                                                    $controller->get('future_client', 'id, organisation', NULL, "", '');
                                                    foreach ($controller->data() as $resClient) { 
                                                        echo "<option value='".$resClient->id."'>".$resClient->organisation."</option>";
                                                    }
                                                ?>
                                            </select>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group" id="data_1">
                                            <label>Start date<small class="red-color">*</small></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="dd/mm/yyyy" name="start_date" id="start_date" class="form-control" data-rule="required" data-msg="Please select start date"/>
                                            <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Event venue<small class="red-color">*</small></label>
                                            <input type="text" name="event_venue" id="event_venue" placeholder="Event venue" class="form-control" data-rule="required" data-msg="Please enter venue"/>
                                            <div class="validate"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Event type<small class="red-color">*</small></label>
                                            <select class="form-control" name="event_type" id="event_type" data-rule="required" data-msg="Please select event type">
                                                <option value="" selected="">[--Select event type--]</option>
                                                <option value="In-person">In-person event</option>
                                                <option value="Virtual">Virtual event</option>
                                                <option value="Hybrid">Hybrid Event</option>
                                            </select>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Ticket type<small class="red-color">*</small></label>
                                            <select class="form-control" name="ticket_type" id="ticket_type" data-rule="required" data-msg="Please select ticket type">
                                                <option value="" selected="">[--Select ticket type--]</option>
                                                <option value="Free attendance">Free attendance</option>
                                                <option value="Paid attendance">Paid attendance</option>
                                                <option value="Free & paid attendance">Free & paid attendance</option>
                                            </select>
                                            <div class="validate"></div>
                                        </div>
                                        <div class="form-group" id="data_1">
                                            <label>End date<small class="red-color">*</small></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="dd/mm/yyyy" name="end_date" id="end_date" class="form-control" data-rule="required" data-msg="Please select end date"/>
                                            <div class="validate"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Event code<small class="red-color">*</small></label>
                                            <input type="text" name="event_code" id="event_code" placeholder="Event code" class="form-control" data-rule="required" data-msg="Please enter code"/>
                                            <div class="validate"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h4>Event Brand Colors</h4>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Primary color<small class="red-color">*</small></label>
                                            <input type="text" class="form-control" name="primary_color" id="primary_color" value="#f47e20" class="form-control" data-rule="required" data-msg="Pick or enter color code"/>
                                            <div class="validate"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Secondary color<small class="red-color">*</small></label>
                                            <input type="text" class="form-control" name="secondary_color" id="secondary_color" value="#5b2c13" class="form-control" data-rule="required" data-msg="Pick or enter color code"/>
                                            <div class="validate"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="hidden" name="request" value="addNewEvent" /> 
                                        <button type="submit" id="addEventButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>

        <script type="text/javascript">
            var linkto = '<?php linkto("pages/events/events_action.php"); ?>';
        </script>

        <script src="<?=DN?>/pages/events/events.js?v=<?=date('Y-m-d H:i:s')?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>
        
        </div>
        </div>
</body>

</html>
