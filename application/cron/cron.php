<?php
/**
 * Cron Script 
 * 
 * Cron script to be run every morning, which batches the previous days applications.
 * 
 * @author Jonathan Simpson
 * @edited Tim Westbaker
 */

//Increase the default timeout, in case this script takes forever.
ini_set('max_execution_time', 300);
ini_set("display_errors", 1);


require_once __DIR__ . '/../configuration.php';
require_once __DIR__ . "/mainestreet.php";
require_once __DIR__ . '/../libraries/Database.php';


require_once __DIR__ . '/../controllers/ApplicationController.php';
require_once __DIR__ . '/../models/Recommendation.php';

/* ================================================== */
/* = Submit Previous Days Applications to Mainestreet
/* ================================================== */


//Date of apps to batch (previous day) - Format is YYYY-MM-DD
$submit_date = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-1,date("y")));


//Send out the query.
$applicationIds = Database::query("SELECT ApplicationId FROM Application WHERE hasBeenSubmitted = 1 AND submittedDate = '%s' AND hasBeenPushed = 1", $submit_date);

//Reformat $submit_date
$dateT 		= new DateTime($submit_date);
$submit_date 	= date_format($dateT, 'm-d-Y');

if ( count($applicantIds) != 0 )
{
	//Temporary directory for holding files
	$tmpSubmissionFolder = $GLOBALS['tmp_path']."UMGradApps_".$submit_date."/";
	mkdir($tmpSubmissionFolder) or die("LINE:".__LINE__."Temp folder:".$tmpSubmissionFolder." creation failed, check permissions.<br />");

	//Loop through each un-pushed applicant.
	foreach($applicationIds as $applicationId) {

		$application = ApplicationController::getApplicationByIdWithoutAnActiveUser($applicationId);

		//Create folder for each applicant, include in it thier application and essay.
		//ex: ../essays/UMGradApps_02_03_2010/Simpson_Jonathan_Daniel_II_91
		$tmpApplicantFolder = $tmpSubmissionFolder . $application->submissionFolderName;

		if( !mkdir($tmpApplicantFolder) ) 
		{ 
			die("LINE:".__LINE__."Folder creation failed: ".$tmpApplicantFolder."</br>");
		}


		/* ------------------------------ */
		/* Copy pdf to temporary location
		/* ------------------------------ */

		$pdfIn  = $completed_pdfs_path . $application->fileNamePDF; //path to their app
		$pdfOut = $tmpApplicantFolder . $application->fileNamePDF; //path to put app

		if ( !copy($pdfIn, $pdfOut) ) 
		{ 
			die("Application PDF not found: " . $application->fileNamePDF . " </br>");
		}


		/* --------------------------------- */
		/* Copy Essays to temporary location
		/* --------------------------------- */

		//glob essays, because extension is unknown
		$essays = glob($GLOBALS['essays_path'].$application->fileNameEssay.".*");

		//if multiple essays of different extensions, all are sent.
		foreach($essays as $essayIn)
		{
			$essayOut = $tmpApplicantFolder . pathinfo($essayIn, PATHINFO_BASENAME); // Path to put essay
			
			if ( !copy($essayIn, $essayOut) )
			{ 
				die("LINE:".__LINE__."Essay copy failed: ".$essayIn."\n"); 
			}
		}


		/* ---------------------------------- */
		/* Copy Resumes to temporary location
		/* ---------------------------------- */

		//glob resumés, because extension is unknown
		$resumes = glob($GLOBALS['resumes_path'].$application->fileNameResume.".*");
		
		//if multiple resumes of different extensions, all are sent.
		foreach($resumes as $resumeIn)
		{
			$resumeOut = $tmpApplicantFolder . pathinfo($resumeIn, PATHINFO_BASENAME); // Path to put resume
			
			if ( !copy($resumeIn, $resumeOut) )
			{
				die("LINE:".__LINE__."Resume copy failed: ".$resumeIn."\n");
			}
		}
		
	} // end applicant processing
	

	/* ----------------------------- */
	/* Copy unpushed Recommendations
	/* ----------------------------- */

	$recommendationsFolder = $GLOBALS['gradschool_path']."recommendations/";

	$recommendations = Database::query("SELECT referenceId, applicationId FROM `APPLICATION_Recommendation` WHERE hasBeenPushed = 0");

	foreach($recommendations as $rec)
	{
		$recommendation = Recommendation::retrieve($rec['applicationId'], $rec['referenceId'])

		$recIn  = $GLOBALS['recommendations_path'] . $recommendation->fileName;
		$recOut = $recommendationsFolder . $recommendation->fileName;

		if ( !copy($recIn, $recOut) ) 
		{ 
			die("LINE:".__LINE__."Recommendation copy failed: ".$recIn."\n"); 
		}

		Database::iquery("UPDATE `APPLICATION_Recommendation` SET hasBeenPushed = 1, pushedDate=NOW() WHERE hasBeenPushed = 0 AND referenceId='%s' AND applicationId='%s'", $rec['referenceId'], $rec['applicationId']);
		chgrp($recOut, $GLOBALS['gradschool_group_name']);
	}
	
	
	/* ------------------------ */
	/* Push Data to Mainestreet
	/* ------------------------ */

	$mainestreetData = "";
	foreach($applicationIds as $applicationId)
	{
		$mainestreetData .= buildMainestreetOutputForApplicant($application->id);
		$mainestreetData .= "\n"; //seperate applications with new line
	}

	// Write output
	$mainestreetFilename = "UMGradApps_Mainestreet" . $submit_date . ".txt";
	$tmp_mainestreet_path = $tmpSubmissionFolder . $mainestreetFilename;
	$fh = fopen($tmp_mainestreet_path, 'w') or die("LINE:".__LINE__." can't open file");
	fwrite($fh, $mainestreetData);
	fclose($fh);

	// Move mainestreet data to destination
	$mainestreet_output_file = $GLOBALS['mainestreet_path'] . $mainestreetFilename;
	rename($tmp_mainestreet_path, $mainestreet_output_file);
	chgrp($mainestreet_output_file, $GLOBALS['mainestreet_group_name']);


	/* ---------------------------------- */
	/* Move All data to final destination
	/* ---------------------------------- */

	$finalSubmissionFolder = $GLOBALS['gradschool_path']."UMGradApps_".$submit_date;
	rename($tmpSubmissionFolder, $finalSubmissionFolder);
	chgrp($finalSubmissionFolder, $GLOBALS['gradschool_group_name']);


	/* ------------------------------ */
	/* Updates status of applications
	/* ------------------------------ */

	Database::iquery("UPDATE `Application` SET hasBeenPushed = 1, pushedDate=NOW() WHERE hasBeenSubmitted = 1 AND submittedDate = '%s'", $submit_date);

} else {// end pushing applicant data

	// Even though there are no applicants to push, we need to keep the recommendations up to date

	$recommendationsDestinationFolder = $GLOBALS['gradschool_path']."recommendations/";

	$recommendations = Database::query("SELECT referenceId, applicationId FROM `APPLICATION_Recommendation` WHERE hasBeenPushed = 0");

	foreach($recommendations as $rec)
	{
		$recommendation = Recommendation::retrieve($rec['applicationId'], $rec['referenceId'])

		$recIn  = $GLOBALS['recommendations_path']  . $recommendation->fileName;
		$recOut = $recommendationsDestinationFolder . $recommendation->fileName;

		if ( !copy($recIn, $recOut) ) 
		{ 
			die("LINE:".__LINE__."Recommendation copy failed: ".$recIn."\n"); 
		}

		Database::iquery("UPDATE `APPLICATION_Recommendation` SET hasBeenPushed = 1, pushedDate=NOW() WHERE hasBeenPushed = 0 AND referenceId='%s' AND applicationId='%s'", $rec['referenceId'], $rec['applicationId']);
		chgrp($recOut, $GLOBALS['gradschool_group_name']);
	}
}



/* ================================================== */
/* = Remove applications older than 6 months
/* ================================================== */
//include_once "app_cleanup.php";

