<?php
include_once "variables.php";
include_once "database.php";
include_once "corefuncs.php";

$user = check_ses_vars();
$user = ($user)?$user:header("location:../pages/index.php");
//check for valid reference values
if( isset($_POST['reference']) ) {
	echo sendReferenceEmail( $_POST['reference'] );
}

//Sends email to recommenders - attempts
function sendReferenceEmail( $reference ) {
	global $user;

	if( !preg_match('/(^reference1$)|(^reference2$)|(^reference3$)|(^xref\d+$)/', $reference)) {
		return "ERROR: Invalid Format";
	}
 
	//Determine which table and fields to use
	if ( strpos($reference, "xref") === FALSE ) {
		$ref_table 		  = "applicants";	
		$email_sent_field = $reference . "_request_sent";
		$ref_online_field = $reference . "_online";
		$email_field 	  = $reference . "_email";
		$ref_fname_field  = $reference . "_first";
		$ref_lname_field  = $reference . "_last";
		$get_code_url 	  = "ref_id=" . $reference;
		$extra_test_field = 1;
		$extra_test_value = 1;
	} else {
		$ref_id = substr($reference, 4);

		$ref_table 		  = "extrareferences";
		$email_sent_field = "reference_request_sent";	
		$ref_online_field = "reference_online";	
		$email_field 	  = "reference_email";
		$ref_fname_field  = "reference_first";
		$ref_lname_field  = "reference_last";
		$get_code_url 	  = "xref_id=" . $ref_id;
		$extra_test_field = "extrareferences_id";
		$extra_test_value = $ref_id;
	}


	$db = new Database();
	$db->connect();

	// Check if reference email has already been submitted
	$result = $db->query("SELECT %s, %s, %s, %s, %s FROM %s WHERE applicant_id = %d AND %s = %s", $ref_fname_field, $ref_lname_field, $email_field, $email_sent_field, $ref_online_field, $ref_table, $user, $extra_test_field, $extra_test_value);

	$ref_data = $result[0];

	if( $ref_data[$email_sent_field] == 1 || $ref_data[$ref_online_field] == 0) {
		return "ERROR: Email already sent or not an online reference"; //email already sent or not an online reference
	}


	//====== Send Email ======//

	$email = filter_var($ref_data[$email_field], FILTER_SANITIZE_EMAIL);
	if($email != "") {
		
		// Queries applicant data
		$result = $db->query("SELECT * FROM applicants WHERE applicant_id =%d", $user);
	
		$userarray = $result[0];

		$fname 	 = sanitizeString($userarray['given_name']);
		$lname 	 = sanitizeString($userarray['family_name']);
		$userid  = $userarray['applicant_id'];
		$gethash = $userarray['login_email_code'];

		$ref_firstname = ucwords(sanitizeString( $ref_data[$ref_fname_field] ));
		$ref_lastname  = ucwords(sanitizeString( $ref_data[$ref_lname_field] ));

		// Subject line of email
		$subject = "UMaine Graduate School Recommendation for ".$fname." ".$lname;
	
		//email headers
		$sender_name  = "University of Maine Graduate School";
		$sender_email = "graduate@maine.edu";
		$headers 	  = "From: $sender_name <$sender_email>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";
		
		$link = "<a href='".$GLOBALS["grad_app_root"]."pages/rec_submit.php?userid=". $gethash ."&" . $get_code_url . "'>Click Here</a>";
		$link_plain = $GLOBALS["grad_app_root"]."pages/rec_submit.php?userid=". $gethash ."&" . $get_code_url;
		
		$message_plain  = "Hello ". $ref_firstname ." ". $ref_lastname . ",\r\r";
		$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message_plain .= "to the University of Maine Graduate School.\r\r";
		$message_plain .= "Please click the link below to submit a recommendation online:\r\r";
		$message_plain .= $link_plain;
		$message_plain .= "\r\r";
		$message_plain .= "Submitting a letter of recommendation online ensures more efficient processing of graduate applications.";
		$message_plain .= "\r\r";
		$message_plain .= "Thank you for support on behalf of the University of Maine's graduate student applicants!";
		$message_plain .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];
	
		mail($email, $subject, $message_plain, $headers);

	}


	//====== Update database field ======//

	$db->iquery("UPDATE %s SET %s = 1 WHERE applicant_id = %d AND %s = %s", $ref_table, $email_sent_field, $user, $extra_test_field, $extra_test_value);

	return "SUCCESS: Email Sent";
}

?>