<?php

session_start();
include '../db_conn_open.php';
include 'ge_config.php';
include 'functions.php';
include '../controller/common_controller.php';
include '../controller/list_controller.php';
include '../controller/school_controller.php';
include '../controller/article_controller.php';
$controller = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : '0000';
switch ($controller) {
    case 'LIST':
        $geObj = new listController($_REQUEST);
        break;
    case 'SCHOOL':
        $schoolObj = new schoolController($_POST);
        break;
    case 'BLOG':
        $blogObj = new blogController($_REQUEST);
        break;
}
