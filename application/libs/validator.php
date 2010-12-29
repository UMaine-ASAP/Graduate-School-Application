<?php
include_once "database.php";
include_once "corefuncs.php";

function get_error_list($db = null) {
	$error_list = "";


	//*********************************************************************************************
	// Open database link
	//*********************************************************************************************
	$db_given = $db != null;
	if(!$db_given) {
		$db = new Database();
		$db->connect();
	}

	//*********************************************************************************************
	// Determine User and page_id
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:pages/login.php");

	//*********************************************************************************************
	// Personal Information
	//*********************************************************************************************

	//get required fields for personal information
	$qry  = "";
	$qry .= "SELECT applicants.given_name, applicants.family_name, applicants.permanent_addr1, ";
	$qry .= "applicants.permanent_city, applicants.permanent_state, applicants.permanent_postal, ";
	$qry .= "applicants.permanent_country, applicants.primary_phone, applicants.country_of_citizenship, ";
	$qry .= "applicants.us_state, applicants.residency_status, applicants.email, applicants.date_of_birth ";
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
	if(!$personal["date_of_birth"]) {
		$error_list .= "<li>You did not enter a Birthday on the <a class = 'error_link' href = 'app_manager.php'>Personal Information Page</a>.</li>";
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
//	if(!$recommendations["reference1_phone"]) {
//		$error_list .= "<li>You did not enter a Phone Number for your first reference on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
//		$error_num++;
//	}
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
//	if(!$recommendations["reference2_phone"]) {
//		$error_list .= "<li>You did not enter a Phone Number for your second reference on the <a class= 'error_link' href='app_manager.php?form_id=6'>Recommendations Page</a>.</li>";
//		$error_num++;
//	}
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

	//*********************************************************************************************
	// Close database link
	//*********************************************************************************************
	if(!$db_given) {
		$db->close();
	}

	return $error_list;
}

?>
