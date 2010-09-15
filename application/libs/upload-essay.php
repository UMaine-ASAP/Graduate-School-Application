<?php
	include_once "corefuncs.php";
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../pages/index.php");

	$allowedExtensions = array("pdf", "doc", "docx", "rtf", "txt");
	$filename = $_FILES["essay"]["name"];
	$ext = strtolower(substr(strrchr($filename, '.'), 1));

	if(in_array($ext, $allowedExtensions)) {
		$finalname = $user."_essay.".$ext;
		$upload = "../../essays/";
		move_uploaded_file($_FILES["essay"]["tmp_name"],  $upload.$finalname);
	} else {
		die("Error: Not an allowed file type");
	}
?>
