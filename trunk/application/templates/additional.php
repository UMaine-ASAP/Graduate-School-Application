<form action="" method="post" id="objectives">
	<input type="hidden" id="user_id" value="{{USER}}"/>
	<fieldset id="education_objectives" name="education_objectives">
		<legend>Additional Programs</legend>
		<span class="required"><p>Fields marked with * are required.</p></span>		
		
		<fieldset id="applied_programs" name="applied_programs">
			<legend>Academic Programs</legend>
			Select one or more programs to apply to.
		
		
			<div id={{APPLIEDPROGRAMS_LIST}}>
				{{APPLIEDPROGRAMS_REPEATABLE}}
			</div>
			<div style="clear:both"></div>
			<div class="addButton"><img src="images/plus.png" alt="Add an Item" title="Add an Item" onClick="addItem('{{APPLIEDPROGRAMS_TABLE_NAME}}'); return false;" />&nbsp;Add another program</div>
		</fieldset>
		<form id="submit_form" method="post">
			<input type="submit" id="submit_app" name="submit_app" value="Resubmit Programs"/>
			<!--Field to tell whether you are submitting new programs-->
			<input type="hidden">
		</form>
	</fieldset><!-- end fieldset "education_objectives" -->
</form>