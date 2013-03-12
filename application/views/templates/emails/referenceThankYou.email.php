<?php
/**
 * Thank you letter to recommenders post recommendation submission
 * 
 * Parameters:
 * {{APPLICANT_FULL_NAME}}
 * {{APPLICANT_GIVEN_NAME}}
 */

$to      = '';
$subject = "Thank You from the University of Maine Graduate School"; //subject
$headers = "From: University of Maine Graduate School <noreply@umaine.edu>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";

$message  = "Thank you for writing a letter on behalf of {{APPLICANT_FULL_NAME}}'s application to the Graduate School at the University of Maine.  We regard letters of recommendation as one of the most crucial pieces of information in evaluating an applicant's potential for success in graduate study.  We deeply appreciate your effort in supporting {{APPLICANT_GIVEN_NAME}}'s application and hope that as you mentor other promising students that you will encourage them to consider the University of Maine.\n\n";
$message .= "Information on our 70 master's degree programs and 30 doctoral programs may be found at www.umaine.edu/graduate.  Please feel free to contact the Graduate School office if you would like to request additional information on any of our programs.\n\n";
$message .= "Thanks again!\n\n";
$message .= "Sincerely,\n\n";
$message .= "Scott G. Delcourt\n";
$message .= "Associate Dean\n";
$message .= "Graduate School\n";
$message .= "University of Maine\n";
$message .= "(207) 581-3291\n";