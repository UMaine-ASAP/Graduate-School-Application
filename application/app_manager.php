<?php
	include_once "libs/database.php";
	include_once "libs/corefuncs.php";
	include_once "libs/variables.php";
	include_once "templates/template.php";

	//*********************************************************************************************
	// Determine User and page_id
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:pages/login.php");
	
	//Eventually have it find first not completed page;
	$page_id = ($_GET['form_id'])?$_GET['form_id']:2; //2 is page one


	//*********************************************************************************************
	// Open database link
	//*********************************************************************************************
	$db = new Database();
	$db->connect();
	
	
	//*********************************************************************************************
	//redirect to lockout page if user has submitted an application SMB 1/29/10 10:44AM
	//*********************************************************************************************
	$sub = $db->query("SELECT applicants.has_been_submitted FROM applicants WHERE applicants.applicant_id = %d",  $user);
	//redirect to lockout page if app has been submitted, remove if the code above is used in the future
	// if($sub[0][0] == 1){
	// 		header("location:lockout.php");
	// 	}
	
	if($sub[0][0] == 1){
		header("location:./pages/lockout.php");
	}
	
	//*********************************************************************************************
	// Test Submission and Verify
	//*********************************************************************************************
	if($_POST['submit_app']) {
		session_start();
		$_SESSION['submitted'] = true;
	}
	
	//*********************************************************************************************
	// Test Submission and Verify
	//*********************************************************************************************
	if($_POST['submit_app'] || $_GET['warning'] || $_SESSION['submitted']){
		session_write_close();
		$error_list = "";
		$error_num = 0;


		//*********************************************************************************************
		// Personal Information
		//*********************************************************************************************
		
		//get required fields for personal information
		$qry  = "";
		$qry .= "SELECT applicants.given_name, applicants.family_name, applicants.permanent_addr1, ";
		$qry .= "applicants.permanent_city, applicants.permanent_state, applicants.permanent_postal, ";
		$qry .= "applicants.permanent_country, applicants.primary_phone, applicants.country_of_citizenship, ";
		$qry .= "applicants.us_state, applicants.residency_status, applicants.email ";
		$qry .= "FROM applicants ";
		$qry .= "WHERE applicants.applicant_id = %d ";
		$qry .= "LIMIT 1";
		$personal_query = $db->query($qry, $user);
		$personal = $personal_query[0];

		//do form verification for personal information
		if(!$personal["given_name"]) {
			$error_list .= "<li>You did not enter a First Name on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["family_name"]) {
			$error_list .= "<li>You did not enter a Last Name on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["permanent_addr1"]) {
			$error_list .= "<li>You did not enter your Permanent Address on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["permanent_city"]) {
			$error_list .= "<li>You did not enter the City of your Permanent Address on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["permanent_state"]) {
			$error_list .= "<li>You did not enter the State of your Permanent Address on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["permanent_postal"]) {
			$error_list .= "<li>You did not enter the Postal Code of your Permanent Address on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["permanent_country"]) {
			$error_list .= "<li>You did not enter the Country of your Permanent Address on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
//		if(!$personal["primary_phone"]) {
//			$error_list .= "<li>You did not enter a Primary Phone Number on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
//			$error_num++;
//		}
		if(!$personal["email"]) {
			$error_list .= "<li>You did not enter an Email Address on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["country_of_citizenship"]) {
			$error_list .= "<li>You did not enter your Country of Citizenship on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["us_state"]) {
			$error_list .= "<li>You did not enter your Legal Residence on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}
		if(!$personal["residency_status"]) {
			$error_list .= "<li>You did not enter your Residency Status on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
			$error_num++;
		}


		//*********************************************************************************************
		// International 
		//*********************************************************************************************
		
		//get required fields for international
		$qry  = "";
		$qry .= "SELECT applicants.international ";
		$qry .= "FROM applicants ";
		$qry .= "WHERE applicants.applicant_id = %d ";
		$qry .= "LIMIT 1";
		$international_query = $db->query($qry, $user);
		$international = $international_query[0];

		//do form verification for personal information
		if(!is_numeric($international["international"])) {
			$error_list .= "<li>You did not select if you are a US Citizen on the <a class = 'error_link' href = 'app_manager.php?form_id=3'>International Page</a>.</li>";
			$error_num++;
		}


		//*********************************************************************************************
		// Educational History 
		//*********************************************************************************************
		
		//get required fields for educational history
		$qry  = "";
		$qry .= "SELECT applicants.prev_um_app, applicants.disciplinary_violation, applicants.criminal_violation ";
		$qry .= "FROM applicants ";
		$qry .= "WHERE applicants.applicant_id = %d ";
		$qry .= "LIMIT 1";
		$history_query = $db->query($qry, $user);
		$history = $history_query[0];

		//do form verification for educational history
		if(!is_numeric($history["prev_um_app"])) {
			$error_list .= "<li>Enter whether you have previously applied to the University of Maine on the <a class= 'error_link' href = 'app_manager.php?form_id=4'>Educational History page</a> must be selected.</li>";
			$error_num++;
		}
		if(!is_numeric($history["disciplinary_violation"])) {
			$error_list .= "<li>A Disciplinary Violations option on the <a class= 'error_link' href = 'app_manager.php?form_id=4'>Educational History page</a> must be selected.</li>";
			$error_num++;
		}
		if(!is_numeric($history["criminal_violation"])) {
			$error_list .= "<li>A Criminal Violations option on the <a class= 'error_link' href = 'app_manager.php?form_id=4'>Educational History page</a> must be selected.</li>";
			$error_num++;
		}


		//*********************************************************************************************
		// Educational Objectives 
		//*********************************************************************************************
		
		//get required fields for educational objectives
		$qry  = "";
		$qry .= "SELECT appliedprograms.student_type, appliedprograms.start_semester, appliedprograms.start_year, ";
		$qry .= "appliedprograms.academic_program, appliedprograms.attendance_load ";
		$qry .= "FROM appliedprograms ";
		$qry .= "WHERE appliedprograms.applicant_id = %d ";
		$objectives_query = $db->query($qry, $user);
		$objectives = $objectives_query[0];

		//do form verification for educational objectives
		if(!$objectives["academic_program"]) {
			$error_list .= "<li>An academic program of study on the <a class= 'error_link' href = 'app_manager.php?form_id=5'>Objective page</a> must be selected.</li>";
			$error_num++;
		}
		if(!$objectives["student_type"]) {
			$error_list .= "<li>You did not enter your student type on the <a class= 'error_link' href = 'app_manager.php?form_id=5'>Objective page</a> must be selected.</li>";
			$error_num++;
		}
		if(!$objectives["start_semester"]) {
			$error_list .= "<li>Start semester on the <a class= 'error_link' href = 'app_manager.php?form_id=5'>Objective page</a> must be selected.</li>";
			$error_num++;
		}
		if(!$objectives["start_year"]) {
			$error_list .= "<li>Start year on the <a class= 'error_link' href = 'app_manager.php?form_id=5'>Objective page</a> must be selected.</li>";
			$error_num++;
		}

		if(!$objectives["attendance_load"]) {
			$error_list .= "<li>You did not enter your Attendance Load on the <a class= 'error_link' href = 'app_manager.php?form_id=5'>Objective page</a> must be selected.</li>";
			$error_num++;
		}

		//*********************************************************************************************
		// Letters of Recommendation
		//*********************************************************************************************
		
		//get required fields for letters of recommendation
		$qry  = "";
		$qry .= "SELECT applicants.waive_view_rights, applicants.reference1_first, applicants.reference1_last, ";
		$qry .= "applicants.reference1_phone, applicants.reference1_online, applicants.reference2_first, ";
		$qry .= "applicants.reference2_last, applicants.reference2_phone, applicants.reference2_online, ";
		$qry .= "reference1_email, reference2_email ";
		$qry .= "FROM applicants ";
		$qry .= "WHERE applicants.applicant_id = %d ";
		$qry .= "LIMIT 1";
		$recommendations_query = $db->query($qry, $user);
		$recommendations = $recommendations_query[0];

		//do form verification for letters of recommendation
		if(!is_numeric($recommendations["waive_view_rights"])) {
			$error_list .= "<li>You did not select a Waive Viewing Rights option on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}
		if(!$recommendations["reference1_first"]) {
			$error_list .= "<li>You did not enter a First Name for your first reference on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}
		if(!$recommendations["reference1_last"]) {
			$error_list .= "<li>You did not enter a Last Name for your first reference on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}
		if(!$recommendations["reference1_phone"]) {
			$error_list .= "<li>You did not enter a Phone Number for your first reference on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}
		if($recommendations["reference1_online"] == 1 && !$recommendations["reference1_email"]) {
			$error_list .= "<li>If your reference will submit online, you must enter an Email Address on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}
		if(!$recommendations["reference2_first"]) {
			$error_list .= "<li>You did not enter a First Name for your second reference on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}
		if(!$recommendations["reference2_last"]) {
			$error_list .= "<li>You did not enter a Last Name for your second reference on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}
		if(!$recommendations["reference2_phone"]) {
			$error_list .= "<li>You did not enter a Phone Number for your second reference on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}
		if($recommendations["reference2_online"] == 1 && !$recommendations["reference2_email"]) {
			$error_list .= "<li>If your reference will submit online, you must enter an Email Address on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
			$error_num++;
		}


		//*********************************************************************************************
		// Build Error List, if any
		//*********************************************************************************************
		if($error_num >= 1) {
			$error_list = "<ul id='error_list'>".$error_list."</ul>";

			if($error_num == 1) {
				$error_list .= "<div>There was <b>1</b> problem with this submission.</div>";
			} else {
				$error_list .= "<div>There were <b>{$error_num}</b> problems with this submission.</div>";
			}
		}

		//Redirect if complete
		if($error_num == 0 && !$error_list){ 
			session_start();
			unset($_SESSION['submitted']);
			session_write_close();
			header("location:submission_manager.php");
		};		

	}

	//*********************************************************************************************
	// Start Building Page Content
	//*********************************************************************************************
	$app_manager_content = new Template();
	$app_manager_content->changeTemplate("templates/page_app_manager.tpl");
	
	//Test database for user's existence
	$result = $db->query("SELECT DISTINCT applicant_id FROM progress WHERE applicant_id=%d", $user);	
	
	//If user is new create section progess values
	if(!$result) {
		$sections = $db->query("SELECT * FROM structure WHERE include=1 ORDER BY `order`");
		$sectionCount = count($sections);
		for($i = 0; $i < $sectionCount ;$i++) {
			$db->iquery("INSERT INTO progress VALUES(%d, %s, 'INCOMPLETE','') ", $user, $sections[$i]['id']);
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
		// highlight the current page in the sidebar
		$isection_replace['SECTION_HERE'] = ($page_id == $isection['structure_id'])?'here':'';
		$isection_content->changeArray($isection_replace);
		$section_content .= $isection_content->parse();
	}
	
	//*********************************************************************************************
	// Build Form
	//*********************************************************************************************


	if($page_id){
		$result = $db->query("SELECT path FROM structure WHERE id=%d", $page_id);
		$build_form = new Template();
		$build_form->changeTemplate($result[0]['path']);
		$replace = array();
		
		//Check if data is already entered and populate fields
		$applicant_data = $db->query("SELECT * FROM applicants WHERE applicants.applicant_id=%d LIMIT 1", $user);
		$applicant_data = $applicant_data[0];
		foreach($applicant_data as $id_key => $form_value) {
		
			if($id_key == "social_security_number") {

				$ssn = $db->query("SELECT AES_DECRYPT(social_security_number, '%s') FROM applicants WHERE applicants.applicant_id=%d LIMIT 1", $GLOBALS['key'], $user);
				$ssn = $ssn[0][0];
				$form_value = $ssn;
			}

			$key_terms = explode("_",$id_key);

			if($key_terms[1] == "repeatable") {
				// Code to make multiples form elements
				$tableName = $key_terms[0];
				$replace[strtoupper($tableName)."_TABLE_NAME"] = $tableName;
				$replace[strtoupper($tableName)."_TEMPLATE_PATH"] = "templates/".$tableName."_repeatable.php";
				$replace[strtoupper($tableName)."_LIST"] = $tableName."_list";
				
				//For each element to be drawn
				$repeat_data = $db->query("SELECT %s_id FROM `%s` WHERE applicant_id=%d", $tableName, $tableName, $user);
				if(count($repeat_data) == 0 AND $tableName != "extrareferences")
					$db->iquery("INSERT INTO `%s` (applicant_id, %s_id) VALUES (%d, 1)", $tableName, $tableName, $user);		
				$repeat_data = $db->query("SELECT %s_id FROM `%s` WHERE applicant_id=%d", $tableName, $tableName, $user);	
				
				$repeat_count = count($repeat_data);
				$replace[strtoupper($tableName)."_COUNT"] = $repeat_count;
				$repeatable_element = '';
				
				for($i = 0; $i < $repeat_count;$i++) {
					$repeat_content = new Template();
					$repeat_content->changeTemplate($replace[strtoupper($tableName)."_TEMPLATE_PATH"]);
					$data = $db->query("SELECT * FROM `%s` WHERE applicant_id=%d AND %s_id=%d", $tableName, $user, $tableName, $repeat_data[$i][0]);
					$data = $data[0];

					foreach($data as $sub_id_key => $subvalue) {
						if(!is_numeric($id_key)) $repeat_replace[strtoupper($sub_id_key)] = $subvalue;
					}
					
					//Replace -> Parse -> Render Repeatable Content
					$repeat_replace['INDEX'] = $data[$tableName.'_id'];
					$repeat_replace['TABLE_NAME'] = $tableName;
					$repeat_replace['COUNT_INDEX'] = $i+1;
					$repeat_content->changeArray($repeat_replace);
					$repeatable_element .= $repeat_content->parse();					
				}
				
				//Replace Form Elements
				$replace[strtoupper($id_key)] = $repeatable_element;
			}else {
				if(!is_numeric($id_key)) $replace[strtoupper($id_key)] = $form_value; 
			}
		}
		
		//Replace -> Parse -> Render iSection Content
		$replace['USER'] = $user;

		$build_form->changeArray($replace);
		$form_content = $build_form->parse();
	} else {
		$form_content = "Please select a page.";
	}
	
	//*********************************************************************************************
	// Replace -> Parse -> Render Final Page Content
	//*********************************************************************************************
	$amc_replace = array();
	$amc_replace['TITLE'] = "UMaine Graduate Application";
	$amc_replace['ID'] = $user;
	$amc_replace['NAME'] =  $db->getFirst("SELECT given_name FROM applicants WHERE applicant_id=%d", $user);
	$amc_replace['NAME'] = ($amc_replace['NAME'])?", ".$amc_replace['NAME']:"";
	$amc_replace['EMAIL'] =  $db->getFirst("SELECT login_email FROM applicants WHERE applicant_id=%d", $user);
	$amc_replace['WARNINGS'] = ($error_list)?$error_list:"Please submit all information that you can.<br />Press the &ldquo;Review Application&rdquo; button when you are ready.";
	$amc_replace['SECTION_CONTENT'] = $section_content;
	$amc_replace['USER'] = $user;
	$amc_replace['FORM'] = $form_content;
	$app_manager_content->changeArray($amc_replace);
	print $app_manager_content->parse();
	
	
	//*********************************************************************************************
	// Close database link
	//*********************************************************************************************
	$db->close();
?>
