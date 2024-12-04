<?php
    require_once "../../core/init.php"; 
    
    $valid['success'] = array('success' => false, 'messages' => array());

    if(Input::checkInput('request', 'post', 1)):
        $_post_request_ = Input::get('request', 'post');
        switch($_post_request_):

            /** Get captcha session */ 
            case 'captchaSession':
                $valid['success']  = true;
                $valid['messages'] = $_SESSION['captcha'];
                echo json_encode($valid);
            break;

            /** Action - User Login */
            case 'login':
                $_form_ = AuthenticationController::userLogin();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $user = new User();
                    if ($user->hasPermission('admin')) {
                        $valid['access'] = "ADMIN";
                    } else {
                        $valid['access'] = "USER";
                    }
                    $valid['messages'] = "Login Successfully";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Incorrect username or password";
                endif;
                echo json_encode($valid);
            break;

            /** Action - Confirm Email */
            case 'confirmEmail':
                $_form_ = AuthenticationController::confirmEmail();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "We've just sent you an email to reset your password.";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = "Sorry! this account is not found";
                endif;
                echo json_encode($valid);
            break;

            /** Action - Reset Password */
            case 'resetPassword':
                $_form_ = AuthenticationController::resetPassword();
                if($_form_->ERRORS == false):
                    $valid['success']  = true;
                    $valid['messages'] = "Your password has been changed!";    
                else:
                    $valid['success']  = false;
                    $valid['messages'] = $_form_->ERRORS_STRING;
                endif;
                echo json_encode($valid);
            break;

        endswitch;
    endif;      
    
?>


