function saveValue(e,user,table_name,index) {

	//alert(e+" "+user+" "+table_name+" "+index);
	var targ;
	if (!e) var e = window.event;
	if (e.target) targ = e.target;
	else if (e.srcElement) targ = e.srcElement;
	if (targ.nodeType == 3) // defeat Safari bug
		targ = targ.parentNode;
		
	if(!table_name) table_name = '';
	if(!index) index = '';
	
	//alert(targ.name+" "+targ.value);
	
	$.ajax({
		type: "POST",
		url: "libs/saveData.php",
		data: "id="+user+"&table="+table_name+"&index="+index+"&field="+targ.name+"&value="+targ.value,
		success: function(msg){/*alert(msg)*/}
	});
}

function saveCheckValue(e,user,table_name,index) {
	var targ;
	if (!e) var e = window.event;
	if (e.target) targ = e.target;
	else if (e.srcElement) targ = e.srcElement;
	if (targ.nodeType == 3) // defeat Safari bug
		targ = targ.parentNode;
		
	//str ="id="+user+"&field="+targ.name+"&value="+targ.value;
	//alert(str);
	
	if(!table_name) table_name = '';
	if(!index) index = '';
	if(!targ.checked) targ.value = "";
	
	field = targ.name;
	if(field.substr(field.length-1,1) == "]") {
		//alert(field);
		field = field.substring(0,field.length-3);
		//alert(field);
	}
	
	//alert("id="+user+"&table="+table_name+"&index="+index+"&field="+targ.name+"&value="+targ.value);
	$.ajax({
		type: "POST",
		url: "libs/saveData.php",
		data: "id="+user+"&table="+table_name+"&index="+index+"&field="+field+"&value="+targ.value,
		success: function(msg){/*alert("*"+msg)*/}
	});
}

function initValue_old(element,value) {
	str = "";
	var e = document.getElementById(element);
	var quit = false;
	
	//alert(e+"\n"+e.id +"\n"+value);
	for(i=0;i<e.length && !quit;i++) {
		//alert("Element ID:"+e.id+"\nValue:"+element[i].value+"\nPassed:"+value+"\ni:"+(i+1)+"/"+e.length);
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
	//alert(element.name+" ?= "+value);
	element.checked = element.value == value;
}

function addItem(table) {
	$.get("templates/"+table+"_repeatable.php",function(data){
		//Get information for replacement
		form_item = data;		
		user  = document.getElementById("user_id").value;
		//alert(user+" "+table+" "+form_item);
		
		index = null;
		$.get("libs/nextIndex.php",{tablename:table,username:user,random:new Date().getTime()},function(nextIndex){
			//alert(nextIndex);

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

function removeItem(id,user) {
	
	//var minimum = document.getElementById();
	
	var d = document.getElementById(id)
	$("#"+d.id+" fieldset").fadeOut();
	$(d).slideUp("normal",function() {
		$(d).remove();
	});
	
	$.ajax({
		type: "POST",
		url: "libs/removeItem.php",
		data: "remove_id="+id+"&user="+user,
		success: function(msg){/*alert(msg);*/}
	});
}

function visibility(elementID,vis) {
	var theDiv = document.getElementById(elementID);
	
	//alert(elementID);
	
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
	//$(elementID).slideDown();
}

function showOrNot(element,showVal) {
	var el = document.getElementById(element);
	
	//alert(el+" "+el.id);
	
	if(showVal == 1) el.style.display = 'block';
	else if (showVal == 0) el.style.display = 'none';
}

function stopRKey(evt) { 
  var evt = (evt) ? evt : ((event) ? event : null); 
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
}
