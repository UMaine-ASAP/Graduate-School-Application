<form action="" method="post" id="recommendations" autocomplete="off">
<input type="hidden" id="user_id" value="{{USER}}"/>
<fieldset id="letters_of_recommendation" name="letters_of_recommendation">
	<legend>Letters of Recommendation</legend>
	<span class="required"><p>Fields marked with * are required.</p></span>
	<p class="message">Please list the contact information of three people whom you are requesting to send letters of recommendation.  The letters of recommendation must be of recent date and written by people qualified, through personal experience with your academic work, to judge your capacity for advanced study. Recommendations must be sent directly from these people to the Graduate School.</p>
	
	<fieldset id="viewing_rights" name="viewing_rights" class="nested">
		<legend>Waiver of Viewing Rights</legend>
		
		<label for="waive_view_rights">
			<p class="message">The Family Education Rights and Privacy Act of 1974 (P.L. 93-380) gives students access to information in their application files. However, to ensure the references will be free to write a candid letter of recommendation, an applicant may waive the right to see letters of reference.</p>
			<p class="required title">Please select an option below: *</p>
			<label for="waive_view_rights_yes"><input type="radio" id="waive_view_rights_yes" name="waive_view_rights" value="1" class="waive_view_rights" onchange="saveCheckValue(event,{{USER}});"> Yes, I wish to waive my right to see letters of reference.</label><br />
			<label for="waive_view_rights_no"><input type="radio" id="waive_view_rights_no" name="waive_view_rights" value="0" class="waive_view_rights" onchange="saveCheckValue(event,{{USER}});" /> No, I do not wish to waive my right to see letters of reference.</label>
		</label>
		<script type="text/javascript">checkInitValue('waive_view_rights_yes',"{{WAIVE_VIEW_RIGHTS}}");</script>
		<script type="text/javascript">checkInitValue('waive_view_rights_no',"{{WAIVE_VIEW_RIGHTS}}");</script>
		
		<div style="clear:both"></div>
	</fieldset><!-- end fieldset "viewing_rights" -->

	<div style="clear:both"></div>

	<fieldset id="reference1" name="reference1" class="nested">
		<legend>Recommendation 1</legend>
		<div>
			<div style="clear:both"></div>

			<label for="reference1_online" >
				<p class="required title">Will this individual submit a recommendation online? *</p>
				<label for="reference1_online_yes"><input type="radio" id="reference1_online_yes" name="reference1_online" value="1" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
				<label for="reference1_online_no"><input type="radio" id="reference1_online_no" name="reference1_online" value="0" onchange="saveCheckValue(event,{{USER}});" /> No</label>
			</label>
			<script type="text/javascript">checkInitValue('reference1_online_yes',"{{REFERENCE1_ONLINE}}");</script>
			<script type="text/javascript">checkInitValue('reference1_online_no',"{{REFERENCE1_ONLINE}}");</script>
		
			<div style="clear:both"></div>

			<p class="message warning">An electronic recommendation form will be emailed to this recommender if you click yes.</p>

			<label for="reference1_first">
				<p class="required title">First Name *</p>
				<input type="text" size="30" maxlength="30" id="reference1_first" name="reference1_first" value="{{REFERENCE1_FIRST}}" class="reference1_first" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference1_last">
				<p class="required title">Last Name *</p>
				<input type="text" size="30" maxlength="30" id="reference1_last" name="reference1_last" value="{{REFERENCE1_LAST}}" class="reference1_last" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference1_email">
				<p class="title">E-Mail</p>
				<input type="text" size="30" id="reference1_email" name="reference1_email" value="{{REFERENCE1_EMAIL}}" class="reference1_email" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference1_relationship">
				<p class="title">Relationship</p>
				<select id="reference1_relationship" name="reference1_relationship" class="reference1_relationship" onchange="saveValue(event,{{USER}});">
					<option value=""	>- None -</option>
					<option value="Work" 	>Work</option>
					<option value="School" 	>School</option>
					<option value="Family" 	>Family</option>
					<option value="Friend" 	>Friend</option>
				</select>
			</label>
			
			<label for="reference1_phone">
				<p class="required title">Phone Number *</p>
				<input type="text" size="16" maxlength="16" id="reference1_phone" name="reference1_phone" value="{{REFERENCE1_PHONE}}" class="reference1_phone" onblur="saveValue(event,{{USER}});"/>
			</label>
			<script type="text/javascript">initValue('reference1_relationship',"{{REFERENCE1_RELATIONSHIP}}");</script>

			<div style="clear:both"></div>
		</div>

		<div style="clear:both"></div>

		<fieldset id="reference1_addr" name="reference1_addr" class="nested">
			<legend>Address</legend>
			<label for="reference1_addr1">
				<p class="title">Street</p>
				<input type="text" size="30" maxlength="30" id="reference1_addr1" name="reference1_addr1" value="{{REFERENCE1_ADDR1}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference1_addr2">
				<p class="title">Additional</p>
				<input type="text" size="30" maxlength="30" id="reference1_addr2" name="reference1_addr2" value="{{REFERENCE1_ADDR2}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference1_city">
				<p class="title">City</p>
				<input type="text" size="30" maxlength="30" id="reference1_city" name="reference1_city" value="{{REFERENCE1_CITY}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference1_state">
				<p class="title">State/Province</p>
				<select id="reference1_state"" name="reference1_state" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getStateOptions('reference1_state');</script>
				<script type="text/javascript">initValue('reference1_state',"{{REFERENCE1_STATE}}");</script>
			</label>
			
			<label for="reference1_postal">
				<p class="title">Postal Code</p>
				<input type="text" size="30" maxlength="30" id="reference1_postal" name="reference1_postal" value="{{REFERENCE1_POSTAL}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference1_country">
				<p class="title">Country</p>
				<select id="reference1_country"" name="reference1_country" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getCountryOptions('reference1_country');</script>
				<script type="text/javascript">initValue('reference1_country',"{{REFERENCE1_COUNTRY}}");</script>
			</label>
			
			<div style="clear:both"></div>
		</fieldset><!-- end fieldset "reference1_addr" -->

		<div style="clear:both"></div>
	</fieldset><!-- end fieldset "reference1" -->


	<fieldset id="reference2" name="reference2" class="nested">
		<legend>Recommendation 2</legend>
		<div>
			<label for="reference2_online">
				<p class="required title">Will this individual submit a recommendation online? *</p>
				<label for="reference2_online_yes"><input type="radio" id="reference2_online_yes" name="reference2_online" value="1" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
				<label for="reference2_online_no"><input type="radio" id="reference2_online_no" name="reference2_online" value="0" onchange="saveCheckValue(event,{{USER}});"  /> No</label>

			</label>
			<script type="text/javascript">checkInitValue('reference2_online_yes',"{{REFERENCE2_ONLINE}}");</script>
			<script type="text/javascript">checkInitValue('reference2_online_no',"{{REFERENCE2_ONLINE}}");</script>

			<div style="clear:both"></div>

			<p class="message warning">An electronic recommendation form will be emailed to this recommender if you click yes.</p>

			<label for="reference2_first">
				<p class="required title">First Name *</p>
				<input type="text" size="30" maxlength="30" id="reference2_first" name="reference2_first" value="{{REFERENCE2_FIRST}}" class="reference2_first" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference2_last">
				<p class="required title">Last Name *</p>
				<input type="text" size="30" maxlength="30" id="reference2_last" name="reference2_last" value="{{REFERENCE2_LAST}}" class="reference2_last" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference2_email">
				<p class="title">E-Mail</p>
				<input type="text" size="30" id="reference2_email" name="reference2_email"value="{{REFERENCE2_EMAIL}}" class="reference2_email" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference2_relationship">
				<p class="title">Relationship</p>
				<select id="reference2_relationship" name="reference2_relationship" class="reference2_email" onchange="saveValue(event,{{USER}});">
					<option value=""	>- None -</option>
					<option value="Work" 	>Work</option>
					<option value="School" 	>School</option>
					<option value="Family" 	>Family</option>
					<option value="Friend" 	>Friend</option>
				</select>
			</label>

			<label for="reference2_phone">
				<p class="required title">Phone Number *</p>
				<input type="text" size="16" maxlength="16" id="reference2_phone" name="reference2_phone" value="{{REFERENCE2_PHONE}}" class="reference2_phone" onblur="saveValue(event,{{USER}});"/>
			</label>
			<script type="text/javascript">initValue('reference2_relationship',"{{REFERENCE2_RELATIONSHIP}}");</script>
		</div>

		<div style="clear:both"></div>

		<fieldset id="reference2_addr" name="reference2_addr" class="nested">
		<legend>Address</legend>
			<label for="reference2_addr1">
				<p class="title">Street</p>
				<input type="text" size="30" maxlength="30" id="reference2_addr1" name="reference2_addr1" value="{{REFERENCE2_ADDR1}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference2_addr2">
				<p class="title">Additional</p>
				<input type="text" size="30" maxlength="30" id="reference2_addr2" name="reference2_addr2" value="{{REFERENCE2_ADDR2}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference2_city">
				<p class="title">City</p>
				<input type="text" size="30" maxlength="30" id="reference2_city" name="reference2_city" value="{{REFERENCE2_CITY}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference2_state">
				<p class="title">State/Province</p>
				<select id="reference2_state"" name="reference2_state" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getStateOptions('reference2_state');</script>
				<script type="text/javascript">initValue('reference2_state',"{{REFERENCE2_STATE}}");</script>
			</label>
			
			<label for="reference2_postal">
				<p class="title">Postal Code</p>
				<input type="text" size="30" maxlength="30" id="reference2_postal" name="reference2_postal" value="{{REFERENCE2_POSTAL}}" onblur="saveValue(event,{{USER}});"/>
			</label>
							
			<label for="reference2_country">
				<p class="title">Country</p>
				<select id="reference2_country"" name="reference2_country" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getCountryOptions('reference2_country');</script>
				<script type="text/javascript">initValue('reference2_country',"{{REFERENCE2_COUNTRY}}");</script>
			</label>
			
			<div style="clear:both"></div>
		</fieldset><!-- end fieldset "reference2_addr" -->
		
		<div style="clear:both"></div>
	</fieldset><!-- end fieldset "reference2" -->

	<div style="clear:both"></div>

	<fieldset id="reference3" name="reference3" class="nested">
		<legend>Recommendation 3</legend>
		<div>
			<label for="reference3_online" >
				<p class="required title">Will this individual submit a recommendation online? *</p>
				<label for="reference3_online_yes"><input type="radio" id="reference3_online_yes" name="reference3_online" value="1" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
				<label for="reference3_online_no"><input type="radio" id="reference3_online_no" name="reference3_online" value="0" onchange="saveCheckValue(event,{{USER}});" checked /> No</label>
			</label>
			<script type="text/javascript">checkInitValue('reference3_online_yes',"{{REFERENCE3_ONLINE}}");</script>
			<script type="text/javascript">checkInitValue('reference3_online_no',"{{REFERENCE3_ONLINE}}");</script>

			<div style="clear:both"></div>

			<p class="message warning">An electronic recommendation form will be emailed to this recommender if you click yes.</p>

			<label for="reference3_first">
				<p class="required title">First Name *</p>
				<input type="text" size="30" maxlength="30" id="reference3_first" name="reference3_first" value="{{REFERENCE3_FIRST}}" class="reference3_first" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference3_last">
				<p class="required title">Last Name *</p>
				<input type="text" size="30" maxlength="30" id="reference3_last" name="reference3_last" value="{{REFERENCE3_LAST}}" class="reference3_last" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference3_email">
				<p class="title">E-Mail</p>
				<input type="text" size="30" id="reference3_email" name="reference3_email" value="{{REFERENCE3_EMAIL}}" class="reference3_email" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference3_relationship">
				<p class="title">Relationship</p>
				<select id="reference3_relationship" name="reference3_relationship" class="reference3_relationship" onchange="saveValue(event,{{USER}});">
					<option value=""	>- None -</option>
					<option value="Work" 	>Work</option>
					<option value="School" 	>School</option>
					<option value="Family" 	>Family</option>
					<option value="Friend" 	>Friend</option>
				</select>
			</label>

			<label for="reference3_phone">
				<p class="required title">Phone Number *</p>
				<input type="text" size="16" maxlength="16" id="reference3_phone" name="reference3_phone" value="{{REFERENCE3_PHONE}}" class="reference3_phone" onblur="saveValue(event,{{USER}});"/>
			</label>
			<script type="text/javascript">initValue('reference3_relationship',"{{REFERENCE3_RELATIONSHIP}}");</script>
		</div>

		<div style="clear:both"></div>

		<fieldset id="reference3_addr" name="reference3_addr" class="nested">
		<legend>Address</legend>
			<label for="reference3_addr1">
				<p class="title">Street</p>
				<input type="text" size="30" maxlength="30" id="reference3_addr1" name="reference3_addr1" value="{{REFERENCE3_ADDR1}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference3_addr2">
				<p class="title">Additional</p>
				<input type="text" size="30" maxlength="30" id="reference3_addr2" name="reference3_addr2" value="{{REFERENCE3_ADDR2}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference3_city">
				<p class="title">City</p>
				<input type="text" size="30" maxlength="30" id="reference3_city" name="reference3_city" value="{{REFERENCE3_CITY}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference3_state">
				<p class="title">State/Province</p>
				<select id="reference3_state"" name="reference3_state" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getStateOptions('reference3_state');</script>
				<script type="text/javascript">initValue('reference3_state',"{{REFERENCE3_STATE}}");</script>
			</label>
			
			<label for="reference3_postal">
				<p class="title">Postal Code</p>
				<input type="text" size="30" maxlength="30" id="reference3_postal" name="reference3_postal" value="{{REFERENCE3_POSTAL}}" onblur="saveValue(event,{{USER}});"/>
			</label>
			
			<label for="reference3_country">
				<p class="title">Country</p>
				<select id="reference3_country"" name="reference3_country" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getCountryOptions('reference3_country');</script>
				<script type="text/javascript">initValue('reference3_country',"{{REFERENCE3_COUNTRY}}");</script>
			</label>
			
			<div style="clear:both"></div>
		</fieldset><!-- end fieldset "reference3_addr" -->

		<div style="clear:both"></div>
	</fieldset><!-- end fieldset "reference3" -->

	<div style="clear:both"></div>

	<fieldset id="extrareferences" name="extrareferences" class="nested">
		<legend>Additional References</legend>
		<p class="message">If you would like to add a reference, use the button below.</p>

		<div id={{EXTRAREFERENCES_LIST}}>
			<input type="hidden" id="gre_count" value="{{REFERENCES_COUNT}}" />
			<input type="hidden" id="gre_count" value="{{REFERENCES_MIN}}" />
			{{EXTRAREFERENCES_REPEATABLE}}
		</div>

		<div style="clear:both"></div>
	
		<div class="addButton"><img src="images/plus.png" alt="Add an Item" title="Add an Item" onClick="addItem('{{EXTRAREFERENCES_TABLE_NAME}}'); return false;" />&nbsp;Add another reference</div>

		<div style="clear:both"></div>
	</fieldset>

	<div style="clear:both"></div>

	<div id="pager">
		<p><span style="float:left;"><a href="app_manager.php?form_id=5"> &lt;&lt; Previous Section</a></span>
		<span style="float:right;"><a href="submission_manager.php"> Review Application >> </a></span></p>
	</div>
	
	<div style="clear:both"></div>
</fieldset><!-- end fieldset "letters_of_recommendation" -->
</form>
