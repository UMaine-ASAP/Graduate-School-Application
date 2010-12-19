<div id="{{TABLE_NAME}}_{{INDEX}}" class="{{HIDE}}">
	<fieldset class="nested">
		<button class="button red small remove" type="button" onClick='removeItem("{{TABLE_NAME}}_{{INDEX}}",{{USER}}); return false;' title="Remove">Remove</button>
		<label for="dviolation_date[{{INDEX}}]">
			<p class="title">Approximate date of incident</p>
			<input type="text" size="14" maxlength="7" id="dviolation_date[{{INDEX}}]" name="dviolation_date" value="{{DVIOLATION_DATE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
			<p class="help">mm/yyyy</p>
		</label>
			
		<label for="dviolation_details[{{INDEX}}]">
			<p class="title">Please give the approximate date of each incident and explain the circumstances.</p>
			<textarea cols="60" rows="5" id="dviolation_details[{{INDEX}}]" name="dviolation_details" value="" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">{{DVIOLATION_DETAILS}}</textarea>
		</label>
		
	</fieldset>
	<div style="clear:both;"></div>
</div>
