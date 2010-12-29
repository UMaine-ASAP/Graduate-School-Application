<?php //print $academic_history_group; ?>
<fieldset class="collapsible" id="academic_history"><legend>Academic History</legend>
<?php print $applied; ?>
<?php print $app_date; ?>
</fieldset>

<?php //print $inst_group; ?>

<fieldset class="collapsible" id="attended_institutions"><legend>Previously Attended Institutions</legend>
<?php print $institution; ?>
<?php print $major; ?>
<?php print $degree; ?>
<?php print $gpa; ?>
	<fieldset id="where"><legend>Where?</legend>
<?php print $city; ?>
<?php print $state; ?>
<?php print $country; ?>
	</fieldset>
	<fieldset id="when"><legend>When?</legend>
<?php //print $date; ?>
<?php print $from_date; ?>
<?php print $to_date; ?>
	</fieldset>
</fieldset>

<fieldset class="collapsible" id="violations"><legend>Post Secondary Violations</legend>
<?php print $violation; ?>
<?php print $violation_date; ?>
<?php print $violation_details; ?>
</fieldset>

<fieldset class="collapsible" id="courses"><legend>Expected Courses</legend>
<?php print $courses; ?>
</fieldset>

<fieldset class="collapsible" id="test_scores"><legend>Test Scores</legend>
<?php //print $test_scores_group; ?>
<?php print $test; ?>
<?php print $status; ?>
<?php print $date; ?>
<?php print $sent; ?>
<?php print $verbal; ?>
<?php print $analytical; ?>
<?php print $quantitative; ?>
<?php print $subject; ?>
</fieldset>
      
<?php print $prev; ?>
<?php print $next; ?>
<?php print $save; ?>
<?php print $submit; ?>
     

<?php //print $whole_form?>
<pre>
<?php print_r($int); ?>	
</pre>
<div style="display: none;">
<?php print drupal_render($form); ?>
</div>