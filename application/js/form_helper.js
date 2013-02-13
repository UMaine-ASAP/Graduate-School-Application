function saveDataCallback(response, target, value) {
	if($("#validate_wrapper")) {
		if (response == 1) {
			$("#validate_wrapper").validate().showErrors(null);
		} else {
			var errObj = new Object();
			errObj[target.name] = response;
			$("#validate_wrapper").validate().showErrors(errObj);
		}
	}
}

function saveValue(e,table_name,index) {
	var targ;
	if (!e) var e = window.event;
	if (e.target) targ = e.target;
	else if (e.srcElement) targ = e.srcElement;
	if (targ.nodeType == 3) // defeat Safari bug
		targ = targ.parentNode;
	
	// Map check box input to correct value
	if(targ.type == 'checkbox') {
		targ.value = targ.checked ? '1' : '0';
	}

	if(!table_name) table_name = '';
	if(!index) index = '';
	$.ajax({
		type: "POST",
		url: WEBROOT + "/saveData",
		data:{	"table": table_name, 
				"index": index, 
				"field": targ.name,
				"value": targ.value
			},
		success: function(response) {
			saveDataCallback(response, targ, targ.value);
		}
	});

}

function saveCheckValue(e,table_name,index) {
	var targ;
	if (!e) var e = window.event;
	if (e.target) targ = e.target;
	else if (e.srcElement) targ = e.srcElement;
	if (targ.nodeType == 3) // defeat Safari bug
		targ = targ.parentNode;
	
	if(!table_name) table_name = '';
	if(!index) index = '';
	if(!targ.checked) targ.value = "";
	
	field = targ.name;
	if(field.substr(field.length-1,1) == "]") {
		field = field.substring(0,field.length-3);
	}

	$.ajax({
		type: "POST",
		url: "scripts/saveData.php",
		data: "table="+table_name+"&index="+index+"&field="+field+"&value="+targ.value,
		success: function(response) {
			saveDataCallback(response, targ, targ.value);
		}
	});
}

function initValue(element,value) {
	var element = document.getElementById(element);
	if(element.length == 1){
		if(element.value == value){ element.selected=true;}
	} else {
		for(i=0;i<element.length;i++) {
			if(element[i].value == value){ element[i].selected=true;}
		}
	}
}

function dynamicInitSelectValue(element,value) {
	setTimeout(function(){
		initValue(element, value);
	},500);
}

function checkInitValue(element, value) {
	element = document.getElementById(element);
	element.checked = element.value == value;
}

function addItem(table) {
	$.get(WEBROOT + "/application/getTemplate/"+table,function(data){
		index = $('.' + table).size() + 1;

		var groupSelector = '#' + table + 's'; 
		var itemSelector  = '#' + table + 's_' + index;

		console.log( $(itemSelector) );

		$(groupSelector).append(data);
		$(itemSelector).fadeIn();
		$(itemSelector).slideDown();
	}, "text");
}
		
		
function removeItem(id) {
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

function visibility(elementID,vis) {
	var theDiv = document.getElementById(elementID);
	
	if(vis) {
		theDiv.style.display = vis;
	}else {
		if (theDiv.style.display == 'none') {
			theDiv.style.display = 'block';
		} else {
			theDiv.style.display = 'none';
		}
	}
}

function visSlideUp(elementID) {
	alert(elementID);
	$("#"+elementID.id).slideUp("slow")
	$("#"+elementID+" fieldset").fadeOut();
}

function visSlideDown(elementID) {
	$("#"+elementID.id).slideDown("slow")
	$("#"+elementID+" fieldset").fadeIn();
}

function showOrNot(element,showVal) {
	var el = document.getElementById(element);
	if(showVal || showVal == 1) el.style.display = 'block';
	else if (!showVal || showVal == 0) el.style.display = 'none';
}

function stopRKey(evt) { 
	var evt = (evt) ? evt : ((event) ? event : null); 
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
	if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
}
