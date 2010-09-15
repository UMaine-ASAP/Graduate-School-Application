<?php
	include_once "application/libs/database.php";
	include_once "forms/signin/includes/corefuncs.php";
	
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:/forms/signin/");
	$db = new Database();
	$db->connect();
	$qry  = "";
	$qry .= "SELECT given_name,family_name,permanent_addr1,permanent_city,permanent_country,email,start_semester,start_year,program_of_study ";
	$qry .= "FROM applicants WHERE applicant_id = %d";
	$result = $db->query($qry, $user);
	if(count($result) == 1) $result = $result[0];
	else die("ERROR: Cannot find user");
	
	//personal page
	$given_name = $result['given_name'];
	$family_name = $result['family_name'];
	$permanent_addr1 = $result['permanent_addr1'];
	$permanent_city = $result['permanent_city'];
	$permanent_country = $result['permanent_country'];
	$email = $result['email'];
	
	//objectives page
	$start_semester = $result['start_semester'];
	$start_year = $result['start_year'];
	$program_of_study = $result['program_of_study'];
		
	
	if (empty($given_name) || empty($family_name) || empty($permanent_addr1) || empty($permanent_city) || empty($permanent_country) || empty($email) || empty($start_semester) || empty($start_year) || empty($program_of_study)){
		// errors
		if(empty ($given_name)) echo "You did not enter a First Name on the Personal Information Page.<br />";
		if(empty ($family_name)) echo "You did not enter a Last Name on the Personal Information Page.<br />";
		if(empty ($permanent_addr1)) echo "You did not enter your Permanent Address on the Personal Information Page.<br />";
		if(empty ($permanent_city)) echo "You did not enter the City of your Permanent Address on the Personal Information Page.<br />";
		if(empty ($permanent_country)) echo "You did not enter the Country of your Permanent Address on the Personal Information Page.<br />";
		if(empty ($primary_phone)) echo "You did not enter a Primary Phone Number on the Personal Information Page.<br />";
		if(empty ($email)) echo "You did not enter an Email Address on the Personal Information Page.<br />";
		if(empty ($start_semester)) echo "Start semester on the Objective page must be selected.<br />";
		if(empty ($start_year)) echo "Start year on the Objective page must be selected.<br />";
		if(empty ($program_of_study)) echo "Program of study on the Objective page must be selected.<br />";
	
	}
	
	$db->close();
?>
