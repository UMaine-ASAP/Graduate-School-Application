<!--<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
  $("#personal_form").validate();
});
</script>-->

<script type="text/javascript" charset="utf-8">


  $(document).ready(function(){
    $("#addButton").click(function () {
    	addItem('{{LANGUAGES_TABLE_NAME}}');
    	//$("p").append('<input type="text" size="20"></input>');
    });

  });
</script>

<form id="personal_form" name="personal_form" action="" method="post" autocomplete="off">
<input type="hidden" id="user_id" value="{{USER}}"/>
<fieldset id="personal" name="personal">
	<legend>Personal Information</legend>
	<p class="required">Fields marked with * are required.</p>

	<fieldset id="name" name="name" class="nested">
		<legend>Name</legend>

			<label for="given_name">
				<p class="required title">First or Given name *</p>
				<input type="text" size="30" maxlength="30" id="given_name" name="given_name" value="{{GIVEN_NAME}}" onblur="saveValue(event,{{USER}});"/>
			</label>

			<label for="middle_name">
				<p class="title">Middle name</p>
				<input type="text" size="30" maxlength="30" id="middle_name" name="middle_name" value="{{MIDDLE_NAME}}" onblur="saveValue(event,{{USER}});"/>
			</label>

			<label for="family_name">
				<p class="required title">Last or Family name *</p>
				<input type="text" size="30" maxlength="30" id="family_name" name="family_name" value="{{FAMILY_NAME}}" onblur="saveValue(event,{{USER}});"/>
			</label>
		
			<label for="suffix">
				<p class="title">Suffix</p>
				<select id="suffix" name="suffix" value="{{SUFFIX}}" onchange="saveValue(event,{{USER}});">
					<option value=""	 >- None -</option>
					<option value="ESQ." >Esq</option>
					<option value="II"   > II</option>
					<option value="III"  >III</option>
					<option value="IV"   > IV</option>
					<option value="V"    >  V</option>
					<option value="JR"   > Jr</option>
					<option value="SR"   > Sr</option>
				</select>
				<script type="text/javascript">initValue('suffix',"{{SUFFIX}}")</script>
			</label>
	
			<label for="alternate_name">
				<p class="title">Name used on previous records</p>
				<input type="text" size="30" maxlength="30" id="alternate_name" name="alternate_name" value="{{ALTERNATE_NAME}}" onblur="saveValue(event,{{USER}});">
			</label>
	
	</fieldset><!-- end fieldset "name" -->
	<div style="clear:both"></div>	


