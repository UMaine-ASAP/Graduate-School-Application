<?php
	include_once "../application/libs/database.php";
	include_once "../application/libs/corefuncs.php";
	include_once "../application/libs/variables.php";

	//*********************************************************************************************
	// Database Login
	//*********************************************************************************************
	$db = new Database();
	$db->connect();

	//*********************************************************************************************
	// Check User
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../application/pages/index.php");

	//*********************************************************************************************
	// Process Pay Later
	//*********************************************************************************************

	// Set Payment Status
	$db->iquery("UPDATE applicants SET application_fee_payment_status='N' WHERE applicants.applicant_id=%d", $user);	
	
	// Get Email and amount information
	$email_array  = $db->query("SELECT login_email FROM applicants WHERE applicant_id = %d",  $user);
	$email = $email_array[0]["login_email"];

	// Build Email
	$to      = $email;
	$subject = 'Application Received';

	$message_plain = "University of Maine Graduate Application Received - Fee Required";
	
	$message_plain .= "\r\rThank you for your application to the Graduate School. You must submit the payment of $65 for your application fee in order for your file to be reviewed for admission.";
	$message_plain .= "\rThe fee may be paid by sending a check/money order, made payable to University of Maine, to the address listed below.  Or you may call our office at 207-581-3291 to pay with a Visa or Mastercard.";
	
	$message_plain .="\r\rInstructions regarding how to log in to MaineStreet - the University's student information system - will be sent shortly with information on how to check the status of your application or pay your fee online, through your Student Center.";
	
	
	$message_plain .="\r\rPlease send checks or money orders to:";
	$message_plain .= "\rGraduate School\rUniversity of Maine\r5755 Stodder Hall\rOrono, ME 04469-5755";
	
	$message_plain .="\r\rYour application will be held for 60 days from the date your application was submitted, and then permanently removed from our files if no fee has been received.";
	
	$message_plain .= "\r\r\rQuestions and Feedback";
	$message_plain .= "\r\rFor questions, suggestions, and other feedback, please contact the administrator at graduate@maine.edu";

	$headers 		= "From: UMaine Graduate School <graduate@maine.edu>\r\nMIME-Version: 1.0\nContent-type: text/html; charset=iso-8859-1";
	$headers_plain  = "From: UMaine Graduate School <graduate@maine.edu>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";
	
	// Send Email
	mail($to, $subject, $message_plain, $headers_plain);
?>
