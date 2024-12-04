<?php

/**
 * Title: Core Initializer
 */

session_start();
require_once("Config.php");

// ENABLING SSL 
$http = "http";
// if($_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1'){
//     $http = 'http'; 
//     if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
//         $redirect = $http.'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//         header('HTTP/1.1 301 Moved Permanently');
//         header('Location: ' . $redirect);
//         exit();
//     }
// }else{
//     $http = 'http';
// } cjx@dbserver / teamwdor_cjx_admin / teamwdor_cjx_db


// GLOBAL VALUES 

// Local config
$GLOBALS['config'] = array(
    'dbc' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'future_summit_db'
    ),

    'server' => array(
        'address' => $http . '://' . $_SERVER['HTTP_HOST'] . '',
        'mediaHost' => $http . '://' . $_SERVER['HTTP_HOST'] . '',
        'appName' => 'adminPortal',
        'mediaURI' => 'cubeMediaBase'
    ),

    'api' => array(
        'payment_callback' => $http . '://' . $_SERVER['HTTP_HOST'] ."/thefuture/adminPortal/api/v1/pay/callback",
        // 'payment_callback' => "$http://{$_SERVER['HTTP_HOST']}/pay/callback/",
    ),

);

// Live config
// $GLOBALS['config'] = array(
//     'dbc' => array(
//         'host' => '127.0.0.1',
//         'username' => 'root',
//         'password' => '',
//         'db' => 'cube_backend_engine'
//     ),

//     'server' => array(
//         'address' => $_SERVER['HTTP_HOST'],
//         'appName' => ''
//     ),

// );



// Application keywords definition
function def()
{

    define("P", ".php");
    define("_", "/");
    define("_PATH_", "/");
    define("_VIEWS_", "web_pages/views");
    define("LANG", "EN");
    define("TF", "d/m/Y");
    define("appBaseURL", Config::get('server/address') . _ . Config::get('server/appName'));
    define("mediaBaseURL", Config::get('server/mediaHost') . _ . Config::get('server/mediaURI'));
    define("mediaRoot", $_SERVER['DOCUMENT_ROOT'] . _ . mediaBaseURL);
    define("LOGIN", appBaseURL . "/login");
    define("appName", Config::get('server/appName'));
    define("appSource", $_SERVER['DOCUMENT_ROOT'] . "/thefuture" ."/". appName . "/source");
    define("DEFAULT_PASSWORD", "12345");
    define("ACTIVE", "ACTIVE");
    define("DELETED", "DELETED");
    define("LOCKED", "LOCKED");
    define("UNLOCKED", "UNLOCKED");
    define("SALT", "LFUCJITENMTERFU#137");
    define("COUNTRY", "RWANDA");
    define("THIS_COMPANY", "Cube Communications Ltd");
    define("DEFAULT_CURRENCY", "USD");
    define("SUCCESS", "200");
    define("CREATED", "201");
    define("BAD_REQUEST", "400");
    define("UNAUTHORIZED", "401");
    define("NOT_FOUND", "404");
    define("FORBIDDEN", "403");
    define("BAD_REQUEST_METHOD", "405");
    define("FAILURE", "500");

    // NOTIFICATION MESSAGES
    define("SUCCESSFUL", "Operation completed successfully");
    define("UNSUCCESSFUL", "Operation failed");
}

// Calling the def function
def();

// INCLUDING GENERAL-PURPOSE CLASSES GLOBALLY - DEVELOPMENT
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/general/General.php';
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/general/DataValidation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/general/Time.php';
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/general/Input.php';
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/core/DBConnection.php';
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/security/Hash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/security/Session.php';
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/httprequests/Httprequests.php';
require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/general/Notifications.php';
 
// Autoloading all the Service classes
spl_autoload_register(function ($class) {
    require_once $_SERVER['DOCUMENT_ROOT'] . _ . "thefuture" . _ . appName . '/source/services/' . $class . '.php';
});

// INCLUDING GENERAL-PURPOSE CLASSES GLOBALLY - PRODUCTION
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/general/General.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/general/DataValidation.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/general/Time.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/general/Input.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/core/DBConnection.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/security/Hash.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/security/Session.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/httprequests/Httprequests.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . _  . '/source/general/Notifications.php';
 
// // Autoloading all the Service classes
// spl_autoload_register(function ($class) {
//     require_once $_SERVER['DOCUMENT_ROOT'] . _ . '/source/services/' . $class . '.php';
// });