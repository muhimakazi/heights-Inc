<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// $_SESSION['user'] = 16;

# CONFIGURE HTTPS
$http = 'http'; # Local 
if ($_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1'):
    $http = 'https';
    if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on"):
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
        exit;
    endif;
endif;


function def()
{
    define("DN", Config::get('server/name'));
    define("_", "/");
    define("P", ".php");
    define("PL", ".php");
    define("CNS", ".php");
    define("CT", "Controller");
    define("CTRL", './app' . _ . 'controller' . PL);
    define("ROUTES", './views' . _ . 'routes' . PL);
    define("DNSIGNIN", DN . _ . 'login');
    define("view_session_off_", "views/app_session_off/");
    define("view_session_off", "views/app_session_off");
    define("_PATH_", "/");
    define("_VIEWS_", "views/");
    define("_PATH_VIEWS_", "./views/");
    define('Controller_NS', 'app\Http\Controllers\\'); // NS => Namespace
    define('Url_NS', 'app\Http\Url\\');
    define("DNADMIN", DN . _ . Config::get('server/name') . _ . 'admin');
    define("DN_IMG_CARDS", DN . _ . 'data_system/img/cards');
    define("VIEW_IMG_ID_DOC", DN . '/data_system/img/id_document/');
    define("VIEW_PROFILE", DN . '/media/pictures/profile/');
    define("DN_IMG_ID_DOC", Config::get('filepath/image') . 'id_document/');
    define("DN_IMG_PROFILE", Config::get('filepath/image') . 'profile/');
    define("VIEW_QR", DN . '/data_system/img/qr/');
    define("DN_IMG_QR", Config::get('filepath/image') . 'qr/');

    define("VIEW_VACCINATION", DN . '/data_system/img/vaccination/');
    define("DN_IMG_VACCINATION", Config::get('filepath/image') . 'vaccination/');
}


$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'future_summit_db'
    ),

//     'mysql' => array(
//        'host' => 'localhost',
//        'username' => 'cubedigital',
//        'password' => 'cubedigital@torus',
//        'db' => 'future_summit_db'
//    ),

    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'sessions' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'server' => array(
        // 'name' => "$http://{$_SERVER['HTTP_HOST']}/thefuture/adminPortal",
        'name' => "$http://{$_SERVER['HTTP_HOST']}",

        // 'web' => "$http://{$_SERVER['HTTP_HOST']}/thefuture/apac",
        'web' => "$http://{$_SERVER['HTTP_HOST']}",
    ),

    'url' => array(

        // 'mail_smtp' => "$http://{$_SERVER['HTTP_HOST']}/thefuture/adminPortal/mail_smtp", // Local
//		'mail_smtp' => "$http://localhost:443/thefuture/adminPortal/mail_smtp", // Live
        'mail_smtp' => "$http://{$_SERVER['HTTP_HOST']}/mail_smtp", // Live

        // 'mail_smtp_noreply' => "$http://{$_SERVER['HTTP_HOST']}/thefuture/adminPortal/mail_smtp_noreply", // Local
        'mail_smtp_noreply' => "$http://localhost:443/thefuture/adminPortal/mail_smtp_noreply", // Live

        // 'group_admin_portal' => "$http://{$_SERVER['HTTP_HOST']}/thefuture/groupPortal", // Local
        'group_admin_portal' => "https://group.torusguru.com", // Live
    ),
    'filepath' => array(
        // 'image' => $_SERVER['DOCUMENT_ROOT'] . '/thefuture/adminPortal/data_system/img/', // Loca
        'image' => $_SERVER['DOCUMENT_ROOT'] . '/data_system/img/', //Live 
    )


);

// require_once $_SERVER['DOCUMENT_ROOT'] . '/thefuture/adminPortal/functions/functions.php'; // Local
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php'; // Live

spl_autoload_register(function ($class) {
    // require_once $_SERVER['DOCUMENT_ROOT'] . '/thefuture/adminPortal/classes/' . $class . '.php'; // Local
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/' . $class . '.php'; // Live
});

/** Initialize Define */
def();

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('sessions/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}
else {
    $user = new User();
}

$errmsg = $succmsg = $page = $link = $sublink = "";
$controller = new Controller();
$encodedEventId = Input::get('eventId');
$progDay = Input::get('day');
$activeEventId = 8;

/** SCRIPT - AUTO EXPIRY - PRIVATE LINKS - */
//FutureEventController::autoExpirationStatusEventPrivateInvitationLink($eventID);

/** Handle Language */
//$GLOBALS['_Lang']     = Session::exists('lang')?Session::get('lang'):'eng-lang';
//$GLOBALS['_LangName'] = Functions::getLanguageName($_Lang);
//
//
///** Dictionary */
//$GLOBALS['_Dictionary'] = new \Properties($_Lang);
