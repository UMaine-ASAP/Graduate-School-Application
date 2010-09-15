<form id="history_form" action="" method="post" autocomplete="off">
<input type="hidden" id="user_id" value="{{USER}}"/>
<fieldset id="educational_history" name="educational_history">
	<span class="required"><p>Fields marked with * are required.</p></span>

	<legend>Educational History</legend>

<fieldset id="previous_app" name="previous_app" class="nested">
	<legend>Previous Application to University of Maine</legend>

	<label for="prev_um_app">
		<p class="required title">Have you previously applied to the University of Maine? *</p>
		<label for="prev_um_app_yes"><input type="radio" id="prev_um_app_yes" name="prev_um_app" value="1" class="prev_um_app" onclick="visibility(this.name+'_section','block')" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
		<label for="prev_um_app_no"><input type="radio" id="prev_um_app_no" name="prev_um_app" value="0" class="prev_um_app" onclick="visibility(this.name+'_section','none')" onchange="saveCheckValue(event,{{USER}});" checked/> No</label>
	</label>
	<script type="text/javascript">checkInitValue('prev_um_app_yes',"{{PREV_UM_APP}}");</script>
	<script type="text/javascript">checkInitValue('prev_um_app_no',"{{PREV_UM_APP}}");</script>

	<div style="clear:both;"></div>

	<div class="hidden" id="prev_um_app_section">
		<label for="prev_um_grad_app">
			<p class="title">For a graduate program?</p>
			<label for="prev_um_grad_app_yes"><input type="radio" id="prev_um_grad_app_yes" name="prev_um_grad_app" value="1" onclick="visibility(this.name+'_section','block')" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
			<label for="prev_um_grad_app_no"><input type="radio" id="prev_um_grad_app_no" name="prev_um_grad_app" value="" onclick="visibility(this.name+'_section','none')" onchange="saveCheckValue(event,{{USER}});" checked/> No</label>
		</label>
		<script type="text/javascript">checkInitValue('prev_um_grad_app_yes',"{{PREV_UM_GRAD_APP}}");</script>
		<script type="text/javascript">checkInitValue('prev_um_grad_app_no',"{{PREV_UM_GRAD_APP}}");</script>

		<div style="clear:both"></div>

		<div class="hidden" id="prev_um_grad_app_section">
			<label for="prev_um_grad_app_date">
				<p class="title">When did you apply?</p>
				<input type="text" size="14" maxlength="7" id="prev_um_grad_app_date" name="prev_um_grad_app_date" value="{{PREV_UM_GRAD_APP_DATE}}" onblur="saveValue(event,{{USER}});" />
				<p class="help">mm/yyyy</p>
			</label>

			<label for="prev_um_grad_app_dept">
				<p class="title">To which department?</p>
				<select id="prev_um_grad_app_dept" name="prev_um_grad_app_dept" value="{{PREV_UM_GRAD_APP_DEPT}}" onchange="saveValue(event,{{USER}});">
					<option value="">- None -</option>
					<script type="text/javascript">drawDeptMenu('prev_um_grad_app_dept',{{USER}},null,null,"{{PREV_UM_GRAD_APP_DEPT}}");</script>
				</select>
			</label>
			<!--<script type="text/javascript">dynamicInitSelectValue('prev_um_grad_app_dept',"{{PREV_UM_GRAD_APP_DEPT}}");</script>-->

			<div style="clear:both"></div>

			<label for="prev_um_grad_degree">
				<p class="title">Degree awarded</p>
				<input type="text" size="31" maxlength="30" id="prev_um_grad_degree" name="prev_um_grad_degree" value="{{PREV_UM_GRAD_DEGREE}}" onblur="saveValue(event,{{USER}});" />
			</label>
	
			<label for="prev_um_grad_degree_date">
				<p class="title">Date awarded</p>
				<input type="text" size="14" maxlength="7" id="prev_um_grad_degree_date" name="prev_um_grad_degree_date" value="{{PREV_UM_GRAD_DEGREE_DATE}}" onblur="saveValue(event,{{USER}});" />
				<div class="help">mm/yyyy</div>
			</label>

			<div style="clear:both"></div>

			<label for="prev_um_grad_withdraw"  width="2000">
				<p class="title">Did you withdraw?</p>
				<label for="prev_um_grad_withdraw_yes"><input type="radio" id="prev_um_grad_withdraw_yes" name="prev_um_grad_withdraw" value="1" onclick="visibility(this.name+'_section','block')" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
				<label for="prev_um_grad_withdraw_no"><input type="radio" id="prev_um_grad_withdraw_no" name="prev_um_grad_withdraw" value="0" onclick="visibility(this.name+'_section','none')"onchange="saveCheckValue(event,{{USER}});" checked /> No</label>
			</label>
			<script type="text/javascript">checkInitValue('prev_um_grad_withdraw_yes',"{{PREV_UM_GRAD_WITHDRAW}}");</script>
			<script type="text/javascript">checkInitValue('prev_um_grad_withdraw_no',"{{PREV_UM_GRAD_WITHDRAW}}");</script>
	
			<div style="clear:both;"></div>

			<div class="hidden" id="prev_um_grad_withdraw_section">
				<label for="prev_um_grad_withdraw_date">
					<p class="title">Date withdrawn</p>
					<input type="text" size="14" maxlength="7" id="prev_um_grad_withdraw_date" name="prev_um_grad_withdraw_date" value="{{PREV_UM_GRAD_WITHDRAW_DATE}}" onblur="saveValue(event,{{USER}});" />
					<div class="help">mm/yyyy</div>
				</label>
			</div>
			<script type="text/javascript">showOrNot('prev_um_grad_withdraw_section',{{PREV_UM_GRAD_WITHDRAW}});</script>
		</div>
		<script type="text/javascript">showOrNot('prev_um_grad_app_section',{{PREV_UM_GRAD_APP}});</script>
	</div>
	<script type="text/javascript">showOrNot('prev_um_app_section',{{PREV_UM_APP}});</script>

	<div style="clear:both"></div>
