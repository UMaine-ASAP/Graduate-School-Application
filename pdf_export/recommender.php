<?php
	include_once "../application/libs/corefuncs.php";
	include_once "../application/libs/database.php";
	
	// Finds user id from session variable
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:pages/login.php");
	
	// Connects to database
	$db = new Database();
	$db->connect();
	
	// Queries applicant data
	$result = $db->query("SELECT * FROM applicants WHERE applicant_id =%d", $user);
	
	$userarray = $result[0];
	$fname = sanitizeString($userarray['given_name']);
	$lname = sanitizeString($userarray['family_name']);
	$userid = $userarray['applicant_id'];
	$gethash = $userarray['login_email_code'];
	
	// Subject line of email
	$subject = "UMaine Graduate School Recommendation for ".$fname." ".$lname;
	
	//email headers
	// $headers  = 'MIME-Version: 1.0' . "\r\n";
	// $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// $headers .= 'From: UMaine Graduate School <graduate@maine.edu>' . "\r\n";
	$sender_name = "University of Maine Graduate School";
	$sender_email = "graduate@maine.edu";
	$headers = "From: $sender_name <$sender_email>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";
	
	// If references online apply with email
	// Reference 1
	$recommender1email = filter_var($userarray['reference1_email'], FILTER_SANITIZE_EMAIL);
	$recommender1online = $userarray['reference1_online'];
	if ($recommender1online == 1 && $recommender1email!="") {
		//email link
		$link = "<a href='".$GLOBALS["grad_app_root"]."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference1"."'>Click Here</a>";
		$link_plain = $GLOBALS["grad_app_root"]."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference1";
		
		$message_plain = "Hello ". ucwords(sanitizeString($userarray['reference1_first'])) ." ". ucwords(sanitizeString($userarray['reference1_last'])).",\r\r";
		$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message_plain .= "to the University of Maine Graduate School.\r\r";
		$message_plain .= "Please click the link below to submit a recommendation online:\r\r";
		$message_plain .= $link_plain;
		$message_plain .= "\r\r";
		$message_plain .= "Submitting a letter of recommendation online ensures more efficient processing of graduate applications.";
		$message_plain .= "\r\r";
		$message_plain .= "Thank you for support on behalf of the University of Maine's graduate student applicants!";
		$message_plain .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];
	
		mail($recommender1email, $subject, $message_plain, $headers);
	}
		
	// Reference 2
	$recommender2email = filter_var($userarray['reference2_email'], FILTER_SANITIZE_EMAIL);
	$recommender2online = $userarray['reference2_online'];
	if ($recommender2online == 1 && $recommender2email!="") {

		//email link
		$link = "<a href='".$GLOBALS['grad_app_root'] ."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference2"."'>Click Here</a>";
		$link_plain = $GLOBALS['grad_app_root'] ."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference2";

		//email message body
		$message_plain = "Hello ". ucwords(sanitizeString($userarray['reference2_first'])) ." ". ucwords(sanitizeString($userarray['reference2_last'])).",\r\r";
		$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message_plain .= "to the University of Maine Graduate School.\r\r";
		$message_plain .= "Please click the link below to submit a recommendation online:\r\r";
		$message_plain .= $link_plain;
		$message_plain .= "\r\r";
		$message_plain .= "Submitting a letter of recommendation online ensures more efficient processing of graduate applications.";
		$message_plain .= "\r\r";
		$message_plain .= "Thank you for support on behalf of the University of Maine's graduate student applicants!";
		//$message_plain .= "\r\rThe University of Maine, Orono, Maine 04469\r(207) 581-3291\rA Member of the University of Maine System\r" . $GLOBALS['graduate_homepage'];
		$message_plain .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];

		mail($recommender2email, $subject, $message_plain, $headers);
	}
		
		
	// Reference 3
	$recommender3email = filter_var($userarray['reference3_email'], FILTER_SANITIZE_EMAIL);
	$recommender3online = $userarray['reference3_online'];
	if ($recommender3online == 1 && $recommender3email!="") {
		
		//email link
		$link = "<a href='".$GLOBALS['grad_app_root'] ."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference3"."'>Click Here</a>";
		$link_plain = $GLOBALS['grad_app_root'] ."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference3";

		//email message body
		$message_plain = "Hello ". ucwords(sanitizeString($userarray['reference3_first'])) ." ". ucwords(sanitizeString($userarray['reference3_last'])).",\r\r";
		$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message_plain .= "to the University of Maine Graduate School.\r\r";
		$message_plain .= "Please click the link below to submit a recommendation online:\r\r";
		$message_plain .= $link_plain;
		$message_plain .= "\r\r";
		$message_plain .= "Submitting a letter of recommendation online ensures more efficient processing of graduate applications.";
		$message_plain .= "\r\r";
		$message_plain .= "Thank you for support on behalf of the University of Maine's graduate student applicants!";
		//$message_plain .= "\r\rThe University of Maine, Orono, Maine 04469\r(207) 581-3291\rA Member of the University of Maine System\r" . $GLOBALS['graduate_homepage'];
		$message_plain .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];
		
		mail($recommender3email, $subject, $message_plain, $headers);
	}	
	
	
	// If there are more then 3 references
	$db->connect();
	// $qry = "SELECT * FROM extrareferences WHERE applicant_id = {$user}";
	// $qry = "SELECT * FROM extrareferences WHERE applicant_id = %d, $user";
	// $result = $db->query($qry);
	$result = $db->query("SELECT * FROM extrareferences WHERE applicant_id = %d", $user);
	
	
	$xrefarray = $result;
	if(count($xrefarray) >= 1){
		foreach($xrefarray as $xref){
			if($xref['reference_online'] == 1 && $xref['reference_email'] != ""){
	
				//email link
				$link = "<a href='".$GLOBALS['grad_app_root'] ."pages/rec_submit.php?userid=". $gethash ."&xref_id=". $xref['extrareferences_id']."'>Click Here</a>";
				$link_plain = $GLOBALS['grad_app_root'] ."pages/rec_submit.php?userid=". $gethash ."&xref_id=". $xref['extrareferences_id'];
						
				//email message body
				$message_plain = "Hello ". ucwords(sanitizeString($xref['reference_first'])) ." ". ucwords(sanitizeString($xref['reference_last'])).",\r\r";


				$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
				$message_plain .= "to the University of Maine Graduate School.\r\r";
				$message_plain .= "Please click the link below to submit a recommendation online:\r\r";
				$message_plain .= $link_plain;
				$message_plain .= "\r\r";
				$message_plain .= "Submitting a letter of recommendation online ensures more efficient processing of graduate applications.";
				$message_plain .= "\r\r";
				$message_plain .= "Thank you for support on behalf of the University of Maine's graduate student applicants!";
				//$message_plain .= "\r\rThe University of Maine, Orono, Maine 04469\r(207) 581-3291\rA Member of the University of Maine System\r" . $GLOBALS['graduate_homepage'];
				$message_plain .= "\r\rThe University of Maine, Graduate School\r5755 Stodder Hall\rOrono, Maine 04469\r(207) 581-3291\r" . $GLOBALS['graduate_homepage'];
				
				mail(filter_var($xref['reference_email'], FILTER_SANITIZE_EMAIL), $subject, $message_plain, $headers);
			}
		}
	}
?>
