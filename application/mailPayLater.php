<?php
	include_once "libs/database.php";
	include_once "libs/corefuncs.php";

	//*********************************************************************************************
	// Database Login
	//*********************************************************************************************
	$db = new Database();
	$db->connect();

	//*********************************************************************************************
	// Determine User  ***check for sql injection risks***
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../forms/signin/");

	$db->iquery("UPDATE applicants SET application_fee_payment_status='N' WHERE applicants.applicant_id=%d", $user);	
	
	$email_array = $db->query("SELECT login_email FROM applicants WHERE applicant_id = %d",  $user);
	$amount_array = $db->query("SELECT application_fee_transaction_amount FROM applicants WHERE applicant_id = %d", $user);
	
	//print_r($email_array);
	$email = $email_array[0]["login_email"];
	$amount = $amount_array[0]["application_fee_transaction_amount"];
	
	$to      = $email;
	$subject = 'Application Received';
	$message = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
<head>
<title>Application Received</title>
<style type=\"text/css\" media=\"screen\">
html {
	background:url(http://".$_SERVER['SERVER_NAME'] ."/grad/drupal6/sites/all/themes/acquia_marina/images/background-tile2.png) #143C55;
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
	font-size: 2.4em;
	font-family: Verdana, Arial, sans-serif;
	display:inline;
	position:relative;
	bottom:40px;
	left:157px;
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

<a href=\"http://".$_SERVER['SERVER_NAME'] ."/grad/drupal6\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" src='http://".$_SERVER['SERVER_NAME'] ."/grad/application/images/grad_logo.png' /></a>

<h1>Welcome!</h1>

<div style=\"clear:both\"></div>

<div id=\"message\">

<h3>Application Received</h3>
<p>Thank you for your application to the Graduate School. Please send a check or money order for the application fee of \$$amount as soon as possible so that your application may be processed. Once the application fee has been received, you will receive an email from the Graduate School acknowledging the processing of your application. In this email, you will receive instructions regarding how to log in to the University&rsquo;s student information system to check the status of your application. Your check should be made payable to the University of Maine and sent to:</p>

<p>
Graduate School<br />
University of Maine<br />
5755 Stodder Hall<br />
Orono, ME 04469-5755</p>

<h3>Questions and Feedback</h3>
<p>For questions, suggestions, and other feedback, please contact the <a href=\"mailto:graduate@maine.edu\">administrator</a>.</p>

</div>

<div style=\"clear:both;\"></div>

<div class=\"gradFooter\">
The University of Maine, Orono, Maine 04469 <br />
(207) 581-3291 <br />
A Member of the University of Maine System
</div>

</div>

</body>
</html>";
	$headers = "From: UMaine Graduate School <graduate@maine.edu>\r\nMIME-Version: 1.0\nContent-type: text/html; charset=iso-8859-1";
	mail($to, $subject, $message, $headers);
?>
