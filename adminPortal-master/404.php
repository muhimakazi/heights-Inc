<?php
require_once "core/init.php";
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TORUS</title>
    <link rel="icon" type="image/png" href="<?=DN?>/data_system/img/favicon.png">
    <link href="<?=DN?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=DN?>/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="<?=DN?>/css/animate.css" rel="stylesheet">
    <link href="<?=DN?>/css/style.css" rel="stylesheet">
    <link href="<?=DN?>/css/custom.css?v=1.1" rel="stylesheet">

</head>

<body class="gray-bg">


    <div class="middle-box text-center animated fadeInDown">
        <h1>404</h1>
        <h3 class="font-bold">Page Not Found</h3>

        <div class="error-desc">
            Sorry, but the page you are looking for has note been found. Try checking the URL for error, then hit the refresh button on your browser or try found something else in our app.
            <form class="form-inline m-t" role="form">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search for page">
                </div>
                <a class="btn btn-primary" href="<?=DN?>/login">Search</a>
            </form>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?=DN?>/js/jquery-2.1.1.js"></script>
    <script src="<?=DN?>/js/bootstrap.min.js"></script>

</body>

</html>