<fieldset id="contact_info" name="contact_info">
	<legend>Contact Information</legend>
	<fieldset class="nested">
		<label for="primary_phone">
			<p class="title">Primary Phone Number </p>
			<input type="text" size="55" maxlength="15" id="primary_phone" name="primary_phone" value="{{PRIMARY_PHONE}}" onblur="saveValue(event,{{USER}});">
		</label>
					
		<label for="secondary_phone">
			<p class="title">Secondary Phone Number</p>
			<input type="text" size="55" maxlength="15" id="secondary_phone" name="secondary_phone" value="{{SECONDARY_PHONE}}" onblur="saveValue(event,{{USER}});">
		</label>
				
		<label for="email">
			<p class="required title">Email *</p>
			<input type="text" size="55" id="email" name="email" value="{{EMAIL}}" onblur="saveValue(event,{{USER}});">
		</label>
	</fieldset>
	<div style="clear:both"></div>	
		<fieldset id="permanent_addr" name="permanent_addr" class="nested">
			<legend>Permanent Address</legend>
			
					<label for="permanent_addr1">
						<p class="required title">Street *</p>
						<input type="text" size="55" maxlength="55" id="permanent_addr1" name="permanent_addr1" value="{{PERMANENT_ADDR1}}" onblur="saveValue(event,{{USER}});">
					</label>
					
					<label for="permanent_addr2">
						<p class="title">Additional</p>
						<input type="text" size="55" maxlength="55" id="permanent_addr2" name="permanent_addr2" value="{{PERMANENT_ADDR2}}" onblur="saveValue(event,{{USER}});">
					</label>
					
					<label for="permanent_city">
						<p class="required title">City *</p>
						<input type="text" size="30" maxlength="30" id="permanent_city" name="permanent_city" value="{{PERMANENT_CITY}}" onblur="saveValue(event,{{USER}});">
					</label>
					
					<label for="permanent_state">
						<p class="required title">State/Province *</p>
						<select id="permanent_state" name="permanent_state" size="1" onchange="saveValue(event,{{USER}});"></select>
						<script type="text/javascript">getStateOptions('permanent_state',"{{PERMANENT_STATE}}");</script>
					</label>
					
					<label for="permanent_postal">
						<p class="required title">Postal Code *</p>
						<input type="text" size="30" maxlength="15" id="permanent_postal" name="permanent_postal" value="{{PERMANENT_POSTAL}}" onblur="saveValue(event,{{USER}});">
					</label>
					
					<label for="permanent_country">
						<p class="required title">Country *</p>
						<select id="permanent_country" name="permanent_country" size="1" onchange="saveValue(event,{{USER}});"></select>
						<script type="text/javascript">getCountryOptions('permanent_country', "{{PERMANENT_COUNTRY}}");</script>
					</label>
				
		</fieldset><!-- end fieldset "permanent_addr" -->

		<fieldset id="mailing_addr" name="mailing_addr" class="nested">
			<legend>Mailing Address</legend>
			
			<label for="mailing_perm">
				<p class="title">Is your mailing address the same as your permanent address?</p>
				<label for="mailing_perm_yes"><input type="radio" id="mailing_perm_yes" name="mailing_perm" value="1" onclick="visibility(this.name+'_section','none')" onchange="saveCheckValue(event,{{USER}});" > Yes</label>
				<label for="mailing_perm_no"><input type="radio" id="mailing_perm_no" name="mailing_perm" value="0" onclick="visibility(this.name+'_section','block')" onchange="saveCheckValue(event,{{USER}});" checked> No</label>
			</label>
			<script type="text/javascript">checkInitValue('mailing_perm_yes',"{{MAILING_PERM}}");</script>
			<script type="text/javascript">checkInitValue('mailing_perm_no',"{{MAILING_PERM}}");</script>

			<div style="clear:both;"></div>

			<div id="mailing_perm_section">

				<label for="mailing_addr1">
					<p class="title">Street</p>
					<input type="text" size="55" maxlength="55" id="mailing_addr1" name="mailing_addr1" value="{{MAILING_ADDR1}}" onblur="saveValue(event,{{USER}});">
				</label>
				
				<label for="mailing_addr2">
					<p class="title">Additional</p>
					<input type="text" size="55" maxlength="55" id="mailing_addr2" name="mailing_addr2" value="{{MAILING_ADDR2}}" onblur="saveValue(event,{{USER}});">
				</label>
				
				<label for="mailing_city">
					<p class="title">City</p>
					<input type="text" size="30" maxlength="30" id="mailing_city" name="mailing_city" value="{{MAILING_CITY}}" onblur="saveValue(event,{{USER}});">
				</label>
				
				<label for="mailing_state">
					<p class="title">State/Province</p>
					<select id="mailing_state" name="mailing_state" size="1" onchange="saveValue(event,{{USER}});"></select>
					<script type="text/javascript">getStateOptions('mailing_state', "{{MAILING_STATE}}");</script>
				</label>
				
				<label for="mailing_postal">
					<p class="title">Postal Code</p>
					<input type="text" size="30" maxlength="15" id="mailing_postal" name="mailing_postal" value="{{MAILING_POSTAL}}" onblur="saveValue(event,{{USER}});">
				</label>
				
				<label for="mailing_country">
					<p class="title">Country</p>
					<select id="mailing_country" name="mailing_country" size="1" onchange="saveValue(event,{{USER}});"></select>
					<script type="text/javascript">getCountryOptions('mailing_country',"{{MAILING_COUNTRY}}");</script>
				</label>
			</div>
			<script type="text/javascript">showOrNot('mailing_perm_section',!{{MAILING_PERM}});</script>

		</fieldset> <!-- end fieldset "mailing_addr" -->
		
		<div style="clear:both;"></div>			
	</fieldset><!-- end fieldset "contact_information" -->

	<fieldset class="nested">
		
		<label for="date_of_birth">
			<p class="title">Date of Birth</p>
			<input type="date_of_birth" size="10" maxlength="10" id="date_of_birth" name="date_of_birth" value="{{DATE_OF_BIRTH}}" onblur="saveValue(event,{{USER}});">
			<p class="help">mm/dd/yyyy</p>

			<!-- DATE PICKER <script type="text/javascript" defer="defer">$(document).ready(function() {$('#date_of_birth').datepicker({dateFormat: "mm/dd/yy", constrainInput: false});});</script> -->

		</label>

		<label for="gender">
			<p class="title">Gender</p>
			<select id="gender" name="gender" onchange="saveValue(event,{{USER}});">
				<option value=""	>- None -</option>
				<option value="M" 	>Male</option>
				<option value="F" 	>Female</option>
				<option value="O" 	>Other</option>
			</select>
			<script type="text/javascript">initValue('gender',"{{GENDER}}")</script>
		</label>

		<!--  ssn verification scripts at end of code -->
		
		<label for="social_security_number">
			<p class="title">U.S. Social Security Number</p>
			<input type="text" size="11" maxlength="11" id="social_security_number" name="social_security_number" value="{{SOCIAL_SECURITY_NUMBER}}" onChange="confirmSSN(fmssn.value);" onblur="saveValue(event,{{USER}});">
			<div class="help">xxx-xx-xxxx</div>
		</label><div style="clear:both"></div>
		
		<p class="message">Your social security number (SSN) is used to verify your identity for administrative, financial aid and campus employment purposes. We need your SSN to process your financial aid. If not provided on your admission application, you will be required to provide it at a later date.</p>
