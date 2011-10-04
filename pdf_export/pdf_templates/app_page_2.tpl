  <h3>Educational Objectives</h3>
  <div class="fieldset">
  
  
	{{PROGRAMS}}
  
  
	<hr size=1>
	<table class="two">
		<tr>
			<td><span class="field">Have you previously applied to this institution?:</span> <span class="value">{{PREV_UM_GRAD_APP}}</span></td>
			<td><span class="field"></span> <span class="value"></span></td>
		</tr>
		<tr>
			<td><span class="field">When?</span> <span class="value">{{PREV_UM_GRAD_APP_DATE}}</span></td>
			<td><span class="field">To what department?</span> <span class="value">{{PREV_UM_GRAD_APP_DEPT}}</span></td>
		</tr>
		<tr>
			<td><span class="field">Did you withdraw?</span> <span class="value">{{PREV_UM_GRAD_WITHDRAW}}</span></td>
			<td><span class="field">When?</span> <span class="value">{{PREV_UM_GRAD_WITHDRAW_DATE}}</span></td>
		</tr>

		<tr >
			<td style='padding-top: 1em;'><span class="field">Do you wish to apply for an assistantship?</span> <span class="value">{{DESIRE_ASSISTANTSHIP}}</span></td>
			<td><span class="field"></span> <span class="value"></span></td>
		</tr>
		<tr>
			<td colspan="2"><span class="field">Do you intend to apply for certification of eligibility under the New England Regional Student Program?</span> <span class="value">{{APPLY_NEBHE}}</span></td>
		</tr>
		<tr>
			<td style='padding-top: 1em;' colspan="2"><span class="field">University of Maine Faculty you have corresponded with regarding your application:</span> <span class="value">{{UM_CORRESPOND_DETAILS}}</span></td>
		</tr>
	</table>
  </div>

<h3>Previous Education</h3>
	<table class="three box">
		<tr>
			{{INSTITUTIONS}}
		</tr>
	</table>

	<h3>Previous GPA and Coursework</h3>
  <div class="fieldset">
	<table class="two">
		<tr>
			<td><span class="field">Cumulative undergraduate GPA:</span> <span class="value">{{UNDERGRAD_GPA}}</span></td>
			<td><span class="field">Cumulative post-baccalaureate GPA:</span> <span class="value">{{POSTBACC_GPA}}</span></td>
		</tr>
		<tr>
			<td style='padding-top: 1em;' colspan="2"><p><span class="field">List, by title, all courses in progress or courses you expect to take before enrolling at UM:</span></p></td>
		</tr>
		<tr>
			<td>
				<span class="value"><pre>{{PREENROLL_COURSES}}</pre></span>
			</td>
		</tr>
	</table>
  </div>
  
<h3>Language Proficiency</h3>
  <div class="fieldset">
	<table class="two">
		{{LANGUAGES}}
		<tr>
			<td style='padding-top: 1em;'  colspan="2"><span class="field">If English is not your primary language, indicate the number of years you have studied English (give dates):</span>
		</tr>
		<tr>
			<td><span class="field">In secondary or middle school:</span> <span class="value">{{ENGLISH_YEARS_SCHOOL}}</span></td>
			<td><span class="field"></span> <span class="value"></span></td>
		</tr>
		<tr>
			<td><span class="field">In university:</span> <span class="value">{{ENGLISH_YEARS_UNIV}}</span></td>
			<td><span class="field"></span> <span class="value"></span></td>
		</tr>
		<tr>
			<td><span class="field">Under private auspices:</span> <span class="value">{{ENGLISH_YEARS_PRIVATE}}</span></td>
			<td><span class="field"></span> <span class="value"></span></td>
		</tr>
	</table>
  </div>

