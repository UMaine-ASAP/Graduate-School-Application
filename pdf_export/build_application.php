<?php
	

	
	
	//Used to embed essay and resume into application, currently not using


	error_reporting(0);
	set_include_path('lib/Zend/ZendFull/Library/');

	require_once "lib/Zend/convertFile.php";
	require_once "application_generate_pdf.php";
	include_once "../application/libs/variables.php";
	include_once "../application/libs/database.php";
	include_once "../application/libs/corefuncs.php";

	$construction_success = true;

//==== Validate user ====//

	$user = isset($_GET['userid']) ? $_GET['userid'] : check_ses_vars();
	$user = ($user)?$user:header("location:../pages/index.php");

//==== Get Personal Data ====//

	$db = new Database();
	$db->connect();

	$primary_query   = "";
	$primary_query  .= "SELECT `given_name`, `family_name`, `date_of_birth`, `resume_file_name`, `essay_file_name` ";
	$primary_query  .= "FROM `applicants` ";
	$primary_query  .= "WHERE `applicant_id` = %d";

	$personal_data = $db->query($primary_query, $user);
	$personal_data = $personal_data[0];

//==== Convert Essay and resume to html ====//

	//essay_file_name and resume_file_name only store the file names of the originally uploaded files, not the filenames on the server - need to reconstruct
	$exDOB  = explode("/", $personal_data['date_of_birth']);
	$newDOB = $exDOB[0].$exDOB[1].$exDOB[2];

	//Construct base filename
	$base_file_name = "$user";
	$additions = Array($personal_data['given_name'], $personal_data['family_name'], $newDOB);
	
	foreach ($additions as $value) {
		if( $value != "")
			$base_file_name .= "_" . $value;
	}

	// =====Process Essay===== //
	//Make sure a file was uploaded
	if ($personal_data['essay_file_name'] != "") {
		$essay_in_ext  = end(explode( '.', $personal_data['essay_file_name'] ));
		$essay_filename_wext  = "essay_"  . $base_file_name . "." ;

		//Convert to pdf
		if ( strtolower($essay_in_ext) != 'pdf') {
			try {
				liveDocxConvertFile($GLOBALS['essays_path'] . $essay_filename_wext  . $essay_in_ext , $GLOBALS['essays_path'] . $essay_filename_wext  . "pdf");
			} catch( Exception $e) { //Conversion failed
				$db->iquery("UPDATE `applicants` SET `application_process_status` = 'F' WHERE `applicant_id` = %d LIMIT 1", $user);
				$construction_success = false;
			}
		}
	}

	// =====Process Resume===== //
	//Make sure a file was uploaded
	if ($personal_data['resume_file_name'] != "") {
		$resume_in_ext = end(explode(".", $personal_data['resume_file_name'] )); 
		$resume_filename_wext = "resume_" . $base_file_name . "." ;

		//Convert to pdf
		if ( strtolower($resume_in_ext) != 'pdf') {
			try {
				liveDocxConvertFile($GLOBALS['resumes_path'] . $resume_filename_wext . $resume_in_ext, $GLOBALS['resumes_path'] . $resume_filename_wext . "pdf");
			} catch( Exception $e) { //Conversion failed
				$db->iquery("UPDATE `applicants` SET `application_process_status` = 'F' WHERE `applicant_id` = %d LIMIT 1", $user);
				$construction_success = false;
			}

		}
	}


//==== Build Application PDF ====//
	$app_name = generate_application_pdf($user);

//==== Merge PDF's and Build Entire Application ====//
	$args = Array($app_name);
	$cwd  = getcwd();
	if($personal_data['essay_file_name'] != "") {
		$args[] = $cwd . "/pdf_templates/essay-front-page.pdf";
		$args[] = $GLOBALS['essays_path'] . $essay_filename_wext . "pdf";

	}
	if($personal_data['resume_file_name'] != "") {
		$args[] = $cwd . "/pdf_templates/resume-front-page.pdf";
		$args[] = $GLOBALS['resumes_path'] . $resume_filename_wext . "pdf";
	}

	$args[] = $app_name;
	exec("/usr/bin/php $cwd/merge_pdf.php " . implode(" ", $args));
	
	if(isset($_GET['userid'])) {
		$msg = ($construction_success) ? "Success" : "Application Build Failed";
		print $msg;
	}
	if($construction_success) {
		$db->iquery("UPDATE `applicants` SET `application_process_status` = 'T' WHERE `applicant_id` = %d LIMIT 1", $user);
	}
?>
