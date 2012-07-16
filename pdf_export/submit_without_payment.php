<?php

	include_once "../application/libs/database.php";
	include_once "../application/libs/corefuncs.php";

	// Check User
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../application/pages/login.php");

	$db = new Database();
	$db->connect();

	//Get has_been_submitted status
	$result = $db->query("SELECT `has_been_submitted` FROM `applicants` WHERE `applicant_id`=%d", $user);
	$has_been_submitted = $result[0]['has_been_submitted'];

	// process application
	if($has_been_submitted == 0){

		// Set Payment Method
		$db->iquery("UPDATE applicants SET application_payment_method='%s' WHERE applicant_id=%d", "PAYLATER", $user);

		// Update database to show that application has been submitted
		$db->iquery("UPDATE `applicants` SET `has_been_submitted` = '1' WHERE `applicant_id` = %d LIMIT 1", $user);
		
		// Set application submit date
		$date = date("Y-m-d");
		$db->iquery("UPDATE `applicants` SET `application_submit_date` = '%s' WHERE `applicant_id` = %d LIMIT 1", $date, $user);

		// Submit recommendation emails
		require 'recommender.php';

		// Build Application
		require 'pdf_export_server.php';

		// Send Email to Applicant
		require 'mailPayLater.php';

		header('Location: ../application/success.php');
	} else {
		header('Location: ../application/pages/lockout.php');
	}
