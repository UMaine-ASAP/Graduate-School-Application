 <?php
 	include_once "./application/libs/database.php";
 	
	function Peoplesoft($userid){
		// Lukas Jordan
		$db = new Database();
		$db->connect();
		$result = $db->query("SELECT * FROM applicants WHERE applicant_id = %d", $userid);
		$user = $result[0];
		/* creates peoplesoft file for one user
		* $filename = "peoplesoft/".$user['given_name']."_".$user['family_name']."_".$user['applicant_id'].".txt";
		* writes to users file
		* $newfile = fopen($filename, "a+") or die("Could not create file");
		*/
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
			$temp .= $user['social_security_number']."\t";
			$temp .= $user['application_submit_date']."\t";
			$temp .= $user['us_citizen']."\t";
			$temp .= $user['desire_financial_aid']."\t";
			$temp .= $user['start_semester']."\t";
			$temp .= $user['start_year']."\t";
			$temp .= $user['alternate_name']."\t";
			$temp .= $user['secondary_phone']."\t";
			$temp .= $user['academic_load']."\t";
		// finds multiple programs if applicable
		$result = $db->query("SELECT * FROM appliedprograms WHERE applicant_id = %d", $user);
		$loop = count($result);
		if ($loop>4) { $loop = 4; $blanks = 0; } else { $blanks = 4-$loop; }
		for ($a = 0; $a <= $loop; $a++){
			$temp .= $result[$a]['additional_academic_program']."\t";
			$temp .= $result[$a]['additional_academic_plan']."\t";
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
		}
		for ($a = 0; $a <= $blanks; $a++){
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
			$temp .= "\t";
		}
			$temp .= $user['desired_housing']."\t";

		// writes previous schools attended
		$result1 = $db->query("SELECT * FROM previousschools WHERE applicant_id = %d", $user);
		$loop = count($result1);
		if ($loop>10) { $loop = 10; $blanks = 0; } else { $blanks = 10-$loop; }
		for ($a = 0; $a <= 10; $a++){
			$temp .= $result[$a]['previous_school_code']."\t";
		}
		for ($a = 0; $a <= $blanks; $a++){
			$temp .= "\t";
		}
		$temp .= $user['residency_status']."\t";
		$temp .= $user['application_fee_payment_status']."\t";
		$temp .= $user['application_fee_transaction_type']."\t";
		$temp .= $user['application_fee_transaction_date']."\t";
		$temp .= $user['application_fee_transaction_amount']."\t";
		$temp .= $user['application_fee_transaction_number'];
		
		echo $temp

?>
