<?php
	include_once "libs/database.php";
	include_once "libs/template.php";
	include_once "libs/corefuncs.php";
	include_once "libs/validator.php";

	//*********************************************************************************************
	// Database Login
	//*********************************************************************************************
	$db = new Database();
	$db->connect();


	//*********************************************************************************************
	// Validate Application and redirect if not complete
	//*********************************************************************************************
	$error_list = get_error_list($db);

	//Redirect if not complete
	if($error_list != "") {
		session_start();
		$_SESSION['submitted'] = TRUE;
		session_write_close();
		header("location:app_manager.php");
	}
	
	//*********************************************************************************************
	// Determine User
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:pages/login.php");
	
	//*********************************************************************************************
	//redirect to lockout page if user has submitted an application SMB 1/29/10 10:44AM
	//*********************************************************************************************
	$sub = $db->query("SELECT applicants.has_been_submitted FROM applicants WHERE applicants.applicant_id = %d", $user);
	
	//if($sub[0][0] == 1) {
	//	header("location:lockout.php");
	//}
	
	if($sub[0][0] == 1){
		header("location:pages/lockout.php");	
	}
	//*********************************************************************************************
	// Test Submission and Verify
	//*********************************************************************************************
	if($_POST['final_submit_app']){
		//Send info to proxy
		//If okay go to payment
	}
	
	//*********************************************************************************************
	// Start Building Page Content
	//*********************************************************************************************
	$app_manager_content = new Template();
	$app_manager_content->changeTemplate("templates/page_sub_manager.tpl");
	
	//Test database for user's existence
	$result = $db->query("SELECT DISTINCT applicant_id FROM progress WHERE applicant_id=%d", $user);	
	
	//If user is new create section progess values
	if(!$result) {
		$sections = $db->query("SELECT * FROM structure");
		$sectionCount = count($sections);
		for($i = 0; $i < $sectionCount ;$i++) {
			$db->iquery("INSERT INTO progress VALUES(%d, %d,'INCOMPLETE','') ", $user, $sections[$i]['id']);
		}
	}
	
	//*********************************************************************************************
	// Build Sidebar
	//*********************************************************************************************
	$app_progress = $db->query("SELECT * FROM progress,structure WHERE progress.applicant_id=%d AND structure.id=progress.structure_id", $user);
	
	$app_status = $app_progress[0];		//Store application progress
	unset($app_progress[0]);		//Pop application progress off render stack
	
	$section_content = '';
	//Create each section
	foreach($app_progress as $isection) {
		$isection_content = new Template();
		$isection_content->changeTemplate("templates/node_isection.tpl");
						
		//Replace -> Parse -> Render iSection Content
		$isection_replace = array();
		$isection_replace['FORM_ID'] = $isection['structure_id'];
		$isection_replace['SECTION_NAME'] = $isection['name'];
		$isection_replace['SECTION_STATUS'] = $isection['status'];
		$isection_replace['SECTION_IMAGE'] = '';
		$isection_replace['SECTION_NOTES'] = $isection['notes'];
		$isection_content->changeArray($isection_replace);
		$section_content .= $isection_content->parse();
	}
	
	//*********************************************************************************************
	//Build Programs and Required Personal Information   ***check for sql injection risks***
	//*********************************************************************************************
	
	//Query All Required Fields for Submission Page
	$qry = "SELECT applicants.given_name, applicants.family_name, applicants.permanent_addr1, applicants.permanent_addr2, ";
	$qry .= "applicants.permanent_city, applicants.permanent_state, applicants.permanent_postal , applicants.permanent_country ,applicants.primary_phone, ";
	$qry .= "applicants.email, appliedprograms.start_semester, appliedprograms.start_year, ";
	$qry .= "appliedprograms.academic_program, appliedprograms.appliedprograms_id ";
	$qry .= "FROM applicants,appliedprograms ";
	$qry .= "WHERE applicants.applicant_id = %d AND applicants.applicant_id = appliedprograms.applicant_id";
	
	$app_data = $db->query($qry, $user);
	$personal_data = $app_data[0];
	
	//Redirect back
	foreach ($app_data as $adata)
		foreach($adata as $f => $data)
			if(!$data && !$f == 'permanent_addr2')
				header("location:app_manager.php?warning=Your application is not complete.");
	
	//Build Required Personal Info
	$req_info_content = new Template();
	$req_info_content->changeTemplate("templates/section_required_info.tpl");
	$req_info_replace = array();
	foreach($personal_data as $pfield => $pvalue){
		if(!is_numeric($pfield))
			$req_info_replace[strtoupper($pfield)] = $pvalue;

		if($pvalue == "") {
			switch($pfield) {
				case "permanent_addr1":
					$req_info_replace[strtoupper($pfield)] = "[Address Missing]";
					break;
				case "permanent_postal":
					$req_info_replace[strtoupper($pfield)] = "[Zipcode Missing]";
					break;
				case "permanent_state":
					$req_info_replace[strtoupper($pfield)] = "[State Missing]";
					break;
				default:
					break;
			}
		}
	}
		
	//Replace -> Parse -> Render iSection Content
	$req_info_content->changeArray($req_info_replace);
	$req_info_section .= $req_info_content->parse();
				
	//retrieve application cost from database
	$cost = $db->query("SELECT first_program, additional_programs FROM application_cost");	
	$first_program = $cost[0]['first_program'];
	$additional_programs = $cost[0]['additional_programs'];
	
	//Build Degree List
	$flag=0;
	foreach($app_data as $app_program){
		$iprogram_content = new Template();
		$iprogram_content->changeTemplate("templates/node_sub_program.tpl");
		
		//Replace -> Parse -> Render iSection Content
		$program_replace = array();
		$program_replace['INDEX'] = $app_program['appliedprograms_id'];
		$program_replace['PROGRAM_NAME'] = $db->getFirst("SELECT description_app FROM um_academic WHERE academic_program='%s'", $app_program['academic_program']);
		$program_replace['PROGRAM_STATUS'] = ($flag==0)?"$". $first_program .".00":"$". $additional_programs .".00";
		$program_replace['PROGRAM_NOTES'] = $app_program['start_semester']." ".$app_program['start_year'];
		$iprogram_content->changeArray($program_replace);
		$program_content .= $iprogram_content->parse();
		
		$flag++;
	}
	
	//Calculate Total Cost	
	$total_cost = $first_program + $additional_programs * ($flag - 1);
	//update database application_fee_transaction_amount
	$db->iquery("UPDATE applicants SET application_fee_transaction_amount='%s' WHERE applicant_id=%d", $total_cost, $user);
	
	//*********************************************************************************************
	//Replace -> Parse -> Render Final Page Content
	//*********************************************************************************************
	$amc_replace = array();
	$amc_replace['TITLE'] = "UMaine Graduate Application";
	$amc_replace['ID'] = $user;
	$amc_replace['ONEUP'] = time();
	$amc_replace['NAME'] =  $db->getFirst("SELECT given_name FROM applicants WHERE applicant_id=%d", $user);
	$amc_replace['LAST_NAME'] =  $db->getFirst("SELECT family_name FROM applicants WHERE applicant_id=%d", $user);
	$amc_replace['EMAIL'] =  $db->getFirst("SELECT login_email FROM applicants WHERE applicant_id=%d", $user);
	//$amc_replace['WARNINGS'] = ($error_list)?$error_list:"Please submit all information that you can.<br />Press the \"Submit Application\" button when you are ready.";
	$amc_replace['SECTION_CONTENT'] = $section_content;
	$amc_replace['USER'] = $user;
	$amc_replace['PERSONAL_INFO'] = $req_info_section;
	$amc_replace['PROGRAM_LIST'] = $program_content;
	$amc_replace['PROGRAM_COUNT'] = $flag;
	$amc_replace['S'] = ($flag > 1)?"s":"";
	$amc_replace['TOTAL_COST'] = "$".number_format($total_cost,2);
	$amc_replace['TOTAL_AMT'] = number_format($total_cost,2);
	$amc_replace['DATE'] = date(c);

	$app_manager_content->changeArray($amc_replace);
	print $app_manager_content->parse();
	
	$db->close();
?>
