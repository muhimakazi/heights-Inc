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
                    <form class="m-t animated fadeInDown" id="loginForm" role="form" method="post" autocomplete="off">
                        <div class="login-header text-center">
                            <img src="data_system/img/logo-white.png" class="form-logo" alt="logo">
                            <!-- <hr>
                            <h3>Sign in to your account</h3> -->
                        </div>
                        <div style="margin: 10px;">
                            <h3 class="mb-2">Sign In</h3>
                            <div class="succes-div" id="success-div" hidden></div>
                            <div class="failed-div" id="failed-div" hidden></div>
                            <div class="wrong mt-1" id="log-div" hidden>
                                <span class="fo-login"><i class="fa fa-info-circle"></i> Authenticating</span>
                                <div class="dot">
                                    <span class="l-1"></span>
                                    <span class="l-2"></span>
                                    <span class="l-3"></span>
                                </div>
                            </div>
                        </div>
                        <div class="login-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span><input type="text" class="form-control" name="username" id="username" placeholder="Email" data-rule="email" data-msg="Please enter your email"/>
                                </div>
                                <div class="validate"></div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Password" data-rule="required" data-msg="Please enter your password"/>
                                    <span class="input-group-addon eye_right"><i toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></i></span>
                                </div>
                                <div class="validate"></div>
                            </div>
                            <!-- <div class="row" style="margin-bottom: 10px;">
                                <div class="col-xs-6">
                                    <img src="<?= linkto('get_captcha.php?rand=' . rand()) ?>" id='captcha' class="img" style="width: 70%;">
                                    <a href="javascript:void(0)" id="reloadCaptcha" title="Refresh" style="color: #0e3635; font-size: 18px; margin-left: 10px;"><i class="fa fa-refresh"></i></a>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <input type="text" id="securityCode" placeholder="Type the captcha" name="securityCode" class="form-control" data-rule="required" data-msg="Enter security code"/>
                                        <div class="validate"></div>
                                        <small><span id="security_error" style="color: red;"></span></small>
                                        <small><span id="securityCode_error" style="color: red;"></span></small>
                                    </div>
                                </div>
                            </div> -->
                            <input type="hidden" name="request" value="login" />
                            <button type="submit" class="btn btn-lg btn-primary block full-width m-b" id="loginButton" data-loading-text="Loading..." autocomplete="off">Sign In <i class="fa fa-sign-in"></i></button>

                            <div class="form-group">
                                <label class="d-block pull-right"><a href="<?php linkto('confirmemail'); ?>" class="btn-link forgot-pass" style="color: #007bff; font-family: Lato-Regular;">Forgot Password?</a></label>
                                <label class="ms-checkbox-wrap" style="font-family: Lato-Regular;"> Remember Me
                                    <input type="checkbox">
                                    <span class="ms-checkbox-check"></span>
                                </label>
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