<?php
include "../libs/corefuncs.php";
include '../libs/variables.php';


user_logout();

header( 'Location: ' . $GLOBALS['grad_app_root'] . "pages/login.php" ) ;
?>
