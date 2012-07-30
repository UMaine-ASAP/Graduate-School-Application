<?php

// Libraries
include_once "variables.php";
include_once "database.php";
include_once "template.php";

/**
* Session Creation
**/
function set_ses_vars($ID) {
	$_SESSION['UMGradSession'] = $ID;
	$_SESSION['lastAccess'] = time();
}

function check_ses_vars() {
	if( !isset($_SESSION) ) { session_start(); }
	if(isset($_SESSION['UMGradSession']) && isset($_SESSION['lastAccess'])) {
		$latestAccess = time();
		if($latestAccess - $_SESSION['lastAccess'] > $GLOBALS["session_timeout"]) {
			user_logout();
			return 0;
		}
		//Make sure user is valid
		$db = Database::getInstance();

		$user_check = $db->query("SELECT applicant_id FROM applicants WHERE applicant_id = %d", $_SESSION['UMGradSession']);

		if( is_array($user_check) ) {
			$_SESSION['lastAccess'] = $latestAccess;
			$db->close();
			return $_SESSION['UMGradSession'];	
		} else {
			$db->close();
			return 0;
		}
	}
	return 0;
}

/**
* Login
**/
function user_login($id) {
	if( !isset($_SESSION) ) { session_start(); }
	set_ses_vars($id);
}

function user_logout() {
	if( !isset($_SESSION) ) { session_start(); }

	session_unset();
	unset($_SESSION['UMGradSession']);
	unset($_SESSION['lastAccess']);
}

function sanitizeString ( $var ) {
	return( filter_var($var, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW) );
}

function is_odd( $int ) {
  return( $int & 1 );
}


function redirect_Unauthorized_User($nonauthenticatedDestination)
{
	$user = check_ses_vars();
	if( $user == 0) {
		header("location:" . $nonauthenticatedDestination);
		exit();
	}
}

function sendSuccessMessage($email, $code) {
	$sender_name = "University of Maine Graduate School"; // sender's name
	$sender_email = "noreply@umaine.edu"; // sender's e-mail address
	$recipient_email = str_replace('@','%40',$email);
	$confirm_url = $GLOBALS['grad_app_root']."pages/login.php";

	$mail_body_plain = "Account Pending Confirmation\n\n";
	$mail_body_plain .= "An account for the University of Maine Graduate School Online Application has been requested by this e-mail address. Please confirm this address is correct before filling out the application. Click here to confirm: ".$confirm_url."?email=" . str_replace('+','%2B',$recipient_email) . "&code=".$code."\n\n";
	$mail_body_plain .= "For questions, suggestions, and other feedback, please contact ".$GLOBALS['admin_email'].".";
	$mail_body_plain .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];


	$subject = "UMaine Grad School: Please Confirm Your Account Request"; //subject
	$header = "From: $sender_name <$sender_email>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";

	mail($email, $subject, $mail_body_plain, $header); //mail command	
}

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


