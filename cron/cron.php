<?php

//Cron script to be run every morning, which batches the previous days applications.
//Jonathan Simpson

include_once "../application/libs/variables.php";
include_once "../application/libs/database.php";
include_once "../application/libs/corefuncs.php";
include_once "./mainestreet.php";

//Increase the default timeout, in case this script takes forever.
ini_set('max_execution_time', 300);

//Instead of dying on errors, store them to be echoed out later. Remove.
$errors = ""; 		
$num_applicants = 0;
$mainestreet = "";
$emailMessage = "";
//Uncomment when live.
ini_set("display_errors", 1);

//Var for errors that are relevant to Grad School (ie. missing essay)
$emailMessaage = "";

//Date of apps to batch (previous day)

//////////////////////////////////////////////
// YYYY-MM-DD
 $submit_date = date('Y-m-d', mktime(0,0,0,date("m"),date("d")-1,date("y")));
// $submit_date = date('Y-m-d', mktime(0,0,0,date("m"),date("d"),date("y")));

// $submit_date = date('Y-m-d');
//////////////////////////////////////////////

//Connect to DB (in database.php) //Prep query to get batch of users
$db = new Database();
$db->connect();

//Send out the query.
$batch = $db->query("SELECT applicant_id, given_name, middle_name, family_name, suffix, date_of_birth FROM applicants WHERE application_submit_date = '%s'", $submit_date);

//Reformat $submit_date
$dateT = new DateTime($submit_date);
$submit_date = date_format($dateT, 'm-d-Y');

