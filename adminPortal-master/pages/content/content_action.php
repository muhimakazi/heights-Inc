<?php
require_once "../../core/init.php"; 
if(!$user->isLoggedIn()) 
    Redirect::to('login');
  
$response['success'] = array('success' => false, 'messages' => array(), 'data' => array());

if(Input::checkInput('request', 'post', 1)):
    $_post_request_ = Input::get('request', 'post');
    switch($_post_request_):

// 1. EVENT LOGO

        //Load event logo
        case 'fetchLogo':
            $getContent = TemplateController::getEventDetailsByID($eventId);
            $general_style = $getContent->general_style;
            $styleArray = json_decode($general_style, true);
            $style = (object)$styleArray;
            $primaryColor = $style->primaryColor;
            if ($getContent->logo != "") {
                $imageUrl = "data_system/img/banner/".$getContent->logo;
            } else {
                $imageUrl = "data_system/img/photo_default.png";
            }
            ?>
            <div class="ibox-title">
                <?php if ($getContent->logo == "") { ?>
                <button class="btn btn-xs btn-primary pull-right edit_logo" data-id="<?=$eventId?>"><i
                        class="fa fa-plus-circle"></i> Add logo</button>
                <?php } else { ?>
                <button class="btn btn-xs btn-primary pull-right edit_logo" data-id="<?=$eventId?>"><i
                        class="fa fa-pencil"></i> Edit logo</button>
                <?php } ?>
            </div>
            <div class="ibox-content">
                <img src="<?php linkto($imageUrl);?>" class="img img-responsive">
            </div>
            <?php 
        break;

        // Edit event logo
        case 'editEventLogo':
            $type = explode('.', $_FILES['editEventLogo']['name']);
            $type = $type[count($type)-1];  
            $fileName = uniqid(rand()).'.'.$type;
            $direct = root("data_system/img/banner/"); 
            $url = $direct . $fileName; 

            if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
                if(is_uploaded_file($_FILES['editEventLogo']['tmp_name'])) {           
                    if(move_uploaded_file($_FILES['editEventLogo']['tmp_name'], $url)) {
                        try {
                            $controller->update("future_event", array('logo' => $fileName), Input::get('eventId'));
                            $response['success']  = true;
                            $response['messages'] = "Successfully uploaded";   
                        } catch(Exception $error) {
                            $response['success']  = false;
                            $response['messages'] = "Error while updating banner";
                        }
                    }
                }
            } else {
                $response['success']  = false;
                $response['messages'] = "Invalid file";
            }
            echo json_encode($response);
        break;


    // 2. EVENT BANNER

        //Load event banner
        case 'fetchBanner':
            $getContent  = TemplateController::getEventDetailsByID($eventId);
            $event_name = $getContent->event_name;
            $start_date = date('j', strtotime(dateFormat($getContent->start_date)));
            $end_date   = date("j F Y", strtotime(dateFormat($getContent->end_date)));
            $event_date = $start_date." - ".$end_date;
            $venue      = $getContent->venue;
            $general_style = $getContent->general_style;
            $styleArray = json_decode($general_style, true);
            $style = (object)$styleArray;
            $primaryColor = $style->primaryColor;
            if ($getContent->banner != "") {
                $imageUrl = "data_system/img/banner/".$getContent->banner;
            } else {
                $imageUrl = "data_system/img/photo_default.png";
            }
            if(array_key_exists("bannerTextPosition", $styleArray)){
                $text_position = $style->bannerTextPosition;
            } else {
                $text_position = 'center';
            }
            ?>
            <div class="ibox-title">
                <?php if ($getContent->banner == "") { ?>
                <button class="btn btn-xs btn-primary pull-right edit_banner" data-id="<?=$eventId?>"><i class="fa fa-plus-circle"></i> Add banner</button>
                <?php } else { ?>
                <div class="col-sm-8">
                    <div class="row form-group">
                        <label class="col-lg-3">Text position</label>
                        <div class="col-lg-7">
                            <select class="form-control" name="text_position" id="text_position" onchange="bannerTextPosition();">
                                <option value="Center">Center</option>
                                <option value="Right">Right</option>
                                <option value="Left">Left</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-xs btn-primary pull-right edit_banner" data-id="<?=$eventId?>"><i class="fa fa-pencil"></i> Edit banner</button>
                <?php } ?>
            </div>
            <div class="slider_area">
                <div class="ibox-content" style="background-image: url(../<?=$imageUrl?>); height: 400px;background-size: cover;background-repeat: no-repeat; background-position: center center; display: flex; align-items: center; justify-content: <?=$text_position?>;">
                    <div style="text-align: center; z-index: 1;">
                        <h3 style="color: #fff; font-size: 40px;"><?=$event_name?></h3>
                        <hr style="border-top: 2px solid #fff; opacity: 1;"/>
                        <p style="color: #fff;font-size: 20px;"><?=$event_date?></p>
                        <p style="color: #fff;font-size: 20px;">Venue: <?=$venue?></p>
                        <a class="btn btn-primary" style="background:<?=$primaryColor?>; border-color: <?=$primaryColor?>;">Register Now</a>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                var text_position = '<?=$text_position?>';
                $("#text_position").val(text_position);
            </script>
            <?php 
        break;

        // Edit event banner
        case 'editEventImage':
            $type = explode('.', $_FILES['editEventImage']['name']);
            $type = $type[count($type)-1];  
            $fileName = uniqid(rand()).'.'.$type;
            $direct = root("data_system/img/banner/"); 
            $url = $direct . $fileName; 

            if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
                if(is_uploaded_file($_FILES['editEventImage']['tmp_name'])) {           
                    if(move_uploaded_file($_FILES['editEventImage']['tmp_name'], $url)) {
                        try {
                            $controller->update("future_event", array('banner' => $fileName), $eventId);
                            $response['success']  = true;
                            $response['messages'] = "Successfully uploaded";   
                        } catch(Exception $error) {
                            $response['success']  = false;
                            $response['messages'] = "Error while updating banner";
                        }
                    }
                }
            } else {
                $response['success']  = false;
                $response['messages'] = "Invalid file";
            }
            echo json_encode($response);
        break;

        // Banner text position
        case 'bannerTextPosition':
            $style['bannerTextPosition'] = Input::get('text_position');
            $styles = json_encode($style);
            $eventDetails = TemplateController::getEventDetailsByID($eventId);
            $general_style = html_entity_decode($eventDetails->general_style);
            if(json_decode($general_style) != null){
                $styles = Functions::json_overwrite($general_style, $styles);
            }
            try {
                $controller->update("future_event", array('general_style' => $styles), $eventId);
                $response['success']  = true;
                $response['messages'] = "Successfully";   
            } catch(Exception $error) {
                $response['success']  = false;
                $response['messages'] = "Error while updating";
            }
            echo json_encode($response);
        break;


    // 2. COUNTDOWN
        case 'fetchCountdown':
            $getContent    = TemplateController::getEventDetailsByID($eventId);
            $start_date    = $getContent->start_date;
            $start_date_c  = dateFormat($getContent->start_date);
            $end_date      = $getContent->end_date;
            $end_date_c    = dateFormat($getContent->end_date);
            $set_countdown = date("F d, Y", strtotime($start_date_c));
            $end_countdown = date("F d, Y", strtotime($end_date_c));
            $eventDays     = dateDiff($start_date_c, $end_date_c);

            $getCountDown = TemplateController::getCountDown($eventId);
            if ($getCountDown) {
                $content_id = $getCountDown->id;
                $title = $getCountDown->title;
                $bg_color = $getCountDown->bg_color;
                $countdownStatus = $getCountDown->status;
            } else {
                $content_id = '';
                $title = 'Countdown to Summit! Are you ready?';
                $bg_color = '#f47e20';
                $countdownStatus = 'DEACTIVE';
            }
        ?>
            <div class="ibox-title" style="height: auto; overflow: auto;">
                <h3 class="pull-right">
                    <?php if($countdownStatus == "ACTIVE") { ?>
                    Activated <input type="checkbox" class="countDown-status" checked />
                    <?php } else { ?>
                        Deactivated <input type="checkbox" class="countDown-status" />
                    <?php } ?>
                </h3>
                <h3><?php echo $start_date ." to ". $end_date; ?> </h3>
                
                <div class="row" style="margin-top: 20px">
                    <div class="col-md-6">
                        <div class="form-group control-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?=$title?>" data-msg="This is required"/>
                            <p class="validate"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Background color (HEX code) <small class="red-color">*</small></label>
                        <div class="form-group control-group">
                            <input type="text" name="bg_color" id="bg_color" class="form-control" value="<?=$bg_color?>" data-rule="required" data-msg="This is required"/>
                            <p class="validate"></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="" style="visibility: hidden; margin-bottom: 0;">Submit</label>
                        <input type="hidden" name="request" value="addCountdown">
                        <input type="hidden" name="contentId" id="contentId" value="<?=$content_id?>">
                        <button class="btn btn-primary" id="countdownButton">Submit</button>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="countdown" style="background:<?=$bg_color?>">
                            <div class="clock" id="clock">
                                <h3><?=$title?></h3>
                                <div class="column w3l" style="background:<?=$bg_color?>">
                                    <div class="timer w3" id="days"></div>
                                    <div class="aits text">DAYS</div>
                                </div>
                                <div class="column w3" style="background:<?=$bg_color?>">
                                    <div class="timer w3layouts" id="hours"></div>
                                    <div class="agileits text">HRS</div>
                                </div>
                                <div class="column wthree" style="background:<?=$bg_color?>">
                                    <div class="timer w3las" id="minutes"></div>
                                    <div class="text aits">MIN</div>
                                </div>
                                <div class="column siteliga" style="background:<?=$bg_color?>">
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
            var setCountdown = '<?=$set_countdown?>';
            var enCountdown = '<?=$end_countdown?>';
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
            <script src="<?=DN?>/js/plugins/switchery/switchery.js"></script>
            <script src="<?=DN?>/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
            <script>
                var elem = document.querySelector('.countDown-status');
                var switchery = new Switchery(elem, { color: '#1AB394' });
                $('#bg_color').colorpicker();
            </script>
        <?php
        break;

        // countdownStatus
        case 'countdownStatus':
            $status = Input::get('status');
            $content_id = Input::get('contentId');
            try {
                $controller->update("future_countdown", array('status' => $status), $content_id);
                $response['success']  = true;
                $response['messages'] = "Successfully uploaded";   
            } catch(Exception $error) {
                $response['success']  = false;
                $response['messages'] = "Error while updating";
            }
            echo json_encode($response);
        break;

        // Add countdown
        case 'addCountdown':
            $findContent = TemplateController::getCountDown($eventId);
            if ($findContent) {
                try {
                    $content_id = Input::get('contentId');
                    $title = escape(Input::get('title'));
                    $bg_color = Input::get('bg_color');
                    $controller->update("future_countdown", array('title' => $title, 'bg_color' => $bg_color), $content_id);
                    $response['success']  = true;
                    $response['messages'] = "Success";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while resetting countdown";
                }
            } else {
                try {
                    $controller->create("future_countdown", array(
                        'event_id'   => $eventId,
                        'title' => escape(Input::get('title')),
                        'bg_color' => Input::get('bg_color')
                    ));
                    $response['success']  = true;
                    $response['messages'] = "Successfully setted";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while setting countdown";
                }
            }
            echo json_encode($response);
        break;

    // 3. ABOUT SECTION
        // Add about content
        case 'addAbout':
            $title = escape(Input::get('title'));
            $about_event = escape(Input::get('about_event'));
            $style['titleColor'] = Input::get('title_color');
            $style['textColor'] = Input::get('text_color');
            $style['bgColor'] = Input::get('bg_color');
            $general_style = json_encode($style);
            $findContent  = TemplateController::getAboutSection($eventId);
            if ($findContent) {
                try {
                    $controller->update("future_homepage_about", array(
                        'title' => $title,
                        'about_event' => $about_event,
                        'general_style' => $general_style,
                    ), Input::get('contentId'));
                    $response['success']  = true;
                    $response['messages'] = "Successfully updated";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while updating content";
                }
            } else {
                try {
                    $controller->create("future_homepage_about", array(
                        'event_id' => $eventId,
                        'title' => $title,
                        'about_event' => $about_event,
                        'general_style' => $general_style,
                        'creation_date' => date("Y-m-d H:i:s")
                    ));
                    $response['success']  = true;
                    $response['messages'] = "Successfully created";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while creating content";
                }
            }
            echo json_encode($response);
        break;

        //Load about section
        case 'fetchAbout':
            $getContent  = TemplateController::getAboutSection($eventId);
            if ($getContent) {
                $content_id = $getContent->id;
                $title = $getContent->title;
                $about_event = $getContent->about_event;
                $status = $getContent->status;
                $general_style = $getContent->general_style;
                $styleArray = json_decode($general_style, true);
                $style = (object)$styleArray;
                $textColor = $style->textColor;
                $titleColor = $style->titleColor;
                $bgColor = $style->bgColor;
            } else {
                $content_id = $about_event = $title = "";
                $textColor = '#7A838B';
                $titleColor = "#7A838B";
                $bgColor = "#ffffff";
            }
        ?>
            <h3 class="pull-right">
                <?php if($status == "ACTIVE") { ?>
                Activated <input type="checkbox" class="about-status" checked />
                <?php } else { ?>
                    Deactivated <input type="checkbox" class="about-status" />
                <?php } ?>
            </h3>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Title<small class="red-color">*</small></label>
                    <input type="text" name="title" id="title" placeholder="Title" class="form-control" data-rule="required" data-msg="Please enter title" value="<?=$title?>">
                    <div class="validate"></div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>About the summit (maximum 1500 characters)<small class="red-color">*</small></label>
                    <textarea name="about_event" id="about_event" class="form-control" placeholder="Tell us about your event"
                        data-rule="maxlen:1500" data-msg="Please only 1500 characters"
                        style="height: 100px;"><?=$about_event?></textarea>
                    <div class="validate"></div>
                </div>
            </div>
            <div class="col-md-12">
                <h4>STYLES</h4>
            </div>
            <div class="col-md-4">
                <label>Title color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="title_color" id="title_color" class="form-control" value="<?=$titleColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-md-4">
                <label>Text color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="text_color" id="text_color" class="form-control" value="<?=$textColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-md-4">
                <label>Background color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="bg_color" id="bg_color" class="form-control" value="<?=$bgColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            
            <div class="col-sm-12">
                <input type="hidden" name="request" value="addAbout" />
                <input type="hidden" name="contentId" id="contentId" value="<?=$content_id?>" />
                <button type="submit" id="addAboutButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
            </div>

            <script src="<?=DN?>/js/plugins/switchery/switchery.js"></script>
            <script src="<?=DN?>/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
            <script>
                var elem = document.querySelector('.about-status');
                var switchery = new Switchery(elem, { color: '#1AB394' });
                $('#title_color').colorpicker();
                $('#text_color').colorpicker();
                $('#bg_color').colorpicker();
            </script>
        <?php
        break;

        // aboutStatus
        case 'aboutStatus':
            $status = Input::get('status');
            $content_id = Input::get('contentId');
            try {
                $controller->update("future_homepage_about", array('status' => $status), $content_id);
                $response['success']  = true;
                $response['messages'] = "Successfully uploaded";   
            } catch(Exception $error) {
                $response['success']  = false;
                $response['messages'] = "Error while updating";
            }
            echo json_encode($response);
        break;


    // 4. WHY ATTEND SECTION SECTION
        // Add content
        case 'addWhyAttend':
            $title = escape(Input::get('title'));
            $reason['reason_1'] = escape(Input::get('reason_1'));
            $reason['reason_2'] = escape(Input::get('reason_2'));
            $reason['reason_3'] = escape(Input::get('reason_3'));
            $reason['reason_4'] = escape(Input::get('reason_4'));
            $reason['reason_5'] = escape(Input::get('reason_5'));
            $reason['reason_6'] = escape(Input::get('reason_6'));
            $reason['reason_7'] = escape(Input::get('reason_7'));
            $reason['reason_8'] = escape(Input::get('reason_8'));
            $reason['reason_9'] = escape(Input::get('reason_9'));
            $reason['reason_10'] = escape(Input::get('reason_10'));
            $reason['reason_11'] = escape(Input::get('reason_11'));
            $reason['reason_12'] = escape(Input::get('reason_12'));
            $reasons = json_encode($reason);
            $style['titleColor'] = Input::get('title_color');
            $style['textColor'] = Input::get('text_color');
            $style['bgColor'] = Input::get('bg_color');
            $general_style = json_encode($style);
            $findContent  = TemplateController::getWhyAttend($eventId);
            if ($findContent) {
                try {
                    $controller->update("future_homepage_whyattend", array(
                        'title' => $title,
                        'reason' => $reasons,
                        'general_style' => $general_style,
                    ), Input::get('contentId'));
                    $response['success']  = true;
                    $response['messages'] = "Successfully updated";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while updating content";
                }
            } else {
                try {
                    $controller->create("future_homepage_whyattend", array(
                        'event_id' => $eventId,
                        'title' => $title,
                        'reason' => $reasons,
                        'general_style' => $general_style,
                        'creation_date' => date("Y-m-d H:i:s")
                    ));
                    $response['success']  = true;
                    $response['messages'] = "Successfully created";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while creating content";
                }
            }
            echo json_encode($response);
        break;

        //Load section
        case 'fetchWhyAttend':
            $getContent  = TemplateController::getWhyAttend($eventId);
            if ($getContent) {
                $title = $getContent->title;
                $content_id = $getContent->id;
                $reasons = $getContent->reason;
                $reasonArray = json_decode($reasons, true);
                $reason = (object)$reasonArray;
                $reason_1 = $reason->reason_1;
                $reason_2 = $reason->reason_2;
                $reason_3 = $reason->reason_3;
                $reason_4 = $reason->reason_4;
                $reason_5 = $reason->reason_5;
                $reason_6 = $reason->reason_6;
                $reason_7 = $reason->reason_7;
                $reason_8 = $reason->reason_8;
                $reason_9 = $reason->reason_9;
                $reason_10 = $reason->reason_10;
                $reason_11 = $reason->reason_11;
                $reason_12 = $reason->reason_12;
                $status = $getContent->status;
                $general_style = $getContent->general_style;
                $styleArray = json_decode($general_style, true);
                $style = (object)$styleArray;
                $titleColor = $style->titleColor;
                $textColor = $style->textColor;
                $bgColor = $style->bgColor;
            } else {
                $content_id = $about_event = $title = "";
                $reason_1 = $reason_2 = $reason_3 = $reason_4 = $reason_5 = $reason_6 = $reason_7 = $reason_8 = $reason_9 = $reason_10 = $reason_11 = $reason_12 = "";
                $textColor = '#7A838B';
                $titleColor = "#7A838B";
                $bgColor = "#ffffff";
                $status = "DEACTIVE";
            }
        ?>
            
            <div class="col-md-12">
                <h3 class="pull-right">
                    <?php if($status == "ACTIVE") { ?>
                    Activated <input type="checkbox" class="why-attend-status" checked />
                    <?php } else { ?>
                        Deactivated <input type="checkbox" class="why-attend-status" />
                    <?php } ?>
                </h3>
            </div>
            <div class="form-group col-md-12">
                <label>Title<small class="red-color">*</small></label>
                <input type="text" name="title" id="title" placeholder="Title" class="form-control" data-rule="required" data-msg="Please enter title" value="<?=$title?>">
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 1</label>
                <textarea name="reason_1" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_1?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 2</label>
                <textarea name="reason_2" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_2?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 3</label>
                <textarea name="reason_3" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_3?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 4</label>
                <textarea name="reason_4" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_4?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 5</label>
                <textarea name="reason_5" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_5?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 6</label>
                <textarea name="reason_6" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_6?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 7</label>
                <textarea name="reason_7" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_7?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 8</label>
                <textarea name="reason_8" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_8?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 9</label>
                <textarea name="reason_9" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_9?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 10</label>
                <textarea name="reason_10" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_10?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 11</label>
                <textarea name="reason_11" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_11?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Reason 12</label>
                <textarea name="reason_12" class="form-control" placeholder="Enter reason" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$reason_12?></textarea>
                <div class="validate"></div>
            </div>
            
            <div class="col-md-4">
                <label>Title color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="title_color" id="title_color" class="form-control" value="<?=$titleColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-md-4">
                <label>Text color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="text_color" id="text_color" class="form-control" value="<?=$textColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-md-4">
                <label>Background color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="bg_color" id="bg_color" class="form-control" value="<?=$bgColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-sm-12">
                <input type="hidden" name="request" value="addWhyAttend" />
                <input type="hidden" name="contentId" id="contentId" value="<?=$content_id?>" />
                <input type="hidden" name="eventId" value="<?=$eventId?>" />
                <button type="submit" id="addWhyAttendButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
            
            <script src="<?=DN?>/js/plugins/switchery/switchery.js"></script>
            <script src="<?=DN?>/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
            <script>
                var elem = document.querySelector('.why-attend-status');
                var switchery = new Switchery(elem, { color: '#1AB394' });
                $('#title_color').colorpicker();
                $('#text_color').colorpicker();
                $('#bg_color').colorpicker();
            </script>
        <?php
        break;

        // whyAttendStatus
        case 'whyAttendStatus':
            $status = Input::get('status');
            $content_id = Input::get('contentId');
            try {
                $controller->update("future_homepage_whyattend", array('status' => $status), $content_id);
                $response['success']  = true;
                $response['messages'] = "Successfully uploaded";   
            } catch(Exception $error) {
                $response['success']  = false;
                $response['messages'] = "Error while updating";
            }
            echo json_encode($response);
        break;
        

    // 5. QUOTE SECTION
        // Add quote content
        case 'addQuote':
            $author = escape(Input::get('author'));
            $quote = escape(Input::get('quote'));
            $style['textColor'] = Input::get('text_color');
            $style['bgColor'] = Input::get('bg_color');
            $general_style = json_encode($style);
            $findContent  = TemplateController::getQuote($eventId);
            if ($findContent) {
                try {
                    $controller->update("future_homepage_quote", array(
                        'author' => $author,
                        'quote' => $quote,
                        'general_style' => $general_style,
                    ), Input::get('contentId'));
                    $response['success']  = true;
                    $response['messages'] = "Successfully updated";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while updating content";
                }
            } else {
                try {
                    $controller->create("future_homepage_quote", array(
                        'event_id' => $eventId,
                        'author' => $author,
                        'quote' => $quote,
                        'general_style' => $general_style,
                        'creation_date' => date("Y-m-d H:i:s")
                    ));
                    $response['success']  = true;
                    $response['messages'] = "Successfully created";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while creating content";
                }
            }
            echo json_encode($response);
        break;

        //Load quote section
        case 'fetchQuote':
            $getContent  = TemplateController::getQuote($eventId);
            if ($getContent) {
                $content_id = $getContent->id;
                $author = $getContent->author;
                $quote = $getContent->quote;
                $status = $getContent->status;
                $general_style = $getContent->general_style;
                $styleArray = json_decode($general_style, true);
                $style = (object)$styleArray;
                $textColor = $style->textColor;
                $bgColor = $style->bgColor;
            } else {
                $content_id = $about_event = $title = "";
                $textColor = '#7A838B';
                $titleColor = "#7A838B";
                $bgColor = "#ffffff";
            }
        ?>
            <h3 class="pull-right">
                <?php if($status == "ACTIVE") { ?>
                Activated <input type="checkbox" class="quote-status" checked />
                <?php } else { ?>
                    Deactivated <input type="checkbox" class="quote-status" />
                <?php } ?>
            </h3>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Quote<small class="red-color">*</small></label>
                    <textarea name="quote" id="quote" class="form-control" placeholder="Enter quote" data-rule="required"
                        data-msg="Please enter quote" style="height: 90px;"><?=$quote?></textarea>
                    <div class="validate"></div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Author</label>
                    <input type="text" name="author" id="author" class="form-control" placeholder="Author" data-msg="Please enter author" value="<?=$author?>" />
                    <div class="validate"></div>
                </div>
            </div>
            <div class="col-md-12">
                <h4>STYLES</h4>
            </div>
            <div class="col-md-6">
                <label>Text color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="text_color" id="text_color" class="form-control" value="<?=$textColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-md-6">
                <label>Background color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="bg_color" id="bg_color" class="form-control" value="<?=$bgColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            
            <div class="col-sm-12">
                <input type="hidden" name="request" value="addQuote" />
                <input type="hidden" name="contentId" id="contentId" value="<?=$content_id?>" />
                <button type="submit" id="addQuoteButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
            <script src="<?=DN?>/js/plugins/switchery/switchery.js"></script>
            <script src="<?=DN?>/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
            <script>
                var elem = document.querySelector('.quote-status');
                var switchery = new Switchery(elem, { color: '#1AB394' });
                $('#text_color').colorpicker();
                $('#bg_color').colorpicker();
            </script>
        <?php
        break;

        // quoteStatus
        case 'quoteStatus':
            $status = Input::get('status');
            $content_id = Input::get('contentId');
            try {
                $controller->update("future_homepage_quote", array('status' => $status), $content_id);
                $response['success']  = true;
                $response['messages'] = "Successfully uploaded";   
            } catch(Exception $error) {
                $response['success']  = false;
                $response['messages'] = "Error while updating";
            }
            echo json_encode($response);
        break;


    // 6. OUTCOME SECTION SECTION
        // Add content
        case 'addOutcome':
            $title = escape(Input::get('title'));
            $outcome['outcome_1'] = escape(Input::get('outcome_1'));
            $outcome['outcome_2'] = escape(Input::get('outcome_2'));
            $outcome['outcome_3'] = escape(Input::get('outcome_3'));
            $outcome['outcome_4'] = escape(Input::get('outcome_4'));
            $outcome['outcome_5'] = escape(Input::get('outcome_5'));
            $outcome['outcome_6'] = escape(Input::get('outcome_6'));
            $outcome['outcome_7'] = escape(Input::get('outcome_7'));
            $outcome['outcome_8'] = escape(Input::get('outcome_8'));
            $outcome['outcome_9'] = escape(Input::get('outcome_9'));
            $outcome['outcome_10'] = escape(Input::get('outcome_10'));
            $outcome['outcome_11'] = escape(Input::get('outcome_11'));
            $outcome['outcome_12'] = escape(Input::get('outcome_12'));
            $outcomes = json_encode($outcome);
            $style['titleColor'] = Input::get('title_color');
            $style['textColor'] = Input::get('text_color');
            $style['bgColor'] = Input::get('bg_color');
            $general_style = json_encode($style);
            $findContent  = TemplateController::getOutcome($eventId);
            if ($findContent) {
                try {
                    $controller->update("future_homepage_outcome", array(
                        'title' => $title,
                        'outcome' => $outcomes,
                        'general_style' => $general_style,
                    ), Input::get('contentId'));
                    $response['success']  = true;
                    $response['messages'] = "Successfully updated";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while updating content";
                }
            } else {
                try {
                    $controller->create("future_homepage_outcome", array(
                        'event_id' => $eventId,
                        'title' => $title,
                        'outcome' => $outcomes,
                        'general_style' => $general_style,
                        'creation_date' => date("Y-m-d H:i:s")
                    ));
                    $response['success']  = true;
                    $response['messages'] = "Successfully created";    
                } catch(Exception $error) {
                    $response['success']  = false;
                    $response['messages'] = "Error while creating content";
                }
            }
            echo json_encode($response);
        break;

        //Load section
        case 'fetchOutcomes':
            $getContent  = TemplateController::getOutcome($eventId);
            if ($getContent) {
                $title = $getContent->title;
                $content_id = $getContent->id;
                $outcomes = $getContent->outcome;
                $outcomeArray = json_decode($outcomes, true);
                $outcome = (object)$outcomeArray;
                $outcome_1 = $outcome->outcome_1;
                $outcome_2 = $outcome->outcome_2;
                $outcome_3 = $outcome->outcome_3;
                $outcome_4 = $outcome->outcome_4;
                $outcome_5 = $outcome->outcome_5;
                $outcome_6 = $outcome->outcome_6;
                $outcome_7 = $outcome->outcome_7;
                $outcome_8 = $outcome->outcome_8;
                $outcome_9 = $outcome->outcome_9;
                $outcome_10 = $outcome->outcome_10;
                $outcome_11 = $outcome->outcome_11;
                $outcome_12 = $outcome->outcome_12;
                $status = $getContent->status;
                $general_style = $getContent->general_style;
                $styleArray = json_decode($general_style, true);
                $style = (object)$styleArray;
                $titleColor = $style->titleColor;
                $textColor = $style->textColor;
                $bgColor = $style->bgColor;
            } else {
                $content_id = $about_event = $title = "";
                $outcome_1 = $outcome_2 = $outcome_3 = $outcome_4 = $outcome_5 = $outcome_6 = $outcome_7 = $outcome_8 = $outcome_9 = $outcome_10 = $outcome_11 = $outcome_12 = "";
                $textColor = '#7A838B';
                $titleColor = "#7A838B";
                $bgColor = "#ffffff";
                $status = "DEACTIVE";
            }
        ?>
            
            <div class="col-md-12">
                <h3 class="pull-right">
                    <?php if($status == "ACTIVE") { ?>
                    Activated <input type="checkbox" class="outcome-status" checked />
                    <?php } else { ?>
                        Deactivated <input type="checkbox" class="outcome-status" />
                    <?php } ?>
                </h3>
            </div>
            <div class="form-group col-md-12">
                <label>Title<small class="red-color">*</small></label>
                <input type="text" name="title" id="title" placeholder="Title" class="form-control" data-rule="required" data-msg="Please enter title" value="<?=$title?>">
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 1</label>
                <textarea name="outcome_1" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_1?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 2</label>
                <textarea name="outcome_2" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_2?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 3</label>
                <textarea name="outcome_3" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_3?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 4</label>
                <textarea name="outcome_4" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_4?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 5</label>
                <textarea name="outcome_5" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_5?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 6</label>
                <textarea name="outcome_6" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_6?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 7</label>
                <textarea name="outcome_7" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_7?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 8</label>
                <textarea name="outcome_8" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_8?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 9</label>
                <textarea name="outcome_9" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_9?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 10</label>
                <textarea name="outcome_10" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_10?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 11</label>
                <textarea name="outcome_11" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_11?></textarea>
                <div class="validate"></div>
            </div>
            <div class="form-group col-md-6">
                <label>Outcome 12</label>
                <textarea name="outcome_12" class="form-control" placeholder="Enter outcome" data-rule="maxlen:150" data-msg="Please only 150 characters"><?=$outcome_12?></textarea>
                <div class="validate"></div>
            </div>
            
            <div class="col-md-4">
                <label>Title color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="title_color" id="title_color" class="form-control" value="<?=$titleColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-md-4">
                <label>Text color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="text_color" id="text_color" class="form-control" value="<?=$textColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-md-4">
                <label>Background color (HEX code) <small class="red-color">*</small></label>
                <div class="form-group control-group">
                    <input type="text" name="bg_color" id="bg_color" class="form-control" value="<?=$bgColor?>" data-rule="required" data-msg="This is required"/>
                    <p class="validate"></p>
                </div>
            </div>
            <div class="col-sm-12">
                <input type="hidden" name="request" value="addOutcome" />
                <input type="hidden" name="contentId" id="contentId" value="<?=$content_id?>" />
                <input type="hidden" name="eventId" value="<?=$eventId?>" />
                <button type="submit" id="addOutcomeButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
            </div>
            
            <script src="<?=DN?>/js/plugins/switchery/switchery.js"></script>
            <script src="<?=DN?>/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
            <script>
                var elem = document.querySelector('.outcome-status');
                var switchery = new Switchery(elem, { color: '#1AB394' });
                $('#title_color').colorpicker();
                $('#text_color').colorpicker();
                $('#bg_color').colorpicker();
            </script>
        <?php
        break;

        // whyAttendStatus
        case 'outcomeStatus':
            $status = Input::get('status');
            $content_id = Input::get('contentId');
            try {
                $controller->update("future_homepage_outcome", array('status' => $status), $content_id);
                $response['success']  = true;
                $response['messages'] = "Successfully uploaded";   
            } catch(Exception $error) {
                $response['success']  = false;
                $response['messages'] = "Error while updating";
            }
            echo json_encode($response);
        break;


    // 7. SPEAKER SECTION
        // Add speaker
        case 'addSpeaker':
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
        break;

        // Edit speaker details
        case 'editSpeaker':
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
        break;

        // Edit speaker picture
        case 'editSpeakerImage':
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
        break;

        // Edit speaker picture
        case 'editSpeakerImage':
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
        break;

        //Load speakers
        case 'fetchSpeakers':
            $getSpeaker  = TemplateController::getSpeaker($eventId);
            $i = 0;
            ?>
            <div class="col-lg-2" style="margin-bottom: 30px;">
                <div class="event-card event-card-add">
                    <div class="event-card-text event-card-speaker">
                        <a data-toggle="modal" data-target="#addSpeakerModal" id="addClient" style="margin-top: 50px;"><i
                                class="fa fa-plus"></i> Add speaker</a>
                    </div>
                </div>
            </div>
            <?php
            if (!$getSpeaker) {
                // Danger("No speaker recorded");
            } else {
                foreach($getSpeaker as $speaker) {
                    $i++;
                    $imageUrl = "data_system/img/speakers/".$speaker->picture;
            ?>
            <div style="display: none;">
                <span id="eName<?=$speaker->id?>"><?=$speaker->name?></span>
                <span id="eOrga<?=$speaker->id?>"><?=$speaker->organisation?></span>
                <span id="eJob<?=$speaker->id?>"><?=$speaker->job_title?></span>
            </div>
            <div class="col-lg-2" style="margin-bottom: 30px;">
                <div class="event-card">
                    <img src="<?php linkto($imageUrl); ?>" class="img img-responsive">
                    <div class="event-card-text event-card-speaker">
                        <a href="#" class="btn btn-white btn-sm pull-right dropdown-toggle edit_speaker"
                            data-id="<?=$speaker->id?>"><i class="fa fa-pencil"></i></a>
                        <h4><?=$speaker->name?></h4>
                        <small class="block text-muted"><?=$speaker->organisation ." ".$speaker->job_title?></small>
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
        break;


        //Load partners
        case 'fetchPartners':
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
                        <a href="#" class="btn btn-white btn-sm pull-right dropdown-toggle edit_partner" data-id="<?php echo $resPartner->id;?>"><i class="fa fa-pencil"></i></a>
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
        break;

    endswitch;
endif;      
?>