</fieldset>
		<fieldset id="birth_location" name="birth_location" class="nested">
			<legend>Birth Location</legend>
		
			<label for="birth_city">
				<p class="title">City</p>
				<input type="text" size="36" maxlength="30" id="birth_city" name="birth_city" value="{{BIRTH_CITY}}" onblur="saveValue(event,{{USER}});">
			</label>
			
			<label for="birth_state">
				<p class="title">State/Province</p>
				<select id="birth_state" name="birth_state" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getStateOptions('birth_state');</script>
				<script type="text/javascript">initValue('birth_state',"{{BIRTH_STATE}}")</script>
			</label>
							
			<label for="birth_country">
				<p class="title">Country</p>
				<select id="birth_country" name="birth_country" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getCountryOptions('birth_country');</script>
				<script type="text/javascript">initValue('birth_country',"{{BIRTH_COUNTRY}}")</script>
			</label>
		
			<div style="clear:both;"></div>
		</fieldset> <!-- end fieldset "birth_location" -->

		<fieldset class="nested">
	
			<label for="country_of_citizenship">
				<p class="required title">Country of Citizenship *</p>
				<select id="country_of_citizenship" name="country_of_citizenship" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getCountryOptions('country_of_citizenship');</script>
				<script type="text/javascript">initValue('country_of_citizenship',"{{COUNTRY_OF_CITIZENSHIP}}")</script>
			</label>
			
			<label for="us_state">
				<p class="required title">Legal resident of *</p>
				<select id="us_state" name="us_state" size="1" onchange="saveValue(event,{{USER}});"></select>
				<script type="text/javascript">getStateOptions('us_state');</script>
				<script type="text/javascript">initValue('us_state',"{{US_STATE}}")</script>
			</label>
			
			<label for="residency_status">
				<p class="required title">Residency Status *</p>
				<select id="residency_status" name="residency_status" onchange="saveValue(event,{{USER}});">
					<option value=""					>- None -</option>
					<option value="resident" 			>Resident</option>
					<option value="non-resident alien" 	>Non-Resident Alien</option>
				</select>
				<script type="text/javascript">initValue('residency_status',"{{RESIDENCY_STATUS}}")</script>
			</label>

			<div style="clear:both;"></div>

			<p class="message"><strong>If you are a resident alien, please submit a copy of your green card.</strong>
			This school is authorized under federal law to enroll non-immigrant alien students. Anyone submitting falsified documents will be denied admission and receive no refund of any fees paid. The University will notify appropriate authorities of this action and information will be shared with government agencies.</p>

			<div style="clear:both;"></div>
		</fieldset>
	<!--
		need green card upload
		green_card_link
	-->

		<fieldset id="ethnicity" name="ethnicity" class="nested">
			<legend>Ethnic Information</legend>
			<p class="message">Note on Ethnicity:
			Colleges and universities are asked by many, including the federal government, accrediting associations, college guides, newspapers and our own college/university communities, to describe the racial/ethnic backgrounds of our students and employees. In order to respond to these requests, we ask you to answer the following two questions:</p>
			<label for="ethnicity_hispa">
				<p class="title">Do you consider yourself to be Hispanic/Latino</p>
				<label for="ethnicity_hispa_yes"><input type="radio" id="ethnicity_hispa_yes" name="ethnicity_hispa" value="HISPA" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
				<label for="ethnicity_hispa_no"><input type="radio" id="ethnicity_hispa_no" name="ethnicity_hispa" value="" checked onchange="saveCheckValue(event,{{USER}});" /> No</label>
			</label>
			<script type="text/javascript">checkInitValue('ethnicity_hispa_yes',"{{ETHNICITY_HISPA}}");</script>
			<script type="text/javascript">checkInitValue('ethnicity_hispa_no',"{{ETHNICITY_HISPA}}");</script>

			<div style="clear:both;"></div>
		
			<label for="ethnicity" class="one_line">
				<p class="title">In addition, select one or more of the following racial categories to describe yourself:</p>
				<label for="ethnicity_amind" class="one_line"><input type="checkbox" id="ethnicity_amind" name="ethnicity_amind" value="AMIND" onchange="saveCheckValue(event,{{USER}});"/>American Indian/Native Alaskan</label>
				<label for="ethnicity_asian" class="one_line"><input type="checkbox" id="ethnicity_asian" name="ethnicity_asian" value="ASIAN" onchange="saveCheckValue(event,{{USER}});"/>Asian</label>
				<label for="ethnicity_black" class="one_line"><input type="checkbox" id="ethnicity_black" name="ethnicity_black" value="BLACK" onchange="saveCheckValue(event,{{USER}});"/>Black/African American</label>
				<label for="ethnicity_pacif" class="one_line"><input type="checkbox" id="ethnicity_pacif" name="ethnicity_pacif" value="PACIF" onchange="saveCheckValue(event,{{USER}});"/>Native Hawaiian/Pacific Islander</label>
				<label for="ethnicity_white" class="one_line"><input type="checkbox" id="ethnicity_white" name="ethnicity_white" value="WHITE" onchange="saveCheckValue(event,{{USER}});"/>White</label>
			</label>
		
			<script type="text/javascript">
				checkInitValue('ethnicity_amind',"{{ETHNICITY_AMIND}}");
				checkInitValue('ethnicity_asian',"{{ETHNICITY_ASIAN}}");
				checkInitValue('ethnicity_black',"{{ETHNICITY_BLACK}}");
				checkInitValue('ethnicity_pacif',"{{ETHNICITY_PACIF}}");
				checkInitValue('ethnicity_white',"{{ETHNICITY_WHITE}}");
			</script>
	
			<div style="clear:both;"></div>
		</fieldset><!-- end fieldset "ethnicity" -->

	<div style="clear:both;"></div>
