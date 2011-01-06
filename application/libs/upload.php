<?php
	include_once "variables.php";
	include_once "database.php";
	include_once "corefuncs.php";

	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../pages/index.php");

function uploadFile($name, $upload_path, $user) {

	//****************************************************************
	// Gather User's name
	//****************************************************************
	$db = new Database();
	$db->connect();

	$primary_query   = "";
	$primary_query  .= "SELECT `given_name`, `family_name`, `date_of_birth` ";
	$primary_query  .= "FROM `applicants` ";
	$primary_query  .= "WHERE `applicant_id` = %d";

	$personal_data = $db->query($primary_query, $user);
	$personal_data = $personal_data[0];
	$db->close();

	$exDOB = explode("/", $personal_data['date_of_birth']);
	$newDOB = $exDOB[0].$exDOB[1].$exDOB[2];

	$allowedExtensions = array("pdf", "doc", "docx", "rtf", "txt");
	$allowedMimeTypes = array("application/pdf" ,"application/msword","application/doc","application/x-rtf","text/richtext","application/vnd.openxmlformats-officedocument.wordprocessingml.document","text/rtf" , "text/plain");

	$filename = $_FILES[$name]["name"];
	
	$ext = strtolower(substr(strrchr($filename, '.'), 1));

	$finalName = $name."_".$user;
	$additions = Array($personal_data['given_name'], $personal_data['family_name'], $newDOB);
	
	foreach ($additions as $value) {
		if( $value != "")
			$finalName .= "_" . $value;
	}
	$finalName .= "." .$ext;
	$dest = $upload_path . $finalName;
	if(in_array($ext, $allowedExtensions) ){//&& in_array($_FILES[$name]['type'], $allowedMimeTypes)) {
		move_uploaded_file($_FILES[$name]["tmp_name"],  $dest);
		chmod($dest, 0660);
		chgrp($dest, $GLOBALS['gradschool_group_name']);

	} else {
		die("Error: Not an allowed file type");
	}
}
?>
