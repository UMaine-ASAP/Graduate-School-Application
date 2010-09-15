			<label for="finance" class="one_line">
				<p>Do you require the Certification of Finance Forms?
				<label for="finance" class="one_line"><input type="checkbox" id="finance" name="finance" value="1" onclick="visibility(this.name+'_section','block')"  onchange="saveCheckValue(event,{{USER}});" > Yes</label>
			</label> 
			<script type="text/javascript">checkInitValue(document.getElementById('finance'),"{{FINANCE}}");</script>
			<div class="hidden" id="finance_section">
				<p>link here!!!
			</div>
			<script type="text/javascript">showOrNot('finance_section',!{{FINANCE}});</script>