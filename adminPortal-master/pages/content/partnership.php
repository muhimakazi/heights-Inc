<?php
    require_once "../../core/init.php"; 
    if(!$user->isLoggedIn()) {
        Redirect::to('login');
    }

    $page = "content";
    $link = "partner_with_us";

?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
    <script src="<?php linkto('js/jquery-2.1.1.js'); ?>"></script>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Participants</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php linkto(''); ?>">Home</a></li>
                    <li><a href="<?php linkto('pages/events/events_list'); ?>">Events</a></li>
                    <li><a>Website content</a></li>
                    <li class="active"><strong>Partner with us</strong></li>
                </ol>
            </div>
            <div class="col-lg-2"></div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <!-- <div class="col-lg-2"></div> -->
                <div class="col-lg-12">
                    <div class="panel blank-panel nav-tabs-custom">
                        <div class="panel-heading">
                            <div class="panel-options nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#tab-1">Partnership</a></li>
                                    <li class=""><a data-toggle="tab" href="#tab-2">Why Partner With Us</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane active">
                                    <div class="ibox-content" id="partnership-table"></div>
                                </div>
                                <div id="tab-2" class="tab-pane">
                                    <strong>Donec quam felis</strong>
                                    <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>
                                    <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-2"></div> -->
            </div>
        </div>

        <script type="text/javascript">
            var eventId = '<?php echo $eventId; ?>';
            var linkto  = '<?php linkto("pages/content/content_action.php"); ?>';
        </script>
        <script src="<?php linkto('pages/content/partnership.js'); ?>"></script>
        
        <?php include $INC_DIR . "footer.php"; ?>

        </div>
        </div>
</body>

</html>
