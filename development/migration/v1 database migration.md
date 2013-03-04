Application Database v1 to v2 Migration
=======================================

This file outlines the conversion process between the Graduate Application version 1 database and Application version 2 database. Details of each conversion step are first described in pseudo code, followed by the sql conversion code. The final code should be implemented by running:

1. START TRANSACTION
1. all conversions queries
1. COMMIT if successful or ROLLBACK if failured


Academic Programs Migration
---------------------------

Converts the academic program information from the old database to the new database. Specifically, it maps the old um_academic table to the new AcademicProgram table.

**Table Conversions**

- um_academic -> AcademicProgram


### Conversion Details ###

**um_academic -> AcademicProgram**

	academic_index 		-> academicProgramId

	active == 'yes'|'no' 	-> isActive 1 | 0

	academic_program 		-> academic_programCode
	academic_plan 			-> academic_planCode
	description_list 		-> academic_planName

	academic_dept_code 		-> department_code
	academic_dept 			-> department_nameShort
	academic_dept_heading 	-> department_nameFull

	academic_degree 		-> degree_code
	academic_degree_heading 	-> degree_name

	nebhe_ct == 'X' | '' 	-> nebhe_ct 1 | 0
	nebhe_ma == 'X' | '' 	-> nebhe_ma 1 | 0
	nebhe_nh == 'X' | '' 	-> nebhe_nh 1 | 0
	nebhe_ri == 'X' | '' 	-> nebhe_ri 1 | 0
	nebhe_vt == 'X' | '' 	-> nebhe_vt 1 | 0


### SQL Query ###

	TRUNCATE TABLE  `gradschool-application-2`.AcademicProgram;
	
	INSERT INTO
	   `gradschool-application-2`.AcademicProgram (
	   academicProgramId,
	   isActive,
	   academic_programCode, academic_planCode, academic_planName, 
	   department_code, department_nameShort, department_nameFull, 
	   degree_code, degree_name, 
	   nebhe_ct, nebhe_ma, nebhe_nh, nebhe_ri, nebhe_vt)
	SELECT
	   academic_index, 
	
	   case when active='yes' then 1 when active='no' then 0 end, 
	
	   academic_program, academic_plan, description_list, 
	
	   academic_dept_code, academic_dept, academic_dept_heading, 
	
	   academic_degree, academic_degree_heading,
	
	   case when nebhe_ct='X' then 1 when nebhe_ct='' then 0 end,   
	   case when nebhe_ma='X' then 1 when nebhe_ma='' then 0 end,   
	   case when nebhe_nh='X' then 1 when nebhe_nh='' then 0 end,   
	   case when nebhe_ri='X' then 1 when nebhe_ri='' then 0 end,
	   case when nebhe_vt='X' then 1 when nebhe_vt='' then 0 end
	FROM
	   gradschool_application.um_academic;


Applications Migration
----------------------

Maps application data from/to appropriate tables.

**Table Conversions**

<<<<<<< HEAD
- applicants 		-> Application and others @TODO
- appliedprograms 	-> Application
- dvioloations & cviolations 	-> APPLICATION_Violation
- gre 			-> APPLICATION_GRE
=======
- applicants 				-> Application and others @TODO

**applicants 					-> Applicant**

	given_name					-> givenName	
	middle_name 				-> middleName
	family_name 				-> familyName
	login_email 				-> loginEmail
	password 					-> password
	login_email_conformed 		-> isEmailConfirmed
	login_email_code 			-> loginEmailCode
	forgot_password_code 		-> forgotPasswordCode

**applicants 					-> Application**

	applicant_id				-> applicantId
	start_semester 				-> startSemester
	start_year 					-> startYear
	desired_housing 			-> desiredHousing
	accept_terms 				-> hasAcceptedTermsOfAgreement
	transaction_id 				-> transactionId


