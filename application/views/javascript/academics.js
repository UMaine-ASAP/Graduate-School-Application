function drawDeptMenu(nameOfForm,user,table,index,initValue) {
	var fieldname = nameOfForm;
	
	//if(index && table) fieldname = fieldname; // + '['+index+']';
	
	$.ajax({
		type: "POST",
		url: "models/listDept.php",
		data: "type=dept",
		success: function(results) {
			options = document.getElementById(fieldname);
			var dept_options = results.split("*");
			for(var i = 0; i < dept_options.length; i++){
				var thisOp = document.createElement('option');
				var data = dept_options[i].split("_");
				thisOp.value = data[0];
				thisOp.appendChild(document.createTextNode(data[0]+" - "+data[1]));
				options.appendChild(thisOp);
			}

			if(initValue != null) {
				var initSrc = "initValue('"+nameOfForm+"','"+initValue+"');";
				var initJS = document.createElement("script");
				initJS.type = 'text/javascript';
				initJS.text = initSrc;
				options.parentNode.appendChild(initJS);
			}
		}
	});
}

function drawProgramMenu(nameOfForm,user,table,index,dept,initValue) {

	var fieldname = nameOfForm;
	
	//if(index && table) fieldname += '['+index+']';
	
	$.ajax({
		type: "POST",
		url: "models/listDept.php",
		data: "type=degree&dept="+dept,
		success: function(results){
			//alert(results);
			var pOptions = document.getElementById(fieldname);
			var prog_options = results.split("*");
			for(var i = 0; i < prog_options.length; i++){
				var thisOp = document.createElement('option');
				var data = prog_options[i].split("_");
				thisOp.value = data[0];
				thisOp.appendChild(document.createTextNode(data[1]));
				pOptions.appendChild(thisOp);
			}

			if(initValue != null) {
				var initSrc = "initValue('"+nameOfForm+"','"+initValue+"');";
				var initJS = document.createElement("script");
				initJS.type = 'text/javascript';
				//initJS.appendChild(document.createTextNode(initSrc));
				initJS.text = initSrc;
				pOptions.parentNode.appendChild(initJS);
			}
		}
	});	
}

function updateAcademicPrograms (ad,ap) {
	var adept = document.getElementById(ad)
	var aprog = document.getElementById(ap);
	
	dept=adept.value;
		
	$.ajax({
		type: "POST",
		url: "models/listDept.php",
		data: "type=degree&dept="+dept,
		success: function(results){
			//alert(results);
						
			//alert(aprog.options.length);
			for(var i = aprog.options.length-1; i >= 0; i--){
				//alert(i+": "+aprog.options[i].text)
				aprog.remove(i);
			}

			var initOp = document.createElement('option');
			initOp.value = "";
			initOp.appendChild(document.createTextNode("Select Degree"));
			aprog.appendChild(initOp);

			var dprog_options = results.split("*");
			for(var i = 0; i < dprog_options.length; i++){
				var data = dprog_options[i].split("_");
				var thisOp = document.createElement('option');
				thisOp.value = data[0];
				thisOp.appendChild(document.createTextNode(data[1]));
				aprog.appendChild(thisOp);
			}
		}
	});
}
