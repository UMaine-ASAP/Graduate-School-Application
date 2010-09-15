<?php
	require_once "database.php";
	$db = new Database();
	$db->connect();
	
	if($_POST['remove_id'] && $_POST['user']) {
		$user = $_POST['user'];
		$values = explode("_",$_POST['remove_id']);
		$table =  $values[0];
		$id = $values[1];
		$count = $db->getFirst("SELECT %s_repeatable FROM applicants WHERE applicant_id=%d", $table, $user);
		$db->iquery("UPDATE applicants SET %s_repeatable=%d WHERE applicants.applicant_id=%d", $table, $count-1, $user);
		$db->iquery("DELETE FROM %s WHERE applicant_id=%d AND %s_id=%d", $table, $user, $table, $id);
	}
	
	$db->close();
?>
