<?php
	require_once "database.php";
	require_once "corefuncs.php";

	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../pages/index.php");

	if($_POST['remove_id']) {

		$db = new Database();
		$db->connect();

		$values = explode("_",$_POST['remove_id']);
		$table =  $values[0];
		$id = $values[1];
		$count = $db->getFirst("SELECT %s_repeatable FROM applicants WHERE applicant_id=%d", $table, $user);
		$db->iquery("UPDATE applicants SET %s_repeatable=%d WHERE applicants.applicant_id=%d", $table, $count-1, $user);
		$db->iquery("DELETE FROM %s WHERE applicant_id=%d AND %s_id=%d", $table, $user, $table, $id);
	
		$db->close();
	}
	
?>
