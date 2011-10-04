  <h3>Graduate Record Examination</h3>
  <div class="fieldset">
	<table class="three">
		<tr>
			<td colspan="3"><span class="field">Have you ever taken or intend to take the GRE exam?</span> <span class="value">{{GRE_TAKEN}}</span></td>
		</tr>
		<tr>
			{{GRESCORES}}
		</tr>
	</table>
  </div>

  <h3>Graduate Management Admission Test</h3>
  <div class="fieldset">
	<table class="two">
		<tr>
			<td colspan='2'><span class="field">Have you ever taken or intend to take the GMAT?</span> <span class="value">{{GMAT_TAKEN}}</span></td>
		</tr>
		<tr>
			<td><span class="field">GMAT Score:</span> <span class="value">{{GMAT_SCORE}}</span></td>
			<td><span class="field">Date:</span> <span class="value">{{GMAT_DATE}}</span></td>
		</tr>
	</table>
  </div>
  <h3>Miller Analogies Test</h3>
  <div class="fieldset">
	<table class="two">
		<tr>
			<td colspan='2'><span class="field">Have you ever taken or intend to take the MAT?</span> <span class="value">{{MAT_TAKEN}}</span></td>
		</tr>
		<tr>
			<td><span class="field">GMAT Score:</span> <span class="value">{{MAT_SCORE}}</span></td>
			<td><span class="field">Date:</span> <span class="value">{{MAT_DATE}}</span></td>
		</tr>
	</table>
  </div>
  <h3>Honors and Achievements</h3>
  <div class="fieldset">
	<p><span class="field">List any honors, prizes, or scholarships previously awarded to you on the basis of academic achievement, or any honor societies to which you have been elected.</span></p>
	<div>
		<span class="value"><pre>{{ACADEMIC_HONORS}}</pre></span>
	</div>
	
  </div>
  <h3></h3>
  <div class="fieldset">
	
	<p><span class="field">List any employment or other activities related to your proposed program of study. If you have taught, name subjects. List published articles or books, research completed or in progress, or any other creative work.</span></p>
	<div>
		<span class="value"><pre>{{EMPLOYMENT_HISTORY}}</pre></span>
	</div>
  </div>
  
  <h3>Recommendations</h3>

	<table class="three box">
		<tr>
			<td>
				<p><span class="value">{{REFERENCE1_FIRST}}</span> <span class="value">{{REFERENCE1_LAST}}</span></p>
				<p><span class="value">{{REFERENCE1_ADDR1}} {{REFERENCE1_ADDR2}}</span></p>
				<p><span class="value">{{REFERENCE1_CITY}}</span><span class="value">{{REFERENCE1_STATE}}</span> <span class="value">{{REFERENCE1_POSTAL}}</span> <span class="value">{{REFERENCE1_COUNTRY}}</span></p>
				<p><span class="field">Email:</span> <span class="value">{{REFERENCE1_EMAIL}}</span></p>
				<p><span class="field">Phone:</span> <span class="value">{{REFERENCE1_PHONE}}</span></p>
			</td>
			<td>
				<p><span class="value">{{REFERENCE2_FIRST}}</span> <span class="value">{{REFERENCE2_LAST}}</span></p>
				<p><span class="value">{{REFERENCE2_ADDR1}} {{REFERENCE2_ADDR2}}</span></p>
				<p><span class="value">{{REFERENCE2_CITY}}</span> <span class="value">{{REFERENCE2_STATE}}</span> <span class="value">{{REFERENCE2_POSTAL}}</span> <span class="value">{{REFERENCE2_COUNTRY}}</span></p>
				<p><span class="field">Email:</span> <span class="value">{{REFERENCE2_EMAIL}}</span></p>
				<p><span class="field">Phone:</span> <span class="value">{{REFERENCE2_PHONE}}</span></p>
			</td>
			<td>
				<p><span class="value">{{REFERENCE3_FIRST}}</span> <span class="value">{{REFERENCE3_LAST}}</span></p>
				<p><span class="value">{{REFERENCE3_ADDR1}} {{REFERENCE3_ADDR2}}</span></p>
				<p><span class="value">{{REFERENCE3_CITY}}</span> <span class="value">{{REFERENCE3_STATE}}</span> <span class="value">{{REFERENCE3_POSTAL}}</span> <span class="value">{{REFERENCE3_COUNTRY}}</span></p>
				<p><span class="field">Email:</span> <span class="value">{{REFERENCE3_EMAIL}}</span></p>
				<p><span class="field">Phone:</span> <span class="value">{{REFERENCE3_PHONE}}</span></p>
			</td>
		</tr>
	</table>
	<table class='three box'>
		<tr>
			{{EXTRA_RECOMMENDATIONS}}
		</tr>
	</table>
	
