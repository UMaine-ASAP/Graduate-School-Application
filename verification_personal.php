<?php
//
// need to add verification for primary phone number
//
if($_POST) {
	echo "post was TRUE\n<br />";
	$given_name = $_POST['given_name'];
	$family_name = $_POST['family_name'];
	$permanent_addr1 = $_POST['permanent_addr1'];
	$permanent_city = $_POST['permanent_city'];
	////////////////////////////////////////////////////////////////////////
	$permanent_postal = $_POST['permanent_postal'];
	////////////////////////////////////////////////////////////////////////
	$permanent_country = $_POST['permanent_country'];
	$primary_phone = $_POST['primary_phone'];
	$email = $_POST['email'];
	
	if (empty($given_name) || empty($family_name) || empty($permanent_addr1) || empty($permanent_city) || empty($permanent_postal) || empty($permanent_country) || empty($primary_phone) || empty($email)) {
					
					// errors
					if (empty($given_name)) {
						echo "You did not enter a First Name<br />";
					}
					if (empty($family_name)) {
						echo "You did not enter a Last Name<br />";
					}
					if (empty($permanent_addr1)) {
						echo "You did not enter your Permanent Address<br />";
					}	
					if (empty($permanent_city)) {
						echo "You did not enter the City of your Permanent Address<br />";
					}
					
					////////////////////////////////////////////////
					if (empty($permanent_postal)) {
						echo "You did not enter the Postal Code of your Permanent Address<br />";
					}
					////////////////////////////////////////////////
					
					if (empty($permanent_country)) {
						echo "You did not enter the Country of your Permanent Address<br />";
					}
					if (empty($primary_phone)) {
						echo "You did not enter a Primary Phone Number";
					}
					if (empty($email)) {
						echo "You did not enter an Email Address";
					}
	
} else { echo "no post"; }
?>