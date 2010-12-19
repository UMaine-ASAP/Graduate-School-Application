<fieldset class="collapsible" id="personal_information"><legend>Personal Information</legend>
<?php print $first_name; ?>
<?php print $middle_name; ?>
<?php print $last_name; ?>
<?php print $maiden_name; ?>
<?php print $email_address; ?>
<?php print $gender; ?>
<?php print $ssn; ?>
<?php print $ethnic_background; ?>
</fieldset>

<fieldset class="collapsible" id="place_of_birth"><legend>Place of Birth</legend>
<?php print $city_of_birth; ?>
<?php print $state_of_birth; ?>
<?php print $country_of_birth; ?>
<?php print $country_of_citizenship; ?>
<?php print $residency_status; ?>
<?php print $date_of_birth; ?>
</fieldset>

<fieldset class="collapsible" id="perm_address"><legend>Permanent Address</legend>
<?php print $perm_street_1; ?>
<?php print $perm_street_2; ?>
<?php print $perm_city; ?>
<?php print $perm_state; ?>
<?php print $perm_zip; ?>
<?php print $perm_country; ?>
</fieldset>

<fieldset class="collapsible" id="mail_address"><legend>Mailing Address</legend>
<?php print $mail_street_1; ?>
<?php print $mail_street_2; ?>
<?php print $mail_city; ?>
<?php print $mail_state; ?>
<?php print $mail_zip; ?>
<?php print $mail_country; ?>
</fieldset>

<fieldset class="collapsible" id="phone_numbers"><legend>Phone Numbers</legend>
<?php print $phone_primary; ?>
<?php print $phone_work; ?>
<?php print $phone_fax; ?>
</fieldset>

<fieldset class="collapsible" id="other_info"><legend>Other Information</legend>
<?php print $student_type; ?>
<?php print $entry_date; ?>
<?php print $entry_year; ?>
</fieldset>

<fieldset class="collapsible" id="emergency_contact"><legend>Emergency Contact Information</legend>
<div id="emergency_contact_name">
<?php print $emergency_first; ?>
<?php print $emergency_last; ?>
<?php print $emergency_relation; ?>
<?php print $emergency_phone; ?>
</div>
<div id="emergency_contact_address">
<?php print $e_street_1; ?>
<?php print $e_street_2; ?>
<?php print $e_city; ?>
<?php print $e_state; ?>
<?php print $e_zip; ?>
<?php //print $e_country; ?>
</div>
</fieldset>

<fieldset class="collapsible" id="language_info"><legend>Language Information</legend>
<?php print $language; ?>
<?php print $english; ?>
</fieldset>

<fieldset class="collapsible" id="crime_info"><legend>Crime Information</legend>
<?php print $crime_radio; ?>
<?php print $crime; ?>
<?php print $crime_doi; ?>
<?php print $crime_explanation; ?>
</fieldset>

<?php print $prev; ?>
<?php print $next; ?>
<?php print $save; ?>
<?php print $submit; ?>

<?php //print $whole_form?>

<div style="display: none;">
<?php print drupal_render($form); ?>
</div>