<?php
	// Libraries
	include_once "libs/database.php";
	include_once "libs/template.php";
	include_once "libs/corefuncs.php";
	include_once "libs/validator.php";

	// Controllers
	include_once "controllers/application.php";
	include_once "controllers/applicant.php";

	//*********************************************************************************************
	// Determine User
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:pages/login.php");

	$applicant = Applicant::getActiveApplicant();
	$application = Application::getActiveApplication();

	$db = Database::getInstance();

	//*********************************************************************************************
	// Validate Application and redirect if not complete
	//*********************************************************************************************
	$error_list = get_error_list($db);

	//Redirect if not complete
	if($error_list != "") 
	{
		if( !isset($_SESSION) ) 
		{ 
			session_start(); 
		}
		$_SESSION['submitted'] = TRUE;
		session_write_close();
		header("location:app_manager.php");
	}
	
	//*********************************************************************************************
	//redirect to lockout page if user has submitted an application SMB 1/29/10 10:44AM
	//*********************************************************************************************

	if( $application->hasBeenSubmitted() )
	{
		header("location:pages/lockout.php");	
	}
	
	//*********************************************************************************************
	// Start Building Page Content
	//*********************************************************************************************
	
	//Test database for user's existence
	$result = $db->query("SELECT DISTINCT applicant_id FROM progress WHERE applicant_id=%d", $user);	
	
	//If user is new create section progess values
	if(!$result) 
	{
		$sections = $db->query("SELECT * FROM structure");
		$sectionCount = count($sections);
		for($i = 0; $i < $sectionCount ;$i++) 
		{
			$db->iquery("INSERT INTO progress VALUES(%d, %d,'INCOMPLETE','') ", $user, $sections[$i]['id']);
		}
	}
	
	//*********************************************************************************************
	// Build Sidebar
	//*********************************************************************************************
	$app_progress = $db->query("SELECT * FROM progress,structure WHERE progress.applicant_id=%d AND structure.id=progress.structure_id", $user);
	
	$app_status = $app_progress[0];	//Store application progress
	unset($app_progress[0]);		//Pop application progress off render stack
	
	$section_content = '';

	//Create each section
	foreach($app_progress as $isection) 
	{
						
		//Replacement Data
		$isection_replace = array();

		$isection_replace['PAGE_ID'] 		= $isection['structure_id'];
		$isection_replace['SECTION_NAME'] 	= $isection['name'];
		$isection_replace['SECTION_STATUS'] = $isection['status'];
		$isection_replace['SECTION_IMAGE'] 	= '';
		$isection_replace['SECTION_NOTES'] 	= $isection['notes'];

		$section_content .= template_parse("templates/node_isection.tpl", $isection_replace);
	}
	
	//*********************************************************************************************
	//Build Programs and Required Personal Information   ***check for sql injection risks***
	//*********************************************************************************************
	
	//Query All Required Fields for Submission Page
	$qry  = "SELECT applicants.given_name, applicants.family_name, applicants.permanent_addr1, applicants.permanent_addr2, ";
	$qry .= "applicants.permanent_city, applicants.permanent_state, applicants.permanent_postal , applicants.permanent_country ,applicants.primary_phone, ";
	$qry .= "applicants.email, appliedprograms.start_semester, appliedprograms.start_year, ";
	$qry .= "appliedprograms.academic_program, appliedprograms.appliedprograms_id ";
	$qry .= "FROM applicants,appliedprograms ";
	$qry .= "WHERE applicants.applicant_id = %d AND applicants.applicant_id = appliedprograms.applicant_id";
	
	$app_data = $db->query($qry, $user);
	$personal_data = $app_data[0];
	
	//Redirect back
	foreach ($app_data as $adata) 
	{
		foreach($adata as $f => $data) 
		{
			if(!$data && !$f == 'permanent_addr2') 
			{
				header("location:app_manager.php?warning=Your application is not complete.");
			}
		}
	}
	
	//Build Required Personal Info
	$req_info_replace = array();

	if( is_array($personal_data) ) 
	{

		foreach($personal_data as $pfield => $pvalue)
		{
			if(!is_numeric($pfield))
				$req_info_replace[strtoupper($pfield)] = $pvalue;
	
			if($pvalue == "") 
			{
				switch($pfield) 
				{
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
	}
	
	// Get Required information content
	$req_info_section = template_parse("templates/section_required_info.tpl", $req_info_replace);
				
	//retrieve application cost from database
	$cost = $db->query("SELECT first_program, additional_programs FROM application_cost");	

	$first_program 		 = $cost[0]['first_program'];
	$additional_programs = $cost[0]['additional_programs'];
	
	//Build Degree List
	$number_programs_applied_to = 0;
	$program_content = "";

	foreach($app_data as $app_program)
	{
		// Replacement Data
		$program_replace = array();

		$program_replace['INDEX'] 			= $app_program['appliedprograms_id'];
		$program_replace['PROGRAM_NAME'] 	= $db->getFirst("SELECT description_app FROM um_academic WHERE academic_program='%s'", $app_program['academic_program']);
		$program_replace['PROGRAM_STATUS'] 	= ($number_programs_applied_to==0)?"$". $first_program .".00":"$". $additional_programs .".00";
		$program_replace['PROGRAM_NOTES'] 	= $app_program['start_semester']." ".$app_program['start_year'];
		
		// Add program information
		$program_content .= template_parse("templates/node_sub_program.tpl", $program_replace);

		$number_programs_applied_to++;
	}
	
	//Calculate Total Cost	
	$total_cost = $first_program + $additional_programs * ($number_programs_applied_to - 1);
	//update database application_fee_transaction_amount
	$db->iquery("UPDATE applicants SET application_fee_transaction_amount='%s' WHERE applicant_id=%d", $total_cost, $user);
	
	//*********************************************************************************************
	//Render Final Page Content
	//*********************************************************************************************

	/** Replacement Data **/
	$amc_replace = array();

	// Top Level Data
	$amc_replace['TITLE'] 		 = "UMaine Graduate Application";
	$amc_replace['ID'] 			 = $applicant->getID();
	$amc_replace['GRADHOMEPAGE'] = $GLOBALS['graduate_homepage'];
	$amc_replace['FAVICON'] 	 = $GLOBALS['grad_images'] . "grad_favicon.ico";
	$amc_replace['SERVER_NAME']  = $GLOBALS['server_name'];

	// User Data
	$amc_replace['USER'] 			= $applicant->getID();
	$amc_replace['NAME'] 			= $applicant->getGivenName();
	$amc_replace['LAST_NAME'] 		= $applicant->getFamilyName();
	$amc_replace['EMAIL'] 			= $applicant->getEmail();
	$amc_replace['PERSONAL_INFO'] 	= $req_info_section;

	// Page Content
	$amc_replace['SECTION_CONTENT'] = $section_content;

	// Program and Cost
	$amc_replace['PROGRAM_LIST'] 	= $program_content;
	$amc_replace['PROGRAM_COUNT'] 	= $number_programs_applied_to;
	$amc_replace['TOTAL_COST'] 		= "$".number_format($total_cost,2);
	$amc_replace['TOTAL_AMT'] 		= number_format($total_cost,2);

	// Extra
	$amc_replace['ONEUP'] 	= time();
	$amc_replace['S'] 		= ($number_programs_applied_to > 1)?"s":"";
	$amc_replace['DATE'] 	= date('F j, Y');

	
	/** Render Data **/
	print template_parse("templates/page_sub_manager.tpl", $amc_replace);

	$db->close();