**applicants 					-> APPLICATION_Primary**

	suffix 						-> suffix
	email 						-> email
	primary_phone 				-> phonePrimary
	secondary_phone 			-> phoneSecondary
	alternate_name				-> alternativeName
	date_of_birth				-> birth_date
	birth_city					-> birth_date
	birth_state					-> birth_state
	birth_country 				-> birth_country
	gender 						-> gender
	us_citizen					-> us_isCitizen
	us_state					-> us_state
	residency_status 			-> residencyStatus
	green_card_link				-> greenCardLink
	country_of_citizenship		-> countryOfCitizenship
	social_security_number		-> socialSecurityNumber
	ethnicity_amind				-> ethnicity_amind
	ethnicity_asian				-> ethnicity_asian
	ethnicity_black				-> ethnicity_black
	ethnicity_pacif				-> ethnicity_pacif
	ethnicity_white				-> ethnicity_white
	ethnicity_unspec			-> ethnicity_unspec
	english_years_school		-> englishYears_school
	english_years_univ			-> englishYears_univ
	english_years_private		-> englishYears_private
	present_occupation 			-> presentOccupation
	undergrad_gpa 				-> undergradGPA
	postbacc_gpa				-> postbaccGPA
	extracurricular_activities	-> extracurricularActivities
	academic_honors				-> academicHonors
	employment_history			-> employmentHistory
	gmat_date					-> gmat_date
	gmat_score					-> gmat_score
	mat_date					-> mat_date
	mat_score					-> mat_score
	prev_um_grad_app_date		-> prevUMGradApp_date
	prev_um_grad_app_dept		-> prevUMGradApp_dept
	prev_um_grad_degree 		-> prevUMGradApp_degree
	prev_um_grad_degree_date	-> precUMGradApp_degreeDate
	prev_um_grad_withdraw_date	-> prevUMGradWithdraw_date
	gmat_verbal 				-> gmat_verbal
	gmat_quantitative 			-> gmat_quantitative
	disciplinary_violation 		-> hasDisciplinaryViolation
	criminal_violation 			-> hasCivilViolation
	gre_taken					-> hasTakenGRE
	gmat_taken 					-> gmat_hasTaken
	gmat_reported 				-> gmat_hasReported
	mat_taken 					-> mat_hasTaken
	mat_reported				-> mat_hasReported
	gmat_analytical 			-> gmat_analytical
	

**applicants 					-> APPLICATION_ContactInformation**

	mailing_addr1 				-> streetAddress1
	mailing_addr2				-> streetAddress2
	mailing_city				-> city
	mailing_state				-> state
	mailing_postal				-> postal


**applicants 					-> APPLICATION_Degree**	

	academic_program 			-> academic_program
	academic_plan 				-> academic_plan
	academic_major 				-> academic_major
	academic_minor 				-> academic_minor
	student_type 				-> studentType
	academic_load 				-> academic_load
	desire_assistantship_dept	-> desiredAssistantshipDepartment
	desire_financial_aid		-> isSeekingFinancialAid
	prev_um_grad_app 			-> prevUMGradApp_appExists
	prev_um_grad_withdraw 		-> prevUMGradWithdraw_exists
	

**applicants 					-> ????????????**

	permanent_addr1				->
	permanent_addr2				->
	permanent_city				->
	permanent_state				->
	permanent_postal			->
	preenroll_courses		 	-> 
	resume_link 				->
	essay_link 					->	
	um_correspond_details		->

	gre_repeatable
	languages_repeatable
	previousschools_repeatable
	international_repeatable
	appliedprograms_repeatable
	dviolations_repeatable
	cviolations_repeatable
	extrareferences_repeatable

	<<<<<<<<<<<all the references>>>>>>>>>

	application_start_date 		-> 
	application_edit_date 		-> 
	application_submit_date 	-> 
	application_signed_date 	-> 
	application_fee_payment_status		-> 
	application_fee_transaction_type 	-> 
	application_fee_transaction_date 	-> 					
	application_fee_transaction_amount 	->
	application_fee_transaction_number 	->
	application_fee_transaction_payment_method	-> 

	mailing_perm 				->
	english_primary 			-> 
	international  				->
	desire_assistantship 		->
	apply_nebhe 				->
	um_correspond 				->
	waive_view_rights			->
	reference1_online 			->
	reference2_online			->
	reference3_online			->
	application_signed 			->
	mailing_country 			->
	permanent_country 			->	
	essay_file_name 			->
	resume_file_name 			->
	application_payment_method 	->
	reference1_filename 		->	
	reference2_filename 		->
	reference3_filename 		->
	application_process_status 	->
	reference1_request_sent 	->
	reference2_request_sent 	->
	reference3_request_sent 	->


**applicant_academic			-> Application**

	application_payment_method 	->	
	has_been_submitted			-> 
	applicant_name				->
	academic_program 			-> 
	start_semester				->
	start_year					->
	student_type				-> 
	attendance_load				-> 
	email 						->
	essay_file_name				->
	resume_file_name			->
	status 						->



