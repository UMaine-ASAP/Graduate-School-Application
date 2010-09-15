<?php
if($_POST) {
	echo "post was TRUE\n<br />";
	$TOEFL_date = $_POST['toefl_date'];
	$TOEFL_score = $_POST['toefl_score'];
	$US_emergency_contact_phone = $_POST['$us_emergency_contact_phone'];
	$HOME_emergency_contact_phone = $_POST['home_emergency_contact_phone'];
	
	$patternScore = "\d";
	$patternDate = "(^((0[1-9])|(1[0-2]))\/(\d{4})$)";
	$patternPhone = "(^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*([\s-./\\])?([0-9]*)([\s-./\\])?([0-9]*)$)";
	$replacement = "";
	
	$TOEFL_Date = preg_replace($patternDate, $replacement, $TOEFL_date);
	$TOEFL_Score = preg_replace($patternScore, $replacement, $TOEFL_score);
	$US_Emergency_contact_phone = preg_replace($patternPhone, $replacement, $US_emergency_contact_phone);
	$HOME_Emergency_contact_phone = preg_replace($patternPhone, $replacement, $HOME_emergency_contact_phone);

	if (($TOEFL_Date != '') || ($TOEFL_Score != '') || ($US_Emergency_contact_phone != '') || ($HOME_Emergency_contact_phone != '')) {
					
					// errors
					if ($TOEFL_Date != '') echo "Date must be in format mm/yyyy .<br />";
					if ($TOEFL_Score != '') echo "TOEFL Grade must be numerical only.<br />";
					if ($US_Emergency_contact_phone != '') echo "Phone must be in format: <br /> (xxx) xxx-xxxx , xxx-xxx-xxxx , xxx xxx xxxx or xxx-xx-xxxxxxxx<br />";
					if ($HOME_Emergency_contact_phone != '') echo "Phone must be in format: <br /> (xxx) xxx-xxxx , xxx-xxx-xxxx , xxx xxx xxxx or xxx-xx-xxxxxxxx<br />";		
	}
} else { echo "no post"; }
?>
