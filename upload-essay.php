<?php
	include_once "libs/corefuncs.php";
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../forms/signin/");

	$allowedExtensions = array("pdf", "doc", "docx", "rtf", "txt");
	$filename = $_FILES["essay"]["name"];
	$ext = strtolower(substr(strrchr($filename, '.'), 1));

	if(in_array($ext, $allowedExtensions)) {
		$finalname = $user."_essay.".$ext;
		// move_uploaded_file($_FILES["essay"]["tmp_name"],  "../essays/".$finalname);
		move_uploaded_file($_FILES["essay"]["tmp_name"],  $essays_path.$finalname);

	} else {
		die("Error: Not an allowed file type");
	}
?>
