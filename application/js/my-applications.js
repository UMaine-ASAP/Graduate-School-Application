$(document).ready(function() {

	// convert created and modified dates

	function humanizeServerTime(context)
	{
		if ($(context).html() == '') return;

		var m = moment($(context).html(), "YYYY-MM-DD HH:mm:ss");
		if( m.isValid() )
		{
			$(context).html( m.from(moment()) );
		}

	}
	
	$('.created').each( function(item) {
		humanizeServerTime(this);
	});

	$('.lastModified').each( function(item) {
		humanizeServerTime(this);
	});



  $( "#create-application-modal" ).dialog({
      height: 250,
      width: 600,
      modal: true,
      autoOpen: false,
      resizable: false
    });

  	function getApplicantId(context)
  	{
		return $(context).parents('tr').data('application');
  	}

	$('#create-application').click( function() {
		$('#create-application-modal').dialog('open');
		console.log( getApplicantId(this) );

	});

	$('.edit-application').click( function() {
		window.location = WEBROOT + '/edit-application/' + getApplicantId(this);
	});


	$('.delete-application').click( function() {
		var allowDeletion = confirm('Are you sure you want to delete this application?');

		if (allowDeletion)
		{
			window.location = WEBROOT + '/delete-application/' + getApplicantId(this);
		}
	});

});
