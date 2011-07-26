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
	// Determine User  ***check for sql injection risks***
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../application/pages/index.php");

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
	background:url(".$GLOBALS['grad_app_root']."images/background-tile2.png) #143C55;
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

<a href=\"".$GLOBALS["graduate_homepage"]."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" src='".$GLOBALS["grad_app_root"]."images/grad_logo.png' /></a>

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

$message_plain = "University of Maine Graduate Application Received - Fee Required";

$message_plain .= "\r\rThank you for your application to the Graduate School. You must submit the payment of $65 for your application fee in order for your file to be reviewed for admission.";
$message_plain .= "\rThe fee may be paid by sending a check/money order, made payable to University of Maine, to the address listed below.  Or you may call our office at 207-581-3291 to pay with a Visa or Mastercard.";

$message_plain .="\r\rInstructions regarding how to log in to MaineStreet - the University's student information system - will be sent shortly with information on how to check the status of your application or pay your fee online, through your Student Center.";


$message_plain .="\r\rPlease send checks or money orders to:";
$message_plain .= "\rGraduate School\rUniversity of Maine\r5755 Stodder Hall\rOrono, ME 04469-5755";

$message_plain .="\r\rYour application will be held for 60 days from the date your application was submitted, and then permanently removed from our files if no fee has been received.";

$message_plain .= "\r\r\rQuestions and Feedback";
$message_plain .= "\r\rFor questions, suggestions, and other feedback, please contact the administrator at graduate@maine.edu";

	$headers = "From: UMaine Graduate School <graduate@maine.edu>\r\nMIME-Version: 1.0\nContent-type: text/html; charset=iso-8859-1";
	$headers_plain = "From: UMaine Graduate School <graduate@maine.edu>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";
	mail($to, $subject, $message_plain, $headers_plain);
?>
