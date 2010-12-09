<div id="{{TABLE_NAME}}_{{INDEX}}" class="{{HIDE}}">
	<fieldset class="nested">
		<label for="cviolation_date[{{INDEX}}]">
			<p class=title>Approximate date of incident</p>
			<input type="text" size="14" maxlength="7" id="cviolation_date[{{INDEX}}]" name="cviolation_date" value="{{CVIOLATION_DATE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
			<p class="help">mm/yyyy</p>
		</label>
			
		<div class="removeButton"><img src="images/minus.png" alt="Remove" title="Remove" onClick='removeItem("{{TABLE_NAME}}_{{INDEX}}",{{USER}}); return false;' /></div>
			
		<label for="cviolation_details[{{INDEX}}]">
			<p class="title">Please give the approximate date of each incident and explain the circumstances.</p>
			<textarea cols="60" rows="5" id="cviolation_details[{{INDEX}}]" name="cviolation_details" value="" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">{{CVIOLATION_DETAILS}}</textarea>
		</label>
	</fieldset>
	<div style="clear:both;"></div>
</div>
