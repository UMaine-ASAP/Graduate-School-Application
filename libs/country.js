function getCountryOptions(nameOfForm, initValue) {

	var countries = document.getElementById(nameOfForm);
	
	var countryOptions  = "_Select,USA_United States,AFG_Afghanistan,ALA_Aland Islands,ALB_Albania,DZA_Algeria,ASM_American Samoa,AND_Andorra,AGO_Angola,AIA_Anguilla,ATA_Antarctica,ATG_Antigua and Barbuda,ARG_Argentina,ARM_Armenia,ABW_Aruba,AUS_Australia,AUT_Austria,AZE_Azerbaijan,BHS_Bahamas,BHR_Bahrain,BGD_Bangladesh,BRB_Barbados,BLR_Belarus,BEL_Belgium,BLZ_Belize,BEN_Benin,BMU_Bermuda,BTN_Bhutan,BOL_Bolivia,BIH_Bosnia and Herzegovina,BWA_Botswana,BVT_Bouvet Island,BRA_Brazil,IOT_British Indian Ocean Territory,BRN_Brunei Darussalam,BGR_Bulgaria,BFA_Burkina Faso,BDI_Burundi,KHM_Cambodia,CMR_Cameroon,CAN_Canada,CPV_Cape Verde,CYM_Cayman Islands,CAF_Central African Republic,TCD_Chad,CHL_Chile,CHN_China,CXR_Christmas Island,CCK_Cocos (Keeling) Islands,COL_Colombia,COM_Comoros,COD_Congo,COK_Cook Islands,CRI_Costa Rica,CIV_C&#244;te D&#39;Ivoire,HRV_Croatia,CUB_Cuba,CYP_Cyprus,CZE_Czech Republic,DNK_Denmark,DJI_Djibouti,DMA_Dominica,DOM_Dominican Republic,TLS_East Timor,ECU_Ecuador,EGY_Egypt,SLV_El Salvador,GNQ_Equatorial Guinea,ERI_Eritrea,EST_Estonia,ETH_Ethiopia,FLK_Falkland Islands (Malvinas),FRO_Faroe Islands,FJI_Fiji,FIN_Finland,MKD_Fmr Yugoslav Rep of Macedonia,FRA_France,GUF_French Guiana,PYF_French Polynesia,ATF_French Southern Territories,GAB_Gabon,GMB_Gambia,GEO_Georgia,DEU_Germany,GHA_Ghana,GIB_Gibraltar,GRC_Greece,GRL_Greenland,GRD_Grenada,GLP_Guadeloupe,GUM_Guam,GTM_Guatemala,GGY_Guernsey,GNB_Guinea-Bissau,GIN_Guinea,GUY_Guyana,HTI_Haiti,HMD_Heard and McDonald Islands,VAT_Holy See (Vatican City State),HND_Honduras,HKG_Hong Kong,HUN_Hungary,ISL_Iceland,IND_India,IDN_Indonesia,IRN_Iran (Islamic Republic Of),IRQ_Iraq,IRL_Ireland,IMN_Isle of Man,ISR_Israel,ITA_Italy,JAM_Jamaica,JPN_Japan,JEY_Jersey,JOR_Jordan,KAZ_Kazakhstan,KEN_Kenya,KIR_Kiribati,LAO_Lao People&rsquo;s Democratic Republic,KOR_Korea, Republic of,KOS_Kosovo,KWT_Kuwait,KGZ_Kyrgyzstan,PRK_Korea, Democratic People&rsquo;s Republic,LVA_Latvia,LBN_Lebanon,LSO_Lesotho,LBR_Liberia,LBY_Libyan Arab Jamahiriya,LIE_Liechtenstein,LTU_Lithuania,LUX_Luxembourg,MAC_Macao,MDG_Madagascar,MWI_Malawi,MYS_Malaysia,MDV_Maldives,MLI_Mali,MLT_Malta,MHL_Marshall Islands,MTQ_Martinique,MRT_Mauritania,MUS_Mauritius,MYT_Mayotte,MEX_Mexico,FSM_Micronesia, Federated States,MDA_Moldova, Republic of,MCO_Monaco,MNG_Mongolia,MSR_Montserrat,MAR_Morocco,MOZ_Mozambique,MMR_Myanmar,NAM_Namibia,NRU_Nauru,NPL_Nepal,ANT_Netherlands Antilles,NLD_Netherlands,NCL_New Caledonia,NZL_New Zealand,NIC_Nicaragua,NER_Niger,NGA_Nigeria,NIU_Niue,NFK_Norfolk Island,MNP_Northern Mariana Islands,NOR_Norway,OMN_Oman,PAK_Pakistan,PLW_Palau,PSE_Palestinian Territory, Occupie,PAN_Panama,PNG_Papua New Guinea,PRY_Paraguay,PER_Peru,PHL_Philippines,PCN_Pitcairn,POL_Poland,PRT_Portugal,PRI_Puerto Rico,QAT_Qatar,MNE_Republic of Montenegro,SRB_Republic of Serbia,REU_Reunion,ROU_Romania,RUS_Russian Federation,RWA_Rwanda,BLM_Saint Barthelemy,SHN_Saint Helena,KNA_Saint Kitts and Nevis,LCA_Saint Lucia,MAF_Saint Martin,SPM_Saint Pierre and Miquelon,WSM_Samoa,SMR_San Marino,STP_Sao Tome and Principe,SAU_Saudi Arabia,SEN_Senegal,SMX_Serbia and Montenegro,SYC_Seychelles,SLE_Sierra Leone,SGP_Singapore,SVK_Slovakia,SVN_Slovenia,SLB_Solomon Islands,SOM_Somalia,ZAF_South Africa,ESP_Spain,LKA_Sri Lanka,VCT_St Vincent and the Grenadines,SGS_South Georgia & South Sandwich Islands,SDN_Sudan,SUR_Suriname,SJM_Svalbard and Jan Mayen,SWZ_Swaziland,SWE_Sweden,CHE_Switzerland,SYR_Syrian Arab Republic,TWN_Taiwan, Province of China,TJK_Tajikistan,TZA_Tanzania, United Republic of,THA_Thailand,TGO_Togo,TKL_Tokelau,TON_Tonga,TTO_Trinidad and Tobago,TUN_Tunisia,TUR_Turkey,TKM_Turkmenistan,TCA_Turks and Caicos Islands,TUV_Tuvalu,UGA_Uganda,UKR_Ukraine,ARE_United Arab Emirates,GBR_United Kingdom,USA_ United States,URY_Uruguay,UMI_US Minor Outlying Islands,UZB_Uzbekistan,VUT_Vanuatu,VEN_Venezuela,VNM_Viet Nam,VGB_Virgin Islands (British),VIR_Virgin Islands (U.S.),WLF_Wallis and Futuna Islands,ESH_Western Sahara,YEM_Yemen,YUG_Yugoslavia,ZMB_Zambia,ZWE_Zimbabwe";

	var countrylist = countryOptions.split(",");


	for(var i = 0; i < countrylist.length; i++) {
		var countryData = countrylist[i].split("_");
		var thisCountry = document.createElement("option");
		thisCountry.value = countryData[0];
		thisCountry.appendChild(document.createTextNode(countryData[1]));
		countries.appendChild(thisCountry);
	}


	if(initValue != null) {
		var initSrc = "initValue('"+nameOfForm+"','"+initValue+"');";
		var initJS = document.createElement("script");
		initJS.type = 'text/javascript';
		//initJS.appendChild(document.createTextNode(initSrc));
		initJS.text = initSrc;
		countries.parentNode.appendChild(initJS);
	}
}
