<?php
	include_once "lib/tcpdf/tcpdf.php";
	include_once "../application/libs/template.php";
	include_once "../application/libs/variables.php";
	include_once "../application/libs/database.php";
	include_once "../application/libs/corefuncs.php";

	//********************************************************************************************
	// Helper Functions
	//********************************************************************************************
	function template_parse($template_file, $replace_data) {
		$process_template = new Template();
		$process_template->changeTemplate($template_file);
		$process_template->changeArray($replace_data);
		return $process_template->parse();
	}
	
	function strip_numeric_indexes(&$a) {
		$result = array();
		foreach($a as $id_key => $value){
			if(!is_numeric($id_key)) $result[strtoupper($id_key)] = $value;
		}
		return $result;
	}

function generate_application_pdf($output_mode) {
	
	$user = check_ses_vars();
	$user = ($user)?$user:header("location:../pages/index.php");

	//*********************************************************************************************
	// Database Login
	//*********************************************************************************************
	$db = new Database();
	$db->connect();
	
	//*********************************************************************************************
	// Initialize PDF Writer
	//*********************************************************************************************	
	
	// Create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false); 
		
	// Set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('University of Maine Graduate School');
	$pdf->SetTitle('Application');
	$pdf->SetSubject('Grad School Application');
	$pdf->SetKeywords('PDF, umaine, application, graduate school');
	
	//Meta
	$pdf->setPDFVersion("1.3");
	$pdf->setLanguageArray($l); 
	
	//Style
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetFont('times', '', 10);

	
	//Structure
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


	//*********************************************************************************************
	// Top Level Variables
	//*********************************************************************************************
	$primary_query = "SELECT given_name, middle_name, family_name, has_been_submitted FROM applicants WHERE applicant_id = %d";
	$personal_data = $db->query($primary_query, $user);
	$global_data   = $global_data[0];
	

	$full_name 	      = $global_data['given_name'] ."_". $global_data['middle_name'] ."_". $global_data['family_name']; //set user name for pdf file name
	$has_been_submitted = $global_data['has_been_submitted']; //determine if user has submitted an application already

	
	//*********************************************************************************************
	// Create Repeatables
	//*********************************************************************************************

	/*===============*/	
	//disciplinary violations
	/*===============*/		
	$dviolation_results = "";

	$dviolation_query = "SELECT * FROM dviolations WHERE applicant_id=$user";
	$dviolations = $db->query($dviolation_query);
	
	foreach($dviolations as $dviolation){
		$dviolation_replace  = strip_numeric_indexes($dviolation);
		$dviolation_results .= template_parse("pdf_templates/disciplinary_violations.tpl", $dviolation_replace);
	}
	
	/*===============*/	
	//Criminal Violations
	/*===============*/
	$cviolation_results = "";
	
	$cviolation_query = "SELECT * FROM cviolations WHERE applicant_id=$user";
	$cviolations = $db->query($cviolation_query);
	
	foreach($cviolations as $cviolation){
		$cviolation_replace  = strip_numeric_indexes($cviolation);		
		$cviolation_results .= template_parse("pdf_templates/criminal_violations.tpl", $cviolation_replace);
	}


	/*===============*/	
	//Programs
	/*===============*/
	$program_results = "";
	$prog_count = 1;
	
	$program_query  = "SELECT * FROM appliedprograms WHERE applicant_id=$user";
	$progs = $db->query($program_query);

	
	foreach($progs as $prog){
		$progs_replace = strip_numeric_indexes($prog);
		
		$progs_replace['INDEX'] = $prog_count;
		$progs_replace['DESCRIPTION_APP']   = $db->getFirst("SELECT description_app FROM um_academic WHERE academic_program='".$prog['academic_program']."'");
		$progs_replace['ATTENDANCE_STATUS'] = ($prog['attendance_load']=='F')?"FULL-TIME":"";
		$progs_replace['ATTENDANCE_STATUS'] = ($prog['attendance_load']=='P')?"PART-TIME":$progs_replace['ATTENDANCE_STATUS'];
		
		$program_results .= template_parse("pdf_templates/programs.tpl", $progs_replace);
		$prog_count++;
	}

	/*===============*/	
	//previous institutions
	/*===============*/	
	$institution_results = "";
	
	$institution_query = "SELECT * FROM previousschools WHERE applicant_id=$user";
	$institutions = $db->query($institution_query);
	
	foreach($institutions as $institution){
		$institution_replace = strip_numeric_indexes($institution);

		//Replace International state option with blank for MaineStreet processing
		if($institution_replace['PREVIOUS_SCHOOLS_STATE'] == "IT") $institution_replace['PREVIOUS_SCHOOLS_STATE'] = "";

		$institution_results .= template_parse("pdf_templates/previous_institutions.tpl", $institution_replace);
	}

	
	/*===============*/	
	// GRE Scores
	/*===============*/	
	$gre_results = "";
	$gre_query = "";
	$gre_query .= "SELECT * FROM gre WHERE applicant_id=$user";
	$gres = $db->query($gre_query);
	$first_pass = true;
	foreach($gres as $gre){
		if($first_pass) {
			$first_pass = false;
		} else {
			$gre_results .= "<tr><td colspan='3' style='font-size: 20px;'>";
			$gre_results .= "----------------------------------------------------------------------------------------------------------";
			$gre_results .= "</td></tr>";
		}
		$gre_replace = strip_numeric_indexes($gre);		
		$gre_results .= template_parse("pdf_templates/gre_scores.tpl", $gre_replace);
	}
	

	/*===============*/	
	// Languages
	/*===============*/	

	$language_results = "";

	$language_query = "SELECT * FROM languages WHERE applicant_id=$user";
	$languages = $db->query($language_query);
	
	$language_list      = Array();
	$reading_prof_list  = Array();
	$speaking_prof_list = Array();
	$writing_prof_list  = Array();
	foreach($languages as $language){
		$language_list[] 	 = $language['LANGUAGE'];
		$reading_prof_list[]  = $language['READING_PROFICIENCY'];
		$speaking_prof_list[] = $language['SPEAKING_PROFICIENCY'];
		$writing_prof_list[]  = $language['WRITING_PROFICIENCY'];

		$language_replace  = strip_numeric_indexes($language);
		$language_results .= template_parse("pdf_templates/languages.tpl", $language_replace);
	}
	
	for($i=0; $i<count($languages); $i++) {
		if( $i%2 == 0 ) {
			$language_results .= "</tr><tr>";
		}
	}
	
	/*===============*/	
	// Recommendations
	/*===============*/	
	$recommendation_results = "";

	$recommendation_query = "SELECT * FROM extrareferences WHERE applicant_id=$user";
	$recommendations = $db->query($recommendation_query);
	
	foreach($recommendations as $recommendation){
		$recommendation_replace = strip_numeric_indexes($recommendation);
		
		//clear International from options
		if($recommendation_replace["REFERENCE_STATE"] == "IT")
			$recommendation_replace["REFERENCE_STATE"] = "";

		if($recommendation_replace['REFERENCE_CITY'] != "") $recommendation_replace['REFERENCE_CITY'] .= ", ";
		
		//substitute
		$recommendation_results .= template_parse("pdf_templates/extra_recommendations.tpl", $recommendation_replace);
		
	}

	//*********************************************************************************************
	// Create Page-1 using Templates
	//*********************************************************************************************

	/*============================*/
	// Query
	/*============================*/

	$primary_query   = "";
	$primary_query  .= "SELECT `applicant_id`, `application_submit_date`, `given_name`, `middle_name`, `family_name`, `suffix`, ";
	$primary_query  .= "`email`, `alternate_name`, ";
	$primary_query  .= "`mailing_perm`, `mailing_addr1`, `mailing_addr2`, `mailing_city`, `mailing_state`, `mailing_postal`, `mailing_country`, ";
	$primary_query  .= "`permanent_addr1`, `permanent_addr2`, `permanent_city`, `permanent_state`, `permanent_postal`, `permanent_country`, ";
	$primary_query  .= "`primary_phone`, `secondary_phone`, `present_occupation`, `ethnicity_hispa`, ";
	$primary_query  .= "`date_of_birth`, `birth_city`, `birth_state`, `birth_country`, `gender`, `us_citizen`, `us_state`, `residency_status`, `country_of_citizenship`, ";
	$primary_query	.= "AES_DECRYPT(`social_security_number`, '%s') AS `social_security_number`, ";
	$primary_query  .= "`ethnicity_amind`, `ethnicity_asian`, `ethnicity_black`, `ethnicity_pacif`, `ethnicity_white`, `has_been_submitted`, `essay_file_name`, `resume_file_name`, `disciplinary_violation`, `criminal_violation`, `application_payment_method` ";
	$primary_query  .= "FROM `applicants` ";
	$primary_query  .= "WHERE `applicant_id` = %d";
	
	$key = $GLOBALS['key'];

	$personal_data = $db->query($primary_query, $key, $user);
	$personal_data = $personal_data[0];
	
	$page1_replace = strip_numeric_indexes($personal_data);

	/*============================*/
	// Replacements  
	/*============================*/
	
	//Handle Ethnity
	$page1_replace['HISPANIC'] = ($personal_data['ethnicity_hispa'])?"Yes":"No";
	$page1_replace['ETHNICITY'] = "";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_amind'])?$personal_data['ethnicity_amind'] = "American Indian/Alaska Native ":"";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_asian'])?$personal_data['ethnicity_asian'] = "Asian ":"";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_black'])?$personal_data['ethnicity_black'] = "Black ":"";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_pacif'])?$personal_data['ethnicity_pacif'] = "Native Hawaiian/Pacific Islander ":"";
	$page1_replace['ETHNICITY'] .= ($personal_data['ethnicity_white'])?$personal_data['ethnicity_white'] = "White ":"";
	$page1_replace['ETHNICITY'] .= (!$page1_replace['ETHNICITY'])?"unspecified":"";
	$page1_replace['APPLICANT_ID'] = $personal_data['applicant_id'];
	$page1_replace['SUBMISSION_DATE'] = $personal_data['application_submit_date'];
	$page1_replace['APPLICANT_PAYMENT_METHOD'] = ($personal_data['application_payment_method'] == 'PAYNOW') ? 'Paying Online' : 'Paying Offline';

	//Replace International state option with blank for MaineStreet processing
	if($page1_replace['PERMANENT_STATE'] == "IT") $page1_replace['PERMANENT_STATE']  = "";
	if($page1_replace['MAILING_STATE'] 	 == "IT") $page1_replace['MAILING_STATE']	 = "";
	if($page1_replace['BIRTH_STATE'] 	 == "IT") $page1_replace['BIRTH_STATE']		 = "";
	if($page1_replace['US_STATE']		 == "IT") $page1_replace['US_STATE']		 = "";


	//if mailing address = permanent address
	if ($personal_data['mailing_perm']){
		$page1_replace['MAILING_ADDR1']   = $personal_data['permanent_addr1'];
		$page1_replace['MAILING_ADDR2']   = $personal_data['permanent_addr2'];
		$page1_replace['MAILING_CITY']    = $personal_data['permanent_city'];
		$page1_replace['MAILING_STATE']   = $page1_replace['PERMANENT_STATE'];
		$page1_replace['MAILING_POSTAL']  = $personal_data['permanent_postal'];
		$page1_replace['MAILING_COUNTRY'] = $personal_data['permanent_country'];
	}


	/*============================*/
	// Additional
	/*============================*/

	//handle dviolation yes/no
	$page1_replace['DISCIPLINARY_VIOLATION'] = ($personal_data['disciplinary_violation'])?"Yes":"No";
	$page1_replace['CRIMINAL_VIOLATION']     = ($personal_data['criminal_violation'])?"Yes":"No";

	//*********************************************************************************************
	// Create Page-2 using Templates
	//*********************************************************************************************
	
	/*============================*/
	// Query
	/*============================*/

