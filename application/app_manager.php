<?php
// Libraries
include_once "libs/variables.php";
include_once "libs/corefuncs.php";
include_once "libs/database.php";
include_once "libs/template.php";
include_once "libs/validator.php";


// Controllers
include_once "controllers/applicant.php";
include_once "controllers/application.php";

redirect_Unauthorized_User("../application/pages/login.php");

//*********************************************************************************************
// Determine User and Current Page
//*********************************************************************************************
$applicant = Applicant::getActiveApplicant();
$user = $applicant->getID();


// Get current page number (2 is page one)
$page_id = isset($_GET['page']) ? $_GET['page'] : 2;

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
// Test for Submission and Verify
//*********************************************************************************************
if( isset($_POST['submit_app']) || isset($_GET['warning']) || isset($_SESSION['submitted']) )
	if( !isset($_SESSION) ) { session_start(); }
	$_SESSION['submitted'] = true;

	$error_list = get_error_list($db);

	//Redirect if complete
	if($error_list == "") {
		unset($_SESSION['submitted']);
		session_write_close();
		header("location:submission_manager.php");
	}
}

//*********************************************************************************************
// Start Building Page Content
//*********************************************************************************************

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

//@TODO: Should check if the page id is actually a valid id
if($page_id){

	$replace = array(); // Array used to store form variables to parse

	// Get applicant Data
	$applicant_data = $db->query("SELECT * FROM applicants WHERE applicants.applicant_id=%d LIMIT 1", $user);
	$applicant_data = $applicant_data[0];
	
	if( is_array($applicant_data) ) {

		// Pass all applicant data to template - @TODO: Not the most efficient way of doing this ...
		foreach($applicant_data as $id_key => $form_value) {
			// Exclude numeric fields
			if ( is_numeric($id_key) ) continue;

			// Decrypt Social Security Number
			if($id_key == "social_security_number") {
				$key = $GLOBALS['key'];
				$ssn = $db->query("SELECT AES_DECRYPT(social_security_number, '$key') AS social_security_number FROM applicants WHERE applicants.applicant_id=%d LIMIT 1", $user);
				$form_value = $ssn[0][0];
			}

			// Check if this is a repeatable field
			$key_terms = explode("_",$id_key);
			$isRepeatable = isset($key_terms[1]) && $key_terms[1] == "repeatable";

			// Build form element for repeatable fields
			if( $isRepeatable ) {

				$tableName 		= $key_terms[0];
				$tableNameUpper = strtoupper($tableName);
				
				// Get Repeating data
				$repeat_data = $db->query("SELECT %s_id FROM `%s` WHERE applicant_id=%d ORDER BY %s_id DESC", $tableName, $tableName, $user, $tableName);
				$repeat_count = count($repeat_data);
				$repeatable_element = "";

				// Set a blank value if no values have been set yet
				if($repeat_count == 0 AND $tableName != "extrareferences") {
					$db->iquery("INSERT INTO `%s` (applicant_id, %s_id) VALUES (%d, 1)", $tableName, $tableName, $user);		
				}
								
				// Process each repeatable value
				for($i = 0; $i < $repeat_count; $i++) {
					// Get the data for this repeatable
					$data = $db->query("SELECT * FROM `%s` WHERE applicant_id=%d AND %s_id=%d", $tableName, $user, $tableName, $repeat_data[$i][0]);
					$data = $data[0];

					/** Set template values **/
					$repeat_replace['INDEX'] 		= $data["${tableName}_id"]; // Store the current index
					$repeat_replace['TABLE_NAME'] 	= $tableName;				// Set the table name for reference
					$repeat_replace['COUNT_INDEX'] 	= $i+1;						// Store the current count

					// Set the field values
					foreach($data as $repeatable_field_name => $value) {
						if( is_numeric($repeatable_field_name) ) continue; // Don't process numeric fields
						$repeat_replace[strtoupper($repeatable_field_name)] = $value;
					}

					// Add the parsed template
					$repeatable_element .= template_parse($replace["${tableNameUpper}_TEMPLATE_PATH"], $repeat_replace);					
				}
				
				// Set the output
				$replace['USER'] = $user;
				$replace["${tableNameUpper}_TABLE_NAME"] 	= $tableName;
				$replace["${tableNameUpper}_TEMPLATE_PATH"] = "templates/${tableName}_repeatable.php";
				$replace["${tableNameUpper}_LIST"] 			= "${$tableName}_list";
				$replace["${tableNameUpper}_COUNT"] 		= $repeat_count;

				$replace[strtoupper($id_key)] 				= $repeatable_element;

			} else { 
				// Process as a normal, nonrepeatable field (just use the field's value)
				$replace[strtoupper($id_key)] = $form_value;
			}
		} //end foreach
	} // end of if is_array

	// Process the form template
	$result = $db->query("SELECT path FROM structure WHERE id=%d", $page_id);
	$form_template_name = $result[0]['path'];

	$form_content = template_parse($form_template_name, $replace);
} else { // The page is not valid
	$form_content = "Please select a page.";
}

//*********************************************************************************************
// Render Final Page Content
//*********************************************************************************************

/** Replacement Data **/
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


/** Render Page	**/
print template_parse("templates/page_app_manager.tpl", $amc_replace);


//*********************************************************************************************
// Close database link
//*********************************************************************************************
$db->close();

