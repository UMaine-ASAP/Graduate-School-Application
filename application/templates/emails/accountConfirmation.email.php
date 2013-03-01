<?php

$to      = '';
$subject = 'UMaine Grad School: Please Confirm Your Account Request';
$headers = "From: University of Maine Graduate School <noreply@umaine.edu>\r\nMIME-Version: 1.0\r\nContent-type: text/plain; charset=iso-8859-1";

// Message
$message = "Account Pending Confirmation\n\n";
$message .= "An account for the University of Maine Graduate School Online Application has been requested by this e-mail address. Please confirm this address is correct before filling out the application. Click here to confirm: {{CONFIRM_URL}}?email={{RECIPIENT_EMAIL}}&code={{CODE}}\n\n";
$message .= "For questions, suggestions, and other feedback, please contact ".$GLOBALS['admin_email'].".";
$message .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];




