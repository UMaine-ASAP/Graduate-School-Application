<?php
	include_once "../application/libs/corefuncs.php";
	include_once "../application/libs/database.php";
	include_once "../application/libs/email_reference.php";
		
	// Finds user id from session variable
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:pages/login.php");
	
	//Normal references	
	sendReferenceEmail("reference1");
	sendReferenceEmail("reference2");
	sendReferenceEmail("reference3");	


	//Extra References
	$db = new Database(); 
	$db->connect();

	$xrefarray = $db->query("SELECT * FROM extrareferences WHERE applicant_id = %d", $user);	 
	
	if(count($xrefarray) >= 1){
		for($xref_index = 1; $xref_index <= count($xrefarray); $xref_index++){
			sendReferenceEmail("xref" . $xref_index);
		}
	}
?>
