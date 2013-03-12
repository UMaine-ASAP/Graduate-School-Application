<div id="{{TABLE_NAME}}_{{INDEX}}" class="{{HIDE}}">
	<fieldset class="nested" >
		<button class="button red small remove" type="button" onClick='removeItem("{{TABLE_NAME}}_{{INDEX}}",{{USER}}); return false;' title="Remove">Remove</button>
		<!-- <legend>GRE Score {{COUNT_INDEX}}</legend> -->
		
		<h2>GRE Score {{COUNT_INDEX}}</h2>

		{{ forms.input({'title':'Date of Exam',
				'name':'gre-date',
				'value':gre.date,
				'size': 14,
				'maxLength':7,
				'help':'mm/dd/yyyy'
			} ) }}

		{{ forms.input({'title':'Verbal',
				'name':'gre-verbal',
				'value':gre.verbal,
				'size': 3,
				'maxLength':3,
			} ) }}

		{{ forms.input({'title':'Quantitative',
				'name':'gre-quantitative',
				'value':gre.quantitative,
				'size': 3,
				'maxLength':3,
			} ) }}		

		{{ forms.input({'title':'Analytical',
				'name':'gre-analytical',
				'value':gre.analytical,
				'size': 3,
				'maxLength':3,
			} ) }}

		{{ forms.input({'title':'Subject Score',
				'name':'gre-score',
				'value':gre.score,
				'size': 3,
				'maxLength':3,
			} ) }}

		<div style="clear:both;"></div>

		
		{{ forms.select( {'title':'GRE Subject',
				'name':'gre-subject',
				'value': gre.subject,
				'options': gre.options_subject
			 }) }}

		<!-- +todo create menu items 

				<option value=""	>- None -</option>
				<option value="BCMB" 	>Biochemistry, Cell and Molecular Biology</option>
				<option value="BIO" 	>Biology</option>
				<option value="CHEM" 	>Chemistry</option>
				<option value="COS" 	>Computer Science</option>
				<option value="LIT" 	>Literature in English</option>
				<option value="MATH" 	>Mathematics</option>
				<option value="PHYS" 	>Physics</option>
				<option value="PSY" 	>Psychology</option>
			</select> -->

		<div style="clear:both;"></div>

		{{ forms.boolean( {'title':'Has this score been reported to the University of Maine?',
			'name':'gre-hasBeenReported',
			'value':gre.hasBeenReported
				} ) }}

		<div style="clear:both;"></div>
	</fieldset>

	<div style="clear:both;"></div>
</div>
