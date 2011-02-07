<?

include_once "variables.php";

/**
* Session Creation
**/
function set_ses_vars($ID) {
	$_SESSION['UMGradSession'] = $ID;
	$_SESSION['lastAccess'] = time();
}

function check_ses_vars() {
	session_start();
	if(isset($_SESSION['UMGradSession']) && isset($_SESSION['lastAccess'])) {
		$latestAccess = time();
		if($latestAccess - $_SESSION['lastAccess'] > $GLOBALS["session_timeout"]) {
			user_logout();
			return 0;
		}
		$_SESSION['lastAccess'] = $latestAccess;
		return $_SESSION['UMGradSession'];
	}
	return 0;
}

/**
* Login
**/
function user_login($id) {
	$UMGradSession = $id;
	session_start();
	set_ses_vars($UMGradSession);
}

function user_logout() {
	session_start();
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

function sendSuccessMessage($email, $code) {
	$sender_name = "University of Maine Graduate School"; // sender's name
	$sender_email = "noreply@umaine.edu"; // sender's e-mail address
	$recipient_email = str_replace('@','%40',$email);
	$confirm_url = $GLOBALS['grad_app_root']."pages/login.php";
	$mail_body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
		<title>Account Created Successfully</title>
		<style type=\"text/css\" media=\"screen\">
		html {
			background:url(" . $GLOBALS['grad_images'] . "background-tile2.png) #143C55;
		}

		body {
			text-align:center;
			font-family: Verdana, Arial, sans-serif;
		}

		a:link, a:visited, a:hover, a:active {
			border:none;
		}

		img, img a, a img, #content img {
			border:none;
		}

		#content {
			width:50em;
			text-align:left;
			margin-left:auto;
			margin-right:auto;
		}

		#message {
			width:50em;
			margin-left: auto;
			margin-right: auto;
			padding:1em;
			background:#dfe9ed;
			-moz-border-radius:8px;
			-webkit-border-radius:8px;
		}

		strong {
			font-weight:bold;
		}

		h1 {
			color:#ffffff;
			margin-top:1em;
			margin-bottom:1.5em;
			font-size: 1.8em;
			font-family: Verdana, Arial, sans-serif;
			display:inline;
			position:relative;
			bottom:40px;
			left:87px;
		}

		p {
			padding-bottom:.5em;
		}

		.gradFooter{
			color:#fff;
			text-align:center;
			margin-top:15px;
			font-family:verdana,geneva,arial,helvetica,sans-serif;
			font-size:0.7em;
		}
		</style>
	</head>
	
	<body>
	<div id=\"content\">
	<a href=\"" . $GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" src='" . $GLOBALS['grad_images'] ."grad_logo.png' /></a>

	<h1>Pending Confirmation</h1>

	<div style=\"clear:both\"></div>

	<div id=\"message\">
		<h3>Account Pending Confirmation</h3>
		<p>An account for the University of Maine Graduate School Online Application has been requested for the e-mail address $email. Please confirm this address is correct before filling out the application.\r\rClick here to confirm: <a href=\"$confirm_url?email=" . str_replace('+','%2B',$recipient_email) . "&code=$code\">$confirm_url?email=" . str_replace('+','%2B',$recipient_email) . "&code=$code</a></p>

		<h3>Questions and Feedback</h3>
		<p>For questions, suggestions, and other feedback, please contact the <a href=\"mailto:$admin_email\">administrator</a>.</p>
		</div>
		<div style=\"clear:both;\"></div>

		<div class=\"gradFooter\">
		The University of Maine, Orono, Maine 04469 <br />
		(207) 581-3291 <br />
		A Member of the University of Maine System
		</div>
	</div>
	</body>
	</html>"; //mail body

	$mail_body_plain = "Account Pending Confirmation\n\n";
	$mail_body_plain .= "An account for the University of Maine Graduate School Online Application has been requested by this e-mail address. Please confirm this address is correct before filling out the application. Click here to confirm: ".$confirm_url."?email=" . str_replace('+','%2B',$recipient_email) . "&code=".$code."\n\n";
//	$mail_body_plain .= "Questions and Feedback";
	$mail_body_plain .= "For questions, suggestions, and other feedback, please contact ".$GLOBALS['admin_email'].".";
	//$mail_body_plain .= "\n\nThe University of Maine, Orono, Maine 04469\n";
	//$mail_body_plain .= "(207) 581-3291\n";
	//$mail_body_plain .= "A Member of the University of Maine System\n";
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
	$mail_body = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
		\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">

	<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
	<head>
		<title>Password Recovery</title>
		<style type=\"text/css\" media=\"screen\">
		html {
			background:url('".$GLOBALS['grad_images'] ."background-tile2.png') #143C55;
		}

		body {
			text-align:center;
			font-family: Verdana, Arial, sans-serif;
		}

		a:link, a:visited, a:hover, a:active {
			border:none;
		}

		img, img a, a img, #content img {
			border:none;
		}

		#content {
			width:50em;
			text-align:left;
			margin-left:auto;
			margin-right:auto;
		}

		#message {
			width:50em;
			margin-left: auto;
			margin-right: auto;
			padding:1em;
			background:#dfe9ed;
			-moz-border-radius:8px;
			-webkit-border-radius:8px;
		}

		strong {
			font-weight:bold;
		}

		h1 {
			color:#ffffff;
			margin-top:1em;
			margin-bottom:1.5em;
			font-size: 1.8em;
			font-family: Verdana, Arial, sans-serif;
			display:inline;
			position:relative;
			bottom:40px;
			left:47px;
		}

		p {
			padding-bottom:.5em;
		}

		.gradFooter{
			color:#fff;
			text-align:center;
			margin-top:15px;
			font-family:verdana,geneva,arial,helvetica,sans-serif;
			font-size:0.7em;
		}
		</style>
	</head>
	
	<body>
	<a href=\"".$GLOBALS['graduate_homepage']."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" src=\"".$GLOBALS['grad_images']."grad_logo.png\" /></a>
		<h1>Password Recovery</h1>
		<div id='message'><p>A password reset for the University of Maine Graduate School Online Application has been requested for the e-mail address $email.</p><p>Click here to reset your password: <a href=\"$confirm_url?email=".str_replace('+','%2B',$recipient_email)."&code=$code\">$confirm_url?email=".str_replace('+','%2B',$recipient_email)."&code=$code</a></p>

		<h3>Questions and Feedback</h3>
		<p>For questions, suggestions, and other feedback, please contact the <a href=\"mailto:$admin_email\">administrator</a>.</p></div>
	
		<div class=\"gradFooter\">
		The University of Maine, Orono, Maine 04469 <br />
		(207) 581-3291 <br />
		A Member of the University of Maine System
		</div>
	
	</body>
	</html>"; //mail body
	
	$mail_body_plain = "";
	$mail_body_plain .= "Password Recovery\n\n";
	$mail_body_plain .= "A password reset for the University of Maine Graduate School Online Application has been requested for the e-mail address $email. Click here to reset your password: $confirm_url?email=$recipient_email&code=$code\n";
	$mail_body_plain .= "\nFor questions, suggestions, and other feedback, please contact ".$GLOBALS['admin_email'].".";
	//$mail_body_plain .= "\n\nThe University of Maine, Orono, Maine 04469\n";
	//$mail_body_plain .= "(207) 581-3291\n";
	//$mail_body_plain .= "A Member of the University of Maine System\n";
	//$mail_body_plain .= $GLOBALS['graduate_homepage'];
	$mail_body_plain .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];

	mail($email, $subject, $mail_body_plain, $header); //mail command	
}

?>
