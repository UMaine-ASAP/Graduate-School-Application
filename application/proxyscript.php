<?php

if($_POST['final_submit_app']) {
	unset($_POST['final_submit_app']);
	foreach($_POST as $field => $value) {
		print $field.": ";
		print ($value)?$value."<br />":"Error (Missing Value)<br />";
	}
} else {
	print "You have reached this page in error";
}

?>