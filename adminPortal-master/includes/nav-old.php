<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element" style="padding-bottom: 30px;">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span style="width:30px; height:30px; border-radius:100%; color:#fff; line-height:30px; text-align:center; background:#155651; float: left;">
                            <?php echo strtoupper(substr($user->data()->username, 0, 1)); ?>
                        </span>
                        <span class="m-t-xs pull-right" style="margin-right: 70px; margin-top: 7px;">
                            <strong class="font-bold"><?php echo escape($user->data()->firstname); ?></strong>
                        </span>
                    </a>
                </div>
            </li>
            <?php if ($user->hasPermission('admin')) { ?>
                <li class="<?php echo ($page == "home" ? "active" : "") ?>">
                    <a href="<?php linkto(''); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard </span></a>
                </li>
                <li class="<?php echo ($page == "events" ? "active" : "") ?>">
                    <a href="#"><i class="fa fa-calendar"></i> <span class="nav-label">Events</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li class="<?php echo ($link == "new" ? "active" : "") ?>"><a href="<?php linkto("pages/events/new_event"); ?>"><i class="fa fa-plus-circle"></i> Add Event</a></li>
                        <li class="<?php echo ($link == "list" ? "active" : "") ?>"><a href="<?php linkto("pages/events/events_list"); ?>"><i class="fa fa-list"></i> Events List</a></li>
                    </ul>
                </li>
                <li class="<?php echo ($page == "accounts" ? "active" : "") ?>">
                    <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Accounts</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li class="<?php echo ($link == "admin" ? "active" : "") ?>"><a href="<?php linkto("pages/accounts/admin"); ?>"><i class="fa fa-user"></i> Admin</a></li>
                        <li class="<?php echo ($link == "clients_list" ? "active" : "") ?>"><a href="<?php linkto("pages/accounts/clients"); ?>"><i class="fa fa-list"></i> Clients List</a></li>
                        <li class="<?php echo ($link == "new_client" ? "active" : "") ?>"><a href="<?php linkto("pages/accounts/new_client"); ?>"><i class="fa fa-plus-circle"></i> Add Client</a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if (!$user->hasPermission('admin')) { ?>
                <li class="<?php echo ($link == "list" ? "active" : "") ?>">
                    <a href="<?php linkto("pages/events/events_list"); ?>"><i class="fa fa-list"></i> Events List</a>
                </li>

            <?php } ?>
            <?php
            if ($encodedEventId != "") {
            ?>  <li class="landing_link">
                    <a><i class="fa fa-th"></i> <span class="nav-label">REGISTRATION</span></a>
                </li>
                <li class="<?php echo ($page == "attedence" ? "active" : "") ?>">
                    <a href="#"><i class="fa fa-list"></i> <span class="nav-label">Participation Types</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li class="<?php echo ($link == "attedence_type" ? "active" : "") ?>">
                            <a href="<?php linkto("pages/content/attedence/type/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> List Type </a>
                        </li>
                        <li class="<?php echo ($link == "attedence_subtype" ? "active" : "") ?>">
                            <a href="<?php linkto("pages/content/attedence/subtype/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> List Sub Type </a>
                        </li>

                    </ul>
                </li>

                <li class="<?php echo ($link == "link" ? "active" : "") ?>">
                    <a href="<?php linkto("pages/content/link/$encodedEventId"); ?>"><i class="fa fa-user-plus"></i>Registration Invites</a>
                </li>

                <li class="<?php echo ($page == "promo" ? "active" : "") ?>">
                    <a href="<?php linkto("pages/promo/list/$encodedEventId"); ?>"><i class="fa fa-bullhorn"></i>Promo Code</a>
                </li>

                <li class="<?php echo ($page == "groups" ? "active" : "") ?>">
                    <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Groups</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li class="<?php echo ($link == "group_list" ? "active" : "") ?>"><a href="<?php linkto("pages/group/list/$encodedEventId"); ?>"><i class="fa fa-list"></i> Group List</a></li>
                        <li class="<?php echo ($link == "group_delegates" ? "active" : "") ?>"><a href="<?php linkto("pages/group/delegates/$encodedEventId"); ?>"><i class="fa fa-users"></i> Group Delegates</a></li>
                    </ul>
                </li>
                <li class="<?php echo ($page == "content" ? "active" : "") ?>" style="display: none;">
                    <a href="#"><i class="fa fa-list"></i> <span class="nav-label">Website Content</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li class="<?php echo ($link == "link" ? "active" : "") ?>"><a href="<?php linkto("pages/content/link/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i>Registration Link</a></li>
                        <li class="">
                            <a href="#"><i class="fa fa-circle-o"></i> Participation <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li class="<?php echo ($link == "attedence_type" ? "active" : "") ?>">
                                    <a href="<?php linkto("pages/content/attedence/type/$encodedEventId"); ?>"><i class="fa fa-circle"></i> List Type </a>
                                </li>
                                <li class="<?php echo ($link == "attedence_subtype" ? "active" : "") ?>">
                                    <a href="<?php linkto("pages/content/attedence/subtype/$encodedEventId"); ?>"><i class="fa fa-circle"></i> List Sub Type </a>
                                </li>

                            </ul>
                        </li>
                        <li class="<?php echo ($link == "banner" ? "active" : "") ?>"><a href="<?php linkto("pages/content/banner/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Event Banner</a></li>
                        <li class="<?php echo ($link == "countdown" ? "active" : "") ?>"><a href="<?php linkto("pages/content/countdown/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Countdown</a></li>
                        <li class="<?php echo ($link == "about" ? "active" : "") ?>"><a href="<?php linkto("pages/content/about/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> About Section</a></li>
                        <li class="<?php echo ($link == "whyattend" ? "active" : "") ?>"><a href="<?php linkto("pages/content/why_attend/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Why Attend Section</a></li>
                        <li class="<?php echo ($link == "quote" ? "active" : "") ?>"><a href="<?php linkto("pages/content/quote/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Quote Section</a></li>
                        <li class="<?php echo ($link == "outcome" ? "active" : "") ?>"><a href="<?php linkto("pages/content/outcomes/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Outcomes Section</a></li>
                        <li class="<?php echo ($link == "speakers" ? "active" : "") ?>"><a href="<?php linkto("pages/content/speakers/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Featured Speakers</a></li>
                        <li class="<?php echo ($link == "partners" ? "active" : "") ?>"><a href="<?php linkto("pages/content/partners/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Partners / Sponsors</a></li>
                        <li class="<?php echo ($link == "program" ? "active" : "") ?>">
                            <a href="#"><i class="fa fa-circle-o"></i> Program <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <?php
                                $eventId  = Hash::decryptToken(Input::get('eventId'));
                                $getDays  = DB::getInstance()->get('future_event', array('id', '=', $eventId));
                                $date1    = dateFormat($getDays->first()->start_date);
                                $date2    = dateFormat($getDays->first()->end_date);
                                $dateDiff = dateDiff($date1, $date2);
                                for ($i = 0; $i <= $dateDiff; $i++) {
                                    $count = $i + 1;
                                    $day = "Day" . $count;
                                ?>
                                    <li class="<?php echo ($sublink == $day ? "active" : "") ?>">
                                        <a href="<?php linkto("pages/content/program/$encodedEventId/$day"); ?>"><i class="fa fa-calendar"></i> <?php echo $day = "Day " . $count; ?> </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="<?php echo ($link == "exhibitors" ? "active" : "") ?>"><a href="<?php linkto("pages/exhibitors/list/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Exhibitors</a></li>
                        <li class="<?php echo ($link == "partner_with_us" ? "active" : "") ?>"><a href="<?php linkto("pages/content/partnership/$encodedEventId"); ?>"><i class="fa fa-circle-o"></i> Partner with Us</a></li>
                    </ul>
                </li>
                <li class="<?php echo ($page == "participants" ? "active" : "") ?>">
                    <a href="<?php linkto("pages/participants/all/$encodedEventId"); ?>"><i class="fa fa-list"></i> Participants </a></li>
                </li>
                <li class="<?php echo ($page == "payments" ? "active" : "") ?>">
                    <a href="<?php linkto("pages/payments/all/$encodedEventId"); ?>"><i class="fa fa-list"></i> Payments </a></li>
                </li>
                <!-- <li class="<?php echo ($link == "report" ? "active" : "") ?>">
                    <a href="<?php linkto("pages/reports/all/$encodedEventId"); ?>"><i class="fa fa-list"></i> Reports</a>
                </li>
                <li class="<?php echo ($link == "accreditation" ? "active" : "") ?>">
                    <a href="<?php linkto("accreditation"); ?>"><i class="fa fa-list"></i> Accreditation</a>
                </li>
                <li class="<?php echo ($link == "accreditation" ? "active" : "") ?>">
                    <a href="<?php linkto("pages/attendance/all/$encodedEventId"); ?>"><i class="fa fa-list"></i> Attendance</a>
                </li> -->
            <?php } ?>
            <li class="<?php echo ($link == "password" ? "active" : "") ?>">
                <a href="<?php linkto("pages/accounts/changepassword"); ?>"><i class="fa fa-cog"></i> Change password</a>
            </li>
        </ul>

    </div>
</nav>

<div id="page-wrapper" class="gray-bg">
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header visible-xs">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li><a href="<?php linkto('logout'); ?>" style="color: #000a32;"><i class="fa fa-sign-out"></i> Log out</a></li>
            </ul>
            <img src="<?php linkto('data_system/img/torus.png'); ?>" class="img-responsive" alt="logo" style="height: 80px;">
            <span class="col-md-4"></span>
            <span class="col-md-4" style="text-align: center;">
                <?php if ($errmsg != '') : ?>
                    <?php echo Danger($errmsg); ?>
                <?php endif; ?>
                <?php if ($succmsg != '') : ?>
                    <?php echo Success($succmsg); ?>
                <?php endif; ?>
            </span>
            <span class="col-md-4"></span>
        </nav>
    </div>