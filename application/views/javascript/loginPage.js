$(document).ready(function(){
	$("#createForm").validate({
		rules: {
			create_email_confirm: {equalTo: "#create_email"},
			create_password: "required",
			create_password_confirm: {equalTo: "#create_password", required: true}
		}
	});
	
	$("#signinForm").validate({
		rules: {
			
		}
	})
});