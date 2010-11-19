<?php
	require_once "database.php";
	require_once "variables.php";
	include_once "corefuncs.php";
	
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../pages/index.php");

	$db = new Database();
	$db->connect();
	if($_POST) {
		$_POST['value'] = sanitizeString($_POST['value']);
		if(!$_POST['table']) {
			if($_POST['field'] == "social_security_number") {
				$db->iquery("UPDATE `applicants` SET %s=AES_ENCRYPT('%s', '%s') WHERE applicants.applicant_id=%d", $_POST['field'], $_POST['value'], $GLOBALS['key'], $user);
			} else {
				$db->iquery("UPDATE `applicants` SET %s='%s' WHERE applicants.applicant_id=%d", $_POST['field'], $_POST['value'], $user);
				
			}

			$db->iquery("UPDATE `applicants` SET %s='%s' WHERE applicants.applicant_id=%d", $_POST['field'], $_POST['value'], $user);

		} else {
			if(!$db->getFirst("SELECT %s_id FROM `%s` WHERE applicant_id=%d AND %s_id=%d", $_POST['table'], $_POST['table'], $user, $_POST['table'], $_POST['index'])) {
				$db->iquery("INSERT INTO `%s` (applicant_id, %s_id) VALUES (%d, %d)", $_POST['table'], $_POST['table'], $user, $_POST['index']);
				$count = $db->getFirst("SELECT %s_repeatable FROM `applicants` WHERE applicant_id=%d", $_POST['table'], $user);
				$db->iquery("UPDATE `applicants` SET %s_repeatable=%d WHERE applicants.applicant_id=%d", $_POST['table'], $count+1, $user);
			}

			
			$db->iquery("UPDATE `%s` SET %s='%s' WHERE %s.applicant_id=%d AND %s_id=%d", $_POST['table'], $_POST['field'], $_POST['value'], $_POST['table'], $user, $_POST['table'], $_POST['index']);
		}
	}
	
	$db->close();

?>