</fieldset><!-- end fieldset "previous_app" -->

<div style="clear:both"></div>

<fieldset id="previous_schools" name="previous_schools" class="nested">
	<legend>Previously Attended Institutions</legend>
	<p class="title">List in chronological order all institutions of collegiate standing, and location, that you have attended. Include dates of entering and leaving, degrees received or for which you are a candidate. Official transcripts must be sent <b>directly</b> from these institutions to the Graduate School. The Graduate School will order UM (ORONO) transcripts only.<br />
	<b>No decision can be made until all your transcripts are received in this office.</b></p>

	
	<div id={{PREVIOUSSCHOOLS_LIST}}>
		{{PREVIOUSSCHOOLS_REPEATABLE}}
	</div>

	<div style="clear:both"></div>

	<div class="addButton"><img src="images/plus.png" alt="Add an Item" title="Add an Item" onClick="addItem('{{PREVIOUSSCHOOLS_TABLE_NAME}}'); return false;" />&nbsp;Add another institution</div>


	<div style="clear:both"></div>
</fieldset><!-- end fieldset "previous_schools" -->

<div style="clear:both"></div>

<fieldset id="grade_information" class="nested">
	<legend>Grade Information</legend>

	<label for="undergrad_gpa">
		<p class="title">Cumulative undergraduate grade point average</p>
		<input type="text" size="4" maxlength="4" id="undergrad_gpa" name="undergrad_gpa" value="{{UNDERGRAD_GPA}}" onblur="saveValue(event,{{USER}});" />
		<p class="help">If possible, please indicate your cumulative GPA on a 4.0 scale (A=4.0).</p>
	</label>

	<label for="postbacc_gpa">
		<p class="title">Cumulative post-baccalaureate grade point average</p>
		<input type="text" size="4" maxlength="4" id="postbacc_gpa" name="postbacc_gpa" value="{{POSTBACC_GPA}}" onblur="saveValue(event,{{USER}});" />
		<p class="help">If possible, please indicate your cumulative GPA on a 4.0 scale (A=4.0).</p>
	</label>

	<div style="clear:both"></div>

	<label for="preenroll_courses">
		<p class="title">List, by title, all course work you expect to take or courses you expect to take before enrolling at UM. <b>Please have a supplementary transcript sent to the Graduate School when grades are available.</b></p>
		<textarea cols="60" rows="5" id="preenroll_courses" name="preenroll_courses" value="" onblur="saveValue(event,{{USER}});">{{PREENROLL_COURSES}}</textarea>
	</label>

	<div style="clear:both"></div>
</fieldset>

<div style="clear:both"></div>

