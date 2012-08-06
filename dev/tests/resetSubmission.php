<?php

include "../application/libs/variables.php";
include "../application/libs/database.php";

$applicant_id = $_GET['id'];

$db = Database::get();
$db->iquery("UPDATE applicants set has_been_submitted = 0 WHERE applicant_id = %d", $applicant_id);



echo "updated";

$db->close();
