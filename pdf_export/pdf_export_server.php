<?php
	require_once "application_generate_pdf.php";
	$user = isset($_GET['userid']) ? $_GET['userid'] : check_ses_vars();
	$user = ($user)?$user:header("location:../pages/index.php");
	generate_application_pdf($user);

	//include "build_application.php";
?>