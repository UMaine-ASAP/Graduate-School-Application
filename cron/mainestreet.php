   <?php
 	include_once "../application/libs/database.php";
 	include_once "../application/libs/variables.php";
	
	function mainestreet($userid) {
	// Lukas Jordan //edits: jonathan simpson.
	//don't change without versioning.
		$db = new Database();
		$db->connect();
		$result = $db->query("SELECT * FROM applicants WHERE applicant_id = %d", $userid);
		$user = $result[0];
		
		$temp = $user['applicant_id']."\t";
		$temp .= $user['given_name']."\t";
		$temp .= $user['family_name']."\t";
		$temp .= $user['middle_name']."\t";
		$temp .= $user['suffix']."\t";
		$temp .= $user['permanent_addr1']."\t";
		$temp .= $user['permanent_addr2']."\t";
		$temp .= $user['permanent_city']."\t";
		$temp .= $user['permanent_country']."\t";
		$temp .= $user['permanent_state']."\t";
		$temp .= $user['permanent_postal']."\t";
		$temp .= preg_replace('/[^\d]/', '', $user['primary_phone'])."\t";
		$temp .= $user['email']."\t";
		$temp .= $user['gender']."\t";
		$temp .= $user['date_of_birth']."\t";
		$temp .= $user['mailing_addr1']."\t";
		$temp .= $user['mailing_addr2']."\t";
		$temp .= $user['mailing_city']."\t";
		$temp .= $user['mailing_state']."\t";
		$temp .= $user['mailing_country']."\t";
		$temp .= $user['mailing_postal']."\t";
		$temp .= $user['ethnicity_hispa']."\t";
		$temp .= $user['ethnicity_amind']."\t";
		$temp .= $user['ethnicity_asian']."\t";
		$temp .= $user['ethnicity_black']."\t";
		$temp .= $user['ethnicity_pacif']."\t";
		$temp .= $user['ethnicity_white']."\t";
		
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
		
		$date_t = new DateTime($user['application_submit_date']);
		$new_date = date_format($date_t, 'm-d-Y');
		$appsubdate = explode("-", $new_date);
		

		$temp .= $appsubdate[0]."/".$appsubdate[1]."/".$appsubdate[2]."\t";
		

		if ($user['permanent_country']=='USA'){
			$temp .= "1"."\t";
		}else $temp .= "4"."\t";
		$temp .= $user['desire_financial_aid']."\t";
		
					
		$altname = $user['alternate_name']."\t";
		$secondary_phone =  preg_replace('/[^\d]/', '', $user['secondary_phone'])."\t";

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
			$attendance_load = $result[$a]['attendance_load']."\t";////////////////////
			
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
		
		$temp.= $t;
		// $temp .= "\t";
		// 		$temp .= "\t";
		// 		$temp .= "\t";
		// 		$temp .= "\t";

		for ($a = 0; $a < $blanks; $a++){
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
		}

		// $temp .= $user['desired_housing']."\t";
		$temp .= "F"."\t";
		
		// writes previous schools attended
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

		// $temp .= $user['residency_status']."\t";
		// $temp .= $user['student_type']."\t";
		$temp .= "\t";
		$temp .= $student_t;
		
		$temp .= $user['application_fee_payment_status']."\t";
		$temp .= $user['application_fee_transaction_type']."\t";
		$appsubdate = explode("-", $user['application_fee_transaction_date']);
		$temp .= $appsubdate[2]."/".$appsubdate[1]."/".$appsubdate[0]."\t";
		if ($user['application_fee_payment_status']=='N'){
			$temp .= "\t";
		}
		else{ $temp .= $user['application_fee_transaction_amount']."\t";}	
		$temp .= $user['application_fee_transaction_number']."\n";
		return $temp;
	}
?>