//	$primary_query = "SELECT `undergrad_gpa`, `postbacc_gpa`, `preenroll_courses`, `academic_honors`, `employment_history`, `disciplinary_violation`, `criminal_violation` FROM `applicants` WHERE `applicant_id` = $user";
	$primary_query = "SELECT * FROM `applicants` WHERE `applicant_id` = $user";
	$institution_data = $db->query($primary_query);
	$institution_data = $institution_data[0];
	
	$page2_replace = strip_numeric_indexes($institution_data);
	$page2_replace['PREV_UM_GRAD_APP']			= ($page2_data['prev_um_grad_app'])		 ? "Yes":"No";
	$page2_replace['PREV_UM_GRAD_WITHDRAW'] 	= ($page2_data['prev_um_grad_withdraw']) ? "Yes":"No";
	$page2_replace['DESIRE_ASSISTANTSHIP'] 		= ($page2_data['desire_assistantship'])	 ? "Yes":"No";
	$page2_replace['APPLY_NEBHE'] 				= ($page2_data['apply_nebhe'])			 ? "Yes":"No";
		
	//*********************************************************************************************
	// Create Page-3 using Templates
	//*********************************************************************************************
	
	/*============================*/
	// Query
	/*============================*/

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
	$primary_query .= "`reference3_postal`, `reference3_country`, `reference3_email`, `reference3_phone`, ";
	$primary_query .= "`academic_honors`, `employment_history` ";
	$primary_query .= "FROM `applicants` ";
	$primary_query .= "WHERE `applicant_id` = $user";
	
	$page3_data = $db->query($primary_query);
	$page3_data = $page3_data[0];
	$page3_replace = strip_numeric_indexes($page3_data);


	/*============================*/
	// Replacements  
	/*============================*/
	
	//Replace International state option with blank for MaineStreet
	if($page3_repace['REFERENCE1_STATE'] == "IT") $page3_repace['REFERENCE1_STATE'] = "";
	if($page3_repace['REFERENCE2_STATE'] == "IT") $page3_repace['REFERENCE2_STATE'] = "";
	if($page3_repace['REFERENCE3_STATE'] == "IT") $page3_repace['REFERENCE3_STATE'] = "";

	if($page3_replace['REFERENCE1_CITY'] != "") $page3_replace['REFERENCE1_CITY'] .= ", ";
	if($page3_replace['REFERENCE2_CITY'] != "") $page3_replace['REFERENCE2_CITY'] .= ", ";
	if($page3_replace['REFERENCE3_CITY'] != "") $page3_replace['REFERENCE3_CITY'] .= ", ";

	//handle yes/no's
	$page3_replace['GRE_TAKEN'] 		= ($page3_data['gre_taken'])		?"Yes":"No";
	$page3_replace['GMAT_TAKEN'] 		= ($page3_data['gmat_taken'])		?"Yes":"No";
	$page3_replace['MAT_TAKEN'] 		= ($page3_data['mat_taken'])		?"Yes":"No";
	
	if( $page3_replace['GMAT_TAKEN'] == "No") {
		$page3_replace['GMAT_DATE']  = "";
		$page3_replace['GMAT_SCORE'] = "";
	}

	if( $page3_replace['MAT_TAKEN'] == "No") {
		$page3_replace['MAT_DATE']  = "";
		$page3_replace['MAT_SCORE'] = "";
	}
	
	//*********************************************************************************************
	// Create Page-4 using Templates
	//*********************************************************************************************
	
	/*===============*/
	// Query
	/*===============*/
	
	$primary_query = "";
	$primary_query .= "SELECT * FROM `international` WHERE `applicant_id` = $user";
	
	$page4_data = $db->query($primary_query);
	$page4_data = $page4_data[0];
	
	$page4_replace = strip_numeric_indexes($page4_data);
	
	/*===============*/
	// Replacements  
	/*===============*/
	
	if($page4_replace['US_EMERGENCY_CONTACT_STATE'] == "IT") $page4_replace['US_EMERGENCY_CONTACT_STATE'] = "";

	//handle yes/no
	$page4_replace['TOEFL_TAKEN'] = ($page4_data['toefl_taken'])?"Yes":"No";
	
	//replace repeatable elements from templates
	$page1_replace['DVIOLATIONS'] = $dviolation_results;
	$page1_replace['CVIOLATIONS'] = $cviolation_results;
	$page2_replace['PROGRAMS'] = $program_results;
	$page2_replace['INSTITUTIONS'] = $institution_results;
	$page2_replace['LANGUAGES'] = $language_results;
	$page3_replace['GRESCORES'] = $gre_results;
	$page3_replace['EXTRA_RECOMMENDATIONS'] = $recommendation_results;

	//*********************************************************************************************
	// Finish Constructing PDF
	//*********************************************************************************************
	
	/*===============*/
	// Build PDF
	/*===============*/
	//MPDF	
	include_once('lib/MPDF52/mpdf.php');
	$mpdf = new mPDF();
	
	//combine all pages for auto page break generation
	$all_html_content = "";
	$all_html_content .= template_parse("pdf_templates/app_page_1.tpl", $page1_replace);
	$all_html_content .= template_parse("pdf_templates/app_page_2.tpl", $page2_replace);
	$all_html_content .= template_parse("pdf_templates/app_page_3.tpl", $page3_replace);
	$all_html_content .= template_parse("pdf_templates/app_page_4.tpl", $page4_replace);

	$mpdf->AddPage();
	$mpdf->WriteHTML($all_html_content);

	/*===============*/
	// Output File
	/*===============*/
	
	$today     = date("m-d-Y");
	$exDOB     = explode("/", $personal_data['date_of_birth']);
	$newDOB    = $exDOB[0].$exDOB[1].$exDOB[2];
	$full_name = $personal_data['given_name'] ."_". $personal_data['middle_name'] ."_". $personal_data['family_name']; //set user 

	if( $output_mode == "SERVER" ) {		
		$pdftitle = $user."_".$personal_data['family_name']."_".$personal_data['given_name']."_".$newDOB.".pdf";	
		$mpdf->Output($GLOBALS['completed_pdfs_path'] . $pdftitle);

		//change permissions
		$cwd = "completed_pdfs/";
		$cwd .= $pdftitle;
		chmod($cwd, 0664);

	} else if( $output_mode == "USER" ){
		$pdftitle = "UMGradApp_". $today ."_". $full_name .".pdf";
		$mpdf->Output($pdftitle, 'D');		
	}
	return;
} //End Function Generate Application PDF
	

//============================================================+
// END OF FILE                                                 
//============================================================+
?>