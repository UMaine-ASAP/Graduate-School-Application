<div id="{{TABLE_NAME}}_{{INDEX}}" class="{{HIDE}}">
	<fieldset class="nested">
		<button class="button red small remove" type="button" onClick='removeItem("{{TABLE_NAME}}_{{INDEX}}",{{USER}});' title="Remove">Remove</button>
		<label for="previous_school_name[{{INDEX}}]">
			<p class="title">Institution</p>
			<input type="text" size="30" maxlength="30" id="previous_schools_name[{{INDEX}}]" name="previous_schools_name" value="{{PREVIOUS_SCHOOLS_NAME}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>
	
		<label for="previous_school_city[{{INDEX}}]">
			<p class="title">City</p>
			<input type="text" size="30" maxlength="30" id="previous_schools_city[{{INDEX}}]" name="previous_schools_city" value="{{PREVIOUS_SCHOOLS_CITY}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>

		<div style="clear:both"></div>
	
		<label for="previous_schools_state[{{INDEX}}]">
			<p class="title">State/Province</p>
			<select id="previous_schools_state[{{INDEX}}]" name="previous_schools_state" size="1" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"></select>
			<script type="text/javascript">getStateOptions('previous_schools_state[{{INDEX}}]', "{{PREVIOUS_SCHOOLS_STATE}}");</script>
		</label>
		
		<label for="previous_schools_country[{{INDEX}}]">
			<p class="title">Country</p>
			<select id="previous_schools_country[{{INDEX}}]" name="previous_schools_country" size="1" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"></select>
			<script type="text/javascript">getCountryOptions('previous_schools_country[{{INDEX}}]', "{{PREVIOUS_SCHOOLS_COUNTRY}}");</script>
		</label>

		<div style="clear:both"></div>

		<label for="previous_school_from_date[{{INDEX}}]">
			<p class="title">From Date</p>
			<input type="text" size="14" maxlength="7" id="previous_schools_from_date[{{INDEX}}]" name="previous_schools_from_date" value="{{PREVIOUS_SCHOOLS_FROM_DATE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
			<p class="help">mm/yyyy</p>
		</label>
	
		<label for="previous_school_to_date[{{INDEX}}]">
			<p class="title">To Date</p>
			<input type="text" size="14" maxlength="7" id="previous_schools_to_date[{{INDEX}}]" name="previous_schools_to_date" value="{{PREVIOUS_SCHOOLS_TO_DATE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
			<p class="help">mm/yyyy</p>
		</label>
	
		<label for="previous_major[{{INDEX}}]">
			<p class="title">Major</p>
			<input type="text" size="30" maxlength="30" id="previous_schools_major[{{INDEX}}]" name="previous_schools_major" value="{{PREVIOUS_SCHOOLS_MAJOR}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>
		<div style="clear:both"></div>
	
		<label for="previous_degree_earned[{{INDEX}}]">
			<p class="title">Degree or Diploma</p>
			<input type="text" size="30" maxlength="30" id="previous_schools_degree_earned[{{INDEX}}]" name="previous_schools_degree_earned" value="{{PREVIOUS_SCHOOLS_DEGREE_EARNED}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>
	
		<label for="previous_degree_date[{{INDEX}}]">
			<p class="title">Date Received or Expected</p>
			<input type="text" size="14" maxlength="7" id="previous_schools_degree_date[{{INDEX}}]" name="previous_schools_degree_date" value="{{PREVIOUS_SCHOOLS_DEGREE_DATE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
			<p class="help">mm/yyyy</p>
		</label>

		<div style="clear:both;"></div>
	</fieldset>
	<div style="clear:both;"></div>
</div>
