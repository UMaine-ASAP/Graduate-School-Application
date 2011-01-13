<div id="{{TABLE_NAME}}_{{INDEX}}" class="{{HIDE}}">
	<fieldset class="nested" >
		<button class="button red small remove" type="button" onClick='removeItem("{{TABLE_NAME}}_{{INDEX}}",{{USER}});' title="Remove">Remove</button>
		<h2>Additional Recommendation {{COUNT_INDEX}}</h2>
		<div>
			<label for="reference_online[{{INDEX}}]">
				<p class="required title">Will this individual submit a recommendation online? *</p>
				<label for="reference_online_yes[{{INDEX}}]"><input type="radio" class='reference_online' id="reference_online_yes[{{INDEX}}]" name="reference_online[{{INDEX}}]" value="1" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" /> Yes</label>
				<label for="reference_online_no[{{INDEX}}]"><input type="radio" class='reference_online' id="reference_online_no[{{INDEX}}]" name="reference_online[{{INDEX}}]" value="0" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" checked /> No</label>
			</label>
			<script type="text/javascript">checkInitValue('reference_online_yes[{{INDEX}}]',"{{REFERENCE_ONLINE}}");</script>
			<script type="text/javascript">checkInitValue('reference_online_no[{{INDEX}}]',"{{REFERENCE_ONLINE}}");</script>

			<div style="clear:both"></div>
		
			<p class="warning">An electronic recommendation form will be emailed to this recommender if you click yes.</p>

			<div style="clear:both"></div>

			<label for="reference_first[{{INDEX}}]">
				<p class="required title">First Name *</p>
				<input type="text" size="30" maxlength="30" id="reference_first[{{INDEX}}]" name="reference_first" value="{{REFERENCE_FIRST}}" class="reference_first" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>
			
			<label for="reference_last[{{INDEX}}]">
				<p class="required title">Last Name *</p>
				<input type="text" size="30" maxlength="30" id="reference_last[{{INDEX}}]" name="reference_last" value="{{REFERENCE_LAST}}" class="reference_last" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>

			<label for="reference_email[{{INDEX}}]">
				<p class="title">E-Mail</p>
				<input type="text" size="30" id="reference_email[{{INDEX}}]" name="reference_email" value="{{REFERENCE_EMAIL}}" class="reference_email" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>
			
			<label for="reference_relationship[{{INDEX}}]">
				<p class="title">Relationship</p>
				<select id="reference_relationship[{{INDEX}}]" name="reference_relationship" value="{{REFERENCE_RELATIONSHIP}}" class="reference_relationship" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
					<option value=""	>- None -</option>
					<option value="Work" 	>Work</option>
					<option value="School" 	>School</option>
					<option value="Family" 	>Family</option>
					<option value="Friend" 	>Friend</option>
				</select>
			</label>
			<script type="text/javascript">initValue('reference_relationship[{{INDEX}}]',"{{REFERENCE_RELATIONSHIP}}");</script>
			
			<label for="reference_phone[{{INDEX}}]">
				<p class="title">Phone Number</p>
				<input type="text" size="16" maxlength="16" id="reference_phone[{{INDEX}}]" name="reference_phone" value="{{REFERENCE_PHONE}}" class="reference_phone" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>
			
		</div>

		<div style="clear:both"></div>
		
		<fieldset id="reference_addr[{{INDEX}}]" name="reference_addr" class="nested">
			<legend>Address</legend>
			<label for="reference_addr1[{{INDEX}}]">
				<p class="title">Street</p>
				<input type="text" size="30" maxlength="30" id="reference_addr1[{{INDEX}}]" name="reference_addr1" value="{{REFERENCE_ADDR1}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>
			
			<label for="reference_addr2[{{INDEX}}]">
				<p class="title">Additional</p>
				<input type="text" size="30" maxlength="30" id="reference_addr2[{{INDEX}}]" name="reference_addr2" value="{{REFERENCE_ADDR2}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>
			
			<label for="reference_city[{{INDEX}}]">
				<p class="title">City</p>
				<input type="text" size="30" maxlength="30" id="reference_city[{{INDEX}}]" name="reference_city" value="{{REFERENCE_CITY}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>
			
			<label for="reference_state[{{INDEX}}]">
				<p class="title">State/Province</p>
				<select id="reference_state[{{INDEX}}]" name="reference_state" size="1" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"></select>
				<script type="text/javascript">getStateOptions('reference_state[{{INDEX}}]');</script>
				<script type="text/javascript">initValue('reference_state[{{INDEX}}]',"{{REFERENCE_STATE}}")</script>
			</label>
			
			<label for="reference_postal[{{INDEX}}]">
				<p class="title">Postal Code</p>
				<input type="text" size="30" maxlength="30" id="reference_postal[{{INDEX}}]" name="reference_postal" value="{{REFERENCE_POSTAL}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>
			
			<label for="reference_country[{{INDEX}}]">
				<p class="title">Country</p>
				<select id="reference_country[{{INDEX}}]" name="reference_country" size="1" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"></select>
				<script type="text/javascript">getCountryOptions('reference_country[{{INDEX}}]');</script>
				<script type="text/javascript">initValue('reference_country[{{INDEX}}]',"{{REFERENCE_COUNTRY}}")</script>
			</label>
				
			<div style="clear:both"></div>
		</fieldset><!-- end fieldset "reference_addr" -->
		
		<div style="clear:both"></div>
	</fieldset>

	<div style="clear:both;"></div>
</div>
