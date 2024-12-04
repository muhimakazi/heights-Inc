<?php
require_once 'core/init.php';
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Torusguru</title>
    <link rel="icon" type="image/png" href="<?php linkto('data_system/img/favicon.png'); ?>">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

</head>

<body class="gray-bg" style="background:#0d1418;">
    <div class="ms-content-wrapper ms-auth">
        <?php
            $FutureAccountTable = new \FutureAccount();
            $salt = Hash::salt(32);
            // $generate_password = Functions::generateStrongPassword(6);
            $generate_password = md5(uniqid(rand()));
            $password = Hash::make($generate_password, $salt);

            $_fields = array(
                'password' => $password,
                'salt' => $salt,
                'token' => $generate_password,
                // 'status' => $_STATUS_,
                // 'approval1_datetime' => time(),
                // 'approval1_by' => $_session_admin_ID
            );

            // $user_id = 109;

            // $FutureAccountTable->update($_fields, $user_id);

            // echo $salt. '<br>'. $generate_password. '<br>'. $password. '<br>';
            echo "Updated";
        ?>
    </div> 
    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="<?php linkto('pages/accounts/login.js'); ?>"></script>

</body>

</html>
