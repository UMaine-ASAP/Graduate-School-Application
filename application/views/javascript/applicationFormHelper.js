/**
 * Application Form Helper JS
 *
 * Application Form Helper defines helper functions for creating and managing application fields.
 * 
 * These functions support setting default field values in the application, saving values asynchronously to the server, 
 * managing application section visibility, and adding/removing application repeatables.
 */



/**
 * Save Value
 *
 * Attempts to save field value to server. Displays any errors next to the field 
 * 
 * @param  Object evt - the event generated by field being saved
 * @return void
 */
function saveValue(evt) {
	if (!evt) var evt = window.event;

	// Convert event to target element
	var target;
	if (evt.target) {
		target = evt.target;
	} else if (evt.srcElement) {
		target = evt.srcElement;
	}

	// defeat Safari bug
	if (target.nodeType == 3) {		
		target = target.parentNode;
	}
	
	// Map check box input to correct value
	if(target.type == 'checkbox') {
		target.value = target.checked ? '1' : '0';
	}

	// Submit save request
	$.ajax({
		type: "POST",
		url: WEBROOT + "/application/saveField",
		data:{ "field": target.name, "value": target.value },
		success: function(response) {
			_saveFieldCallback(response, target);
		}
	});

}


/**
 * (Internal Method) Save Field Callback
 *
 * Processes the result of saveValue by displaying an error message
 * 
 * @param  {[type]} response [description]
 * @param  {[type]} target   [description]

 * @return void
 */
function _saveFieldCallback(response, target) {
	saveFieldCallback(response, target);
	if($("#validate_wrapper")) {
		if (response == 1 || response == '' || response == '\n') {
			// Save was successful
			$("#validate_wrapper").validate().showErrors(null);
		} else {
			// There was an Error saving the field name, display response
			var errObj = new Object();
			errObj[target.name] = response;
			$("#validate_wrapper").validate().showErrors(errObj);
		}
	}
}


/**
 * Save Field Callback
 *
 * External callback for when a field has successfully saved. Override this function and use to your hearts content 
 * 
 * @param  {[type]} response [description]
 * @param  {[type]} target   [description]

 * @return void
 */
function saveFieldCallback(response, target) { }


/**
 * Init Value
 *
 * Loads the default value into the specified element
 * 
 * @param  String elementId - The id of the element to load the data into
 * @param  String value     - The value to load into the element

 * @return void
 */
function initValue(elementId, value) {
	var element = document.getElementById(elementId);
	if(element.length == 1){
		if(element.value == value){ element.selected=true;}
	} else {
		for(i=0;i<element.length;i++) {
			if(element[i].value == value){ element[i].selected=true;}
		}
	}
}


/**
 * Check Init Value
 *
 * Checks the corresponding element if the value is set.
 * Used primarily as a helper for forms.macro.twig
 * 
 * @param  string element - the id of the item to check
 * @param  string value   - the value to check
 * 
 * @return void
 */
function checkInitValue(elementId, value) {
	element = document.getElementById(elementId);
	element.checked = element.value == value;
}


/**
 * Add Repeatable Of Type
 *
 * Appends a new repeatable of the specified type
 * 
 * @param string repeatableType - the name of the repeatable to create
 *
 * @return void
 */
function addRepeatableOfType(repeatableType) {
	$.get(WEBROOT + "/application/getTemplate/"+repeatableType, function(data){
		index = $('.' + repeatableType).size() + 1;

		var groupSelector = '#' + repeatableType + 's'; 
		var itemSelector  = '#' + repeatableType + 's_' + index;

		console.log( $(itemSelector) );

		$(groupSelector).append(data);
		$(itemSelector).fadeIn();
		$(itemSelector).slideDown();
	}, "text");
}


/**
 * Remove Repeatable With Id
 *
 * Removes the repeatable with the specified id
 * 
 * @param string id - the id of the repeatable to remove
 *
 * @return void
 */
function removeRepeatableWithId(id) {
	var d = document.getElementById(id);
	$("#"+d.id+" fieldset").fadeOut();
	$(d).slideUp("normal",function() {
		$(d).remove();
	});
	$.ajax({
		type: "POST",
		url: "/application/delete-repeatable",
		data: "id="+id,
		success: function(data) {
		}
	});
}


/** 
 * Visibility
 *
 * Sets the visibility of a particular element
 * 
 * @param  string  elementId - The id of the item to show or hide
 * @param  Boolean or int isVisible - Indicates whether the element should be shown or not
 * 
 * @return void
 */
function showOrNot(elementId, isVisible) {
	var el = document.getElementById(elementId);
	if(isVisible || isVisible == 1) el.style.display = 'block';
	else if (!isVisible || isVisible == 0) {
		if (el.style.display == 'none') {
			el.style.display = 'block';
		} else {
			el.style.display = 'none';
		}
	}
}


/**
 * Stop Return Key
 *
 * Keeps the return key from submitting the form. Use by setting onkeypress function. e.g. document.onkeypress = stopRKey;
 * 
 * @param  Object evt - the keyboard event.
 * 
 * @return void
 */
function stopRKey(evt) { 
	var evt = (evt) ? evt : ((event) ? event : null); 
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
	if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
}