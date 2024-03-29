<form action="" method="post" id="objectives" autocomplete="off">
<input type="hidden" id="user_id" value="{{USER}}"/>
<fieldset id="education_objectives" name="education_objectives">
	<legend>Education Objectives</legend>
	
	<span class="required"><p>Fields marked with * are required.</p></span>
		
	<fieldset id="applied_programs" name="applied_programs" class="nested">
		<legend>Academic Programs</legend>
		
		<p class="title">Select a program to apply to.</p>
		
		<div id="{{APPLIEDPROGRAMS_LIST}}">
			{{APPLIEDPROGRAMS_REPEATABLE}}
		</div>

		<div style="clear:both"></div>
	</fieldset>

	<fieldset id="assistantship_info" name="assistantship_info" class="nested">
		<legend>Assistantship Request</legend>
		<label for="desire_assistantship">
			<p class="title">Do you wish to apply for an assistantship?</p>
			<label for="desire_assistantship_yes"><input type="radio" id="desire_assistantship_yes" name="desire_assistantship" value="1"  onclick="visibility(this.name+'_section','block')" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
			<label for="desire_assistantship_no"><input type="radio" id="desire_assistantship_no" name="desire_assistantship" value="0"  onclick="visibility(this.name+'_section','none')" onchange="saveCheckValue(event,{{USER}});" checked/> No</label>
			</label>
		<script type="text/javascript">checkInitValue('desire_assistantship_yes',"{{DESIRE_ASSISTANTSHIP}}");</script>
		<script type="text/javascript">checkInitValue('desire_assistantship_no',"{{DESIRE_ASSISTANTSHIP}}");</script>

		<div style="clear:both"></div>

<!-- SMB removed department select list from assistantship request 11-18-09
		<div class="hidden" id="desire_assistantship_section">
			<label for="desire_assistantship_dept">
			If so, specify the department
				
				<select id="desire_assistantship_dept" name="desire_assistantship_dept" value="{{DESIRE_ASSISTANTSHIP_DEPT}}" onchange="saveValue(event,{{USER}})">	
					<option value="">Select Department</option>
					<script type="text/javascript">
						drawDeptMenu('desire_assistantship_dept',{{USER}});
					</script>
				</select>
				<script type="text/javascript">dynamicInitSelectValue(document.getElementById('desire_assistantship_dept'),"{{DESIRE_ASSISTANTSHIP_DEPT}}")</script> -->
			

		<!--<script type="text/javascript">showOrNot('desire_assistantship_section',{{DESIRE_ASSISTANTSHIP}});</script>-->

		<label>
			<p class="message"><strong>You must contact the department Chairperson directly if you desire a teaching or research assistantship or if you wish to be nominated for a University fellowship. Please note that while most assistantships are awarded by departments, not all departments currently have assistantships available.</strong></p>
		</label>

		<div style="clear:both"></div>
	</fieldset>

	<div style="clear:both"></div>

	<fieldset id="nebhe_set" name="nebhe_set" class="nested">
		<legend>New England Regional Student Program</legend>
		<label for="apply_nebhe">
			<p class="title">Do you intend to apply for certification of eligibility under the New England Regional Student Program?</p>	
			<label for="apply_nebhe_yes"><input type="radio" id="apply_nebhe_yes" name="apply_nebhe" value="1" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
			<label for="apply_nebhe_no"><input type="radio" id="apply_nebhe_no" name="apply_nebhe" value="0" onchange="saveCheckValue(event,{{USER}});" checked/> No</label>
		</label>
		<script type="text/javascript">checkInitValue('apply_nebhe_yes',"{{APPLY_NEBHE}}");</script>
		<script type="text/javascript">checkInitValue('apply_nebhe_no',"{{APPLY_NEBHE}}");</script>

		<div style="clear:both"></div>

		<p class="message"><strong>The New England Regional Student Program is for New England students only. To verify eligibility call 617-357-9620, email: <a href="mailto:rsp@nebhe.org" class="text_link">rsp@nebhe.org</a>, or contact the NERSP representative within your home state. For more information visit www.nebhe.org</strong></p>
		
		<div style="clear:both"></div>
	</fieldset>

	<div style="clear:both"></div>
	
	<fieldset id="additional_info" name="additional_info" class="nested">
		<legend>Additional Information, Essay &amp; R&eacute;sum&eacute;</legend>
		<div>
			<label for="um_correspond">
				<p class="title">Have you spoken to or corresponded with any member of the University of Maine faculty regarding your application?</p>
				<label for="um_correspond_yes"><input type="radio" id="um_correspond_yes" name="um_correspond" value="1"  onclick="visibility(this.name+'_section','block')" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
				<label for="um_correspond_no"><input type="radio" id="um_correspond_no" name="um_correspond" value="0"  onclick="visibility(this.name+'_section','none')" onchange="saveCheckValue(event,{{USER}});" checked/> No</label>
			</label>
			<script type="text/javascript">checkInitValue('um_correspond_yes',"{{UM_CORRESPOND}}");</script>
			<script type="text/javascript">checkInitValue('um_correspond_no',"{{UM_CORRESPOND}}");</script>
	
			<div style="clear:both"></div>

			<div class="hidden" id="um_correspond_section">
				<label for="um_correspond_details">
					<p class="title">If so, please provide a name or names</p>
					<input type="text" size="55" maxlength="55" id="um_correspond_details" name="um_correspond_details" value="{{UM_CORRESPOND_DETAILS}}" onblur="saveValue(event,{{USER}});" />
				</label>	
			</div>
			<script type="text/javascript">showOrNot('um_correspond_section',"{{UM_CORRESPOND}}");</script>

		</div>

		<div><p class='title'>To upload an essay or resume, select the appropriate button above. Accepted file formats are pdf, doc, docx, rtf, and txt. If you have difficulties uploading a file, please send it as an attachment to graduate@maine.edu.</p></div>

