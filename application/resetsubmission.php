<?php

include_once 'libs/extern_variables.php';
include_once 'libs/database.php';

if( $server_name == 'hoonah.asap.um.maine.edu') {
	$db = new Database();

	$db->connect();

	$user = 2;
	$query = "UPDATE applicants SET has_been_submitted = 0 where applicant_id = %d";
	$db->iquery($query, $user);
	$db->close();
}