<fieldset id="disciplinary_violations" name="disciplinary_violations" class="nested">
	<legend>Disciplinary Violations</legend>

	<!-- need additional record functionality -->
	<label for="disciplinary_violation">
		<p class="required title">Have you ever been found responsible for a disciplinary violation at a post-secondary educational institution you have attended (or the international equivalent) whether related to academic misconduct or behavioral misconduct, that resulted in your suspension, removal, dismissal or expulsion from the institution? *
</p>
		<label for="disciplinary_violation_yes"><input type="radio" id="disciplinary_violation_yes" name="disciplinary_violation" value="1" onclick="visibility(this.name+'_section','block')" class="disciplinary_violation" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
		<label for="disciplinary_violation_no"><input type="radio" id="disciplinary_violation_no" name="disciplinary_violation" value="0" onclick="visibility(this.name+'_section','none')" class="disciplinary_violation" onchange="saveCheckValue(event,{{USER}});" checked /> No</label>
	</label>
	<script type="text/javascript">checkInitValue('disciplinary_violation_yes',"{{DISCIPLINARY_VIOLATION}}");</script>
	<script type="text/javascript">checkInitValue('disciplinary_violation_no',"{{DISCIPLINARY_VIOLATION}}");</script>

	<div style="clear:both"></div>

	<div class="hidden" id="disciplinary_violation_section">
		<div id={{DVIOLATIONS_LIST}}>
			{{DVIOLATIONS_REPEATABLE}}
		</div>

		<div style="clear:both"></div>

		<div class="addButton"><img src="images/plus.png" alt="Add an Item" title="Add an Item" onClick="addItem('{{DVIOLATIONS_TABLE_NAME}}'); return false;" />&nbsp;Add another incident</div>
	</div> 
	<script type="text/javascript">showOrNot('disciplinary_violation_section',{{DISCIPLINARY_VIOLATION}});</script>

	<div style="clear:both"></div>
</fieldset><!--end fieldset "disciplinary_violations"-->		

<div style="clear:both"></div>

<fieldset id="criminal" name="criminal" class="nested" >
	<legend>Crime Information</legend>

	<label for="criminal_violation">
		<p class="required title">Have you ever been convicted of a misdemeanor, felony or other crime, or adjudicated of committing a juvenile crime? *</p>
		<label for="criminal_violation_yes"><input type="radio" id="criminal_violation_yes" name="criminal_violation" value="1" onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','block')" /> Yes</label>
		<label for="criminal_violation_no"><input type="radio" id="criminal_violation_no" name="criminal_violation" value="0" onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','none')" checked/> No</label>
	</label>
	<script type="text/javascript">checkInitValue('criminal_violation_yes',"{{CRIMINAL_VIOLATION}}");</script>
	<script type="text/javascript">checkInitValue('criminal_violation_no',"{{CRIMINAL_VIOLATION}}");</script>

	<div class="hidden" id="criminal_violation_section">
		<div id={{CVIOLATIONS_LIST}}>
			{{CVIOLATIONS_REPEATABLE}}
		</div>	

		<div style="clear:both"></div>

		<div class="addButton"><img src="images/plus.png" alt="Add an Item" title="Add an Item" onClick="addItem('{{CVIOLATIONS_TABLE_NAME}}'); return false;" />&nbsp;Add another incident</div>
	</div> 
	<script type="text/javascript">showOrNot('criminal_violation_section',{{CRIMINAL_VIOLATION}});</script>

	<div style="clear:both"></div>
</fieldset><!-- end fieldset "criminal" -->

<div style="clear:both"></div>

