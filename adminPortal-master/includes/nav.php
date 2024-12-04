<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element" style="padding-bottom: 30px;">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span
                            style="width:30px; height:30px; border-radius:100%; color:#fff; line-height:30px; text-align:center; background:#155651; float: left;">
                            <?php echo strtoupper(substr($user->data()->username, 0, 1)); ?>
                        </span>
                        <span class="m-t-xs pull-right" style="margin-right: 70px; margin-top: 7px;">
                            <strong class="font-bold"><?php echo escape($user->data()->firstname); ?></strong>
                        </span>
                    </a>
                </div>
            </li>
            <?php if (!$user->hasPermission('guest') AND !$user->hasPermission('group-admin') AND !$user->hasPermission('accreditation')) { ?>

            <li class="<?=$page == "home"?"active":""?>">
                <a href="<?php linkto(''); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard
                    </span></a>
            </li>
            
            <!-- SUPER ADMIN -->
            <?php if ($user->hasPermission('admin')) { ?>
            <li class="landing_link">
                <a><i class="fa fa-th"></i> <span class="nav-label">Accounts</span></a>
            </li>
            <li class="<?=$link == "admin"?"active":""?>">
                <a href="<?=DN?>/accounts/admin"><i class="fa fa-users"></i> Users</a>
            </li>
            <li class="<?=$link == "clients_list"?"active":""?>">
                <a href="<?=DN?>/accounts/client"><i class="fa fa-users"></i> Clients</a>
            </li>
            <?php } ?>

            <li class="<?=$page == "events"?"active":""?>">
                <a href="<?=DN?>/events/list"><i class="fa fa-calendar"></i> Events</a>
            </li>

            <!-- ADMIN AND CLIENTS -->

            <li class="landing_link">
                <a><i class="fa fa-th"></i> <span class="nav-label">Registration</span></a>
            </li>
            <li class="<?=$page == "participants"?"active":""?>">
                <a href="<?=DN?>/participants/list"><i class="fa fa-list"></i> Participants
                </a>
            </li>
            <li class="<?=$page == "payments"?"active":""?>">
                <a href="<?=DN?>/payments/list"><i class="fa fa-money"></i> Payments </a></li>
            </li>
            <?php if ($user->hasPermission('admin')) { ?>
            <li class="<?=$page == "pass"?"active":""?>">
                <a href="#"><i class="fa fa-id-badge"></i> <span class="nav-label">Pass Types</span><span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?=$link == "type"?"active":""?>">
                        <a href="<?=DN?>/pass/type"><i class="fa fa-circle-o"></i> List Type </a>
                    </li>
                    <li class="<?=$link == "subtype"?"active":""?>">
                        <a href="<?=DN?>/pass/subtype"><i class="fa fa-circle-o"></i> List Sub Type </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <li class="<?=$page == 'link'?'active':''?>">
                <a href="<?=DN?>/link/list"><i class="fa fa-paper-plane"></i>Registration
                    Invites</a>
            </li>

            <li class="<?=$page == 'promo'?'active':''?>">
                <a href="<?=DN?>/promo/list"><i class="fa fa-bullhorn"></i>Promo Code</a>
            </li>

            <li class="<?=$page == "groups"?"active":""?>">
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Groups</span><span
                        class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?=$link == "new_group"?"active":""?>"><a href="<?=DN?>/group/new_group"><i class="fa fa-plus"></i> New Group</a></li>
                    <li class="<?=$link == "group_list"?"active":""?>"><a href="<?=DN?>/group/list"><i class="fa fa-list"></i> Group List</a></li>
                    <li class="<?=$link == "group_delegates"?"active":""?>"><a href="<?=DN?>/group/delegates"><i class="fa fa-users"></i> Group Delegates</a></li>
                </ul>
            </li>

            <li class="<?=$page == 'form'?'active':''?>">
                <a href="<?=DN?>/form/list"><i class="fa fa-pencil"></i>Forms</a>
            </li>

            <li class="landing_link">
                <a><i class="fa fa-th"></i> <span class="nav-label">Accreditation</span></a>
            </li>
            <li class="<?=$link == "accreditation"?"active":""?>">
                <a href="<?=DN?>/accreditation/list"><i class="fa fa-id-badge"></i> Accreditation</a>
            </li>
            <li class="<?=$link == "scan"?"active":""?>">
                <a href="<?=DN?>/accreditation/scan"><i class="fa fa-id-badge"></i> Scan badge</a>
            </li>
            <li class="<?=$link == "attendance"?"active":""?>">
                <a href="<?=DN?>/attendance/list"><i class="fa fa-pencil"></i> Attendance</a>
            </li>

            <?php if ($user->hasPermission('admin')) { ?>
            <li class="landing_link">
                <a><i class="fa fa-th"></i> <span class="nav-label">Website Content</span></a>
            </li>
            <li class="<?=$page == "content"?"active":""?>">
                <a href="#"><i class="fa fa-list"></i> <span class="nav-label">Website Content</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?=$link == "logo"?"active":""?>"><a href="<?=DN?>/content/logo"><i class="fa fa-circle-o"></i> Event Logo</a></li>
                    <li class="<?=$link == "banner"?"active":""?>"><a href="<?=DN?>/content/banner"><i class="fa fa-circle-o"></i> Event Banner</a></li>
                    <li class="<?=$link == "countdown"?"active":""?>"><a href="<?=DN?>/content/countdown"><i class="fa fa-circle-o"></i> Countdown</a></li>
                    <li class="<?=$link == "about"?"active":""?>"><a href="<?=DN?>/content/about"><i class="fa fa-circle-o"></i> About Section</a></li>
                    <li class="<?=$link == "whyattend"?"active":""?>"><a href="<?=DN?>/content/why_attend"><i class="fa fa-circle-o"></i> Why Attend Section</a></li>
                    <li class="<?=$link == "quote"?"active":""?>"><a href="<?=DN?>/content/quote"><i class="fa fa-circle-o"></i> Quote Section</a></li>
                    <li class="<?=$link == "outcome"?"active":""?>"><a href="<?=DN?>/content/outcomes"><i class="fa fa-circle-o"></i> Outcomes Section</a></li>
                    <li class="<?=$link == "speakers"?"active":""?>"><a href="<?=DN?>/content/speakers"><i class="fa fa-circle-o"></i> Speakers</a></li>
                    <li class="<?=$link == "partners"?"active":""?>"><a href="<?=DN?>/content/partners"><i class="fa fa-circle-o"></i> Partners / Sponsors</a></li>
                    <li class="<?=$link == "program"?"active":""?>">
                        <a href="#"><i class="fa fa-circle-o"></i> Program <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <?php
                            $eventId  = Hash::decryptToken($encodedEventId);
                            $getDays  = DB::getInstance()->get('future_event', array('id', '=', $eventId));
                            $date1    = dateFormat($getDays->first()->start_date);
                            $date2    = dateFormat($getDays->first()->end_date);
                            $dateDiff = dateDiff($date1, $date2);
                            for ($i = 0; $i <= $dateDiff; $i++) {
                                $count = $i + 1;
                                $day = "Day" . $count;
                            ?>
                                <li class="<?=$sublink == $day ? "active":""?>">
                                    <a href="<?=DN?>/content/program/<?=$day?>"><i class="fa fa-calendar"></i> <?php echo $day = "Day " . $count; ?> </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="<?=$link == "exhibitors"?"active":""?>"><a href="<?=DN?>/exhibitors/list"><i class="fa fa-circle-o"></i> Exhibitors</a></li>
                    <li class="<?=$link == "partner_with_us"?"active":""?>"><a href="<?=DN?>/content/partnership"><i class="fa fa-circle-o"></i> Partner with Us</a></li>
                </ul>
            </li>
            <?php } ?>

            <?php } ?>

            <!-- GROUP ADMIN -->
            <?php if ($user->hasPermission('group-admin')) { ?>
            <li class="landing_link">
                <a><i class="fa fa-th"></i> <span class="nav-label">Registration</span></a>
            </li>
            <li class="<?=$page == "home"?"active":""?>">
                <a href="<?=DN?>/group/dashboard"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard </span></a>
            </li>
            <li class="<?=$page == "group_register_delegates"?"active":""?>">
                <a href="<?=DN?>/group/register"><i class="fa fa-user-plus"></i> <span class="nav-label">Add new member </span></a>
            </li>
            <?php } ?>

            <?php if ($user->hasPermission('accreditation')) { ?>

            <li class="landing_link">
                <a><i class="fa fa-th"></i> <span class="nav-label">Accreditation</span></a>
            </li>
            <li class="<?=$link == "accreditation"?"active":""?>">
                <a href="<?=DN?>/accreditation/list"><i class="fa fa-id-badge"></i> Accreditation</a>
            </li>
            <li class="<?=$link == "scan"?"active":""?>">
                <a href="<?=DN?>/accreditation/scan"><i class="fa fa-id-badge"></i> Scan badge</a>
            </li>

            <?php } ?>

            <?php if ($user->hasPermission('admin') || $user->hasPermission('event-admin')) { ?>
            <li class="landing_link">
                <a><i class="fa fa-th"></i> <span class="nav-label">Hotel Booking</span></a>
            </li>
            <li class="<?=$link == "booking"?"active":""?>">
                <a href="<?=DN?>/hotel/booking"><i class="fa fa-list"></i> Bookings</a>
            </li>
            <li class="<?=$link == "hotel"?"active":""?>">
                <a href="<?=DN?>/hotel/list"><i class="fa fa-th-large"></i> Hotels</a>
            </li>
            <li class="<?=$link == "room"?"active":""?>">
                <a href="<?=DN?>/hotel/room"><i class="fa fa-th-large"></i> Rooms</a>
            </li>
            <?php } ?>

            <!-- ALL -->
            <li class="landing_link">
                <a><i class="fa fa-th"></i> <span class="nav-label">Settings</span></a>
            </li>
            <li class="<?=$link == "password"?"active":""?>">
                <a href="<?=DN?>/accounts/changepassword"><i class="fa fa-cog"></i> Change password</a>
            </li>
        </ul>

    </div>
</nav>

<div id="page-wrapper" class="gray-bg">
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message" style="color: orange;"><strong><?=$eventName?></strong></span>
                </li>
                <li><a href="<?php linkto('logout'); ?>" style="color: #000a32;"><i class="fa fa-sign-out"></i> Log
                        out</a></li>
            </ul>
            <img src="<?php linkto('data_system/img/torus.png'); ?>" class="img-responsive" alt="logo"
                style="height: 60px;">
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