<?php
	require_once "database.php";
	$db = new Database();
	$db->connect();
	if($_POST) {
		if(!$_POST['table']) {
			// we should encode HTML entities without screwing up umlauts and other special characters
			$db->iquery("UPDATE `applicants` SET %s='%s' WHERE applicants.applicant_id=%d", $_POST['field'], $_POST['value'], $_POST['id']);
		} else {
			if(!$db->getFirst("SELECT %s_id FROM `%s` WHERE applicant_id=%d AND %s_id=%d", $_POST['table'], $_POST['table'], $_POST['id'], $_POST['table'], $_POST['index'])) {
				$db->iquery("INSERT INTO `%s` (applicant_id, %s_id) VALUES (%d, %d)", $_POST['table'], $_POST['table'], $_POST['id'], $_POST['index']);
				$count = $db->getFirst("SELECT %s_repeatable FROM `applicants` WHERE applicant_id=%d", $_POST['table'], $_POST['id']);
				$db->iquery("UPDATE `applicants` SET %s_repeatable=%d WHERE applicants.applicant_id=%d", $_POST['table'], $count+1, $_POST['id']);
			}
			
			$db->iquery("UPDATE `%s` SET %s='%s' WHERE %s.applicant_id=%d AND %s_id=%d", $_POST['table'], $_POST['field'], $_POST['value'], $_POST['table'], $_POST['id'], $_POST['table'], $_POST['index']);
		}
	}
	
	$db->close();

?>
