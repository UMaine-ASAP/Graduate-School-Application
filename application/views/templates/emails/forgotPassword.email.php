<?php

$to      = '';
$subject = "UMaine Grad School: Password Recovery";
$header  = "From: University of Maine Graduate School <noreply@umaine.edu>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";

// Message
$message = "Password Recovery\n\n";
$message .= "A password reset for the University of Maine Graduate School Online Application has been requested for the e-mail address {{APPLICANT_EMAIL}}. Click here to reset your password:{{CONFIRM_URL}}\n";
$message .= "\nFor questions, suggestions, and other feedback, please contact {{ADMIN_EMAIL}}.";

$message .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r{{GRADUATE_HOMEPAGE}}";

