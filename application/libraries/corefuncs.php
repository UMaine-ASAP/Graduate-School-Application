<?php

// Libraries
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/database.php";
require_once __DIR__ . "/template.php";






function sendRecoverMessage($email, $code) {
	$sender_name = "University of Maine Graduate School"; // sender's name
	$sender_email = "noreply@umaine.edu"; // sender's e-mail address
	$recipient_email = str_replace('+','%2B',$email);
	$confirm_url = $GLOBALS['grad_app_root']."pages/forgot.php";//"http". ((!empty($_SERVER['HTTPS'])) ? "s" : ""). "://" .$GLOBALS['server_name']. $_SERVER['REQUEST_URI'];
	$subject = "UMaine Grad School: Password Recovery";
	$header = "From: $sender_name <$sender_email>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";
	
	$mail_body_plain = "";
	$mail_body_plain .= "Password Recovery\n\n";
	$mail_body_plain .= "A password reset for the University of Maine Graduate School Online Application has been requested for the e-mail address $email. Click here to reset your password: $confirm_url?email=$recipient_email&code=$code\n";
	$mail_body_plain .= "\nFor questions, suggestions, and other feedback, please contact ".$GLOBALS['admin_email'].".";
	$mail_body_plain .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];

	mail($email, $subject, $mail_body_plain, $header); //mail command	
}


