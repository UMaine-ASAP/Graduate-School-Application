<?php
include_once "libs/variables.php";
include_once "libs/database.php";

$db = new Database();
$db->connect();

$key = $GLOBALS['key'];
$user = 2;
$ssn = "999-99-9999";

$db->iquery("UPDATE applicants SET social_security_number=AES_ENCRYPT('%s', %s) WHERE applicant_id=%d", $ssn, $key, $user);

$ssn = $db->query("SELECT AES_DECRYPT(social_security_number, '%s') AS 'social_security_number' FROM applicants WHERE applicants.applicant_id=%d LIMIT 1", $key, $user);
$ssn = $ssn[0][0];

echo "SSN: ".print_r($ssn, true)."<br />\n";




/*
	$primary_query   = "";
	$primary_query  .= "SELECT applicant_id, given_name, middle_name, family_name, suffix, ";
	$primary_query  .= "email, alternate_name, ";
	$primary_query  .= "mailing_perm, mailing_addr1, mailing_addr2, mailing_city, mailing_state, mailing_postal, mailing_country, ";
	$primary_query  .= "permanent_addr1, permanent_addr2, permanent_city, permanent_state, permanent_postal, permanent_country, ";
	$primary_query  .= "primary_phone, secondary_phone, present_occupation, ethnicity_hispa, ";
	$primary_query  .= "date_of_birth, birth_city, birth_state, birth_country, gender, us_citizen, us_state, residency_status, country_of_citizenship, ";
	//$primary_query	.= "social_security_number, ";
	$primary_query	.= "AES_DECRYPT(social_security_number, '%s') AS 'social_security_number', ";
	$primary_query  .= "ethnicity_amind, ethnicity_asian, ethnicity_black, ethnicity_pacif, ethnicity_white ";
	$primary_query  .= "FROM applicants ";
	$primary_query  .= "WHERE applicant_id = %d";

	$personal_data = $db->query($primary_query, $key, $user);
	//$personal_data = $db->query($primary_query, $user);
	$personal_data = $personal_data[0];

print_r($personal_data);
*/



$db->close();

?>
