<?php
//error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set("session.use_cookies",1);
session_start();

$years = 60 * 60 * 24 * 3650 + time(); 
setcookie("first_time_check", "time_check",$years,"/", "", 0);

require '../lib/bootstrap.php';
require '../lib/controller.php';
require '../lib/query.php';
require '../lib/view.php';
require '../lib/model.php';
require '../lib/tvdb.php';
require '../lib/twitter.php';
require '../lib/facebook.php';
require '../lib/functions.php';
require '../config/path.php';

$app = new bootstrap();

?>