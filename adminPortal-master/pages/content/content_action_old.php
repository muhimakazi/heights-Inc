<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $valid['success'] = array('success' => false, 'messages' => array());

    // $_POST = array(
    //     'name'           => "AAAA Test",
    //     'payment_state'  => "FREE",
    //     'price'          => "200",
    //     'currency'       => "RWF",
    //     'Id' => '3131393635303238313035',

    //     'category'              => "INPERSON",
    //     'participation_type'    => "376d31354b4470754451376f416f717146457663713958336e3778574c4a2b374564765a777652364c6a6f",

    //     'request' => 'editParticipationSubType',
    // );

    //Load event banner
    if(Input::get('request') && Input::get('request') == 'fetchBanner') {
        $getContent  = DB::getInstance()->get('future_event', array('id', '=', $eventId));
        if ($getContent->first()->banner != "") {
            $imageUrl = "data_system/img/banner/".$getContent->first()->banner;
        } else {
            $imageUrl = "data_system/img/photo_default.png";
        }
    ?>
<div class="ibox-title">
    <?php if ($getContent->first()->banner == "") { ?>
    <button class="btn btn-xs btn-primary pull-right edit_banner" data-id="<?php echo $eventId;?>"><i
            class="fa fa-plus-circle"></i> Add banner</button>
    <?php } else { ?>
    <button class="btn btn-xs btn-primary pull-right edit_banner" data-id="<?php echo $eventId;?>"><i
            class="fa fa-pencil"></i> Edit banner</button>
    <?php } ?>
</div>
<div class="ibox-content">
    <img src="<?php linkto($imageUrl); ?>" class="img img-responsive">
</div>
<?php }

    // Edit event banner
    if(Input::get('request') && Input::get('request') == 'editEventImage') {
        $type = explode('.', $_FILES['editEventImage']['name']);
        $type = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct = root("data_system/img/banner/"); 
        $url = $direct . $fileName; 

        if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
            if(is_uploaded_file($_FILES['editEventImage']['tmp_name'])) {           
                if(move_uploaded_file($_FILES['editEventImage']['tmp_name'], $url)) {
                    try {
                        $controller->update("future_event", array('banner' => $fileName), Input::get('eventId'));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully uploaded";   
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while updating banner";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Invalid file";
        }
        echo json_encode($valid);
    }
    

    //Load countdown
	if(Input::get('request') && Input::get('request') == 'fetchCountdown') {
        $getContent    = DB::getInstance()->get('future_event', array('id', '=', $eventId));
        $start_date    = $getContent->first()->start_date;
        $start_date_c  = dateFormat($getContent->first()->start_date);
        $end_date      = $getContent->first()->end_date;
        $end_date_c    = dateFormat($getContent->first()->end_date);
        $set_countdown = date("F d, Y", strtotime($start_date_c));
        $end_countdown = date("F d, Y", strtotime($end_date_c));
        $eventDays     = dateDiff($start_date_c, $end_date_c);
	?>
<div class="ibox-title" style="height: auto; overflow: auto;">
    <h3><?php echo $start_date ." to ". $end_date; ?> </h3>
</div>
<div class="ibox-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="countdown">
                <div class="clock" id="clock">
                    <h3><?php echo $set_countdown; ?></h3>
                    <div class="column w3l">
                        <div class="timer w3" id="days"></div>
                        <div class="aits text">DAYS</div>
                    </div>
                    <div class="column w3">
                        <div class="timer w3layouts" id="hours"></div>
                        <div class="agileits text">HRS</div>
                    </div>
                    <div class="column wthree">
                        <div class="timer w3las" id="minutes"></div>
                        <div class="text aits">MIN</div>
                    </div>
                    <div class="column siteliga">
                        <div class="timer stuoyal3w" id="seconds"></div>
                        <div class="text wthree">SEC</div>
                    </div>
                </div>
                <div id="eventStart">
                    <h3 id="headline" style="color: #fff;"></h3>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var setCountdown = '<?php echo $set_countdown; ?>';
var enCountdown = '<?php echo $end_countdown; ?>';
(function() {
    const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;
    let event = setCountdown,
        end_event = enCountdown,
        countDown = new Date(event).getTime(),
        countDownEnd = new Date(end_event).getTime(),
        x = setInterval(function() {
            let now = new Date().getTime(),
                distance = countDown - now;
            distanceEnd = countDownEnd - now;
            document.getElementById("days").innerText = Math.floor(distance / (day)),
                document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
                document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
                document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);
            //do something later when date is reached
            if (distance < 0 && distanceEnd >= 0) {
                let clock = document.getElementById("clock"),
                    eventStart = document.getElementById("eventStart"),
                    headline = document.getElementById("headline");
                clock.style.display = "none";
                headline.innerText = "The event has started!";
                eventStart.style.display = "block";
                clearInterval(x);
            } else if (distanceEnd < 0) {
                let clock = document.getElementById("clock"),
                    eventStart = document.getElementById("eventStart"),
                    headline = document.getElementById("headline");
                clock.style.display = "none";
                headline.innerText = "This event has ended";
                eventStart.style.display = "block";
            }
            //seconds
        }, 0)
}());

$('#data_1 .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true
});
</script>
<?php }

    // Add countdown
    // if(Input::get('request') && Input::get('request') == 'addCountdown') {
    //     $findContent  = DB::getInstance()->get('future_countdown', array('event_id', '=', Input::get('eventId')));
    //     if ($findContent->count()) {
    //         try {
    //             $controller->update("future_countdown", array('start_date' => escape(Input::get('start_date'))), Input::get('contentId'));
    //             $valid['success']  = true;
    //             $valid['messages'] = "Successfully resetted";    
    //         } catch(Exception $error) {
    //             $valid['success']  = false;
    //             $valid['messages'] = "Error while resetting countdown";
    //         }
    //     } else {
    //         try {
    //             $controller->create("future_countdown", array(
    //                 'event_id'   => escape(Input::get('eventId')),
    //                 'start_date' => escape(Input::get('start_date'))
    //             ));
    //             $valid['success']  = true;
    //             $valid['messages'] = "Successfully setted";    
    //         } catch(Exception $error) {
    //             $valid['success']  = false;
    //             $valid['messages'] = "Error while setting countdown";
    //         }
    //     }
    //     echo json_encode($valid);
    // }

    // load registration link form

    if(Input::get('request') && Input::get('request') == 'fetchRegistrationLink') {
        $eventId = Input::get('eventId');
        $controller->get('future_homepage_partners', '*', NULL, "`event_id` = '$eventId'", 'p_order ASC');
        $i = 0;
        ?>
<div class="col-lg-3" style="margin-bottom: 30px;">
    <div class="event-card event-card-add">
        <div class="event-card-text event-card-speaker">
            <a href="" data-toggle="modal" data-target="#generateLink" id="addClient" style="margin-top: 50px;"><i
                    class="fa fa-external-link"></i> Generate private link</a>
        </div>
    </div>
</div>

<script>
$(function() {
    $('[data-toggle="popover"]').popover({
        html: true,
        content: function() {
            return $('#popover-content').html();
        }
    });
    $(document).on('click', function(e) {
        $('[data-toggle="popover"]').each(function() {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover')
                .has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
});
</script>
<?php
    }



    //Load about section
    if(Input::get('request') && Input::get('request') == 'fetchAbout') {
        $getContent  = DB::getInstance()->get('future_homepage_about', array('event_id', '=', $eventId));
        if ($getContent->count()) {
            $content_id          = $getContent->first()->id;
            $about_event         = $getContent->first()->about_event;
            $inperson_start_date = $getContent->first()->inperson_start_date;
            $inperson_end_date   = $getContent->first()->inperson_end_date;
            $inperson_venue      = $getContent->first()->inperson_venue;
            $inperson_city       = $getContent->first()->inperson_city;
            $inperson_country    = $getContent->first()->inperson_country;
            $virtual_start_date  = $getContent->first()->virtual_start_date;
            $virtual_end_date    = $getContent->first()->virtual_end_date;
            $virtual_location    = $getContent->first()->virtual_location;
        } else {
            $content_id = $about_event = $inperson_venue = $inperson_city = $inperson_country = $virtual_location = "";
            $inperson_start_date = $inperson_end_date = $virtual_start_date = $virtual_end_date = "dd/mm/yyyy";
        }
    ?>
<div class="col-sm-12">
    <div class="form-group">
        <label>About the summit (maximum 250 characters)<small class="red-color">*</small></label>
        <textarea name="about_event" id="about_event" class="form-control" placeholder="Tell us about your event"
            data-rule="maxlen:1020" data-msg="Please only 1020 characters"
            style="height: 100px;"><?php echo $about_event; ?></textarea>
        <div class="validate"></div>
    </div>
</div>
<div class="col-sm-12"><label style="text-transform: uppercase; margin: 10px 0; color: #000;">In-person Sessions</label>
</div>
<div class="col-sm-6">
    <div class="form-group" id="data_1">
        <label>Start date<small class="red-color">*</small></label>
        <div class="input-group date">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                value="<?php echo $inperson_start_date; ?>" name="inperson_start_date" id="inperson_start_date"
                data-rule="required" data-msg="Please select start date" />
            <div class="validate"></div>
        </div>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group" id="data_1">
        <label>End date<small class="red-color">*</small></label>
        <div class="input-group date">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                value="<?php echo $inperson_end_date; ?>" name="inperson_end_date" id="inperson_end_date"
                data-rule="required" data-msg="Please select end date" />
            <div class="validate"></div>
        </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label>Venue name<small class="red-color">*</small></label>
        <input type="text" name="inperson_venue" id="inperson_venue" class="form-control" data-rule="required"
            placeholder="Session link" data-msg="Please enter venue name" value="<?php echo $inperson_venue; ?>" />
        <div class="validate"></div>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label>City<small class="red-color">*</small></label>
        <input type="text" name="inperson_city" id="inperson_city" placeholder="City" class="form-control"
            data-rule="required" data-msg="Please enter city" value="<?php echo $inperson_city; ?>">
        <div class="validate"></div>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label>Country<small class="red-color">*</small></label>
        <select class="form-control" name="inperson_country" id="inperson_country" data-rule="required"
            data-msg="Please select  country">
            <option value="" selected="">[--Select country--]</option>
            <?php $controller->country();?>
        </select>
        <div class="validate"></div>
    </div>
</div>

<div class="col-sm-12"><label style="text-transform: uppercase; margin: 10px 0; color: #000;">Virtual Sessions</label>
</div>
<div class="col-sm-4">
    <div class="form-group" id="data_1">
        <label>Start date<small class="red-color">*</small></label>
        <div class="input-group date">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                value="<?php echo $virtual_start_date; ?>" name="virtual_start_date" id="virtual_start_date"
                data-rule="required" data-msg="Please select start date" />
            <div class="validate"></div>
        </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group" id="data_1">
        <label>End date<small class="red-color">*</small></label>
        <div class="input-group date">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                value="<?php echo $virtual_end_date; ?>" name="virtual_end_date" id="virtual_end_date"
                data-rule="required" data-msg="Please select end date" />
            <div class="validate"></div>
        </div>
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label>Venue name<small class="red-color">*</small></label>
        <input type="text" name="virtual_location" id="virtual_location" class="form-control" data-rule="required"
            placeholder="Session link" data-msg="Please enter link" value="<?php echo $virtual_location; ?>" />
        <div class="validate"></div>
    </div>
</div>
<div class="col-sm-12">
    <input type="hidden" name="request" value="addAbout" />
    <input type="hidden" name="contentId" value="<?php echo $content_id; ?>" />
    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>" />
    <button type="submit" id="addAboutButton" class="btn btn-primary pull-right" data-loading-text="Loading..."
        autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
</div>
<script type="text/javascript">
$('#data_1 .input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true
});
var inpersonCountry = '<?php echo $inperson_country; ?>';
$('#inperson_country').val(inpersonCountry);
</script>
<?php }

    // Add about content
    if(Input::get('request') && Input::get('request') == 'addAbout') {
        $findContent  = DB::getInstance()->get('future_homepage_about', array('event_id', '=', Input::get('eventId')));
        if ($findContent->count()) {
            try {
                $controller->update("future_homepage_about", array(
                    'about_event'         => escape(Input::get('about_event')),
                    'inperson_start_date' => escape(Input::get('inperson_start_date')),
                    'inperson_end_date'   => escape(Input::get('inperson_end_date')),
                    'inperson_venue'      => escape(Input::get('inperson_venue')),
                    'inperson_city'       => escape(Input::get('inperson_city')),
                    'inperson_country'    => escape(Input::get('inperson_country')),
                    'virtual_start_date'  => escape(Input::get('virtual_start_date')),
                    'virtual_end_date'    => escape(Input::get('virtual_end_date')),
                    'virtual_location'    => escape(Input::get('virtual_location'))
                ), Input::get('contentId'));
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            } catch(Exception $error) {
                $valid['success']  = false;
                $valid['messages'] = "Error while updating content";
            }
        } else {
            try {
                $controller->create("future_homepage_about", array(
                    'about_event'         => escape(Input::get('about_event')),
                    'inperson_start_date' => escape(Input::get('inperson_start_date')),
                    'inperson_end_date'   => escape(Input::get('inperson_end_date')),
                    'inperson_venue'      => escape(Input::get('inperson_venue')),
                    'inperson_city'       => escape(Input::get('inperson_city')),
                    'inperson_country'    => escape(Input::get('inperson_country')),
                    'virtual_start_date'  => escape(Input::get('virtual_start_date')),
                    'virtual_end_date'    => escape(Input::get('virtual_end_date')),
                    'virtual_location'    => escape(Input::get('virtual_location')),
                    'creation_date'       => date('Y-m-d')
                ));
                $valid['success']  = true;
                $valid['messages'] = "Successfully created";    
            } catch(Exception $error) {
                $valid['success']  = false;
                $valid['messages'] = "Error while creating content";
            }
        }
        echo json_encode($valid);
    }


    //Load quote section
    if(Input::get('request') && Input::get('request') == 'fetchQuote') {
        $getContent  = DB::getInstance()->get('future_homepage_quote', array('event_id', '=', $eventId));
        if ($getContent->count()) {
            $content_id = $getContent->first()->id;
            $author     = $getContent->first()->author;
            $quote      = $getContent->first()->quote;
        } else {
            $content_id = $author = $quote = "";
        }
	?>
<div class="col-sm-12">
    <div class="form-group">
        <label>Quote<small class="red-color">*</small></label>
        <textarea name="quote" id="quote" class="form-control" placeholder="Enter quote" data-rule="required"
            data-msg="Please enter quote" style="height: 90px;"><?php echo $quote; ?></textarea>
        <div class="validate"></div>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label>Author <small class="red-color">*</small></label>
        <input type="text" name="author" id="author" class="form-control" placeholder="Author" data-rule="required"
            data-msg="Please enter author" value="<?php echo $author; ?>" />
        <div class="validate"></div>
    </div>
</div>
<div class="col-sm-12">
    <input type="hidden" name="request" value="addQuote" />
    <input type="hidden" name="contentId" value="<?php echo $content_id; ?>" />
    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>" />
    <button type="submit" id="addQuoteButton" class="btn btn-primary pull-right" data-loading-text="Loading..."
        autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
</div>
<?php }

    // Add quote content
    if(Input::get('request') && Input::get('request') == 'addQuote') {
        $findContent  = DB::getInstance()->get('future_homepage_quote', array('event_id', '=', Input::get('eventId')));
        if ($findContent->count()) {
            try {
                $controller->update("future_homepage_quote", array(
                    'author' => escape(Input::get('author')),
                    'quote'  => escape(Input::get('quote'))
                ), Input::get('contentId'));
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            } catch(Exception $error) {
                $valid['success']  = false;
                $valid['messages'] = "Error while updating content";
            }
        } else {
            try {
                $controller->create("future_homepage_quote", array(
                    'event_id'      => escape(Input::get('eventId')),
                    'author'        => escape(Input::get('author')),
                    'quote'         => escape(Input::get('quote')),
                    'creation_date' => date('Y-m-d')
                ));
                $valid['success']  = true;
                $valid['messages'] = "Successfully created";    
            } catch(Exception $error) {
                $valid['success']  = false;
                $valid['messages'] = "Error while creating content";
            }
        }
        echo json_encode($valid);
    }


    //Load why attend section
    if(Input::get('request') && Input::get('request') == 'fetchWhyAttend') {
        $getContent  = DB::getInstance()->get('future_homepage_whyattend', array('event_id', '=', $eventId));
        if ($getContent->count()) {
            $content_id = $getContent->first()->id;
            $reason_1   = $getContent->first()->reason_1;
            $reason_2   = $getContent->first()->reason_2;
            $reason_3   = $getContent->first()->reason_3;
            $reason_4   = $getContent->first()->reason_4;
            $reason_5   = $getContent->first()->reason_5;
            $reason_6   = $getContent->first()->reason_6;
        } else {
            $reason_1 = $reason_2 = $reason_3 = $reason_4 = $reason_5 = $reason_6 = "";
        }
    ?>
<div class="col-sm-6">
    <div class="form-group">
        <label>Reason 1 <small class="red-color">*</small></label>
        <textarea name="reason_1" id="reason_1" class="form-control" placeholder="Enter reason" data-rule="required"
            data-msg="Please enter reason 1"><?php echo $reason_1; ?></textarea>
        <div class="validate"></div>
    </div>
    <div class="form-group">
        <label>Reason 3</label>
        <textarea name="reason_3" id="reason_3" class="form-control"
            placeholder="Enter reason"><?php echo $reason_3; ?></textarea>
    </div>
    <div class="form-group">
        <label>Reason 5</label>
        <textarea name="reason_5" id="reason_5" class="form-control"
            placeholder="Enter reason"><?php echo $reason_5; ?></textarea>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label>Reason 2</label>
        <textarea name="reason_2" id="reason_2" class="form-control"
            placeholder="Enter reason"><?php echo $reason_2; ?></textarea>
    </div>
    <div class="form-group">
        <label>Reason 4</label>
        <textarea name="reason_4" id="reason_4" class="form-control"
            placeholder="Enter reason"><?php echo $reason_4; ?></textarea>
    </div>
    <div class="form-group">
        <label>Reason 6</label>
        <textarea name="reason_6" id="reason_6" class="form-control"
            placeholder="Enter reason"><?php echo $reason_6; ?></textarea>
    </div>
</div>
<div class="col-sm-12">
    <input type="hidden" name="request" value="addWhyAttend" />
    <input type="hidden" name="contentId" value="<?php echo $content_id; ?>" />
    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>" />
    <button type="submit" id="addWhyAttendButton" class="btn btn-primary pull-right" data-loading-text="Loading..."
        autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
</div>
<?php }

    // Add why attend content
    if(Input::get('request') && Input::get('request') == 'addWhyAttend') {
        $findContent  = DB::getInstance()->get('future_homepage_whyattend', array('event_id', '=', Input::get('eventId')));
        if ($findContent->count()) {
            try {
                $controller->update("future_homepage_whyattend", array(
                    'reason_1' => escape(Input::get('reason_1')),
                    'reason_2' => escape(Input::get('reason_2')),
                    'reason_3' => escape(Input::get('reason_3')),
                    'reason_4' => escape(Input::get('reason_4')),
                    'reason_5' => escape(Input::get('reason_5')),
                    'reason_6' => escape(Input::get('reason_6'))
                ), Input::get('contentId'));
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            } catch(Exception $error) {
                $valid['success']  = false;
                $valid['messages'] = "Error while updating content";
            }
        } else {
            try {
                $controller->create("future_homepage_whyattend", array(
                    'event_id'      => escape(Input::get('eventId')),
                    'reason_1'      => escape(Input::get('reason_1')),
                    'reason_2'      => escape(Input::get('reason_2')),
                    'reason_3'      => escape(Input::get('reason_3')),
                    'reason_4'      => escape(Input::get('reason_4')),
                    'reason_5'      => escape(Input::get('reason_5')),
                    'reason_6'      => escape(Input::get('reason_6')),
                    'creation_date' => date('Y-m-d')
                ));
                $valid['success']  = true;
                $valid['messages'] = "Successfully created";    
            } catch(Exception $error) {
                $valid['success']  = false;
                $valid['messages'] = "Error while creating content";
            }
        }
        echo json_encode($valid);
    }


    //Load outcomes section
    if(Input::get('request') && Input::get('request') == 'fetchOutcomes') {
        $getContent  = DB::getInstance()->get('future_homepage_outcome', array('event_id', '=', $eventId));
        if ($getContent->count()) {
            $content_id = $getContent->first()->id;
            $outcome_1  = $getContent->first()->outcome_1;
            $outcome_2  = $getContent->first()->outcome_2;
            $outcome_3  = $getContent->first()->outcome_3;
            $outcome_4  = $getContent->first()->outcome_4;
            $outcome_5  = $getContent->first()->outcome_5;
            $outcome_6  = $getContent->first()->outcome_6;
        } else {
            $outcome_1 = $outcome_2 = $outcome_3 = $outcome_4 = $outcome_5 = $outcome_6 = "";
        }
    ?>
<div class="col-sm-6">
    <div class="form-group">
        <label>Outcome 1 <small class="red-color">*</small></label>
        <textarea name="outcome_1" id="outcome_1" class="form-control" placeholder="Enter outcome" data-rule="required"
            data-msg="Please enter outcome 1"><?php echo $outcome_1; ?></textarea>
        <div class="validate"></div>
    </div>
    <div class="form-group">
        <label>Outcome 3</label>
        <textarea name="outcome_3" id="outcome_3" class="form-control"
            placeholder="Enter outcome"><?php echo $outcome_3; ?></textarea>
    </div>
    <div class="form-group">
        <label>Outcome 5</label>
        <textarea name="outcome_5" id="outcome_5" class="form-control"
            placeholder="Enter outcome"><?php echo $outcome_5; ?></textarea>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label>Outcome 2</label>
        <textarea name="outcome_2" id="outcome_2" class="form-control"
            placeholder="Enter outcome"><?php echo $outcome_2; ?></textarea>
    </div>
    <div class="form-group">
        <label>Outcome 4</label>
        <textarea name="outcome_4" id="outcome_4" class="form-control"
            placeholder="Enter outcome"><?php echo $outcome_4; ?></textarea>
    </div>
    <div class="form-group">
        <label>Outcome 6</label>
        <textarea name="outcome_6" id="outcome_6" class="form-control"
            placeholder="Enter outcome"><?php echo $outcome_6; ?></textarea>
    </div>
</div>
<div class="col-sm-12">
    <input type="hidden" name="request" value="addOutcome" />
    <input type="hidden" name="contentId" value="<?php echo $content_id; ?>" />
    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>" />
    <button type="submit" id="addOutcomeButton" class="btn btn-primary pull-right" data-loading-text="Loading..."
        autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
</div>
<?php }

    // Add outcomes content
    if(Input::get('request') && Input::get('request') == 'addOutcome') {
        $findContent  = DB::getInstance()->get('future_homepage_outcome', array('event_id', '=', Input::get('eventId')));
        if ($findContent->count()) {
            try {
                $controller->update("future_homepage_outcome", array(
                    'outcome_1' => escape(Input::get('outcome_1')),
                    'outcome_2' => escape(Input::get('outcome_2')),
                    'outcome_3' => escape(Input::get('outcome_3')),
                    'outcome_4' => escape(Input::get('outcome_4')),
                    'outcome_5' => escape(Input::get('outcome_5')),
                    'outcome_6' => escape(Input::get('outcome_6'))
                ), Input::get('contentId'));
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            } catch(Exception $error) {
                $valid['success']  = false;
                $valid['messages'] = "Error while updating content";
            }
        } else {
            try {
                $controller->create("future_homepage_outcome", array(
                    'event_id'      => escape(Input::get('eventId')),
                    'outcome_1'     => escape(Input::get('outcome_1')),
                    'outcome_2'     => escape(Input::get('outcome_2')),
                    'outcome_3'     => escape(Input::get('outcome_3')),
                    'outcome_4'     => escape(Input::get('outcome_4')),
                    'outcome_5'     => escape(Input::get('outcome_5')),
                    'outcome_6'     => escape(Input::get('outcome_6')),
                    'creation_date' => date('Y-m-d')
                ));
                $valid['success']  = true;
                $valid['messages'] = "Successfully created";    
            } catch(Exception $error) {
                $valid['success']  = false;
                $valid['messages'] = "Error while creating content";
            }
        }
        echo json_encode($valid);
    }


    //Load speakers
    if(Input::get('request') && Input::get('request') == 'fetchSpeakers') {
        $eventId = Input::get('eventId');
        $controller->get('future_homepage_speakers', '*', NULL, "`event_id` = '$eventId'", 'id DESC');
        $i = 0;
        ?>
<div class="col-lg-2" style="margin-bottom: 30px;">
    <div class="event-card event-card-add">
        <div class="event-card-text event-card-speaker">
            <a href="" data-toggle="modal" data-target="#addSpeakerModal" id="addClient" style="margin-top: 50px;"><i
                    class="fa fa-plus"></i> Add speaker</a>
        </div>
    </div>
</div>
<?php
        if (!$controller->count()) {
            Danger("No speaker recorded");
        } else {
            foreach($controller->data() as $resSpeaker) {
                $i++;
                $imageUrl = "data_system/img/speakers/".$resSpeaker->picture;
        ?>
<div style="display: none;">
    <span id="eName<?=$resSpeaker->id?>"><?php echo $resSpeaker->name; ?></span>
    <span id="eOrga<?=$resSpeaker->id?>"><?php echo $resSpeaker->organisation; ?></span>
    <span id="eJob<?=$resSpeaker->id?>"><?php echo $resSpeaker->job_title; ?></span>
</div>
<div class="col-lg-2" style="margin-bottom: 30px;">
    <div class="event-card">
        <img src="<?php linkto($imageUrl); ?>" class="img img-responsive">
        <div class="event-card-text event-card-speaker">
            <a href="#" class="btn btn-white btn-sm pull-right dropdown-toggle edit_speaker"
                data-id="<?php echo $resSpeaker->id;?>"><i class="fa fa-pencil"></i></a>
            <h4><?php echo $resSpeaker->name; ?></h4>
            <small class="block text-muted"><?php echo $resSpeaker->organisation ." ".$resSpeaker->job_title; ?></small>
        </div>
    </div>
</div>
<?php } } ?>
<script>
$(function() {
    $('[data-toggle="popover"]').popover({
        html: true,
        content: function() {
            return $('#popover-content').html();
        }
    });
    $(document).on('click', function(e) {
        $('[data-toggle="popover"]').each(function() {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover')
                .has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
});
</script>
<?php
    }

    // Add speaker
    if(Input::get('request') && Input::get('request') == 'addSpeaker') {
        $type     = explode('.', $_FILES['image']['name']);
        $type     = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct   = root("data_system/img/speakers/"); 
        $url      = $direct . $fileName; 

        if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
            if(is_uploaded_file($_FILES['image']['tmp_name'])) {            
                if(move_uploaded_file($_FILES['image']['tmp_name'], $url)) {
                    try {
                        $controller->create("future_homepage_speakers", array(
                            'event_id'      => escape(Input::get('eventId')),
                            'name'          => escape(Input::get('name')),
                            'organisation'  => escape(Input::get('organisation')),
                            'job_title'     => escape(Input::get('job_title')),
                            'picture'       => $fileName,
                            'creation_date' => date('Y-m-d')
                        ));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully created";    
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while creating speaker";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Upload a valid image";
        }
        echo json_encode($valid);
    }

    // Edit speaker details
    if(Input::get('request') && Input::get('request') == 'editSpeaker') {
        try {
            $controller->update("future_homepage_speakers", array(
                'name'         => escape(Input::get('ename')),
                'organisation' => escape(Input::get('eorganisation')),
                'job_title'    => escape(Input::get('ejob_title'))
            ), Input::get('speakerId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully Updated";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while updating speaker";
        }
        echo json_encode($valid);
    }

    // Edit speaker picture
    if(Input::get('request') && Input::get('request') == 'editSpeakerImage') {
        $type = explode('.', $_FILES['editSpeakerImage']['name']);
        $type = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct = root("data_system/img/speakers/"); 
        $url = $direct . $fileName; 

        if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
            if(is_uploaded_file($_FILES['editSpeakerImage']['tmp_name'])) {           
                if(move_uploaded_file($_FILES['editSpeakerImage']['tmp_name'], $url)) {
                    try {
                        $controller->update("future_homepage_speakers", array('picture' => $fileName), Input::get('speakerId'));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully uploaded";   
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while updating picture";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Invalid file";
        }
        echo json_encode($valid);
    }


    //Load partners
    if(Input::get('request') && Input::get('request') == 'fetchPartners') {
        $eventId = Input::get('eventId');
        $controller->get('future_homepage_partners', '*', NULL, "`event_id` = '$eventId'", 'p_order ASC');
        $i = 0;
        ?>
<div class="col-lg-2" style="margin-bottom: 30px;">
    <div class="event-card event-card-add">
        <div class="event-card-text event-card-speaker">
            <a href="" data-toggle="modal" data-target="#addPartnerModal" id="addClient" style="margin-top: 50px;"><i
                    class="fa fa-plus"></i> Add partner</a>
        </div>
    </div>
</div>
<?php
        if (!$controller->count()) {
            // Danger("No partner recorded");
        } else {
            foreach($controller->data() as $resPartner) {
                $i++;
                $imageUrl = "data_system/img/partners/".$resPartner->logo;
                $eventId  = base64_encode($resPartner->id);
        ?>
<div style="display: none;">
    <span id="eName<?=$resPartner->id?>"><?php echo $resPartner->name; ?></span>
    <span id="eOrder<?=$resPartner->id?>"><?php echo $resPartner->p_order; ?></span>
</div>
<div class="col-lg-2" style="margin-bottom: 30px;">
    <div class="event-card">
        <img src="<?php linkto($imageUrl); ?>" class="img img-responsive">
        <div class="event-card-text event-card-speaker">
            <a href="#" class="btn btn-white btn-sm pull-right dropdown-toggle edit_partner"
                data-id="<?php echo $resPartner->id;?>"><i class="fa fa-pencil"></i></a>
            <h4><?php echo $resPartner->name; ?></h4>
        </div>
    </div>
</div>
<?php } } ?>
<script>
$(function() {
    $('[data-toggle="popover"]').popover({
        html: true,
        content: function() {
            return $('#popover-content').html();
        }
    });
    $(document).on('click', function(e) {
        $('[data-toggle="popover"]').each(function() {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover')
                .has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
});
</script>
<?php
    }

    // Add partner
    if(Input::get('request') && Input::get('request') == 'addPartner') {
        $type     = explode('.', $_FILES['image']['name']);
        $type     = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct   = root("data_system/img/partners/"); 
        $url      = $direct . $fileName; 

        if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
            if(is_uploaded_file($_FILES['image']['tmp_name'])) {            
                if(move_uploaded_file($_FILES['image']['tmp_name'], $url)) {
                    try {
                        $controller->create("future_homepage_partners", array(
                            'event_id'      => escape(Input::get('eventId')),
                            'p_order'       => escape(Input::get('order')),
                            'name'          => escape(Input::get('name')),
                            'logo'          => $fileName,
                            'creation_date' => date('Y-m-d')
                        ));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully created";    
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while creating partner";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Upload a valid image";
        }
        echo json_encode($valid);
    }

    // Edit partner details
    if(Input::get('request') && Input::get('request') == 'editPartner') {
        try {
            $controller->update("future_homepage_partners", array(
                'p_order' => escape(Input::get('eorder')),
                'name'    => escape(Input::get('ename'))
            ),Input::get('partnerId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully Updated";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while updating partner";
        }
        echo json_encode($valid);
    }

    // Edit partner logo
    if(Input::get('request') && Input::get('request') == 'editPartnerImage') {
        $type = explode('.', $_FILES['editPartnerImage']['name']);
        $type = $type[count($type)-1];  
        $fileName = uniqid(rand()).'.'.$type;
        $direct = root("data_system/img/partners/"); 
        $url = $direct . $fileName; 

        if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
            if(is_uploaded_file($_FILES['editPartnerImage']['tmp_name'])) {           
                if(move_uploaded_file($_FILES['editPartnerImage']['tmp_name'], $url)) {
                    try {
                        $controller->update("future_homepage_partners", array('logo' => $fileName), Input::get('partnerId'));
                        $valid['success']  = true;
                        $valid['messages'] = "Successfully uploaded";   
                    } catch(Exception $error) {
                        $valid['success']  = false;
                        $valid['messages'] = "Error while updating logo";
                    }
                }
            }
        } else {
            $valid['success']  = false;
            $valid['messages'] = "Invalid file";
        }
        echo json_encode($valid);
    }


    //Load program sessions
    if(Input::get('request') && Input::get('request') == 'fetchSessions') {
        $eventId   = Input::get('eventId');
        $day       = Input::get('day');
        $getVenue  = DB::getInstance()->get('future_homepage_about', array('event_id', '=', $eventId));
        $city      = @$getVenue->first()->inperson_city;
        $continent = country_to_continent(@$getVenue->first()->inperson_country);
        $timezone  = $continent."/".$city;
        $controller->get('future_homepage_program', '*', NULL, "`event_id` = '$eventId' && `day` = '$day'", 'DATE(start_time) ASC');
        if (!$controller->count()) {
            Danger("No session recorded");
        } else {
            foreach($controller->data() as $resSession) {
        ?>
<div style="display: none;">
    <span id="eName<?=$resSession->id?>"><?php echo $resSession->session_name; ?></span>
    <span id="eType<?=$resSession->id?>"><?php echo $resSession->session_type; ?></span>
    <span id="eAtt<?=$resSession->id?>"><?php echo $resSession->attendance; ?></span>
    <span id="eStart<?=$resSession->id?>"><?php echo $resSession->start_time; ?></span>
    <span id="eEnd<?=$resSession->id?>"><?php echo $resSession->end_time; ?></span>
    <span id="eDescr<?=$resSession->id?>"><?php echo $resSession->description; ?></span>
    <span id="eRoom<?=$resSession->id?>"><?php echo $resSession->room; ?></span>
    <span id="eVid<?=$resSession->id?>"><?php echo $resSession->video; ?></span>
</div>
<div class="media">
    <div class="forum-avatar">
        <h4><?php echo date('g:ia', strtotime($resSession->start_time));?></h4>
        <p><?=$city?></p>
        <h4><?php echo date('g:ia', strtotime(utc_time($resSession->start_time, $timezone)));?></h4>
        <p>GMT</p>
    </div>
    <div class="media-body">
        <button class="btn btn-xs btn-primary pull-right edit_session" data-id="<?php echo $resSession->id;?>"><i
                class="fa fa-pencil"></i> Edit</button>
        <h4 class="session_name"><?php echo $resSession->session_name;?></h4>
        <h5 class="session_type"><?php echo $resSession->session_type;?></h5>
        <?php echo htmlspecialchars_decode(stripslashes($resSession->description));?>
        <h5 class="session_att"><?php echo $resSession->attendance;?></h5>
    </div>
</div>
<?php } } 
    }

    // Add program session
    if(Input::get('request') && Input::get('request') == 'addSession') {
        try {
            $controller->create("future_homepage_program", array(
                'event_id'      => escape(Input::get('eventId')),
                'start_time'    => escape(Input::get('start_time')),
                'end_time'      => escape(Input::get('end_time')),
                'session_name'  => escape(Input::get('session_name')),
                'session_type'  => escape(Input::get('session_type')),
                'attendance'    => escape(Input::get('attendance')),
                'description'   => escape(Input::get('description')),
                'video'         => escape(Input::get('video')),
                'room'          => escape(Input::get('room')),
                'day'           => escape(Input::get('day')),
                'creation_date' => date('Y-m-d')
            ));
            $valid['success']  = true;
            $valid['messages'] = "Successfully created";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while creating session";
        }
        echo json_encode($valid);
    }

    // Edit program session
    if(Input::get('request') && Input::get('request') == 'editSession') {
        try {
            $controller->update("future_homepage_program", array(
                'start_time'   => escape(Input::get('estart_time')),
                'end_time'     => escape(Input::get('eend_time')),
                'session_name' => escape(Input::get('esession_name')),
                'session_type' => escape(Input::get('esession_type')),
                'attendance'   => escape(Input::get('eattendance')),
                'description'  => escape(Input::get('edescription')),
                'video'        => escape(Input::get('evideo')),
                'room'         => escape(Input::get('eroom'))
            ), Input::get('sessionId'));
            $valid['success']  = true;
            $valid['messages'] = "Successfully Updated";    
        } catch(Exception $error) {
            $valid['success']  = false;
            $valid['messages'] = "Error while updating session";
        }
        echo json_encode($valid);
    }

    // Load partnership table
    if(Input::get('fetchParticitants')) {
        $controller->get('future_contact', '*', NULL, "`category` = 'Partner'", 'id DESC LIMIT 15');
        $i = 0;
        if (!$controller->count()) {
            Danger("No user recorded");
        } else {
        ?>
<table class="table dataTables-example">
    <thead>
        <tr>
            <th>#ID</th>
            <th>Full name</th>
            <th>Email</th>
            <th>Telephone</th>
            <th>Organisation</th>
            <th>Message</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
                foreach($controller->data() as $resPart) {
                    $i++;
                ?>

        <tr class="gradeX" style="background: #f8f8f8; border-bottom: 2px solid #fff;">
            <td>
                <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                    <?php echo $i;?>
                </span>
            </td>
            <td><?php echo $resPart->firstname. " ".$resPart->lastname; ?></< /td>
            <td><?php echo $resPart->email; ?></td>
            <td><?php echo $resPart->telephone; ?></td>
            <td><?php echo $resPart->organisation_name; ?></td>
            <td><?php echo $resPart->message; ?></td>
            <td><span class="label label-<?php echo $resPart->status == 'Confirm'? 'success' : 'warning'; ?>"
                    style="display: block;"><?php echo $resPart->status; ?></span></td>
            <td>
                <div class="ibox-tools">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                    <ul class="dropdown-menu dropdown-user popover-menu-list">
                        <li><a class="menu edit_client" data-id="<?php echo $resPart->id;?>"><i
                                    class="fa fa-envelope icon"></i> Message</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        <?php } } ?>
    </tbody>
</table>
<script>
$(document).ready(function() {
    $('.dataTables-example').dataTable({
        responsive: true,
        "dom": 'T<"clear">lfrtip',
        "tableTools": {
            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
        }
    });
});
</script>
<?php
    }


            /**  Generate and send link for registration */
        if(Input::get('request') && Input::get('request') == 'sendPrivateLink') {
                $_form_ = FutureEventController::createEventParticipantPrivateLink();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully generated and sent to {$_form_->EMAIL}";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
        }

        /** Edit Generated Link and send email */ 
        if(Input::get('request') && Input::get('request') == 'editAndSendPrivateLink') {
                $_form_ = FutureEventController::updateEventParticipantPrivateLink();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully updated ";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
        }
        
        /** Edit Generated Link and send email */ 
        if(Input::get('request') && Input::get('request') == 'activatePrivateLink') {
            $_form_ = FutureEventController::changeStatusParticipantPrivateLink('ACTIVE');
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully activated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
        }

           
        /** Edit Generated Link and send email */ 
        if(Input::get('request') && Input::get('request') == 'deactivatePrivateLink') {
            $_form_ = FutureEventController::changeStatusParticipantPrivateLink('DEACTIVE');
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully deactivated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
        }
     
        /** Edit Generated Link and send email */ 
        if(Input::get('request') && Input::get('request') == 'resendPrivateLink') {
            $_form_ = FutureEventController::resendEventParticipantPrivateLink();
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully resent";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
        }

        // Delete private link
        if(Input::get('request') && Input::get('request') == 'deletePrivateLink') {
            try {
                $controller->delete('future_private_links', array('id', '=', Input::get('Id')));
                $valid['success'] = true;
                $valid['messages'] = "Successfully deleted";    
            } catch(Exception $error) {
                $valid['success'] = false;
                $valid['messages'] = "Error while deleting";
            }
            echo json_encode($valid);   
        }

        /** Load List Of Generated Links */
        if(Input::get('request') && Input::get('request') == 'fetchGeneratedLinks') {
            $eventId     = Input::get('eventId');
            $_LIST_DATA_ = FutureEventController::getGeneratedPrivateLinks($eventId);

            if (!$_LIST_DATA_) {
                Danger("No link recorded");
            } else {
    ?>

<table class="table dataTables-example customTable">
    <thead>
        <tr>
            <th>#ID</th>
            <th>First name</th>
            <th>Last name</th>
            <th>Email</th>
            <th>Participation Type</th>
            <th>Generated time</th>
            <th>Registration time</th>
            <th>Copy link</th>
            <th>Status</th>
            <th>Action</th>

        </tr>
    </thead>
    <tbody>
        <?php
// $_LIST_DATA_ = FutureEventController::getGeneratedPrivateLinks($eventId);
if($_LIST_DATA_): $count_ = 0;
    foreach($_LIST_DATA_  As $_link_): $count_++;

        $_status_ = $_link_->status;
        $_status_label_ = 'badge-warning';

        if($_status_ == 'COMPLETED' || $_status_ == 'USED')
            $_status_label_ = 'badge-success';
        if($_status_ == 'ACTIVE')
            $_status_label_ = 'badge-info';
        if($_status_ == 'DEACTIVE')
            $_status_label_ = 'badge-danger';
        if($_status_ == 'EXPIRED')
            $_status_label_ = 'badge-default';

        
?>
        <tr class="gradeX">
            <td>
                <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                    <?="TRSPL-". $count_;?>
                </span>
            </td>
            <td><?=$_link_->firstname?></td>
            <td><?=$_link_->lastname?></td>
            <td><?=$_link_->email?></td>
            <td><?=$_link_->type_name .'  '.$_link_->sub_type_name?></td>
            <td><?=date('d-M-Y', $_link_->access_generated_time)?></td>
            <td><?=date('d-M-Y', $_link_->access_generated_time)?></td>
            <td>
                <button class="btn-white btn-bitbucket" data-toggle="tooltip" data-placement="top"
                    title="Copy to clipboard" onclick="copyToClipboard('#tooltip_<?=$_link_->id?>', <?=$_link_->id?>)">
                    <i class="fa fa-copy text-info" data-link="<?=$_link_->generated_link?>"
                        id="tooltip_<?=$_link_->id?>"></i>
                        <small id="alert_<?=$_link_->id?>" style="color: #23c6c8;"></small>
                </button>
            </td>
            <td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
            <td>
                <div class="ibox-tools">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                    <ul class="dropdown-menu dropdown-user popover-menu-list">
                        <li><a class="menu edit_client" data-toggle="modal"
                                data-target="#editLinkModal<?=Hash::encryptToken($_link_->id)?>"><i
                                    class="fa fa-pencil icon"></i> Edit</a></li>
                        <!-- <li><a class="menu edit_client" data-toggle="modal" data-target="#activateModal<?=Hash::encryptToken($_link_->id)?>" ><i class="fa fa-check icon"></i> Activate</a></li>
                                                <li><a class="menu edit_client" data-toggle="modal" data-target="#deactivateModal<?=Hash::encryptToken($_link_->id)?>" ><i class="fa fa-remove icon"></i> Deactivate</a></li> -->
                        <li><a class="menu edit_client" data-toggle="modal"
                                data-target="#resendModal<?=Hash::encryptToken($_link_->id)?>"><i
                                    class="fa fa-send icon"></i> Resend Link</a></li>

                        <?php if($user->hasPermission('admin')): ?>
                        <li><a class="menu edit_client" data-toggle="modal" data-target="#deleteModal<?=Hash::encryptToken($_link_->id)?>"><i class="fa fa-trash icon"></i> Delete Link</a></li>
                        <?php endif;?>
                    </ul>
                </div>
            </td>
        </tr>


        <?php
    endforeach;
endif;
?>
    </tbody>
</table>
<script>
$(document).ready(function() {
    $('.dataTables-example').dataTable({
        responsive: true,
        "dom": 'T<"clear">lfrtip',
        "tableTools": {
            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
        }
    });
});
</script>
<?php
            }
        }









    
        /**  Create Event Participation Type */
        if(Input::get('request') && Input::get('request') == 'registerParticipationType') {
                $_form_ = FutureEventController::createEventParticipationType();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully registered";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
        }

        /** Edit Event Participation Type */ 
        if(Input::get('request') && Input::get('request') == 'editParticipationType') {
                $_form_ = FutureEventController::updateEventParticipationType();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Successfully updated";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
                endif;
                echo json_encode($valid);
        }
        
        /** Change Status Active Event Participation Type */ 
        if(Input::get('request') && Input::get('request') == 'activateParticipationType') {
            $_form_ = FutureEventController::changeStatusParticipationType('ACTIVE');
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully activated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
        }

           
        /** Change Status Deactive Event Participation Type */ 
        if(Input::get('request') && Input::get('request') == 'deactivateParticipationType') {
            $_form_ = FutureEventController::changeStatusParticipationType('DEACTIVE');
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully deactivated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
        }

        /** Load List Of Event Participation Types */
        if(Input::get('request') && Input::get('request') == 'fetchParticipationTypes') {
            $eventId     = Input::get('eventId');
            $_LIST_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId);

            if (!$_LIST_DATA_) {
                Danger("No link recorded");
            } else {
    ?>

<table class="table dataTables-example customTable">
    <thead>
        <tr>
            <th>#ID</th>
            <th>Type name</th>
            <th>Payment status</th>
            <th>Visibility</th>
            <th>Status</th>
            <th>Action</th>

        </tr>
    </thead>
    <tbody>
        <?php
$_LIST_DATA_ = FutureEventController::getPacipationTypeyByEventID($eventId);
if($_LIST_DATA_): $count_ = 0;
    foreach($_LIST_DATA_  As $_participation_): $count_++;
        
        $_status_ = $_participation_->status;
        $_status_label_ = 'badge-warning';

        if($_status_ == 'COMPLETED')
            $_status_label_ = 'badge-info';
        if($_status_ == 'ACTIVE')
            $_status_label_ = 'badge-success';
        if($_status_ == 'DEACTIVE')
            $_status_label_ = 'badge-danger';
        if($_status_ == 'EXPIRED')
            $_status_label_ = 'badge-default';
?>
        <tr class="gradeX">
            <td>
                <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                    <?php echo "FSUM-". $count_;?>
                </span>
            </td>
            <td><?=$_participation_->name?></td>
            <td><?=$_participation_->payment_state?></td>
            <td><span class="badge <?= $_participation_->visibility_state == 0? 'badge-success':'badge-default'?>"
                    style="display: block;"><?=$_participation_->visibility_state == 0?'Private':'Public'?></span></td>
            <td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
            <td>
                <div class="ibox-tools">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                    <ul class="dropdown-menu dropdown-user popover-menu-list">
                        <li><a class="menu edit_client" data-toggle="modal"
                                data-target="#editModal<?=Hash::encryptToken($_participation_->id)?>"><i
                                    class="fa fa-pencil icon"></i> Edit</a></li>
                        <li><a class="menu edit_client" data-toggle="modal"
                                data-target="#activateModal<?=Hash::encryptToken($_participation_->id)?>"><i
                                    class="fa fa-check icon"></i> Activate</a></li>
                        <li><a class="menu edit_client" data-toggle="modal"
                                data-target="#deactivateModal<?=Hash::encryptToken($_participation_->id)?>"><i
                                    class="fa fa-remove icon"></i> Deactivate</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        <?php
    endforeach;
endif;
?>
    </tbody>
</table>
<script>
$(document).ready(function() {
    $('.dataTables-example').dataTable({
        responsive: true,
        "dom": 'T<"clear">lfrtip',
        "tableTools": {
            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
        }
    });
});
</script>
<?php
               
            }
}















    
        /**  Create Event Participation Sub Type */
        if(Input::get('request') && Input::get('request') == 'registerParticipationSubType') {
            $_form_ = FutureEventController::createEventParticipationSubType();
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully registered";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
    }

    /** Edit Event Participation Type */ 
    if(Input::get('request') && Input::get('request') == 'editParticipationSubType') {
            $_form_ = FutureEventController::updateEventParticipationSubType();
            if($_form_->ERRORS == false):
                $valid['success']  = true;
                $valid['messages'] = "Successfully updated";    
            else:
                $valid['success']  = false;
                $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
            endif;
            echo json_encode($valid);
    }
    
    /** Change Status Active Event Participation Type */ 
    if(Input::get('request') && Input::get('request') == 'activateParticipationSubType') {
        $_form_ = FutureEventController::changeStatusParticipationSubType('ACTIVE');
        if($_form_->ERRORS == false):
            $valid['success']  = true;
            $valid['messages'] = "Successfully activated";    
        else:
            $valid['success']  = false;
            $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
        endif;
        echo json_encode($valid);
    }

       
    /** Change Status Deactive Event Participation Type */ 
    if(Input::get('request') && Input::get('request') == 'deactivateParticipationSubType') {
        $_form_ = FutureEventController::changeStatusParticipationSubType('DEACTIVE');
        if($_form_->ERRORS == false):
            $valid['success']  = true;
            $valid['messages'] = "Successfully deactivated";    
        else:
            $valid['success']  = false;
            $valid['messages'] = "Error {$_form_->ERRORS_STRING}";
        endif;
        echo json_encode($valid);
    }

    /** Load List Of Event Participation Types */
    if(Input::get('request') && Input::get('request') == 'fetchParticipationSubTypes') {
        $eventId     = Input::get('eventId');
        $_LIST_DATA_ = FutureEventController::getPacipationSubType($eventId);

        if (!$_LIST_DATA_) {
            Danger("No link recorded");
        } else {
?>
<table class="table dataTables-example customTable">
    <thead>
        <tr>
            <th>#ID</th>
            <th>Type name</th>
            <th>Sub Type name</th>
            <th>Category</th>
            <th>Payment</th>
            <th>Price</th>
            <th>Visibility</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
$_LIST_DATA_ = FutureEventController::getPacipationSubType($eventId);
if($_LIST_DATA_): $count_ = 0;
    foreach($_LIST_DATA_  As $_data_): $count_++;

        $_price_currency_ = ($_data_->price == 0 || $_data_->payment_state == 'FREE')?'-': number_format($_data_->price).' <small class="pull-rightt  text-dark text-bold" >'.$_data_->currency.'<small>';    

        $_status_ = $_data_->status;
        $_status_label_ = 'badge-warning';

        if($_status_ == 'COMPLETED')
            $_status_label_ = 'badge-info';
        if($_status_ == 'ACTIVE')
            $_status_label_ = 'badge-success';
        if($_status_ == 'DEACTIVE')
            $_status_label_ = 'badge-danger';
        if($_status_ == 'EXPIRED')
            $_status_label_ = 'badge-default';
?>
        <tr>
            <td>
                <span style="color: #3c8dbc; border-left: 2px solid #3c8dbc; padding: 3px; font-size: 12px;">
                    <?=$count_;?>
                </span>
            </td>
            <td><?=$_data_->type_name?></td>
            <td><?=$_data_->name?></td>
            <td><?=$_data_->category?></td>
            <td><?=$_data_->payment_state?></td>
            <td><?=$_price_currency_?></td>
            <td><span class="badge <?= $_data_->type_visibility == 0? 'badge-success':'badge-default'?>"
                    style="display: block;"><?=$_data_->type_visibility == 0?'Private':'Public'?></span></td>
            <td><span class="badge <?= $_status_label_ ?>" style="display: block;"><?=$_status_ ?></span></td>
            <td>
                <div class="ibox-tools">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #3c8dbc;">More</a>
                    <ul class="dropdown-menu dropdown-user popover-menu-list">
                        <li><a class="menu edit_client" data-toggle="modal"
                                data-target="#editModal<?=Hash::encryptToken($_data_->id)?>"><i
                                    class="fa fa-pencil icon"></i> Edit</a></li>
                        <li><a class="menu edit_client" data-toggle="modal"
                                data-target="#activateModal<?=Hash::encryptToken($_data_->id)?>"><i
                                    class="fa fa-check icon"></i> Activate</a></li>
                        <li><a class="menu edit_client" data-toggle="modal"
                                data-target="#deactivateModal<?=Hash::encryptToken($_data_->id)?>"><i
                                    class="fa fa-remove icon"></i> Deactivate</a></li>
                    </ul>
                </div>
            </td>
        </tr>
        <?php
    endforeach;
endif;
?>
    </tbody>
</table>
<script>
$(document).ready(function() {
    $('.dataTables-example').dataTable({
        responsive: true,
        "dom": 'T<"clear">lfrtip',
        "tableTools": {
            "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
        }
    });
});
</script>
<?php
           
        }
}
?>