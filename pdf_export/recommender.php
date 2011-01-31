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
	$headers = "From: $sender_name <$sender_email>\r\nMIME-Version: 1.0\nContent-type: text/html; charset=iso-8859-1";
	
	// If references online apply with email
	// Reference 1
	$recommender1email = filter_var($userarray['reference1_email'], FILTER_SANITIZE_EMAIL);
	$recommender1online = $userarray['reference1_online'];
	if ($recommender1online == 1 && $recommender1email!="") {
		//email link
		$link = "<a href='".$GLOBALS["grad_app_root"]."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference1"."'>Click Here</a>";
		
		//email message body
		$message = "
		<?xml version=\"1.0\" encoding=\"UTF-8\"?>
		<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
			\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">

		<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
		<head>
		  <title>UMaine Graduate School Recommendation</title>";
		
		$message.= "<style type=\"text/css\" media=\"screen\">
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
			padding:2em 2em 3em 2em;
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
			left:157px;
		}

		.gradFooter{
			color:#fff;
			text-align:center;
			margin-top:15px;
			font-family:verdana,geneva,arial,helvetica,sans-serif;
			font-size:0.7em;
		}
		</style>";
		
		$message.= "</head>"."<body>";

		$message .= "<a href=\"".$GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" 		src='".$GLOBALS['grad_app_root'] ."images/grad_logo.png' /></a>";
		$message .= "<div id= 'message'>"."<p style='font-weight:bold;'>"."Hello ". ucwords(sanitizeString($userarray['reference1_first'])) ." ". ucwords(sanitizeString($userarray['reference1_last'])) .", </p><br/>";
		$message .= $fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message .= "to the University of Maine Graduate School.<br/><br/>";
		$message .= "Please click the link below to submit a recommendation online.<br/><br/>";
		$message .= $link;
		$message .= "<br/><br/>";
		$message .= "Submitting a letter of recommendation online ensures advanced processing of graduate applications.";
		$message .= "<br/><br/>";
		$message .= "Thank you for supporting the University of Maine's graduate mission!";
		$message .= "</div>";
		$message .= "<div class=\"gradFooter\">
		The University of Maine, Orono, Maine 04469 <br />
		(207) 581-3291 <br />
		A Member of the University of Maine System
		</div>";
		$message .= "</body>
		</html>";

		$message_plain = "<a href=\"".$GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" 		src='".$_SERVER['grad_app_root'] ."images/grad_logo.png' /></a>";
		$message_plain .= "Hello ". ucwords(sanitizeString($userarray['reference3_first'])) ." ". ucwords(sanitizeString($userarray['reference3_last'])).",/r/r";


		$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message_plain .= "to the University of Maine Graduate School./r/r";
		$message_plain .= "Please click the link below to submit a recommendation online./r/r";
		$message_plain .= $link;
		$message_plain .= "/r/r";
		$message_plain .= "Submitting a letter of recommendation online ensures advanced processing of graduate applications.";
		$message_plain .= "/r/r";
		$message_plain .= "Thank you for supporting the University of Maine's graduate mission!";
		$message_plain .= "
		The University of Maine, Orono, Maine 04469
		(207) 581-3291
		A Member of the University of Maine System";
	
		mail($recommender1email, $subject, $message, $headers);
	}
		
	// Reference 2
	$recommender2email = filter_var($userarray['reference2_email'], FILTER_SANITIZE_EMAIL);
	$recommender2online = $userarray['reference2_online'];
	if ($recommender2online == 1 && $recommender2email!="") {

		//email link
		$link = "<a href='".$GLOBALS['grad_app_root'] ."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference2"."'>Click Here</a>";
		
		//email message body
		$message = "
		<?xml version=\"1.0\" encoding=\"UTF-8\"?>
		<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
			\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">

		<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
		<head>
		  <title>UMaine Graduate School Recommendation</title>";
		
		$message.= "<style type=\"text/css\" media=\"screen\">
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
			padding:2em 2em 3em 2em;
			
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
			left:157px;
		}

		

		.gradFooter{
			color:#fff;
			text-align:center;
			margin-top:15px;
			font-family:verdana,geneva,arial,helvetica,sans-serif;
			font-size:0.7em;
		}
		</style>";
		
		$message.= "</head>"."<body>";
		$message .= "<a href=\"".$GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" 		src='".$GLOBALS['grad_app_root'] ."images/grad_logo.png' /></a>";
		$message .= "<div id= 'message'>"."<p style='font-weight:bold;'>"."Hello ". ucwords(sanitizeString($userarray['reference2_first'])) ." ". ucwords(sanitizeString($userarray['reference2_last'])) .", </p><br/>";
		$message .= $fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message .= "to the University of Maine Graduate School.<br/><br/>";
		$message .= "Please click the link below to submit a recommendation online.<br/><br/>";
		$message .= $link;
		$message .= "<br/><br/>";
		$message .= "Submitting a letter of recommendation online ensures advanced processing of graduate applications.";
		$message .= "<br/><br/>";
		$message .= "Thank you for supporting the University of Maine's graduate mission!";
		$message .= "</div>";
		$message .= "<div class=\"gradFooter\">
		The University of Maine, Orono, Maine 04469 <br />
		(207) 581-3291 <br />
		A Member of the University of Maine System
		</div>";
		$message .= "
		</body>
		</html>";

		$message_plain = "<a href=\"".$GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" 		src='".$_SERVER['grad_app_root'] ."images/grad_logo.png' /></a>";
		$message_plain .= "Hello ". ucwords(sanitizeString($userarray['reference3_first'])) ." ". ucwords(sanitizeString($userarray['reference3_last'])).",/r/r";


		$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message_plain .= "to the University of Maine Graduate School./r/r";
		$message_plain .= "Please click the link below to submit a recommendation online./r/r";
		$message_plain .= $link;
		$message_plain .= "/r/r";
		$message_plain .= "Submitting a letter of recommendation online ensures advanced processing of graduate applications.";
		$message_plain .= "/r/r";
		$message_plain .= "Thank you for supporting the University of Maine's graduate mission!";
		$message_plain .= "
		The University of Maine, Orono, Maine 04469
		(207) 581-3291
		A Member of the University of Maine System";

		mail($recommender2email, $subject, $message, $headers);
	}
		
		
	// Reference 3
	$recommender3email = filter_var($userarray['reference3_email'], FILTER_SANITIZE_EMAIL);
	$recommender3online = $userarray['reference3_online'];
	if ($recommender3online == 1 && $recommender3email!="") {
		
		//email link
		$link = "<a href='".$GLOBALS['grad_app_root'] ."pages/rec_submit.php?userid=". $gethash ."&ref_id=reference3"."'>Click Here</a>";
		
		//email message body
		$message = "
		<?xml version=\"1.0\" encoding=\"UTF-8\"?>
		<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
			\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">

		<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">
		<head>
		  <title>UMaine Graduate School Recommendation</title>";
		
		$message.= "<style type=\"text/css\" media=\"screen\">
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
			padding:2em 2em 3em 2em;
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
			left:157px;
		}


		.gradFooter{
			color:#fff;
			text-align:center;
			margin-top:15px;
			font-family:verdana,geneva,arial,helvetica,sans-serif;
			font-size:0.7em;
		}
		</style>";
		
		$message.= "</head>"."<body>";
		$message .= "<a href=\"".$GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" 		src='".$_SERVER['grad_app_root'] ."images/grad_logo.png' /></a>";
		$message .= "<div id= 'message'>"."<p style='font-weight:bold;'>"."Hello ". ucwords(sanitizeString($userarray['reference3_first'])) ." ". ucwords(sanitizeString($userarray['reference3_last'])) .", </p><br/>";
		$message .= $fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message .= "to the University of Maine Graduate School.<br/><br/>";
		$message .= "Please click the link below to submit a recommendation online.<br/><br/>";
		$message .= $link;
		$message .= "<br/><br/>";
		$message .= "Submitting a letter of recommendation online ensures advanced processing of graduate applications.";
		$message .= "<br/><br/>";
		$message .= "Thank you for supporting the University of Maine's graduate mission!";
		$message .= "</div>";
		$message .= "<div class=\"gradFooter\">
		The University of Maine, Orono, Maine 04469 <br />
		(207) 581-3291 <br />
		A Member of the University of Maine System
		</div>";
		$message .= "
		</body>
		</html>";

		$message_plain = "<a href=\"".$GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" 		src='".$_SERVER['grad_app_root'] ."images/grad_logo.png' /></a>";
		$message_plain .= "Hello ". ucwords(sanitizeString($userarray['reference3_first'])) ." ". ucwords(sanitizeString($userarray['reference3_last'])).",\r\r";


		$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
		$message_plain .= "to the University of Maine Graduate School.\r\r";
		$message_plain .= "Please click the link below to submit a recommendation online.\r\r";
		$message_plain .= $link;
		$message_plain .= "\r\r";
		$message_plain .= "Submitting a letter of recommendation online ensures advanced processing of graduate applications.";
		$message_plain .= "\r\r";
		$message_plain .= "Thank you for supporting the University of Maine's graduate mission!";
		$message_plain .= "
		The University of Maine, Orono, Maine 04469
		(207) 581-3291
		A Member of the University of Maine System";

		
		mail($recommender3email, $subject, $message, $headers);
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
						
				//email message body
				$message = "
				<html>
				<head>
	    		<title>UMaine Graduate School Recommendation</title>
	
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
					padding:2em 2em 3em 2em;
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
					left:157px;
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
				<body>";
				$message .= "<a href=\"".$GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" 		src='".$GLOBALS['grad_app_root'] ."/images/grad_logo.png' /></a>";
				$message .= "<div id= 'message'>"."<p style='font-weight:bold;'>"."Hello ". ucwords(sanitizeString($xref['reference_first'])) ." ". ucwords(sanitizeString($xref['reference_last'])) .", </p><br/>";
				$message .= $fname. " " .$lname. " has requested that you write a recommendation for their application "; 
				$message .= "to the University of Maine Graduate School.<br/>";
				$message .= "Please click the link below to submit a recommendation online.<br/><br/>";
				$message .= $link;
				$message .= "<br/><br/>";
				$message .= "Submitting a letter of recommendation online ensures advanced processing of graduate applications.";
				$message .= "<br/><br/>";
				$message .= "Thank you for supporting the University of Maine's graduate mission!";
				$message .= "</div>";
				$message .= "<div class=\"gradFooter\">
				The University of Maine, Orono, Maine 04469 <br />
				(207) 581-3291 <br />
				A Member of the University of Maine System
				</div>";
				$message .= "
				</body>
				</html>";

		$message_plain = "<a href=\"".$GLOBALS['graduate_homepage'] ."\"><img alt=\"The University of Maine Graduate School\" height=\"99\" width=\"245\" 		src='".$_SERVER['grad_app_root'] ."images/grad_logo.png' /></a>";
		$message_plain .= "Hello ". ucwords(sanitizeString($userarray['reference3_first'])) ." ". ucwords(sanitizeString($userarray['reference3_last'])).",/r/r";


				$message_plain .= "".$fname. " " .$lname. " has requested that you write a recommendation for their application "; 
				$message_plain .= "to the University of Maine Graduate School./r/r";
				$message_plain .= "Please click the link below to submit a recommendation online./r/r";
				$message_plain .= $link;
				$message_plain .= "/r/r";
				$message_plain .= "Submitting a letter of recommendation online ensures advanced processing of graduate applications.";
				$message_plain .= "/r/r";
				$message_plain .= "Thank you for supporting the University of Maine's graduate mission!";
				$message_plain .= "
				The University of Maine, Orono, Maine 04469
				(207) 581-3291
				A Member of the University of Maine System";
				
				mail(filter_var($xref['reference_email'], FILTER_SANITIZE_EMAIL), $subject, $message, $headers);
			}
		}
	}
?>