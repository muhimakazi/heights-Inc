<?php
require_once "../../core/init.php"; 

if(!$user->isLoggedIn()) {
    Redirect::to('login');
}
$link = "password";
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
                <h2>Settings</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php linkto(''); ?>">Home</a>
                    </li>
                    <li>
                        <a>Settings</a>
                    </li>
                    <li class="active">
                        <strong>Change password</strong>
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
                        <div class="ibox-title" style="height: auto;">
                            <!-- <h5>Update Password</h5> -->
                        </div>
                        <div class="ibox-content" style="overflow: auto;">
                            <form action="<?php linkto("pages/accounts/accounts_action.php"); ?>" method="post" id="passwordForm" class="formCustom">
                                <div id="change-password-messages"></div>
                                <div class="form-group">
                                    <label>Old password</label>
                                    <input type="password" class="form-control" name="old_password" id="old_password" data-rule="required" data-msg="Please enter your old password"/>
                                    <div class="validate"></div>
                                </div>
                                <div class="form-group">
                                    <label>New password</label>
                                    <input type="password" class="form-control" name="password" id="password" data-rule="minlen:6" data-msg="Please enter at least 6 chars"/>
                                    <div class="validate"></div>
                                </div>
                                <div class="form-group">
                                    <label>Confirm password</label>
                                    <input type="password" name="password_again" id="password_again" class="form-control" data-rule="matches" data-msg="Password doesn't match"/>
                                    <div class="validate"></div>
                                </div>
                                <div>
                                    <input type="hidden" name="request" value="changePassword" /> 
                                    <button type="submit" id="passwordButton" class="btn btn-primary pull-right" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-check-circle"></i> Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>
        
        <?php include $INC_DIR . "footer.php"; ?>

        <script type="text/javascript">
            var linkto = '<?php linkto("pages/accounts/accounts_action.php"); ?>';
        </script>

        <script src="<?php linkto('pages/accounts/login.js'); ?>"></script>

        </div>
        </div>
</body>

</html>
