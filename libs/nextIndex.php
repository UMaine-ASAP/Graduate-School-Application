<?php
	require_once "database.php";
	$db = new Database();
	$db->connect();
	
	if($_GET['tablename'] && $_GET['username']){
		$table = $_GET['tablename'];
		$user  = $_GET['username'];
				
		$index = 1;
		$cIndex = $db->getFirst("SELECT %s_id FROM %s WHERE applicant_id=%d AND %s_id=". $index, $table, $table, $user, $table);
		while($cIndex) { 
			$index++;
			$cIndex = $db->getFirst("SELECT %s_id FROM %s WHERE applicant_id=%d AND %s_id=%d", $table, $table, $user, $table, $index);		
		}

		$db->iquery("INSERT INTO %s (applicant_id, %s_id) VALUES (%d, %d)", $table, $table, $user, $index);
		
		$count = $db->getFirst("SELECT %s_repeatable FROM applicants WHERE applicant_id=%d", $table, $user);
		if($count <= 0) $count = 1;
		$db->iquery("UPDATE applicants SET %s_repeatable=%d WHERE applicants.applicant_id=%d", $table, $count+1, $user);

		print $index;		
	}
	$db->close()
?>
