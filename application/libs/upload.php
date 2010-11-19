<?php
	include_once "corefuncs.php";
	include_once "variables.php";
 
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../pages/index.php");

function uploadFile($name, $upload_path, $user) {

	$allowedExtensions = array("pdf", "doc", "docx", "rtf", "txt");
	$allowedMimeTypes = array("application/pdf" ,"application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/rtf" , "text/plain");

	$filename = $_FILES[$name]["name"];
	
	$ext = strtolower(substr(strrchr($filename, '.'), 1));

	if(in_array($ext, $allowedExtensions) && in_array($_FILES[$name]['type'], $allowedMimeTypes)) {
		$finalname = $user . "_" . $name . "." . $ext;

		move_uploaded_file($_FILES[$name]["tmp_name"],  $upload_path . $finalname);
	} else {
		die("Error: Not an allowed file type");
	}
}
?>
