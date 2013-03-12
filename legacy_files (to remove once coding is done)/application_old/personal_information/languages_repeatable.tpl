<div id="{{TABLE_NAME}}_{{INDEX}}" class="{{HIDE}}">
	<fieldset class="nested">
		<button class="button red small remove" type="button" onClick='removeItem("{{TABLE_NAME}}_{{INDEX}}",{{USER}}); return false;' title="Remove">Remove</button>
		<label for="language[{{INDEX}}]">
			<p>Language</p>
			<input type="text" size="20" maxlength="20" id="language[{{INDEX}}]" name="language" value="{{LANGUAGE}}" onblur="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
		</label>
		
		<label for="writing_proficiency[{{INDEX}}]">
			<p>Writing Proficiency</p>
			<select id="writing_proficiency[{{INDEX}}]" name="writing_proficiency" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value=""	>- None -</option>
				<option value="Good" 	>Good</option>
				<option value="Fair" 	>Fair</option>
				<option value="Poor" 	>Poor</option>
			</select>
		</label>
		<script type="text/javascript">initValue('writing_proficiency[{{INDEX}}]',"{{WRITING_PROFICIENCY}}");</script>
		
		<label for="reading_proficiency[{{INDEX}}]">
			<p>Reading Proficiency</p>
			<select id="reading_proficiency[{{INDEX}}]" name="reading_proficiency" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value=""		>- None -</option>
				<option value="Good" 	>Good</option>
				<option value="Fair" 	>Fair</option>
				<option value="Poor" 	>Poor</option>
			</select>
		</label>
		<script type="text/javascript">initValue('reading_proficiency[{{INDEX}}]',"{{READING_PROFICIENCY}}");</script>
		
		<label for="speaking_proficiency[{{INDEX}}]">
			<p>Speaking Proficiency</p>
			<select id="speaking_proficiency[{{INDEX}}]" name="speaking_proficiency" onchange="saveValue(event,{{USER}},'{{TABLE_NAME}}',{{INDEX}});">
				<option value=""		>- None -</option>
				<option value="Good" 	>Good</option>
				<option value="Fair" 	>Fair</option>
				<option value="Poor" 	>Poor</option>
			</select>
		</label>
		<script type="text/javascript">initValue('speaking_proficiency[{{INDEX}}]',"{{SPEAKING_PROFICIENCY}}");</script>
	</fieldset>

	<div style="clear:both;"></div>
</div>