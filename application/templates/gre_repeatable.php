<div id="{{TABLE_NAME}}_{{INDEX}}" class="{{HIDE}}">
	<fieldset class="nested" >
		<button class="button red small remove" type="button" onClick='removeItem("{{TABLE_NAME}}_{{INDEX}}",{{USER}}); return false;' title="Remove">Remove</button>
		<!-- <legend>GRE Score {{COUNT_INDEX}}</legend> -->
		<h2>GRE Score {{COUNT_INDEX}}</h2>

		<label for="gre_date[{{INDEX}}]">
			<p class="title">Date of Exam</p>
			<input type="text" size="14" maxlength="7" id="gre_date[{{INDEX}}]" name="gre_date" value="{{GRE_DATE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
			<p class="help">mm/yyyy</p>
		</label>

		<label for="gre_verbal[{{INDEX}}]">
			<p class="title">Verbal</p>
			<input type="text" size="3" maxlength="3" id="gre_verbal[{{INDEX}}]" name="gre_verbal" value="{{GRE_VERBAL}}" class="digits gre_verbal" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>

		<label for="gre_quantitative[{{INDEX}}]">
			<p class="title">Quantitative</p>
			<input type="text" size="3" maxlength="3" id="gre_quantitative[{{INDEX}}]" name="gre_quantitative" value="{{GRE_QUANTITATIVE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>

		<label for="gre_analytical[{{INDEX}}]">
			<p class="title">Analytical</p>
			<input type="text" size="5" maxlength="5" id="gre_analytical[{{INDEX}}]" name="gre_analytical" value="{{GRE_ANALYTICAL}}" class="gre_analaytical" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>

		<label for="gre_score[{{INDEX}}]">
			<p class="title">Subject Score</p>
			<input type="text" size="3" maxlength="3" id="gre_score[{{INDEX}}]" name="gre_score" value="{{GRE_SCORE}}" class="gre_score" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>

		<div style="clear:both;"></div>

		<label for="gre_subject[{{INDEX}}]">
			<p class="title">GRE Subject</p>
			<select id="gre_subject[{{INDEX}}]" name="gre_subject"  value="{{GRE_SUBJECT}}" class="gre_subject" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value=""	>- None -</option>
				<option value="BCMB" 	>Biochemistry, Cell and Molecular Biology</option>
				<option value="BIO" 	>Biology</option>
				<option value="CHEM" 	>Chemistry</option>
				<option value="COS" 	>Computer Science</option>
				<option value="LIT" 	>Literature in English</option>
				<option value="MATH" 	>Mathematics</option>
				<option value="PHYS" 	>Physics</option>
				<option value="PSY" 	>Psychology</option>
			</select>
		</label>
		<script type="text/javascript">initValue('gre_subject[{{INDEX}}]','{{GRE_SUBJECT}}');</script>

		<div style="clear:both;"></div>

		<label for="gre_reported">
			<p class="title">Has this score been reported to the University of Maine?</p>
			<label for="gre_reported_yes[{{INDEX}}]"><input type="radio" id="gre_reported_yes[{{INDEX}}]" name="gre_reported[{{INDEX}}]" value="1" onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" /> Yes</label>
			<label for="gre_reported_no[{{INDEX}}]"><input type="radio" id="gre_reported_no[{{INDEX}}]" name="gre_reported[{{INDEX}}]" value="" checked onchange="saveCheckValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" /> No</label>
		</label>
		<script type="text/javascript">checkInitValue('gre_reported_yes[{{INDEX}}]',"{{GRE_REPORTED}}");</script>
		<script type="text/javascript">checkInitValue('gre_reported_no[{{INDEX}}]',"{{GRE_REPORTED}}");</script>
		

		<div style="clear:both;"></div>
	</fieldset>

	<div style="clear:both;"></div>
</div>
