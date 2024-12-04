<?php
require_once 'core/init.php';

if ($user->isLoggedIn()) {
    Redirect::to('dashboard');
}
if(empty(Input::get('user_token')) && empty(Input::get('user_code'))) {
    Redirect::to('404');
}
if(Input::get('user_token') && Input::get('user_code')) {
    $user_token = Input::get('user_token');
    $user_code = Input::get('user_code');
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
                    <form class="m-t animated fadeInDown" id="resetForm" role="form" method="post" autocomplete="off">
                        <div class="login-header text-center">
                            <img src="<?=DN?>/data_system/img/logo-white.png" class="form-logo" alt="logo">
                            <!-- <hr>
                            <h3>Sign in to your account</h3> -->
                        </div>
                        <div style="margin: 10px;">
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
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span><input type="password" class="form-control" name="password" id="password" placeholder="New password" data-rule="minlen:6" data-msg="Please enter at least 6 chars"/>
                                </div>
                                <div class="validate"></div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span><input type="password" class="form-control" name="password_again" id="password_again" placeholder="Confirm new password" data-rule="matches" data-msg="Password doesn't match"/>
                                </div>
                                <div class="validate"></div>
                            </div>
                            <input type="hidden" name="request" value="resetPassword" />
                            <input type="hidden" name="user_token" value="<?=$user_token?>" />
                            <input type="hidden" name="user_code" value="<?=$user_code?>" /> 
                            <button type="submit" class="btn btn-lg btn-primary block full-width m-b" id="resetButton">RESET NOW</button>
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