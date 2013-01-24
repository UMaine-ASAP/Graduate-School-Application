<div id="personal_info">
	<div id="personal_name" class="personal_item">
		<div class="personal_label">Name</div>
		<div class="personal_value">{{GIVEN_NAME}} {{FAMILY_NAME}}</div>
	</div>
	
	<div id="personal_addr" class="personal_item">
		<div class="personal_label">Address</div>
		<div class="personal_value">
			<p>{{PERMANENT_ADDR1}}</p>
			<p>{{PERMANENT_ADDR2}}</p>
			<p>{{PERMANENT_CITY}}, {{PERMANENT_STATE}}, {{PERMANENT_POSTAL}}, {{PERMANENT_COUNTRY}}</p>
		</div>
	</div>
	
	<div id="personal_email" class="personal_item">
		<div class="personal_label">Email</div>
		<div class="personal_value">{{EMAIL}}</div>
	</div>
	
	<div id="personal_phone" class="personal_item">
		<div class="personal_label">Phone</div>
		<div class="personal_value">{{PRIMARY_PHONE}}</div>
	</div>
	
	<div id="personal_Edit" class="personal_item">
		<div class="personal_label">&nbsp;</div>
		<div class="personal_value">
			<form method="post" action="app_manager.php">
				<input type="submit" name="submit" value="Edit Information">
			</form>	
		</div>
	</div>	
</div>
