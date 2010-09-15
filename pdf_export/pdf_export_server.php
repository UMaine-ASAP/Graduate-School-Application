<?php
	include_once "tcpdf/tcpdf.php";
	include_once "../application/libs/variables.php";
	include_once "../application/libs/template.php";
	include_once "../application/libs/database.php";
	include_once "../forms/signin/includes/corefuncs.php";

	//*********************************************************************************************
	// Database Login
	//*********************************************************************************************
	$db = new Database();
	$db->connect();
	
	//*********************************************************************************************
	// Determine User and page_id
	//*********************************************************************************************
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../forms/signin/");
	
	//*********************************************************************************************
	// Initialize PDF Writer
	//*********************************************************************************************	
	
	// Create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
	
	// Set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('University of Maine Graduate School');
	$pdf->SetTitle('Application');
	$pdf->SetSubject('Grad School Application');
	$pdf->SetKeywords('PDF, umaine, application, graduate school');
	
	// Remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// Set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	// Set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	
	// Set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	// Set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	// Set some language-dependent strings
	$pdf->setLanguageArray($l); 


	// Set font
	$pdf->SetFont('times', '', 10);
	
	//*********************************************************************************************
	// Create Page-1 using Templates
	//*********************************************************************************************

	//Build queries for page1
	$primary_query   = "";
	$primary_query  .= "SELECT `applicant_id`, `given_name`, `middle_name`, `family_name`, `suffix`, ";
	$primary_query  .= "`email`, `alternate_name`, ";
	$primary_query  .= "`mailing_perm`, `mailing_addr1`, `mailing_addr2`, `mailing_city`, `mailing_state`, `mailing_postal`, `mailing_country`, ";
	$primary_query  .= "`permanent_addr1`, `permanent_addr2`, `permanent_city`, `permanent_state`, `permanent_postal`, `permanent_country`, ";
	$primary_query  .= "`primary_phone`, `secondary_phone`, `present_occupation`, `ethnicity_hispa`, ";
	$primary_query  .= "`date_of_birth`, `birth_city`, `birth_state`, `birth_country`, `gender`, `us_citizen`, `us_state`, `residency_status`, `country_of_citizenship`, `social_security_number`, ";
	$primary_query  .= "`ethnicity_amind`, `ethnicity_asian`, `ethnicity_black`, `ethnicity_pacif`, `ethnicity_white`, `has_been_submitted` ";
	$primary_query  .= "FROM `applicants` ";
	$primary_query  .= "WHERE `applicant_id` = $user";
	
	$personal_data = $db->query($primary_query);
	$personal_data = $personal_data[0];
	foreach($personal_data as $id_key => $value) {
		if(!is_numeric($id_key)) $page1_replace[strtoupper($id_key)] = $value;
		//print $id_key." ".$value."<br/>";
	}
	
	//set user name for pdf file name
	$full_name = $personal_data['given_name'] ."_". $personal_data['middle_name'] ."_". $personal_data['family_name'];
	//determine if user has submitted an application already
	$has_been_submitted = $personal_data['has_been_submitted'];
	
	
	//Handle Ethnity
	$page1_replace['HISPANIC'] = ($personal_data['ethnic_hispa'])?"Yes":"No";
	$page1_replace['ETHNICITY'] = "";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_amind'])?$personal_data['ethnicity_amind'] = "American Indian/Alaska Native, ":"";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_asian'])?$personal_data['ethnicity_asian'] = "Asian, ":"";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_black'])?$personal_data['ethnicity_black'] = "Black, ":"";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_pacif'])?$personal_data['ethnicity_pacif'] = "Native Hawaiian/Pacific Islander, ":"";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_white'])?$personal_data['ethnicity_white'] = "White ":"";
	$page1_replace['ETHNICITY'] .= (!$page1_replace['ETHNICITY'])?"unspecified":"";
	
	//if mailing address = permanent address
	if ($personal_data['mailing_perm']){
		$page1_replace['MAILING_ADDR1'] = $personal_data['permanent_addr1'];
		$page1_replace['MAILING_ADDR2'] = $personal_data['permanent_addr2'];
		$page1_replace['MAILING_CITY'] = $personal_data['permanent_city'];
		$page1_replace['MAILING_STATE'] = $personal_data['permanent_state'];
		$page1_replace['MAILING_POSTAL'] = $personal_data['permanent_postal'];
		$page1_replace['MAILING_COUNTRY'] = $personal_data['permanent_country'];
	}
	
	//Get Programs
	$program_results = "";
	$program_query  = "";
	$program_query .= "SELECT * FROM appliedprograms WHERE applicant_id=$user";
	$progs = $db->query($program_query);
	$prog_count = 1;
	foreach($progs as $prog){
		$progs_replace = array();
		foreach($prog as $id_key => $value) {
			if(!is_numeric($id_key)) $progs_replace[strtoupper($id_key)] = $value;
		}
		$progs_replace['INDEX'] = $prog_count;
		$progs_replace['DESCRIPTION_APP'] = $db->getFirst("SELECT description_app FROM um_academic WHERE academic_program='".$prog['academic_program']."'");
		$progs_replace['ATTENDANCE_STATUS'] = ($prog['attendance_load']=='F')?"FULL-TIME":"";
		$progs_replace['ATTENDANCE_STATUS'] = ($prog['attendance_load']=='P')?"PART-TIME":$progs_replace['ATTENDANCE_STATUS'];
		
		//Replace -> Parse -> Render Page 1
		$prog_template = new Template();
		$prog_template->changeTemplate("pdf_templates/programs.tpl");
		$prog_template->changeArray($progs_replace);
		$program_results .= $prog_template->parse();
		$prog_count++;
	}
	
	//*********************************************************************************************
	// Create Page-2 using Templates
	//*********************************************************************************************
	
	//Build queries for page2
	$primary_query = "";
	$primary_query  .= "SELECT `undergrad_gpa`, `postbacc_gpa`, `preenroll_courses`, ";
	$primary_query  .= "`academic_honors`, `employment_history`, `disciplinary_violation`, ";
	$primary_query  .= "`criminal_violation` ";
	$primary_query  .= "FROM `applicants` ";
	$primary_query  .= "WHERE `applicant_id` = $user";
	
	$institution_data = $db->query($primary_query);
	$institution_data = $institution_data[0];
	foreach($institution_data as $id_key => $value) {
		if(!is_numeric($id_key)) $page2_replace[strtoupper($id_key)] = $value;
		//print $id_key." ".$value."<br/>";
	}
	//handle dviolation yes/no
	$page2_replace['DISCIPLINARY_VIOLATION'] = ($institution_data['disciplinary_violation'])?"Yes":"No";
	$page2_replace['CRIMINAL_VIOLATION'] = ($institution_data['criminal_violation'])?"Yes":"No";
	
	//Get previous institutions
	$institution_results = "";
	$institution_query = "";
	$institution_query .= "SELECT * FROM previousschools WHERE applicant_id=$user";
	$institutions = $db->query($institution_query);
	foreach($institutions as $institution){
		$institution_replace = array();
		foreach($institution as $id_key => $value) {
			if(!is_numeric($id_key)) $institution_replace[strtoupper($id_key)] = $value;
		}
		//replace code
		
		//Replace -> parse -> render page 2 institutions
		$institution_template = new Template();
		$institution_template->changeTemplate("pdf_templates/previous_institutions.tpl");
		$institution_template->changeArray($institution_replace);
		$institution_results .= $institution_template->parse();
	}
	
	//get disciplinary violations
	$dviolation_results = "";
	$dviolation_query = "";
	$dviolation_query .= "SELECT * FROM dviolations WHERE applicant_id=$user";
	$dviolations = $db->query($dviolation_query);
	foreach($dviolations as $dviolation){
		$dviolation_replace = array();
		foreach($dviolation as $id_key => $value){
			if(!is_numeric($id_key)) $dviolation_replace[strtoupper($id_key)] = $value;
		}
		//replace code
		
		//replace -> parse -> render page 2 disciplinary violations
		$dviolations_template = new Template();
		$dviolations_template->changeTemplate("pdf_templates/disciplinary_violations.tpl");
		$dviolations_template->changeArray($dviolation_replace);
		$dviolation_results .= $dviolations_template->parse();
	}
	
	//get criminal violations
	$cviolation_results = "";
	$cviolation_query = "";
	$cviolation_query .= "SELECT * FROM cviolations WHERE applicant_id=$user";
	$cviolations = $db->query($cviolation_query);
	foreach($cviolations as $cviolation){
		$cviolation_replace = array();
		foreach($cviolation as $id_key => $value){
			if(!is_numeric($id_key)) $cviolation_replace[strtoupper($id_key)] = $value;
		}
		//replace code
		
		//replace -> parse -> render page 2 criminal violations
		$cviolations_template = new Template();
		$cviolations_template->changeTemplate("pdf_templates/criminal_violations.tpl");
		$cviolations_template->changeArray($cviolation_replace);
		$cviolation_results .= $cviolations_template->parse();
	}
	
	//*********************************************************************************************
	// Create Page-3 using Templates
	//*********************************************************************************************
	
	//Build queries for page 3
	$primary_query = "";
	$primary_query .= "SELECT `gre_taken`, `gmat_taken`, `gmat_date`, `gmat_score`, `mat_taken`, ";
	$primary_query .= "`mat_date`, `mat_score`, `english_years_school`, `english_years_univ`, ";
	$primary_query .= "`english_years_private`, `prev_um_grad_app`, `prev_um_grad_app_date`, "; 
	$primary_query .= "`prev_um_grad_app_dept`, `prev_um_grad_degree`, `prev_um_grad_degree_date`, "; 
	$primary_query .= "`prev_um_grad_withdraw`, `prev_um_grad_withdraw_date`, `desire_assistantship`, "; 
	$primary_query .= "`desire_assistantship_dept`, `apply_nebhe`, `um_correspond_details`, ";
	$primary_query .= "`reference1_first`, `reference1_last`, `reference1_addr1`, ";
	$primary_query .= "`reference1_addr2`, `reference1_city`, `reference1_state`, ";
	$primary_query .= "`reference1_postal`, `reference1_country`, `reference1_email`, `reference1_phone`, ";
	$primary_query .= "`reference2_first`, `reference2_last`, `reference2_addr1`, ";
	$primary_query .= "`reference2_addr2`, `reference2_city`, `reference2_state`, ";
	$primary_query .= "`reference2_postal`, `reference2_country`, `reference2_email`, `reference2_phone`, ";
	$primary_query .= "`reference3_first`, `reference3_last`, `reference3_addr1`, ";
	$primary_query .= "`reference3_addr2`, `reference3_city`, `reference3_state`, ";
	$primary_query .= "`reference3_postal`, `reference3_country`, `reference3_email`, `reference3_phone` ";
	$primary_query .= "FROM `applicants` ";
	$primary_query .= "WHERE `applicant_id` = $user";
	
	$page3_data = $db->query($primary_query);
	$page3_data = $page3_data[0];
	foreach($page3_data as $id_key => $value) {
		if(!is_numeric($id_key)) $page3_replace[strtoupper($id_key)] = $value;
		//print $id_key." ".$value."<br/>";
	}
	//handle yes/no's
	$page3_replace['GRE_TAKEN'] = ($page3_data['gre_taken'])?"Yes":"No";
	$page3_replace['GMAT_TAKEN'] = ($page3_data['gmat_taken'])?"Yes":"No";
	$page3_replace['MAT_TAKEN'] = ($page3_data['mat_taken'])?"Yes":"No";
	$page3_replace['PREV_UM_GRAD_APP'] = ($page3_data['prev_um_grad_app'])?"Yes":"No";
	$page3_replace['PREV_UM_GRAD_WITHDRAW'] = ($page3_data['prev_um_grad_withdraw'])?"Yes":"No";
	$page3_replace['DESIRE_ASSISTANTSHIP'] = ($page3_data['desire_assistantship'])?"Yes":"No";
	$page3_replace['APPLY_NEBHE'] = ($page3_data['apply_nebhe'])?"Yes":"No";
	
	//get GRE scores
	$gre_results = "";
	$gre_query = "";
	$gre_query .= "SELECT * FROM gre WHERE applicant_id=$user";
	$gres = $db->query($gre_query);
	foreach($gres as $gre){
		$gre_replace = array();
		foreach($gre as $id_key => $value){
			if(!is_numeric($id_key)) $gre_replace[strtoupper($id_key)] = $value;
		}
		//replace code
		
		//replace -> parse -> render page 3 gre scores
		$gre_template = new Template();
		$gre_template->changeTemplate("pdf_templates/gre_scores.tpl");
		$gre_template->changeArray($gre_replace);
		$gre_results .= $gre_template->parse();
	}
	
	//GET LANGUAGES
	$language_results = "";
	$language_query = "";
	$language_query .= "SELECT * FROM languages WHERE applicant_id=$user";
	$languages = $db->query($language_query);
	foreach($languages as $language){
		$language_replace = array();
		foreach($language as $id_key => $value){
			if(!is_numeric($id_key)) $language_replace[strtoupper($id_key)] = $value;
		}
		//replace code
		
		//replace -> parse -> render page 3 languages
		$language_template = new Template();
		$language_template->changeTemplate("pdf_templates/languages.tpl");
		$language_template->changeArray($language_replace);
		$language_results .= $language_template->parse();
	}
	
	//get extra recommendations
	$recommendation_results = "";
	$recommendation_query = "";
	$recommendation_query .= "SELECT * FROM extrareferences WHERE applicant_id=$user";
	$recommendations = $db->query($recommendation_query);
	foreach($recommendations as $recommendation){
		$recommendation_replace = array();
		foreach($recommendation as $id_key => $value){
			if(!is_numeric($id_key)) $recommendation_replace[strtoupper($id_key)] = $value;
		}
		//replace code
		
		//replace -> parse -> render page 3 extra recommendations
		$recommendation_template = new Template();
		$recommendation_template->changeTemplate("pdf_templates/extra_recommendations.tpl");
		$recommendation_template->changeArray($recommendation_replace);
		$recommendation_results .= $recommendation_template->parse();
	}
	
	//*********************************************************************************************
	// Create Page-4 using Templates
	//*********************************************************************************************
	
	//Build queries for page 4
	$primary_query = "";
	$primary_query .= "SELECT * FROM `international` WHERE `applicant_id` = $user";
	
	$page4_data = $db->query($primary_query);
	$page4_data = $page4_data[0];
	foreach($page4_data as $id_key => $value) {
		if(!is_numeric($id_key)) $page4_replace[strtoupper($id_key)] = $value;
		//print $id_key." ".$value."<br/>";
	}
	
	//handle yes/no
	$page4_replace['TOEFL_TAKEN'] = ($page4_data['toefl_taken'])?"Yes":"No";
	
	//replace repeatable elements from templates
	$page1_replace['PROGRAMS'] = $program_results;
	$page2_replace['INSTITUTIONS'] = $institution_results;
	$page2_replace['DVIOLATIONS'] = $dviolation_results;
	$page2_replace['CVIOLATIONS'] = $cviolation_results;
	$page3_replace['GRESCORES'] = $gre_results;
	$page3_replace['LANGUAGES'] = $language_results;
	$page3_replace['EXTRA_RECOMMENDATIONS'] = $recommendation_results;
	
	
	//Replace -> Parse -> Render Page 1
	$app_export_template = new Template();
	$app_export_template->changeTemplate("pdf_templates/app_page_1.tpl");
	$app_export_template->changeArray($page1_replace);
	$htmlcontent01 = $app_export_template->parse();
	
	//Replace -> Parse -> Render Page 2
	$app_export_template = new Template();
	$app_export_template->changeTemplate("pdf_templates/app_page_2.tpl");
	$app_export_template->changeArray($page2_replace);
	$htmlcontent02 = $app_export_template->parse();
	
	//replace -> parse -> render page 3
	$app_export_template = new Template();
	$app_export_template->changeTemplate("pdf_templates/app_page_3.tpl");
	$app_export_template->changeArray($page3_replace);
	$htmlcontent03 = $app_export_template->parse();
	
	//replace -> parse -> render page 4
	$app_export_template = new Template();
	$app_export_template->changeTemplate("pdf_templates/app_page_4.tpl");
	$app_export_template->changeArray($page4_replace);
	$htmlcontent04 = $app_export_template->parse();
			
	// output the HTML content
	// $pdf->AddPage();
	// $pdf->writeHTML($htmlcontent01, true, 0, true, 0);
	// $pdf->AddPage();
	// $pdf->writeHTML($htmlcontent02, true, 0, true, 0);
	// $pdf->AddPage();
	// $pdf->writeHTML($htmlcontent03, true, 0, true, 0);
	// $pdf->AddPage();
	// $pdf->writeHTML($htmlcontent04, true, 0, true, 0);
	
	//combine all pages for auto page break generation
	$all_html_content = $htmlcontent01;
	$all_html_content .= $htmlcontent02;
	$all_html_content .= $htmlcontent03;
	$all_html_content .= $htmlcontent04;
	
	$pdf->AddPage();
	$pdf->writeHTML($all_html_content, true, 0, true, 0);

	$today = date("m-d-Y");
