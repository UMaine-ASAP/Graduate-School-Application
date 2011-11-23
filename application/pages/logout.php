<?php
include_once "../libs/corefuncs.php";
include_once '../libs/variables.php';


user_logout();

header( 'Location: ' . $GLOBALS['grad_app_root'] . "pages/login.php" ) ;
?>