</fieldset><!-- end fieldset "personal_information" -->

<fieldset id="languages" name="languages">
	<legend>Language Information</legend>
	
	<fieldset id="english" name="english" class="nested">
		<legend>English Proficiency</legend>
		
		<label for="english">
			<p class="title">Is English your primary language?</p>
			<label for="english_primary_yes"><input type="radio" id="english_primary_yes" name="english_primary" value="1" onclick="visibility(this.name+'_section','none')" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
			<label for="english_primary_no"><input type="radio" id="english_primary_no" name="english_primary" value="0" onclick="visibility(this.name+'_section','block')" onchange="saveCheckValue(event,{{USER}});" checked/> No</label>
		</label><br /><br /><br />
		<script type="text/javascript">checkInitValue('english_primary_yes',"{{ENGLISH_PRIMARY}}");</script>
		<script type="text/javascript">checkInitValue('english_primary_no',"{{ENGLISH_PRIMARY}}");</script>
	
		<div style="clear:both;"></div>

		<div class="hidden" id="english_primary_section">
			<p class="title">Please indicate the number of years you have studied English:</p>
					
			<label for="english_years_school">
				<p class="title">In secondary or middle school</p>
				<input type="text" size="20" maxlength="2" id="english_years_school" name="english_years_school" value="{{ENGLISH_YEARS_SCHOOL}}" onblur="saveValue(event,{{USER}});">
			</label>
			
			<label for="english_years_univ">
				<p class="title">In university</p>
				<input type="text" size="20" maxlength="2" id="english_years_univ" name="english_years_univ" value="{{ENGLISH_YEARS_UNIV}}" onblur="saveValue(event,{{USER}});">
			</label>
			
			<label for="english_years_private">
				<p class="title">Under private auspices</p>
				<input type="text" size="20" maxlength="2" id="english_years_private" name="english_years_private" value="{{ENGLISH_YEARS_PRIVATE}}" onblur="saveValue(event,{{USER}});">
			</label>
		</div>
		<script type="text/javascript">showOrNot('english_primary_section',!{{ENGLISH_PRIMARY}});</script>
	</fieldset><!-- end fieldset "english" -->
	
	<p class="title">Knowledge of other languages and proficiency in each</p>

	<div id="languages_count" class="hidden">{{LANGUAGES_COUNT}}</div> 
	<div id={{LANGUAGES_LIST}}>
		{{LANGUAGES_REPEATABLE}}
	</div>
	<div class="addButton"><img id="addButton" src="images/plus.png" alt="Add" title="Add" />&nbsp;Add another language</div> 
		

	<div style="clear:both;"></div>
	
	<div id="pager">
		<p><span style="float:right;"><a href="app_manager.php?form_id=3"> Next Section >> </a></span></p>
	</div>
	
</fieldset><!-- end fieldset "languages" -->
		

<!-- FUNCTIONS -->
<script type="text/javascript" charset="utf-8">
function isNumberString (InString)  {                         
 if(InString.length==0) return (false);                       
    var RefString="1234567890";                               
    for (Count=0; Count < InString.length; Count++)  {        
       TempChar= InString.substring (Count, Count+1);         
    if (RefString.indexOf (TempChar, 0)==-1)                  
       return (false); }                                      
       return (true); }   
                                           
function confirmSSN(fmssn){                         
  if (!(fmssn == "")) {                             
  var answer=confirm("Is this your Social Security number? " + fmssn); 
  if (!(answer)) {                                  
     document.forms[0].elements[4].value="";        
     document.forms[0].elements[4].focus();}}} 
</script>

</form>
