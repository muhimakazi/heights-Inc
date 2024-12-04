<?php
require_once 'core/init.php';

if ($user->isLoggedIn()) {
    Redirect::to('dashboard');
}
?>

<!DOCTYPE html>
<html>

<head>
    <?php include $INC_DIR . "head-login.php"; ?>
</head>

<body class="gray-bg login-page">
    <div class="ms-content-wrapper ms-auth">
        <div class="ms-auth-container">
            <!-- <div class="ms-auth-col hidden-xs">
                <div class="ms-auth-bg"></div>
            </div> -->
            <div class="row ms-auth-col">
                <!-- <div class="col-lg-4"></div> -->
                <div class="middle-box loginscreen ms-auth-form">
                    <form class="m-t animated fadeInDown" id="confirmForm" role="form" method="post" autocomplete="off">
                        <div class="login-header text-center">
                            <img src="data_system/img/logo-white.png" class="form-logo" alt="logo">
                            <!-- <hr>
                            <h3>Sign in to your account</h3> -->
                        </div>
                        <div style="margin: 10px; padding-bottom: 1px;">
                            <div class="succes-div" id="success-div" hidden></div>
                            <div class="failed-div" id="failed-div" hidden></div>
                        </div>
                        <div class="login-body" id="hideForm">
                            <div class="text-center">
                                <p>Please enter your email address. We will send you an email to reset your password.</p>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span><input type="text" class="form-control" name="username" id="username" placeholder="Email" data-rule="email" data-msg="Please enter your email"/>
                                </div>
                                <div class="validate"></div>
                            </div>
                            <input type="hidden" name="request" value="confirmEmail" />
                            <button type="submit" class="btn btn-lg btn-primary block full-width m-b" id="confirmButton" data-loading-text="Loading..." autocomplete="off">SEND EMAIL</button>

                            <div class="form-group">
                                <label class="d-block"><a href="<?php linkto('login');?>" class="btn-link forgot-pass" style="color: #007bff; font-family: Lato-Regular;"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back to Login</a></label>
                            </div>
                        </div>
                    </form>
                    <p class="m-t text-center">
                        <small>Copyright &copy; <?php echo date("Y"); ?> <strong>Cube Digital</strong>, all rights reserved</small>
                    </p>
                </div>
                <!-- <div class="col-lg-4"></div> -->
            </div>
        </div>
    </div>
    <!-- Mainly scripts -->
    <?php include $INC_DIR . "footer-login.php"; ?>

</body>

</html>