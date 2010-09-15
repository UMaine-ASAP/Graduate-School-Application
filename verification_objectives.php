<?php
if($_POST) {
	
	echo "post was TRUE\n<br />";
	
	$START_semester = $_POST['start_semester'];
	$START_year = $_POST['start_year'];
	$PROGRAM_of_study = $_POST['program_of_study'];
	
	
	if (empty($START_semester) || empty($START_year) || empty($PROGRAM_of_study)) {				
					// errors
					if(empty($START_semester)) echo "Start semester must be selected.";
					if(empty($START_year)) echo "Start year must be selected.";
					if(empty($PROGRAM_of_study)) echo"Program of study must be selected.";
	}
} else { echo "no post"; }
?>