$emailMessage .= count($batch)." applications were submitted yesterday, ".$submit_date.".\n";
if (count($batch)!=0) {
	//Batch is not empty, so there is some work to do.
	//Temporary directory for holding files
	$tempFolder = $GLOBALS['essays_path']."UMGradApps_".$submit_date."/";
	//Create temp folder.
	//Create folder for each applicant, include in it thier application and essay.
	mkdir($tempFolder) or $errors .= "LINE:".__LINE__."Temp folder:".$tempFolder." creation failed, check permissions.<br />";
	//Loop through each un-pushed applicant.
	foreach($batch as $applicant) {
		$num_applicants++;
		$name = getName($applicant);
		//ex: 91
		$userid = $applicant[0];
		$given_name = sanitizeString($applicant[1]);
		$family_name = sanitizeString($applicant[3]);
		$date_of_birth = $applicant[5];
		$mainestreet .= mainestreet($userid);
		//Temp folder for individual applicant:
		//ex: ../essays/UMGradApps_02_03_2010/Simpson_Jonathan_Daniel_II_91
		//Temp folder for each user.
		$newFolder = $tempFolder.$name."_".$userid;
		//Create tmp folder
		if(!mkdir($newFolder)) $errors .= "LINE:".__LINE__."Folder creation failed: ".$newFolder."</br>";
		//path to their app
		// $pdfin = "../pdf_export/completed_pdfs/UMGradApp_".$userid.".pdf";
		// $pdfin = $completed_pdfs_path."/UMGradApp_".$userid.".pdf";
		$pdfin = $completed_pdfs_path.$userid."_".$family_name."_".$given_name."_".str_replace("/", "-", $date_of_birth).".pdf";

		//path to put app, then copy.
		$pdfout = $newFolder."/UMGradApp_".$applicant[3]."_".$userid.".pdf";
		chmod($pdfin, 0777) or $errors .= "LINE:".__LINE__."Error chmoding $pdfin";
		if (!copy($pdfin,$pdfout)) $emailMessage .= "Application PDF not found: UMGradApp_".$userid.", ".$name."\n";
		chmod($pdfin, 0222);
		//glob essays, because extension is unknown
		// $essays = glob("../essays/".$userid."_essay.*");
		$essays = glob($GLOBALS['essays_path'].$userid."_essay.*");
		if (count($essays)==0) $emailMessage .= "No essay found: ".$name."\n";
		//if multiple essays of different extensions, all are sent.
		foreach($essays as $tmpessay) {
			//Get extension from essay file
			chmod($tmpessay, 0777);
			$extension = pathinfo($tmpessay, PATHINFO_EXTENSION);
			//Generate output path for essay
			// $essayout = $newFolder."/UMGradApp_".$applicant[3]."_".$userid."_Essay.".$extension;
			
			// $essayout = "/Users/gradd2app/gradschool/UMGradApps_".$submit_date."/".$name."_".$userid."/"."UMGradApp_".$applicant[3]."_".$userid."_Essay.".$extension;
			$essayout = $GLOBALS['gradschool_path']."UMGradApps_".$submit_date."/".$name."_".$userid."/"."UMGradApp_".$applicant[3]."_".$userid."_Essay.".$extension;
			
			//Copy essay to destination
			if (!copy($tmpessay,$essayout)) $errors .= "LINE:".__LINE__."Essay copy failed: ".$tmpessay."\n";
			chmod($tmpessay, 0222);
		}
		//glob resumés, because extension is unknown
		$resumes = glob($GLOBALS['resumes_path'].$userid."_resume.*");
		if (count($essays)==0) $emailMessage .= "No resume found: ".$name."\n";
		//if multiple resumes of different extensions, all are sent.
		foreach($resumes as $tmpresume) {
			//Get extension from essay file
			chmod($tmpresume, 0777);
			$extension = pathinfo($tmpresume, PATHINFO_EXTENSION);
			//Generate output path for resume
			// $resumeout = $newFolder."/UMGradApp_".$applicant[3]."_".$userid."_Resume.".$extension;
			$resumeout = $GLOBALS['gradschool_path']."UMGradApps_".$submit_date."/".$name."_".$userid."/"."UMGradApp_".$applicant[3]."_".$userid."_Resume.".$extension;
			
			//Copy essay to destination
			if (!copy($tmpresume,$resumeout)) $errors .= "LINE:".__LINE__."Resume copy failed: ".$tmpresume."\n";
			chmod($tmpresume, 0222);
		}
		
	}
	
	
	//$rcfldr = $tempFolder."recommendations/";
	// $rcfldr = "/Users/gradd2app/gradschool/recommendations/";
	$rcfldr = $GLOBALS['gradschool_path']."recommendations/";
	
	
	if(!mkdir($rcfldr)) $errors .= "LINE:".__LINE__."Recommendation folder creation failed: ".$rcfldr."</br>";
	$rcmds = glob($GLOBALS['recommendations_path']."*.*");
	// foreach($rcmds as $entry){echo $entry."</br>";}
	//if (count($rcmds)==0) $emailMessage .= "No recommendations\n";
	// $emailMessage .= count($rcmds)." recommendations submitted.\n";
	foreach($rcmds as $tmp) {
		//Get extension from essay file
		chmod($tmp, 0777);
		$base = pathinfo($tmp, PATHINFO_BASENAME);
		//Generate output path for essay
		$tmpout = $rcfldr.$base;
		//Copy essay to destination
		if (!copy($tmp,$tmpout)) $errors .= "LINE:".__LINE__."Recommendation copy failed: ".$tmp."\n";
		chmod($tmp, 0222);
		//unlink($tmp);//delete recommendation
	}
	
	
	//Save mainestreet data to text file.
	$mstf = $tempFolder."UMGradApps_Mainestreet".$submit_date.".txt";
	$fh = fopen($mstf, 'w') or die("LINE:".__LINE__." can't open file");
	fwrite($fh, $mainestreet);
	fclose($fh);

	//Temporary file destruction
	$msout = pathinfo($mstf, PATHINFO_BASENAME);
	$msout = $GLOBALS['mainestreet_path'].$msout;
	rename($mstf, $msout);
	// chgrp($msout, "gradmainstreet");
	chgrp($msout, $GLOBALS['mainestreet_group_name']);
	// $newLocation = "/Users/gradd2app/gradschool/UMGradApps_".$submit_date;
	$newLocation = $GLOBALS['gradschool_path']."UMGradApps_".$submit_date;
	rename($tempFolder, $newLocation);
	// chgrp($newLocation, "gradoffice");
	chgrp($newLocation, $GLOBALS['gradschool_group_name']);
}


//Construct email components

$mailto = '<graduate@maine.edu>';
// $mailto = '<joshua.e.mcgrath@gmail.com>';


$subject = 'UMaine Grad School Applications for '.$submit_date; 

$headers = "";
$headers .= "Content-Type: text/html;\n";
$headers .= "Content-Transfer-Encoding: 7bit\n";
$headers .= "From: <NOREPLY@hoonah.asap.um.maine.edu>\n";
$headers .= "X-Priority: 3\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "\n\n";

$mail_sent = @mail($mailto, $subject, $emailMessage, $headers);

//Remove when live, echos out any errors that occured...
echo "<p>".$errors."</p>";
//echo "<p>".$mail_sent."</p>";

//update has_been_pushed for batch
$result = $db->iquery("UPDATE `applicants` SET has_been_pushed = 1 WHERE application_submit_date = '%s'", $submit_date);



function getName($applicant) { //Peice together their name from supplied information.
	$name = "";
	if ($applicant[3]!="") $name .= sanitizeString($applicant[3]); 		//ex: Simpson 
	if ($applicant[1]!="") $name .="_".sanitizeString($applicant[1]);	//ex: Simpson_Jonathan
	if ($applicant[2]!="") $name .="_".sanitizeString($applicant[2]);	//ex: Simpson_Jonathan_Daniel
	if ($applicant[4]!="") $name .="_".sanitizeString($applicant[4]);	//ex: Simpson_Jonathan_Daniel_II
	return $name;
}

?>
