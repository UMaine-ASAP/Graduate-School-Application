<?php

//check to make sure has not been submitted
	include_once "../application/libs/database.php";
	include_once "../application/libs/corefuncs.php";

	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../application/pages/login.php");

	$db = new Database();
	$db->connect();

	//Get has_been_submitted status
	$query  = "SELECT `has_been_submitted`";
	$query .= "FROM `applicants`";
	$query .= "WHERE `applicant_id`= %d";
	$result = $db->query($query, $user);
	$has_been_submitted = $result[0]['has_been_submitted'];

	//process application
	if($has_been_submitted == 0){

		//update database to show that application has been submitted
		$db_update = "";
		$db_update .= "UPDATE `applicants` ";
		$db_update .= "SET `has_been_submitted` = '1' ";
		$db_update .= "WHERE `applicant_id` = $user LIMIT 1";
		$db->iquery($db_update);
		
		//update application submit date in database
		$db_update = "";
		$db_update .= "UPDATE `applicants` ";
		$db_update .= "SET `application_submit_date` = '". date("Y-m-d");
		$db_update .= "' WHERE `applicant_id` = $user LIMIT 1";
		$db->iquery($db_update);

		require 'recommender.php';
		require 'mailPayLater.php';
		require 'pdf_export_server.php'; 
		header('Location: ../application/success.php');
	} else {
		header('Location: ../application/pages/lockout.php');
	}
?>
