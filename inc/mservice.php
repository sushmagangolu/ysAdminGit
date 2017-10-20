<?phpheader('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');
include '../db_conn_open.php';
include 'ge_config.php';
include 'functions.php';
include '../controller/common_controller.php';
include '../controller/mobile_controller.php';
$geObj = new mobileController($conn);
