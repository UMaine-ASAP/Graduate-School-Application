<fieldset class="collapsible" id="work_history"><legend>Work History and Awards</legend>
<?php print $studies; ?>
<?php print $honors; ?>
<?php print $resume; ?>
</fieldset>

<fieldset class="collapsible" id="recommendations"><legend>Recommendations</legend>
<?php print $recommendations_first_name; ?>
<?php print $recommendations_last_name; ?>
<?php print $recommendations_employer; ?>
<?php print $recommendations_job_title; ?>
<?php print $recommendations_address; ?>
<?php print $recommendations_email; ?>
<?php print $recommendations_relation; ?>
<?php print $recommendations_online; ?>
<?php print $recommendations_prior; ?>
</fieldset>

<?php print $prev; ?>
<?php print $next; ?>
<?php print $save; ?>
<?php print $submit; ?>

<?php //print $whole_form?>
<div style="display: none;">
<?php print drupal_render($form); ?>
</div>