<!-- 		<fieldset id="essay_upload" name="essay_upload"> -->
<!-- 			<legend>Essay</legend> -->

<script type="text/javascript" >
	$(function(){
		var upload=$('#essay_upload');
		var status=$('#essay_status');
		var filename=$('#essay_name');
		new AjaxUpload(upload, {
			action: 'libs/upload-essay.php',
			name: 'essay',
			onSubmit: function(file, ext){
				 if (! (ext && /^(pdf|doc|txt|rtf|docx)$/.test(ext))){
					status.text('Only PDF, DOC, DOCX, TXT or RTF files are allowed');
					return false;
				}
				status.text('Uploading...');
			},
			onComplete: function(file, response){
				if (response=='') {
					filename.text(file);
					status.text('Your essay uploaded successfully.');
				} else {
					status.text('There was a problem and your essay was not uploaded.');
					
				}
			}
		});
		
	});
</script>

<script type="text/javascript" >
	$(function(){
		var upload=$('#resume_upload');
		var status=$('#resume_status');
		var filename=$('#resume_name');
		new AjaxUpload(upload, {
			action: 'libs/upload-resume.php',
			name: 'resume',
			onSubmit: function(file, ext){
				 if (! (ext && /^(pdf|doc|txt|rtf|docx)$/.test(ext))){
					status.text('Only PDF, DOC, DOCX, TXT or RTF files are allowed');
					return false;
				}
				status.text('Uploading...');
			},
			onComplete: function(file, response){
				if (response=='') {
					filename.text(file);
					status.text('Your résumé uploaded successfully.');
				} else {
					status.text('There was a problem and your résumé was not uploaded.');
				}
			}
		});
		
	});
</script>
	
		<div class='upload_container'>
			<span id="essay_upload" class="upload">Upload Essay</span>
			<span id='essay_name' class='filename'>{{ESSAY_NAME}}</span>
		</div>
		<span id="essay_status" class="status"></span>

		<label for="essay_upload">
			<p class="title">Upload a brief essay (300-500 words) to be read by professors in your field on your academic and personal intentions and objectives. Identify any special interest you would like to pursue now or in the future. If you have previously attended another graduate school, explain why you wish to transfer to The University of Maine.</p>
		</label>

		<div class='upload_container'>
			<span id="resume_upload" class="upload">Upload R&eacute;sum&eacute;</span>
			<span id='resume_name' class='filename'>{{RESUME_NAME}}</span>
		</div>
		<span id="resume_status" class="status"></span>

		<label for="resume_upload">
			<p class="title">Upload a brief r&eacute;sum&eacute; to be read by professors in your field.</p>
		</label>

		<div style="clear:both"></div>
	</fieldset>
	

	<div style="clear:both"></div>

	<div id="pager">
		<p><span style="float:left;"><a href="app_manager.php?form_id=4"><span class="chevron">&#0171;</span> Previous Section</a></span>
		<span style="float:right;"><a href="app_manager.php?form_id=6"> Next Section <span class="chevron">&#0187;</span></a></span></p>
	</div>
</fieldset><!-- end fieldset "education_objectives" -->
</form>
