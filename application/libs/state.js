function getStateOptions(inputID, initValue) {
	var input = document.getElementById(inputID);
	
	options  = "_Select ,INT_International,AL_Alabama,AK_Alaska,AZ_Arizona,AR_Arkansas,CA_California,CO_Colorado,CT_Connecticut,DC_District of Columbia,DE_Delaware,FL_Florida,GA_Georgia,HI_Hawaii,ID_Idaho,IL_Illinois,IN_Indiana,IA_Iowa,KS_Kansas,KY_Kentucky,LA_Louisiana,ME_Maine, MD_Maryland,MA_Massachusetts,MI_Michigan,MN_Minnesota,MS_Mississippi,MO_Missouri,MT_Montana,NE_Nebraska,NV_Nevada,NH_New Hampshire,NJ_New Jersey,NM_New Mexico,NY_New York,NC_North Carolina,ND_North Dakota,OH_Ohio,OK_Oklahoma,OR_Oregon,PA_Pennsylvania,RI_Rhode Island,SC_South Carolina,SD_South Dakota,TN_Tennessee,TX_Texas,UT_Utah,VT_Vermont,VA_Virginia,WA_Washington,WV_West Virginia,WI_Wisconsin,WY_Wyoming,AB_Alberta,BC_British Columbia,MB_Manitoba,NB_New Brunswick,NL_Newfoundland and Labrador,NS_Nova Scotia, ON_Ontario,PE_Prince Edward Island,QC_Quebec,SK_Saskatchewan,NT_Northwest Territories,NU_Nunavut,YT_Yukon";

	var optionList = options.split(",");
	for(var i = 0; i < optionList.length; i++) {
		var data = optionList[i].split("_");
		var option = document.createElement("option");
		option.value = data[0];
		option.appendChild(document.createTextNode(data[1]));
		input.appendChild(option);
	}

	if(initValue != null) {
		var initSrc = "initValue('"+inputID+"','"+initValue+"');";
		var initJS = document.createElement("script");
		initJS.type = 'text/javascript';
		//initJS.appendChild(document.createTextNode(initSrc));
		initJS.text = initSrc;
		input.parentNode.appendChild(initJS);
	}
}
