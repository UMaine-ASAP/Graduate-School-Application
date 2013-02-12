<?php
/**
 * Cron Script 
 * 
 * Cron script to be run every morning, which batches the previous days applications.
 * 
 * @author Jonathan Simpson
 * @edited Tim Westbaker
 */

include_once "../application/libs/variables.php";
include_once "../application/libs/database.php";
include_once "../application/libs/corefuncs.php";
include_once "./mainestreet.php";


function getName($applicant) { //Peice together their name from supplied information.
	$name = "";
	if ($applicant[3]!="") $name .= sanitizeString($applicant[3]); 		//ex: Simpson 
	if ($applicant[1]!="") $name .="_".sanitizeString($applicant[1]);	//ex: Simpson_Jonathan
	if ($applicant[2]!="") $name .="_".sanitizeString($applicant[2]);	//ex: Simpson_Jonathan_Daniel
	if ($applicant[4]!="") $name .="_".sanitizeString($applicant[4]);	//ex: Simpson_Jonathan_Daniel_II
	return $name;
}

/* -------------------- */
/* Setup
/* -------------------- */

//Increase the default timeout, in case this script takes forever.
ini_set('max_execution_time', 300);


$errors = ""; //Instead of dying on errors, store them to be echoed out later. Remove.
$num_applicants = 0;
$mainestreet = "";

//Uncomment when live.
ini_set("display_errors", 1);

//Var for errors that are relevant to Grad School (ie. missing essay)

//Date of apps to batch (previous day)
// Format is YYYY-MM-DD
 $submit_date = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-1,date("y")));


/* -------------------- */
/* Script
/* -------------------- */

$db = new Database();
$db->connect();

//Send out the query.
$batch = $db->query("SELECT applicant_id, given_name, middle_name, family_name, suffix, date_of_birth FROM applicants WHERE application_submit_date = '%s'", $submit_date);

//Reformat $submit_date
$dateT 		= new DateTime($submit_date);
$submit_date 	= date_format($dateT, 'm-d-Y');

if ( count($batch) != 0 )
{
	//Batch is not empty, so there is some work to do.
	//Temporary directory for holding files
	$tmpSubmissionFolder = $GLOBALS['essays_path']."UMGradApps_".$submit_date."/";

	mkdir($tmpSubmissionFolder) or die("LINE:".__LINE__."Temp folder:".$tmpSubmissionFolder." creation failed, check permissions.<br />");

	//Loop through each un-pushed applicant.
	foreach($batch as $applicant) {
		$num_applicants++;
		$name 		= getName($applicant);
		$userid 		= $applicant[0];
		$given_name 	= sanitizeString($applicant[1]);
		$family_name 	= sanitizeString($applicant[3]);
		$date_of_birth = $applicant[5];

		$mainestreet  .= mainestreet($userid);

		//Create folder for each applicant, include in it thier application and essay.
		//ex: ../essays/UMGradApps_02_03_2010/Simpson_Jonathan_Daniel_II_91
		$tmpApplicantFolder = $tmpSubmissionFolder.$name."_".$userid."/";

		if(!mkdir($tmpApplicantFolder)) { die("LINE:".__LINE__."Folder creation failed: ".$tmpApplicantFolder."</br>"); }


		/*--- Process Application ---*/

		//path to their app
		$pdfin = $completed_pdfs_path.$userid."_".$family_name."_".$given_name."_".str_replace("/", "-", $date_of_birth).".pdf";

		//path to put app, then copy.
		$pdfout = $tmpApplicantFolder . "UMGradApp_" . $applicant[3] . "_" . $userid . ".pdf";

		if (!copy($pdfin,$pdfout)) { die("Application PDF not found: UMGradApp_".$userid.", ".$name."\n"); }
		//chmod($pdfin, 0222);


		/*--- Process Essays ---*/

		//glob essays, because extension is unknown
		$essays = glob($GLOBALS['essays_path'].$userid."_essay.*");

		//if multiple essays of different extensions, all are sent.
		foreach($essays as $tmpessay)
		{
			//Get extension from essay file
			$extension = pathinfo($tmpessay, PATHINFO_EXTENSION);

			//Generate output path for essay			
			$essayout = $tmpApplicantFolder."UMGradApp_".$applicant[3]."_".$userid."_Essay.".$extension;
			
			//Copy essay to destination
			if (!copy($tmpessay,$essayout)) { die("LINE:".__LINE__."Essay copy failed: ".$tmpessay."\n"); }
			//chmod($tmpessay, 0222);
		}


		/*--- Process Resumes ---*/

		//glob resumés, because extension is unknown
		$resumes = glob($GLOBALS['resumes_path'].$userid."_resume.*");
		
		//if multiple resumes of different extensions, all are sent.
		foreach($resumes as $tmpresume)
		{
			//Get extension from essay file
			$extension = pathinfo($tmpresume, PATHINFO_EXTENSION);

			//Generate output path for resume
			$resumeout = $tmpApplicantFolder."UMGradApp_".$applicant[3]."_".$userid."_Resume.".$extension;
			
			//Copy essay to destination
			if (!copy($tmpresume, $resumeout)) { die("LINE:".__LINE__."Resume copy failed: ".$tmpresume."\n"); }
			//chmod($tmpresume, 0222);
		}
		
	}
	

	/*--- Process Recommendations ---*/

	$recommendations_folder = $tmpApplicantFolder . "recommendations/";	
	if(!mkdir($recommendations_folder)) { die("LINE:".__LINE__."Recommendation folder creation failed: ".$recommendations_folder."</br>"); }

	$recommendations = glob($GLOBALS['recommendations_path']."*.*");
	foreach($recommendations as $recommendation) {
		//Get extension from essay file
		$base = pathinfo($recommendation, PATHINFO_BASENAME);

		//Generate output path for essay
		$tmpout = $recommendations_folder . $base;

		//Copy essay to destination
		if (!copy($recommendation, $tmpout)) { die("LINE:".__LINE__."Recommendation copy failed: ".$recommendation."\n"); }
		//chmod($recommendation, 0222);
	}
	
	
	/*--- Save Mainestreet Data ---*/
	$tmp_mainestreet_path = $tmpSubmissionFolder . "UMGradApps_Mainestreet" . $submit_date . ".txt";
	$fh = fopen($tmp_mainestreet_path, 'w') or die("LINE:".__LINE__." can't open file");
	fwrite($fh, $mainestreet);
	fclose($fh);


	//Temporary file destruction
	$mainestreet_filename  	= pathinfo($tmp_mainestreet_path, PATHINFO_BASENAME);
	$mainestreet_output_file = $GLOBALS['mainestreet_path'] . $mainestreet_filename;

	rename($tmp_mainestreet_path, $mainestreet_output_file);
	chgrp($mainestreet_output_file, $GLOBALS['mainestreet_group_name']);


	/*--- Move data to final destination ---*/
	$finalSubmissionFolder = $GLOBALS['gradschool_path']."UMGradApps_".$submit_date;
	rename($tmpSubmissionFolder, $finalSubmissionFolder);

	chgrp($finalSubmissionFolder, $GLOBALS['gradschool_group_name']);
}

/* -------------------- */
/* Updates status of applications
/* -------------------- */

$db->iquery("UPDATE `applicants` SET has_been_pushed = 1 WHERE application_submit_date = '%s'", $submit_date);


/* -------------------- */
/* Remove applications older than 6 months
/* -------------------- */
//include_once "app_cleanup.php";

