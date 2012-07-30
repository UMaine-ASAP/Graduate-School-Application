<?php
	// Libraries
	include_once "../libs/corefuncs.php";
	include_once "../libs/database.php";
	include_once "email_reference.php";
	
	// Controllers
	include_once "../controllers/applicant.php";

	// Check User
	redirect_Unauthorized_User("pages/login.php");

	$applicant = Applicant::getActiveApplicant();
	
	//Normal references	
	sendReferenceEmail("reference1");
	sendReferenceEmail("reference2");
	sendReferenceEmail("reference3");

	//Extra References
	$db = Database::getInstance();

	$xrefarray = $db->query("SELECT * FROM extrareferences WHERE applicant_id = %d", $applicant->applicant_id);
	
	if(count($xrefarray) >= 1){
		for($xref_index = 1; $xref_index <= count($xrefarray); $xref_index++){
			sendReferenceEmail("xref" . $xref_index);
		}
	}

	$db->close();

