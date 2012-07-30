<?php

// Libraries
include_once "../libs/database.php";
include_once "../libs/template.php";
include_once "../libs/corefuncs.php";
include_once "../libs/variables.php";

// Controllers
include_once "../controllers/applicant.php";
include_once "../controllers/application.php";

// Redirect if user is not logged in
redirect_Unauthorized_User("../application/pages/login.php");

// Data
$application = Application::getActiveApplication();
$applicant 	 = Applicant::getActiveApplicant();
$db 		 = Database::getInstance();

// Process Submission
if( ! $application->hasBeenSubmitted() ){

	//Send Recommendations
	require "emailRecommenders.php";

	$application->generateServerPDF();

	//Send Payment
	$application->submitWithPayment(true);
} else {
	header('Location: ../application/pages/lockout.php');	
}

