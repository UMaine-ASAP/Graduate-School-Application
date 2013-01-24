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

function initValue_old(element,value) {
	str = "";
	var e = document.getElementById(element);
	var quit = false;
	
	for(i=0;i<e.length && !quit;i++) {
		if(e[i].value == value) { 
			e[i].selected = true;
			quit = true;
		}
	}
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
	$.get("templates/"+table+"_repeatable.php",function(data){
		//Get information for replacement
		form_item = data;		
		user = document.getElementById("user_id").value;
		
		index = null;
		$.get("libs/nextIndex.php",{tablename:table,username:user,random:new Date().getTime()},function(nextIndex){

			index = Number(nextIndex);

			//Replace relevant template items
			form_item = form_item.replace(/{{TABLE_NAME}}/g,table);
			form_item = form_item.replace(/{{INDEX}}/g,index);
			form_item = form_item.replace(/{{USER}}/g,user);
			form_item = form_item.replace(/{{HIDE}}/g,"hidden");
			form_item = form_item.replace(/{{([A-Z]|[0-9]|_)+}}/g,""); 		//Remove extra template items
			
			$("#"+table+"_list").append(form_item); 					//Add Form Item
			$("#"+table+"_"+index+" fieldset").fadeIn();
			$("#"+table+"_"+index).slideDown();

		},"text");		
		
	},"text");	
}

function removeItem(id) {	
	var d = document.getElementById(id)
	$("#"+d.id+" fieldset").fadeOut();
	$(d).slideUp("normal",function() {
		$(d).remove();
	});
	
	$.ajax({
		type: "POST",
		url: "libs/removeItem.php",
		data: "remove_id="+id
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
	if(showVal == 1) el.style.display = 'block';
	else if (showVal == 0) el.style.display = 'none';
}

function stopRKey(evt) { 
	var evt = (evt) ? evt : ((event) ? event : null); 
	var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
	if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
}
