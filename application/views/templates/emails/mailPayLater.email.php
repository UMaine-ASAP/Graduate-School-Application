<?php

$to      = '';
$subject = 'Application Received';
$headers = "From: UMaine Graduate School <graduate@maine.edu>\r\nMIME-Version: 1.0\r\nContent-type: text/plain; charset=iso-8859-1";

// Message
$message = "University of Maine Graduate Application Received - Fee Required";

$message .= "\r\rThank you for your application to the Graduate School. You must submit the payment of $65 for your application fee in order for your file to be reviewed for admission.";
$message .= "\rThe fee may be paid by sending a check/money order, made payable to University of Maine, to the address listed below.  Or you may call our office at 207-581-3291 to pay with a Visa or Mastercard.";

$message .="\r\rInstructions regarding how to log in to MaineStreet - the University's student information system - will be sent shortly with information on how to check the status of your application or pay your fee online, through your Student Center.";


$message .="\r\rPlease send checks or money orders to:";
$message .= "\rGraduate School\rUniversity of Maine\r5755 Stodder Hall\rOrono, ME 04469-5755";

$message .="\r\rYour application will gg be held for 60 days from the date your application was submitted, and then permanently removed from our files if no fee has been received.";

$message .= "\r\r\rQuestions and Feedback";
$message .= "\r\rFor questions, suggestions, and other feedback, please contact the administrator at graduate@maine.edu";
