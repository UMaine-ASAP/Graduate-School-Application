$(document).ready( function() {
	// makes command buttons into clickable links	
	$('.command-href').click( function(e) {
		e.preventDefault();
		window.location = $(this).data('href');
	});
})
