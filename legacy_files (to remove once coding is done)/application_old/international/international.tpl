<form action="" method="post" autocomplete="off">
<fieldset id="international" name="international">
	<legend>International Information</legend>
	
	<span class="required"><p>Fields marked with * are required.</p></span>
	
	<fieldset class="nested">
		<label for="international" class="one_line">
			<p class="required">Are you a citizen of the United States?*</p>
			<label for="international_yes"><input type="radio" id="international_yes" name="international" value="1" onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','none')" /> Yes</label>

			<label for="international_no"><input type="radio" id="international_no" name="international" value="0" onchange="saveCheckValue(event,{{USER}});" onclick="visibility(this.name+'_section','block')" /> No</label>
		</label>
		<script type="text/javascript">checkInitValue('international_yes',"{{INTERNATIONAL}}");</script>
		<script type="text/javascript">checkInitValue('international_no',"{{INTERNATIONAL}}");</script>
		<div style="clear:both"></div>
	</fieldset>

	<div style="clear:both"></div>

	<div class="hidden" id="international_section">
		<div id="{{INTERNATIONAL_LIST}}">
			{{INTERNATIONAL_REPEATABLE}}
		</div>
	</div>
	<script type="text/javascript">showOrNot('international_section',!{{INTERNATIONAL}});</script>

	<div style="clear:both"></div>

	<div id="pager">
		<p><span style="float:left;"><a href="app_manager.php?form_id=2" ><span class="chevron">&#0171;</span> Previous Section</a></span>
		<span style="float:right;"><a href="app_manager.php?form_id=4"> Next Section <span class="chevron">&#0187;</span></a></span></p>
	</div>

</fieldset>
</form>
