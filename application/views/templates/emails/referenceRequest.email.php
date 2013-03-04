<?php
/**
 * Reference Request Email Template
 * 
 * Data:
 * 
 * 	APPLICANT_FULL_NAME
 * 	REFERENCE_FULL_NAME
 * 	RECOMMENDATION_LINK
 * 		$link_plain = $GLOBALS["grad_app_root"]."pages/rec_submit.php?userid=". $gethash ."&" . $get_code_url;
 * 	GRADUATE_HOMEPAGE
 */

$to = '';
$subject = "UMaine Graduate School Recommendation for {{APPLICANT_FULL_NAME}}";

//email headers
$sender_name  = "University of Maine Graduate School";
$sender_email = "graduate@maine.edu";
$headers 	  = "From: $sender_name <$sender_email>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";

$message  = "Hello {{REFERENCE_FULL_NAME}},\r\r";
$message .= "{{APPLICANT_FULL_NAME}} has requested that you write a recommendation for their application "; 
$message .= "to the University of Maine Graduate School.\r\r";
$message .= "Please click the link below to submit a recommendation online:\r\r";
$message .= "{{RECOMMENDATION_LINK}}";
$message .= "\r\r";
$message .= "Submitting a letter of recommendation online ensures more efficient processing of graduate applications.";
$message .= "\r\r";
$message .= "Thank you for support on behalf of the University of Maine's graduate student applicants!";
$message .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r {{GRADUATE_HOMEPAGE}}";
