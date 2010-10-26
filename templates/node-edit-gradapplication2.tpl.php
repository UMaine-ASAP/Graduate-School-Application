<fieldset class="collapsible" id="international_information"><legend>International Information</legend>
<?php print $international_radio; ?>
<?php print $international_time; ?>

<fieldset class="collapsible" id="degree_plans"><legend>Degree Plans</legend>
<?php print $international_degree; ?>
<fieldset id="degree_where"><legend>Where</legend>
<?php print $international_where; ?>
<?php print $international_subject; ?>
</fieldset>
<div id="degree_when">
<?php print $international_when; ?>
</div>
</fieldset>
<fieldset class="collapsible" id="grad_work"><legend>Work Plans</legend>
<?php print $international_grad; ?>
<?php print $international_start; ?>
<?php print $international_employer; ?>

<?php print $international_location_name; ?>
<?php print $international_location_street1; ?>
<?php print $international_location_street2; ?>
<?php print $international_location_city; ?>
<?php print $international_location_state; ?>
<?php print $international_location_zip; ?>

</fieldset>
<fieldset class="collapsible" id="int_dependants"><legend>Dependants</legend>
<?php print $international_dependants; ?>
<div id="int_dep_info" style="padding-right: 20px;">
<?php print $international_dep_arrive; ?>
<?php print $international_dep_relation; ?>
<?php print $international_dep_fname; ?>
<?php print $international_dep_lname; ?>
<?php print $international_dep_dob; ?>
<?php print $international_dep_cob; ?>
<?php //print $international_dep_citz; ?>
</div>
<fieldset id="int_dep_address"><legend>Dependant Address Information</legend>
<?php print $international_dep_street1; ?>
<?php print $international_dep_street2; ?>
<?php print $international_dep_city; ?>
<?php print $international_dep_state; ?>
<?php print $international_dep_zip; ?>
<?php print $international_dep_country; ?>
</fieldset>
</fieldset>

</fieldset>

<?php print $prev; ?>
<?php print $next; ?>
<?php print $save; ?>
<?php print $submit; ?>

<?php //print $whole_form?>
<div style="display: none;">
<?php print drupal_render($form); ?>
</div>