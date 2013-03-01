		// stop the Return/Enter key from submitting the form
		document.onkeypress = stopRKey;

		$(document).ready(function(){
			$("#validate_wrapper").validate({
				errorElement: "div",
				wrapper: "span",
				errorPlacement: function(error, element) {
					var vis = element.is(":visible");
					if (!vis)
				    element.show();// must be visible to get .position
				var offset = element.position();
				if (!vis)
				    element.hide();// restore visibility

				error.insertBefore(element);
				error.addClass('error_wrapper');
				error.css('position', 'absolute');
				var left = offset.left + element.outerWidth();
				var top = offset.top - (error.outerHeight() - element.outerHeight())/2;
				error.css('left', left);
				//error.css('top', top);
			}
		});
		});




$(document).ready(function() {

	$('#save-command').click( function() {
		alert('Your progress has been saved');
	});
});
