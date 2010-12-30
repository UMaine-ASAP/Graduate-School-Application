<div id="{{TABLE_NAME}}_{{INDEX}}" class="{{HIDE}}">
	<fieldset class="nested">

		<label for="academic_dept_code[{{INDEX}}]"> 
			<p class="required title">Department*</p>
			<select id="academic_dept_code[{{INDEX}}]" name="academic_dept_code" value="{{ACADEMIC_DEPT_CODE}}" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}}); updateAcademicPrograms('academic_dept_code[{{INDEX}}]','academic_program[{{INDEX}}]');">
				<option value="">Select Department</option>
			</select>
			<script type="text/javascript">drawDeptMenu('academic_dept_code[{{INDEX}}]',{{USER}},'{{TABLE_NAME}}',{{INDEX}},"{{ACADEMIC_DEPT_CODE}}");</script>
			<!-- <script type="text/javascript">dynamicInitSelectValue('academic_dept_code[{{INDEX}}]',"{{ACADEMIC_DEPT_CODE}}")</script> -->
		</label>
		
		<label for="academic_program[{{INDEX}}]">
			<p class="required title">Degree*</p>
			<select id="academic_program[{{INDEX}}]" name="academic_program" value="{{ACADEMIC_PROGRAM}}" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value="">Select Degree</option>
			</select>
			<script type="text/javascript">drawProgramMenu('academic_program[{{INDEX}}]',{{USER}},'{{TABLE_NAME}}',{{INDEX}},'{{ACADEMIC_DEPT_CODE}}',"{{ACADEMIC_PROGRAM}}");</script>
			<!-- <script type="text/javascript">dynamicInitSelectValue('academic_program[{{INDEX}}]',"{{ACADEMIC_PROGRAM}}")</script> -->
		</label>
				
		<div style="clear:both;"></div>

		<label for="academic_major[{{INDEX}}]">
			<p class="title">Major area of Interest</p>
			<input type="text" size="29" maxlength="30" id="academic_major[{{INDEX}}]" name="academic_major" value="{{ACADEMIC_MAJOR}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>
		
		<label for="academic_minor[{{INDEX}}]">
			<p class="title">Minor area of Interest</p>
			<input type="text" size="30" maxlength="30" id="academic_minor[{{INDEX}}]" name="academic_minor" value="{{ACADEMIC_MINOR}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});" />
		</label>
	
		<div style="clear:both;"></div>

		<label for="student_type[{{INDEX}}]">
			<p class="required title">Student Type *</p>
			<select id="student_type[{{INDEX}}]" name="student_type"  value="{{STUDENT_TYPE}}" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value=""	>- None -</option>
				<option value="IS" 	>In-State</option>
				<option value="OS" 	>Out of State</option>
				<option value="INTNL" 	>International</option>
				<option value="NEBHE" 	>NEBHE program</option>
			</select>
		</label> 	
		<script type="text/javascript">initValue('student_type[{{INDEX}}]',"{{STUDENT_TYPE}}")</script>

		<label for="start_semester[{{INDEX}}]">
			<p class="required title">Start Semester*</p>
			<select id="start_semester[{{INDEX}}]" name="start_semester"  value="{{START_SEMESTER}}" class="start_semester" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value=""	>- None -</option>
				<option value="FALL" 	>Fall</option>
				<option value="SPRING" 	>Spring</option>
				<option value="SUMMER" 	>Summer</option>
			</select>
		</label>
		<script type="text/javascript">initValue('start_semester[{{INDEX}}]',"{{START_SEMESTER}}")</script>
		
		<label for="start_year[{{INDEX}}]">
			<p class="required title">Start Year*</p>
			<select id="start_year[{{INDEX}}]" name="start_year"  value="{{START_YEAR}}" class="start_year" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value="">- None -</option>
				<script type="text/javascript">
					var d = new Date();
					for(var i=0; i<4; i++) {
						var year = d.getFullYear()+i;
						document.write('<option value="'+year+'">'+year+'</option>');
					}
				</script>

			</select>
		</label>
		<script type="text/javascript">initValue('start_year[{{INDEX}}]',"{{START_YEAR}}")</script>
		
		<label for="attendance_load[{{INDEX}}]">
			<p class="required title">Do you expect to study full time or part time?*</p>
			<select id="attendance_load[{{INDEX}}]" name="attendance_load"  value="{{ATTENDANCE_LOAD}}" class="attendance_load" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value=""	>- None -</option>
				<option value="F" 	>Full-Time</option>
				<option value="P" 	>Part-Time</option>
			</select>
		</label>
		<script type="text/javascript">initValue('attendance_load[{{INDEX}}]',"{{ATTENDANCE_LOAD}}");</script>

		<div style="clear:both;"></div>
	</fieldset>

	<div style="clear:both;"></div>
</div>
