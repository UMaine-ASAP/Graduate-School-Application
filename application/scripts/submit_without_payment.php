<?php

// Libraries
include_once "../libs/database.php";
include_once "../libs/corefuncs.php";

// Controllers
include_once "../controllers/applicant.php";
include_once "../controllers/application.php";

// Check User
redirect_Unauthorized_User("../application/pages/login.php");


$application = Application::getActiveApplication();

// process application
if( ! $application->hasBeenSubmitted() ){

	// Finish Submission
	require 'emailRecommenders.php';  // Submit recommendation emails
	require 'mailPayLater.php'; 	  // Send Email to Applicant

	$application->generateServerPDF();

	// Update application
	$application->submitWithPayment(false);

	header('Location: ../application/success.php');
} else {
	header('Location: ../application/pages/lockout.php');
}
