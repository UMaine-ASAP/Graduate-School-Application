<?php
if($_POST) {
	echo "post was TRUE\n<br />";
	$viewing = $_POST['viewing_rights_waiver'];
	$reference1_first = $_POST['reference1_first'];
	$reference1_last = $_POST['reference1_last']
	$referemce1_email = $_POST['reference1_email'];
	$reference1_relationship = $_POST['reference1_relationship'];
	$reference2_first = $_POST['reference2_first'];
	$reference2_last = $_POST['reference2_last']
	$referemce2_email = $_POST['reference2_email'];
	$reference2_relationship = $_POST['reference2_relationship'];
	$reference3_first = $_POST['reference3_first'];
	$reference3_last = $_POST['reference3_last']
	$referemce3_email = $_POST['reference3_email'];
	$reference3_relationship = $_POST['reference3_relationship'];

	if (empty($viewing) || (empty($reference1_first) || (empty($referemce1_last) || (empty($reference1_email) || (empty($reference1_relationship) || (empty($reference2_first) || (empty($referemce2_last) || (empty($reference2_email) || (empty($reference2_relationship) || (empty($reference3_first) || (empty($referemce3_last) || (empty($reference3_email) || (empty($reference3_relationship)) {
					
					// errors
					if (empty($viewing) echo "You did not choice if you want to waive your rights to view the letters of referance<br />";
					if (empty($reference1_first) echo "<br />";
					if (empty($reference1_last) echo "<br />";
					if (empty($reference1_email) echo "<br />";
					if (empty($reference1_relationship) echo "<br />";
					if (empty($reference2_first) echo "<br />";
					if (empty($reference2_last) echo "<br />";
					if (empty($reference2_email) echo "<br />";
					if (empty($reference2_relationship) echo "<br />";
					if (empty($reference3_first) echo "<br />";
					if (empty($reference3_last) echo "<br />";
					if (empty($reference3_email) echo "<br />";
					if (empty($reference3_relationship) echo "<br />";
					
	}
} else { echo "no post"; }
?>
