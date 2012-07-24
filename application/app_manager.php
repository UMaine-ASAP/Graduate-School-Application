<?php
	include_once "libs/database.php";
	include_once "libs/corefuncs.php";
	include_once "libs/variables.php";
	include_once "libs/validator.php";
	include_once "templates/template.php";

	// Controllers
	include_once "controllers/application.php";

	//*********************************************************************************************
	// Determine User and Current Page
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:pages/login.php");
	
	// Get current page number
	//@TODO: Eventually have it find first not completed page;
	$page_id = isset($_GET['page']) ? $_GET['page'] : 2; //2 is page one

	$application = Application::getActiveApplication();
	//*********************************************************************************************
	// Open database link
	//*********************************************************************************************
	$db = Database::getInstance();
	
	//*********************************************************************************************
	//redirect to lockout page if user has submitted an application SMB 1/29/10 10:44AM
	//*********************************************************************************************

	if( $application->hasBeenSubmitted() ){
		header("location:./pages/lockout.php");
	}
	
	//*********************************************************************************************
	// Test Submission and Verify
	//*********************************************************************************************
	if( isset($_POST['submit_app']) && $_POST['submit_app']) {
		if( !isset($_SESSION) ) { session_start(); }
		$_SESSION['submitted'] = true;
	}
	
	//*********************************************************************************************
	// Test Submission and Verify
	//*********************************************************************************************
	if(    isset($_POST['submit_app']) 	 && $_POST['submit_app'] 
		|| isset($_GET['warning']) 		 && $_GET['warning'] 
		|| isset($_SESSION['submitted']) && $_SESSION['submitted']
		){

		$error_list = get_error_list($db);

		//Redirect if complete
		if($error_list == "") {
			if( !isset($_SESSION) ) { session_start(); }
			unset($_SESSION['submitted']);
			session_write_close();
			header("location:submission_manager.php");
		}
	}

	//*********************************************************************************************
	// Start Building Page Content
	//*********************************************************************************************
	$app_manager_content = new Template();
	$app_manager_content->changeTemplate("templates/page_app_manager.tpl");
	
	//Test database for user's existence
	$result = $db->query("SELECT DISTINCT applicant_id FROM progress WHERE applicant_id=%d", $user);	

	//If user is new, create section progess values
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

		$isection_replace['PAGE_ID'] 		= $isection['structure_id'];
		$isection_replace['SECTION_NAME'] 	= $isection['name'];
		$isection_replace['SECTION_STATUS'] = $isection['status'];
		$isection_replace['SECTION_IMAGE'] 	= '';
		$isection_replace['SECTION_NOTES'] 	= $isection['notes'];
		$isection_replace['SECTION_HERE'] 	= ($page_id == $isection['structure_id'])?'here':''; // highlight the current page in the sidebar

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
		
		if(is_array($applicant_data)) {
			foreach($applicant_data as $id_key => $form_value) {
			
				if($id_key == "social_security_number") {
					$key = $GLOBALS['key'];
					$ssn = $db->query("SELECT AES_DECRYPT(social_security_number, '$key') AS social_security_number FROM applicants WHERE applicants.applicant_id=%d LIMIT 1", $user);
					$ssn = $ssn[0][0];
					$form_value = $ssn;
				}
	
				$key_terms = explode("_",$id_key);
	
				if(isset($key_terms[1]) && $key_terms[1] == "repeatable") {

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
			} //end foreach
		} // end of if is_array

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

	// Top Level
	$amc_replace['TITLE'] 		 = "UMaine Graduate Application";
	$amc_replace['GRADHOMEPAGE'] = $GLOBALS['graduate_homepage'];
	$amc_replace['FAVICON'] 	 = $GLOBALS['grad_images'] . "grad_favicon.ico";

	// User Data
	$amc_replace['ID'] 		= $user;
	$amc_replace['NAME'] 	=  $db->getFirst("SELECT given_name FROM applicants WHERE applicant_id=%d", $user);
	$amc_replace['NAME'] 	= ($amc_replace['NAME'])?", ".$amc_replace['NAME']:"";
	$amc_replace['EMAIL'] 	=  $db->getFirst("SELECT login_email FROM applicants WHERE applicant_id=%d", $user);
	
	// Application Data
	$amc_replace['WARNINGS'] 		= ( isset($error_list) && $error_list != '' ) ? $error_list : "Please submit all information that you can.<br />Press the &ldquo;Review Application&rdquo; button when you are ready.";
	$amc_replace['SECTION_CONTENT'] = $section_content;
	$amc_replace['USER'] 			= $user;
	$amc_replace['FORM'] 			= $form_content;

	// Extra Data
	$amc_replace['SERVER_NAME'] 	= $GLOBALS['server_name'];
	$amc_replace['ESSAY_NAME'] 		= $db->getFirst("SELECT essay_file_name FROM applicants WHERE applicant_id=%d", $user);
	$amc_replace['RESUME_NAME'] 	= $db->getFirst("SELECT resume_file_name FROM applicants WHERE applicant_id=%d", $user);
	$date = getDate();
	$amc_replace['FIRST_START_YEAR'] = $date['year'];


	// Output page	
	$app_manager_content->changeArray($amc_replace);
	print $app_manager_content->parse();
	
	
	//*********************************************************************************************
	// Close database link
	//*********************************************************************************************
	$db->close();
?>