<fieldset id="examinations" name="examinations" class="nested">
	<legend>Examinations</legend>

	<p class="title"><strong>Please have official scores sent directly to the Graduate School from the testing institution:</strong><p>
	<p class="message">For GRE, Educational Testing Service, <a href="http://www.ets.org" class="text_link" target="_blank">www.ets.org</a>, institution code for UM (ORONO): 3916</p>
	<p class="message">For GMAT, Graduate Management Admissions Council, <a href="http://www.mba.com" class="text_link" target="_blank">www.mba.com</a>, institution code for UM (ORONO): 1ZF-RM-41</p>
	<p class="message">For MAT, Harcourt Assessment, <a href="http://www.milleranalogies.com" class="text_link" target="_blank">www.milleranalogies.com</a>, institution code for UM (ORONO): 1278</p>

	<fieldset id="gre" name="gre" class="nested">
		<legend>Graduate Record Exam</legend>

		<label for="gre_taken">
			<p class="title">Have you taken or plan to take the GRE?</p>
			<label for="gre_taken_yes"><input type="radio" id="gre_taken_yes" name="gre_taken" value="1" onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','block')" /> Yes</label>
			<label for="gre_taken_no"><input type="radio" id="gre_taken_no" name="gre_taken" value="0" checked onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','none')" /> No</label>
		</label>
		<script type="text/javascript">checkInitValue('gre_taken_yes',"{{GRE_TAKEN}}");</script>
		<script type="text/javascript">checkInitValue('gre_taken_no',"{{GRE_TAKEN}}");</script>
	
		<div style="clear:both"></div>

		<div class="hidden" id="gre_taken_section">
			<div id={{GRE_LIST}}>
				<input type="hidden" id="gre_count" value="{{GRE_COUNT}}" />
				{{GRE_REPEATABLE}}
			</div>

			<div style="clear:both"></div>

			<div class="addButton"><img src="images/plus.png" alt="Add an Item" title="Add an Item" onClick="addItem('{{GRE_TABLE_NAME}}'); return false;" />&nbsp;Add another score</div>
		</div>
		<script type="text/javascript">showOrNot('gre_taken_section',{{GRE_TAKEN}});</script>

		<div style="clear:both"></div>
	</fieldset><!-- end fieldset "gre" -->
	
	<div style="clear:both;"></div>

	<fieldset id="gmat" name="gmat" class="nested">
		<legend>Graduate Management Admission Test</legend>
				
		<label for="gmat_taken">
			<p class="title">Have you taken or plan to take the GMAT?</p>
			<label for="gmat_taken_yes"><input type="radio" id="gmat_taken_yes" name="gmat_taken" value="1" onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','block')" /> Yes</label>
			<label for="gmat_taken_no"><input type="radio" id="gmat_taken_no" name="gmat_taken" value="0" checked onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','none')" /> No</label>
		</label>
		<script type="text/javascript">checkInitValue('gmat_taken_yes',"{{GMAT_TAKEN}}");</script>
		<script type="text/javascript">checkInitValue('gmat_taken_no',"{{GMAT_TAKEN}}");</script>

		<div style="clear:both;"></div>

		<div class="hidden" id="gmat_taken_section">
			<label for="gmat_date">
				<p class="title">Date of Exam</p>
				<input type="text" size="14" maxlength="7" id="gmat_date" name="gmat_date" value="{{GMAT_DATE}}" onblur="saveValue(event,{{USER}});" />
				<p class="help">mm/yyyy</p>
			</label>

			<label for="gmat_verbal">
				<p>Verbal</p>
				<input type="text" size="2" maxlength="2" id="gmat_verbal" name="gmat_verbal" value="{{GMAT_VERBAL}}" class="gmat_verbal" onblur="saveValue(event,{{USER}});" />
			</label>

			<label for="gmat_quantitative">
				<p class="title">Quantitative</p>
				<input type="text" size="2" maxlength="2" id="gmat_quantitative" name="gmat_quantitative" value="{{GMAT_QUANTITATIVE}}" class="gmat_quantitative" onblur="saveValue(event,{{USER}});" />
			</label>

			<label for="gmat_analytical">
				<p class="title">Analytical</p>
				<input type="text" size="3" maxlength="3" id="gmat_analytical" name="gmat_analytical" value="{{GMAT_ANALYTICAL}}" class="gmat_analytical" onblur="saveValue(event,{{USER}});" />
			</label>

			<label for="gmat_score">
				<p class="title">Total Score</p>
				<input type="text" size="3" maxlength="3" id="gmat_score" name="gmat_score" value="{{GMAT_SCORE}}" class="gmat_score" onblur="saveValue(event,{{USER}});" />
			</label>

			<div style="clear:both;"></div>

			<label for="gmat_reported">
				<p class="title">Has this score been reported to the University of Maine?</p>
				<label for="gmat_reported_yes"><input type="radio" id="gmat_reported_yes" name="gmat_reported" value="1" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
				<label for="gmat_reported_no"><input type="radio" id="gmat_reported_no" name="gmat_reported" value="0"checked onchange="saveCheckValue(event,{{USER}});" /> No</label>
			</label>
			<script type="text/javascript">checkInitValue('gmat_reported_yes',"{{GMAT_REPORTED}}");</script>
			<script type="text/javascript">checkInitValue('gmat_reported_no',"{{GMAT_REPORTED}}");</script>

			<div style="clear:both"></div>
		</div>
		<script type="text/javascript">showOrNot('gmat_taken_section',{{GMAT_TAKEN}});</script>

		<div style="clear:both"></div>
	</fieldset><!-- end fieldset "gmat" -->

	<div style="clear:both"></div>

	<fieldset id="mat" name="mat" class="nested">
		<legend>Miller Analogies Test</legend>

		<label for="mat_taken">
			<p class="title">Have you taken or plan to take the MAT?</p>
			<label for="mat_taken_yes"><input type="radio" id="mat_taken_yes" name="mat_taken" value="1" onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','block')" />Yes </label>
			<label for="mat_taken_no"><input type="radio" id="mat_taken_no" name="mat_taken" value="0" checked onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','none')" /> No</label>
		</label>
		<script type="text/javascript">checkInitValue('mat_taken_yes',"{{MAT_TAKEN}}");</script>
		<script type="text/javascript">checkInitValue('mat_taken_no',"{{MAT_TAKEN}}");</script>

		<div style="clear:both;"></div>

		<div class="hidden" id="mat_taken_section">
			<label for="mat_date">
				<p class="title">Date of Exam</p>
				<input type="text" size="14" maxlength="7" id="mat_date" name="mat_date" value="{{MAT_DATE}}" onblur="saveValue(event,{{USER}});" />
				<div class="help">mm/yyyy</div>
			</label>

			<label for="mat_score">
				<p class="title">MAT Score</p>
				<input type="text" size="3" maxlength="3" id="mat_score" name="mat_score" value="{{MAT_SCORE}}" class="mat_score" onblur="saveValue(event,{{USER}});" />
			</label>

			<div style="clear:both;"></div>

			<label for="mat_reported">
				<p class="title">Has this score been reported to the University of Maine?</p>
				<label for="mat_reported_yes"><input type="radio" id="mat_reported_yes" name="mat_reported" value="1" onchange="saveCheckValue(event,{{USER}});" /> Yes</label>
				<label for="mat_reported_no"><input type="radio" id="mat_reported_no" name="mat_reported" value="0"checked onchange="saveCheckValue(event,{{USER}});" /> No</label>
			</label>
			<script type="text/javascript">checkInitValue('mat_reported_yes',"{{MAT_REPORTED}}");</script>
			<script type="text/javascript">checkInitValue('mat_reported_no',"{{MAT_REPORTED}}");</script>

			<div style="clear:both"></div>
		</div>
		<script type="text/javascript">showOrNot('mat_taken_section',{{MAT_TAKEN}});</script>

		<div style="clear:both;"></div>
	</fieldset><!-- end fieldset "mat" -->

	<div style="clear:both;"></div>