- cviolations		-> APPLICATION_CivilViolation
- dvioloations 		-> APPLICATION_DisciplinaryViolation

**cviolations 					-> APPLICATION_Violation**
	
	applicant_id 				-> applicationId
	cviolations_id				-> violationId
	cviolation_type				-> type
	cviolation_date				-> date
	cviolation_details			-> details

**dviolations 					-> APPLICATION_Violation**

	applicant_id 				-> applicationId
	dviolations_id				-> violationId
	dviolation_type				-> type
	dviolation_date				-> date 
	dviolation_details			-> details	


- gre 				-> APPLICATION_GRE

**gre 							-> APPLICATION_GRE**

	applicant_id 				-> applicationId 
	gre_id 						-> GREId
	gre_date 					-> date
	gre_verbal 					-> verbal
	gre_quantitative 			-> quantitative
	gre_analytical 				-> analytical
	gre_subject 				-> subject
	gre_reported 				-> hasBeenReported
	gre_score					-> score


**international 				-> APPLICATION_International**
	
	applicant_id 				-> applicationId
	international_id 			-> 
	toefl_date 					-> toefl_date
	toefl_score 				-> toefl_score
	us_career 					-> hasUSCareer
	us_career_details 			-> usCareerDetails
	further_studies_details 	-> furtherStudiesDetails
	home_career_details 		-> homeCareerDetails
	finance_details 			-> financeDetails
	us_contacts  				-> usFriendsOrRelatives
	us_emergency_contact_name 	-> usEmergencyContact_name
	us_emergency_contact_addr1 	-> 
	us_emergency_contact_addr2 	-> 
	us_emergency_contact_city 	-> 
	us_emergency_contact_state	->
	us_emergency_contact_zip	-> 
	us_emergency_contact_phone 	-> 
	us_emergency_contact_relationship 	->usEmergencyContact_relationship
	home_emergency_contact_name 	-> homeEmergencyContact_name
	home_emergency_contact_addr1 	-> 
	home_emergency_contact_addr2 	-> 
	home_emergency_contact_city		-> 
	home_emergency_contact_state 	-> 
	home_emergency_contact_postal	-> 
	home_emergency_contact_country	-> 
	home_emergency_contact_phone 	-> 
	home_emergency_contact_relationship -> homeEmergencyContact_relationship
	toefl_taken 				-> toefl_hasTaken
	toefl_reported 				-> toefl_hasReported
	further_studies 			-> hasFurtherStudies
	home_career 				-> hasHomeCareer

- languages			-> APPLICATION_Language

**languages 					-> APPLICATION_Language**

	applicant_id				-> applicationId
	languages_id				-> languageId
	language 					-> language
	writing_proficiency			-> proficiency_writing
	reading_proficiency			-> proficiency_reading
	speaking_proficiency		-> proficiency_speaking


- previousschools	-> APPLICATION_PreviousSchool

**previousschools 				-> APPLICATION_PreviousSchools**
	
	applicant_id				-> applicationId
	previousschools_id			-> previousSchoolId
	previous_schools_name 		-> name
	previous_schools_city 		-> city
	previous_schools_state 		-> state
	previous_schools_country 	-> country
	previous_schools_code 		-> code
	previous_schools_from_date 	-> startDate
	previous_schools_to_date 	-> endDate
	previous_schools_major 		-> major
	previous_schools_degree_earned 	-> degreeEarned_name
	previous_schools_degree_date	-> degreeEarned_date



- progress			-> APPLICATION_Progress

**appliedprograms 				-> Application**

	applicant_id				->
	appliedprograms_id			-> 
	academic_program 			-> 
	academic_plan 				->
	description_list 			->
	academic_dept_code 			->
	academic_major 				->
	academic_minor 				->
	student_type 				->
	start_semester 				->
	start_year 					->
	attendance_load 			->

References Migration
--------------------

Maps reference data from/toto appropriate tables.

**Table Conversions**

- applicants 		-> APPLICATION_Reference
- extrareferences 	-> APPLICATION_Reference


Interface Username Migration
----------------------------

Loads application administration username.

**Table Conversions**

- admin -> AdminAccount


Todo
----

- is the v1 table \`structure\` important?
- Does the new structure support the needs of the table views? (applicant\_academic, applicant\_references, reference\_data)



