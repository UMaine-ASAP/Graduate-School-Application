<div id="{{TABLE_NAME}}_{{INDEX}}">
	<fieldset id="toefl" name="toefl" class="nested">
		<legend>TOEFL Exam</legend>
				
		<label for="toefl_taken[{{INDEX}}]" class="one_line">
			<p class="title">Have you taken or plan to take the TOEFL examination?</p>
			<label for="toefl_taken_yes[{{INDEX}}]"><input type="radio" id="toefl_taken_yes[{{INDEX}}]" name="toefl_taken" value="1" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" onclick="visibility(this.name+'_section[{{INDEX}}]','block')"> Yes</label>
			<label for="toefl_taken_no[{{INDEX}}]"><input type="radio" id="toefl_taken_no[{{INDEX}}]" name="toefl_taken" value="0" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" onclick="visibility(this.name+'_section[{{INDEX}}]','none')" checked> No</label>
		</label>
		<script type="text/javascript">checkInitValue('toefl_taken_yes[{{INDEX}}]',"{{TOEFL_TAKEN}}");</script>
		<script type="text/javascript">checkInitValue('toefl_taken_no[{{INDEX}}]',"{{TOEFL_TAKEN}}");</script>

		<div style="clear:both"></div>

		<div class="hidden" id="toefl_taken_section[{{INDEX}}]">
	
			<label for="toefl_date[{{INDEX}}]">
				<p class="title">Date of Exam</p>
				<input type="text" size="7" maxlength="7" id="toefl_date[{{INDEX}}]" name="toefl_date" value="{{TOEFL_DATE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
				<p class="help">mm/yyyy</p>
			</label>
		
			<label for="toefl_score[{{INDEX}}]">
				<p class="title">TOEFL Score</p>
				<input type="text" size="7" maxlength="3" id="toefl_score[{{INDEX}}]" name="toefl_score" value="{{TOEFL_SCORE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
			</label>

			<div style="clear:both"></div>
			
			<label for="toefl_reported[{{INDEX}}]" class="one_line">
				<p class="title">Has this score been reported to the University of Maine?</p>
				<label  for="toefl_reported_yes[{{INDEX}}]"><input type="radio" id="toefl_reported_yes[{{INDEX}}]" name="toefl_reported" value="1" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">Yes </label>
				<label  for="toefl_reported_no[{{INDEX}}]"><input type="radio" id="toefl_reported_no[{{INDEX}}]" name="toefl_reported" value="0" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" checked> No </label>
			</label>
			<script type="text/javascript">checkInitValue('toefl_reported_yes[{{INDEX}}]',"{{TOEFL_REPORTED}}");</script>
			<script type="text/javascript">checkInitValue('toefl_reported_no[{{INDEX}}]',"{{TOEFL_REPORTED}}");</script>

			<div style="clear:both"></div>	
		
			<p class="message"><strong>Please have official scores sent directly to the graduate school from the testing institution:</strong> For TOEFL, Educational Testing Service, <a href="http://www.ets.org" class="text_link">www.ets.org</a>, institution code for UM (ORONO): 3916</p>

			<p class="message"><strong>Remember: International applicants are required to submit TOEFL score reports sent directly from ETS, unless they received a degree from an English speaking institution.</strong></p>
		</div>
		<script type="text/javascript">showOrNot('toefl_taken_section[{{INDEX}}]',{{TOEFL_TAKEN}});</script>

		<div style="clear:both"></div>
	</fieldset><!-- end fieldset "toefl" -->

	<div style="clear:both"></div>
	
	<fieldset id="future_plans[{{INDEX}}]" name="future_plans" class="nested">
		<legend>Future Plans</legend>
		
		<label for="further_studies[{{INDEX}}]" class="one_line">
			<p class="title">Will you continue studies for another degree?</p>
			<label for="further_studies_yes[{{INDEX}}]"><input type="radio" id="further_studies_yes[{{INDEX}}]" name="further_studies" value="1" onclick="visibility(this.name+'_section[{{INDEX}}]','block')" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" /> Yes</label>
			<label for="further_studies_no[{{INDEX}}]"><input type="radio" id="further_studies_no[{{INDEX}}]" name="further_studies" value="0" onclick="visibility(this.name+'_section[{{INDEX}}]','none')" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" checked /> No</label>
		</label>
		<script type="text/javascript">checkInitValue('further_studies_yes[{{INDEX}}]',"{{FURTHER_STUDIES}}");</script>
		<script type="text/javascript">checkInitValue('further_studies_no[{{INDEX}}]',"{{FURTHER_STUDIES}}");</script>

		<div style="clear:both"></div>

		<div class="hidden" id="further_studies_section[{{INDEX}}]">		
			<label for="further_studies_details[{{INDEX}}]">
				<p class="title">Briefly describe the subject, location, estimated beginning date and approximate duration</p>
				<textarea cols="60" rows="5" id="further_studies_details[{{INDEX}}]" name="further_studies_details" value="" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">{{FURTHER_STUDIES_DETAILS}}</textarea>
			</label>
		</div>
		<script type="text/javascript">showOrNot('further_studies_section[{{INDEX}}]',{{FURTHER_STUDIES}});</script>
					
		<label for="us_career[{{INDEX}}]" class="one_line">
			<p class="title">Will you pursue a career <strong>in the United States</strong> after completion of studies?</p>
			<label for="us_career_yes[{{INDEX}}]"><input type="radio" id="us_career_yes[{{INDEX}}]" name="us_career" value="1" onclick="visibility(this.name+'_section[{{INDEX}}]','block')" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" /> Yes</label>
			<label for="us_career_no[{{INDEX}}]"><input type="radio" id="us_career_no[{{INDEX}}]" name="us_career" value="0" onclick="visibility(this.name+'_section[{{INDEX}}]','none')" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" checked /> No</label>
		</label>
		<script type="text/javascript">checkInitValue('us_career_yes[{{INDEX}}]',"{{US_CAREER}}");</script>
		<script type="text/javascript">checkInitValue('us_career_no[{{INDEX}}]',"{{US_CAREER}}");</script>

		<div style="clear:both"></div>

		<div class="hidden" id="us_career_section[{{INDEX}}]">
			<label for="us_career_details[{{INDEX}}]">
				<p class="title">Briefly describe the subject, location, estimated beginning date and approximate duration</p>
				<div style="clear:both"></div>
				<textarea cols="60" rows="5" id="us_career_details[{{INDEX}}]" name="us_career_details" value="" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">{{US_CAREER_DETAILS}}</textarea>
			</label>
		</div>
		<script type="text/javascript">showOrNot('us_career_section[{{INDEX}}]',{{US_CAREER}});</script>
			
		<label for="home_career[{{INDEX}}]" class="one_line">
			<p class="title">Will you pursue a career <strong>in your home country </strong> after completion of studies?</p>
			<label for="home_career_yes[{{INDEX}}]"><input type="radio" id="home_career_yes[{{INDEX}}]" name="home_career" value="1" onclick="visibility(this.name+'_section[{{INDEX}}]','block')" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" /> Yes</label>
			<label for="home_career_no[{{INDEX}}]"><input type="radio" id="home_career_no[{{INDEX}}]" name="home_career" value="0" onclick="visibility(this.name+'_section[{{INDEX}}]','none')" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" checked /> No</label>
		</label>
		<script type="text/javascript">checkInitValue('home_career_yes[{{INDEX}}]',"{{HOME_CAREER}}");</script>
		<script type="text/javascript">checkInitValue('home_career_no[{{INDEX}}]',"{{HOME_CAREER}}");</script>
		
		<div style="clear:both"></div>

		<div class="hidden" id="home_career_section[{{INDEX}}]">
			<label for="home_career_details[{{INDEX}}]">
				<p class="title">Briefly describe the subject, location, estimated beginning date and approximate duration</p>
				<div style="clear:both"></div>
				<textarea cols="60" rows="5" id="home_career_details[{{INDEX}}]" name="home_career_details" value="" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">{{HOME_CAREER_DETAILS}}</textarea>
			</label>
		</div>
		<script type="text/javascript">showOrNot('home_career_section[{{INDEX}}]',{{HOME_CAREER}});</script>

	</fieldset><!-- end fieldset "future_plans" -->

	<fieldset id="finances" name="finances" class="nested">
		<legend>Financial Details</legend>
		<label for="finance_details[{{INDEX}}]">
			<p class="title">How do you expect to finance your graduate study?</p>
			<div style="clear:both"></div>
			<textarea cols="60" rows="5" id="finance_details[{{INDEX}}]" name="finance_details" value="" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">{{FINANCE_DETAILS}}</textarea>
		</label>
		
		<label for="us_contacts[{{INDEX}}]">
			<p class="title">List the names and addresses of any close friends or relatives in the United States (indicate relationship)</p>
			<div style="clear:both"></div>
			<textarea cols="60" rows="5" id="us_contacts[{{INDEX}}]" name="us_contacts" value="" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">{{US_CONTACTS}}</textarea>
		</label>
	</fieldset>
	<div style="clear:both"></div>
	<fieldset class="nested">	
		<legend>United States Emergency Contact</legend>
		<p class="title">Please list a close friend or relative <b>in the United States</b> that should be contacted in case of emergency.</p>
		
		<label for="us_emergency_contact_name[{{INDEX}}]">
			<p class="title">Name</p>
			<input type="text" size="25" maxlength="30" id="us_emergency_contact_name[{{INDEX}}]" name="us_emergency_contact_name" value="{{US_EMERGENCY_CONTACT_NAME}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="us_emergency_contact_relationship[{{INDEX}}]">
			<p class="title">Relationship</p>
			<input type="text" size="25" maxlength="30" id="us_emergency_contact_relationship[{{INDEX}}]" name="us_emergency_contact_relationship" value="{{US_EMERGENCY_CONTACT_RELATIONSHIP}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="us_emergency_contact_phone[{{INDEX}}]">
			<p class="title">Phone Number</p>
			<input type="text" size="15" maxlength="15" id="us_emergency_contact_phone[{{INDEX}}]" name="us_emergency_contact_phone" value="{{US_EMERGENCY_CONTACT_PHONE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="us_emergency_contact_addr1[{{INDEX}}]">
			<p class="title">Street</p>
			<input type="text" size="55" maxlength="55" id="us_emergency_contact_addr1[{{INDEX}}]" name="us_emergency_contact_addr1" value="{{US_EMERGENCY_CONTACT_ADDR1}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="us_emergency_contact_addr2[{{INDEX}}]">
			<p class="title">Additional</p>
			<input type="text" size="55" maxlength="55" id="us_emergency_contact_addr2[{{INDEX}}]" name="us_emergency_contact_addr2" value="{{US_EMERGENCY_CONTACT_ADDR2}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="us_emergency_contact_city[{{INDEX}}]">
			<p class="title">City</p>
			<input type="text" size="30" maxlength="30" id="us_emergency_contact_city[{{INDEX}}]" name="us_emergency_contact_city" value="{{US_EMERGENCY_CONTACT_CITY}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="us_emergency_contact_state[{{INDEX}}]">
			<p class="title">State</p>
			<select id="us_emergency_contact_state[{{INDEX}}]" name="us_emergency_contact_state" size="1" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"></select>
			<script type="text/javascript">getStateOptions('us_emergency_contact_state[{{INDEX}}]');</script>
			<script type="text/javascript">initValue('us_emergency_contact_state[{{INDEX}}]',"{{US_EMERGENCY_CONTACT_STATE}}")</script>
		</label>
		
		<label for="us_emergency_contact_zip[{{INDEX}}]">
			<p class="title">Postal Code</p>
			<input type="text" size="15" maxlength="15" id="us_emergency_contact_zip[{{INDEX}}]" name="us_emergency_contact_zip" value="{{US_EMERGENCY_CONTACT_ZIP}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>	
	</fieldset><!-- end fieldset "us_emergency_contact" -->

	<div style="clear:both"></div>

	<fieldset class="nested">
		<legend>Home Country Emergency Contact</legend>
		<p class="title">Please list a close friend or relative <strong>in your home country </strong> that should be contacted in case of emergency.</p>
		<label for="home_emergency_contact_name[{{INDEX}}]">
			<p class="title">Name</p>
			<input type="text" size="25" maxlength="30" id="home_emergency_contact_name[{{INDEX}}]" name="home_emergency_contact_name" value="{{HOME_EMERGENCY_CONTACT_NAME}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="home_emergency_contact_relationship[{{INDEX}}]">
			<p class="title">Relationship</p>
			<input type="text" size="25" maxlength="30" id="home_emergency_contact_relationship[{{INDEX}}]" name="home_emergency_contact_relationship" value="{{HOME_EMERGENCY_CONTACT_RELATIONSHIP}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="home_emergency_contact_phone[{{INDEX}}]">
			<p class="title">Phone Number</p>
			<input type="text" size="15" maxlength="15" id="home_emergency_contact_phone[{{INDEX}}]" name="home_emergency_contact_phone" value="{{HOME_EMERGENCY_CONTACT_PHONE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="home_emergency_contact_addr1[{{INDEX}}]">
			<p class="title">Street</p>
			<input type="text" size="55" maxlength="55" id="home_emergency_contact_addr1[{{INDEX}}]" name="home_emergency_contact_addr1" value="{{HOME_EMERGENCY_CONTACT_ADDR1}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="home_emergency_contact_addr2[{{INDEX}}]">
			<p class="title">Additional</p>
			<input type="text" size="55" maxlength="55" id="home_emergency_contact_addr2[{{INDEX}}]" name="home_emergency_contact_addr2" value="{{HOME_EMERGENCY_CONTACT_ADDR2}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="home_emergency_contact_city[{{INDEX}}]">
			<p class="title">City</p>
			<input type="text" size="30" maxlength="30" id="home_emergency_contact_city[{{INDEX}}]" name="home_emergency_contact_city" value="{{HOME_EMERGENCY_CONTACT_CITY}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
		
		<label for="home_emergency_contact_postal[{{INDEX}}]">
			<p class="title">Postal Code</p>
			<input type="text" size="15" maxlength="15" id="home_emergency_contact_postal[{{INDEX}}]" name="home_emergency_contact_postal" value="{{HOME_EMERGENCY_CONTACT_POSTAL}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"/>
		</label>
						
		<label for="home_emergency_contact_country[{{INDEX}}]">
			<p class="title">Country</p>
			<select id="home_emergency_contact_country[{{INDEX}}]" name="home_emergency_contact_country" size="1" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});"></select>
			<script type="text/javascript">getCountryOptions('home_emergency_contact_country[{{INDEX}}]');</script>
			<script type="text/javascript">initValue('home_emergency_contact_country[{{INDEX}}]',"{{HOME_EMERGENCY_CONTACT_COUNTRY}}");</script>
		</label>
			
		<div style="clear:both;"></div>
	</fieldset><!-- end fieldset "home_emergency_contact" -->
</div>