</fieldset><!--end fieldset "examinations"-->	

<div style="clear:both"></div>

<fieldset id="work" name="work" class="nested">
	<legend>Work History and Awards</legend>	

	<label for="present_occupation">
		<p class="title">Present Occupation</p>
		<input type="text" size="30" maxlength="30" id="present_occupation" name="present_occupation" value="{{PRESENT_OCCUPATION}}" onblur="saveValue(event,{{USER}});" />
	</label>

	<label for="employment_history">
		<p class="title"><b>Employment/Extra-Curricular Activties</b><br />List any employment or other activities related to your proposed program of study. If you have taught, name subjects. List published articles or books, research completed or in progress, or any other creative work.</p>
		<textarea cols="60" rows="5" id="employment_history" name="employment_history" value="" onblur="saveValue(event,{{USER}});">{{EMPLOYMENT_HISTORY}}</textarea>
	</label>

	<label for="academic_honors">
		<p class="title"><b>Honors/Scholarships</b><br />
		List any honors, prizes or scholarships previously awarded to you on the basis of academic achievement, or any honor societies to which you have been elected.</p>
		<textarea cols="60" rows="5" id="academic_honors" name="academic_honors" value="" onblur="saveValue(event,{{USER}});">{{ACADEMIC_HONORS}}</textarea>
	</label>

	<div style="clear:both;"></div>
</fieldset><!-- end fieldset "work" -->

<div style="clear:both;"></div>

<div id="pager">
	<p><span style="float:left;"><a href="app_manager.php?form_id=3"> << Previous Section</a></span>
	<span style="float:right;"><a href="app_manager.php?form_id=5"> Next Section >> </a></span></p>
</div>

<div style="clear:both;"></div>
</fieldset><!-- end fieldset "educational_history" -->
</form>
