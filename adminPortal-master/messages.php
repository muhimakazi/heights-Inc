<?php
    require_once 'core/init.php'; 
    if(!$user->isLoggedIn()) {
        Redirect::to('admin/login');
    }

    // $page = $link = "users";

    $controller = new Controller();

    if(isset($_POST['sendMessage'])) {
        $controller->update('contact', array('status' => "sent"), Input::get('id'));
        $message = '                    
        <html>
            <body style="font-family: helvetica;">
                <section style="background: #e7e9ea; padding: 0 100px;">
                    <center>
                        <article style="border-top: 3px solid #025652; border-radius: 0px; background: #ffffff;">
                            <div style="background: #025652; padding: 10px 0; height: auto; overflow: auto; color: #ffffff;">
                                <img src="https://agrf.org/img/logo_event.png" class="img img-responsive" style="float: left; margin-left: 10px; height: 90px;">
                                <h1 style="font-weight: 400; text-transform: uppercase;">Contact details</h1>
                            </div>
                            <div style="padding: 20px 0;">
                                <table rules="all" style="border-color: #666;" cellpadding="10">
                                    <tr><td><strong>First name:</strong> </td><td>' . Input::get('firstname') . '</td></tr>
                                    <tr><td><strong>Last name:</strong> </td><td>' . Input::get('lastname') . '</td></tr>
                                    <tr><td><strong>Telephone:</strong> </td><td>' . Input::get('telephone') . '</td></tr>
                                    <tr><td><strong>Email:</strong> </td><td>' . Input::get('email') . '</td></tr>
                                    <tr><td><strong>Organization name:</strong> </td><td>' . Input::get('organisation-name') . '</td></tr>
                                    <tr><td><strong>Job title:</strong> </td><td>' . Input::get('job-title') . '</td></tr>
                                    <tr><td><strong>Country:</strong> </td><td>' . Input::get('country') . '</td></tr>
                                    <tr><td><strong>Product:</strong> </td><td>' . Input::get('product') . '</td></tr>
                                </table>
                            </div>
                        </article>
                    </center>
                </section>
            </body>
        </html>';
        
        $email = "secretariat@agrf.org";
        // $email = "mikindip@gmail.com";        
        $subject = "Contact Us";          
        $user->send_mail($email,$message,$subject);
        
        $succmsg .= "Successful";

    }

?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head.php"; ?>
</head>

<body>
    <div id="wrapper">

        <?php include $INC_DIR . "nav.php"; ?>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <!-- <button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add User</button> -->
                            <?php if ($errmsg != ''): ?><p style="color: red; text-align: center;"><?php echo $errmsg; ?></p><?php endif; ?>
                            <h5>Contact Us</h5>
                        </div>
                        <div class="ibox-content">
                            <?php 
                            $controller->get('contact', '*', NULL, "", 'id DESC');
                            $i = 0;
                            if (!$controller->count()) {
                                Danger("No user recorded");
                            } else {
                            ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Names</th>
                                        <th>Telephone</th>
                                        <th>Email</th>
                                        <th>Organization</th>
                                        <th>Country</th>
                                        <th>Product</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach($controller->data() as $resUser){
                                        $i++;
                                    ?>
                                    <tr class="gradeX" style="background: #f8f8f8; border-bottom: 2px solid #fff;">
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $resUser->firstname . " " . $resUser->lastname; ?></td>
                                        <td><?php echo $resUser->telephone; ?></td>
                                        <td><?php echo $resUser->email; ?></td>
                                        <td><?php echo $resUser->org_name; ?></td>
                                        <td><?php echo $resUser->country; ?></td>
                                        <td><?php echo $resUser->product; ?></td>
                                        <!-- <td>
                                            <?php
                                            //if($resUser->status != "sent"){?>
                                            <form action="" method="post">
                                                <input type="hidden" name="id" value="<?=$resUser->id?>">
                                                <input type="hidden" name="firstname" value="<?=$resUser->firstname?>">
                                                <input type="hidden" name="lastname" value="<?=$resUser->lastname?>">
                                                <input type="hidden" name="telephone" value="<?=$resUser->telephone?>">
                                                <input type="hidden" name="email" value="<?=$resUser->email?>">
                                                <input type="hidden" name="organisation-name" value="<?=$resUser->org_name?>">
                                                <input type="hidden" name="job-title" value="<?=$resUser->job_title?>">
                                                <input type="hidden" name="country" value="<?=$resUser->country?>">
                                                <input type="hidden" name="product" value="<?=$resUser->product?>">
                                                <button class="btn btn-primary btn-xs" type="submit" name="sendMessage">Send message</button>
                                            </form>
                                            <?php //} else { ?>
                                                <button class="btn btn-success btn-xs">Message sent</button>
                                            <?php //} ?>
                                        </td> -->
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include $INC_DIR . "footer.php"; ?>

        </div>
        </div>
</body>

</html>
