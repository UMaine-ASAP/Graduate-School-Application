<?php
/**
 * MaineStreet Data File
 * 
 * Nightly dump (from Cron script) of data for Mainestreet importing
 * 
 * Each line is a tab delimited, predetermined, output of fields for a single completed application
 * 
 */

include_once "../application/libs/database.php";
include_once "../application/libs/variables.php";

/**
 * Map State Value
 * 
 * Conversion function for state value. 'IT' is the state code for international (used because state is a required field)
 */
function mapStateValue($value) {
	if($value == "IT") {
		return "";
	} else {
		return $value;
	}
}


/**
 * Mainestreet
 * 
 * Generates tab-delimited data for Mainestreet import for a single completed applicant
 * 
 * This file is fine-tuned for the import - don't change without versioning!
 * 
 * @authors: Lukas Jordan //edits: jonathan simpson.
 */
// 
function mainestreet($userid) {

	$db = new Database();
	$db->connect();
	$result = $db->query("SELECT * FROM applicants WHERE applicant_id = %d", $userid);
	$user = $result[0];

	/* -------------------- */
	/* Personal Data
	/* -------------------- */
	$temp  = $user['applicant_id']."\t";
	$temp .= $user['given_name']."\t";
	$temp .= $user['family_name']."\t";
	$temp .= $user['middle_name']."\t";
	$temp .= $user['suffix']."\t";
	$temp .= $user['permanent_addr1']."\t";
	$temp .= $user['permanent_addr2']."\t";
	$temp .= $user['permanent_city']."\t";
	$temp .= $user['permanent_country']."\t";
	$temp .= mapStateValue($user['permanent_state'])."\t";
	$temp .= $user['permanent_postal']."\t";
	$temp .= preg_replace('/[^\d]/', '', $user['primary_phone'])."\t";
	$temp .= $user['email']."\t";
	$temp .= $user['gender']."\t";
	$temp .= $user['date_of_birth']."\t";
	$temp .= $user['mailing_addr1']."\t";
	$temp .= $user['mailing_addr2']."\t";
	$temp .= $user['mailing_city']."\t";
	$temp .= mapStateValue($user['mailing_state'])."\t";
	$temp .= $user['mailing_country']."\t";
	$temp .= $user['mailing_postal']."\t";
	$temp .= $user['ethnicity_hispa']."\t";
	$temp .= $user['ethnicity_amind']."\t";
	$temp .= $user['ethnicity_asian']."\t";
	$temp .= $user['ethnicity_black']."\t";
	$temp .= $user['ethnicity_pacif']."\t";
	$temp .= $user['ethnicity_white']."\t";
	
	
	/* -------------------- */
	/* Social Security Number
	/* -------------------- */

	$key = $GLOBALS['key'];
	$ssn_result = $db->query("SELECT AES_DECRYPT(social_security_number, '%s') AS 'social_security_number' FROM applicants WHERE applicant_id = %d", $key, $userid);
	$ssn = $ssn_result[0]['social_security_number'];
	$user['social_security_number'] = $ssn;
	
	$ssnEx = explode("-", $user['social_security_number']);
	$ssnNew = "";
	foreach ($ssnEx as $key)
	{
		$ssnNew.= $key;  
	}
	$temp .= $ssnNew."\t";
	

	/* -------------------- */
	/* Application Submission Date
	/* -------------------- */

	$date_t = new DateTime($user['application_submit_date']);
	$new_date = date_format($date_t, 'm-d-Y');
	$appsubdate = explode("-", $new_date);

	$temp .= $appsubdate[0]."/".$appsubdate[1]."/".$appsubdate[2]."\t";
	

	/* -------------------- */
	/* Country Code
	/* -------------------- */
	if ($user['permanent_country']=='USA')
	{
		// USA
		$temp .= "1"."\t";
	} else {
		// International
		$temp .= "4"."\t";
	}


	/* -------------------- */
	/* Seeking Financial Aid
	/* -------------------- */

	$temp .= $user['desire_financial_aid']."\t";
	

	$altname = $user['alternate_name']."\t";
	$secondary_phone =  preg_replace('/[^\d]/', '', $user['secondary_phone'])."\t";


	/* -------------------- */
	/* Program of Study
	/* -------------------- */

	// finds multiple programs if applicable
	$result = $db->query("SELECT * FROM appliedprograms WHERE applicant_id = %d", $userid);
	$loop = count($result);
	if ($loop>4) {
		$loop = 4;
		$blanks = 0;
	} else {
		$blanks = 4-$loop;
	}
	
	$t = "";
	
	for ($a = 0; $a < $loop; $a++){
		$programresult = $db->query("SELECT academic_plan FROM um_academic WHERE academic_program = '%s'", $result[$a]['academic_program']);
		
		$start_semester = $result[$a]['start_semester']."\t";////////////////////
		$start_year = $result[$a]['start_year']."\t";////////////////////
		
		// $temp .= $user['alternate_name']."\t";
		// $temp .=  preg_replace('/[^\d]/', '', $user['secondary_phone'])."\t";
		$al_temp = $result[$a]['attendance_load'];
		$attendance_load = (al_temp == 'F' || al_temp == 'P') ? "F\t" : "\t";//$result[$a]['attendance_load']."\t";////////////////////
		
		$academic_program = $result[$a]['academic_program']."\t";
		$academic_plan= $programresult[0]['academic_plan']."\t";
		// $academic_plan= $result[$a]['academic_dept_code']."\t";
		
		$student_t = $result[$a]['student_type']."\t";////////////////////
		
		$t .= "\t"."\t"."\t"."\t";
		
	}
	
	$temp .= $start_semester;
	$temp .= $start_year;
	$temp .= $altname;
	$temp .= $secondary_phone;
	$temp .= $attendance_load;
	$temp .= $academic_program;
	$temp .= $academic_plan;
	
	$temp .= $t;

	for ($a = 0; $a < $blanks; $a++){
		$temp .= "\t";
		$temp .= "\t";
		$temp .= "\t";
		$temp .= "\t";
		$temp .= "\t";
		$temp .= "\t";
	}

	$temp .= "F"."\t";
	

	/* -------------------- */
	/* Previously Attended Schools
	/* -------------------- */

	$result1 = $db->query("SELECT * FROM previousschools WHERE applicant_id = %d", $userid);
	$loop = count($result1);

	if ($loop>10) {
		$loop = 10;
		$blanks = 0;
	} else {
		$blanks = 10-$loop;
	}

	for ($a = 0; $a < $loop; $a++)
		$temp .= $result1[$a]['previous_schools_code']."\t";
	for ($a = 0; $a < $blanks; $a++)
		$temp .= "\t";

	// residency status and student type
	$temp .= "\t";
	$temp .= $student_t; // See previous section. @TODO: Note that multiple programs don't work in this approach ...


	/* -------------------- */
	/* Output application payment data
	/* -------------------- */

	$temp .= $user['application_fee_payment_status']."\t";

	$appsubdate = explode("-", $user['application_fee_transaction_date']);

	if ($user['application_fee_payment_status']=='Y') {
		$temp .= $user['application_fee_transaction_type']."\t";
		$temp .= $appsubdate[2]."/".$appsubdate[1]."/".$appsubdate[0]."\t";
		$temp .= $user['application_fee_transaction_amount']."\t";
		$temp .= $user['application_fee_transaction_payment_method'] . "\t";// transaction payment method
	} else {
		//otherwise value is 'N' - use all blanks
		$temp .= "\t"; //transaction type
		$temp .= "\t"; //date
		$temp .= "\t"; //transaction amount
		$temp .= "\t"; //transaction payment method
	}

	// New Line
	$temp .= "\n";

	return $temp;
}