//	$today .= "_";
//	$today .= date("U");//append unix timestamp for server copy of PDF
	// $pdftitle = "UMGradApp_". $user .".pdf";
	$exDOB = explode("/", $personal_data['date_of_birth']);
	$newBOD = $exDOB[0].$exDOB[1].$exDOB[2];
	$pdftitle = $user."_".$personal_data['family_name']."_".$personal_data['given_name']."_".$newBOD.".pdf";
	echo "XXXXXXXX".$newBOD;
		
	if($has_been_submitted == 0){
		//Close and output PDF document to local file on server
		// $pdf->Output("./completed_pdfs/".$pdftitle, 'F');
		$pdf->Output($completed_pdfs_path.$pdftitle, 'F');
		//set permissions on pdf to write only 
		// $cwd = getcwd();
		// $cwd .= "/completed_pdfs/";
		$cwd = $completed_pdfs_path;
		$cwd .= $pdftitle;
		chmod($cwd, 0222);
	
		//update database to show that application has been submitted
		$db_update = "";
		$db_update .= "UPDATE `applicants` ";
		$db_update .= "SET `has_been_submitted` = '1' ";
		$db_update .= "WHERE `applicant_id` = $user LIMIT 1";
		$db->iquery($db_update);
		
		//update application submit date in database
		$db_update = "";
		$db_update .= "UPDATE `applicants` ";
		$db_update .= "SET `application_submit_date` = '". date("Y-m-d");
		$db_update .= "' WHERE `applicant_id` = $user LIMIT 1";
		$db->iquery($db_update);
		
		header("location:../forms/signin/");
		// 	//echo "application copy has been made on server: ". $pdftitle;
		}
		else{
					header("location:../forms/signin/");
					//echo "application copy already exists on server: ". $pdftitle;
				}
		
	//$today = date("m-d-y");//todays date without unix timestamp for user copy
	//$pdftitle = "UMGradApp_". $today ."_". $full_name .".pdf";
	
	//Close and output PDF document inline to browser
	//$pdf->Output($pdftitle, 'I');
	

//============================================================+
// END OF FILE                                                 
//============================================================+
?>