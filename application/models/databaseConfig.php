<?php

/**
 * Specifies database field information
 * 
 * Describes every saveable database field as well as important field configuration information
 * format:
 * 		section => array(
 *   		fieldname => array(
 *     			filter         => 'value'     - The filter to apply when saving the field
 *        		isRequired     => 'boolean'   - Boolean indicating whether a value is required
 *          		requireMessage => 'string'    - Display message if the field is required and not filled in
 *            	); 
 *         );
 */


$GLOBALS['databaseFields'] = array(
	// Fields are divided according to application pages


	/* --------- Personal Information --------- */

	ApplicationSection::personalInformation => array(
	
		// Name
		'personal-givenName'       => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not enter a First Name'),
		'personal-middleName'      => array('filter' => 'filter_generic'),
		'personal-familyName'      => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not enter a Last Name'),
		'personal-suffix'          => array('filter' => 'filter_generic'), // @TODO: filter using options
		'personal-alternativeName' => array('filter' => 'filter_generic'),


		// Contact Information
		'personal-phonePrimary'   => array('filter' => 'filter_phone'),
		'personal-phoneSecondary' => array('filter' => 'filter_phone'),	
		'personal-email'          => array('filter' => 'filter_email', 'isRequired' => true, 'requiredMessage'=>'You did not enter an Email Address'),

		'personal-permanentMailing-streetAddress1' => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not enter your Permanent Address'),
		'personal-permanentMailing-streetAddress2' => array('filter' => 'filter_generic'),
		'personal-permanentMailing-city'           => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not enter the City of your Permanent Address'),
		'personal-permanentMailing-postal'         => array('filter' => 'filter_zipcode', 'isRequired' => true, 'requiredMessage'=>'You did not enter the Postal Code of your Permanent Address'),
		'personal-permanentMailing-state'          => array('filter' => 'filter_state', 'isRequired' => true, 'requiredMessage'=>'You did not enter the State of your Permanent Address'), 
		'personal-permanentMailing-country'        => array('filter' => 'filter_country', 'isRequired' => true, 'requiredMessage'=>'You did not enter the Country of your Permanent Address'),

		'personal-isMailingSameAsPermanent' => array('filter' => 'filter_boolean'),
		'personal-mailing-streetAddress1'   => array('filter' => 'filter_generic'),
		'personal-mailing-streetAddress2'   => array('filter' => 'filter_generic'),
		'personal-mailing-city'             => array('filter' => 'filter_generic'),
		'personal-mailing-postal'           => array('filter' => 'filter_zipcode'),
		'personal-mailing-state'            => array('filter' => 'filter_state'),
		'personal-mailing-country'          => array('filter' => 'filter_country'),

		'personal-birth_date'           => array('filter' => 'filter_long_date', 'isRequired' => true, 'requiredMessage'=>'You did not enter a Birthday'),
		'personal-socialSecurityNumber' => array('filter' => 'filter_ssn'),
		'personal-gender'               => array('filter' => 'filter_gender'),	//@TODO filter using options

		'personal-birth_city'           => array('filter' => 'filter_generic'),
		'personal-birth_state'          => array('filter' => 'filter_state'),
		'personal-birth_country'        => array('filter' => 'filter_country'),

		'personal-countryOfCitizenship' => array('filter' => 'filter_country', 'isRequired' => true, 'requiredMessage'=>'You did not enter your Country of Citizenship'),
		'personal-us_state'             => array('filter' => 'filter_state', 'isRequired' => true, 'requiredMessage'=>'You did not enter your Legal Residence'),
		'personal-residencyStatus'      => array('filter' => 'filter_residency', 'isRequired' => true, 'requiredMessage'=>'You did not enter your Residency Status'), //@TODO filter using options
		'personal-greenCardLink'        => array('filter' => 'filter_generic'),

		// TODO these should probably be booleans? 
		'personal-ethnicity_hispa'  => array('filter' => 'filter_boolean'),
		'personal-ethnicity_amind'  => array('filter' => 'filter_boolean'),
		'personal-ethnicity_asian'  => array('filter' => 'filter_boolean'),
		'personal-ethnicity_black'  => array('filter' => 'filter_boolean'),
		'personal-ethnicity_pacif'  => array('filter' => 'filter_boolean'),
		'personal-ethnicity_white'  => array('filter' => 'filter_boolean'),
		'personal-ethnicity_unspec' => array('filter' => 'filter_boolean'),


		// Language Information
		'personal-isEnglishPrimary'     => array('filter' => 'filter_boolean'),

		'personal-englishYears_school'  => array('filter' => 'filter_date_range'),
		'personal-englishYears_univ'    => array('filter' => 'filter_date_range'),
		'personal-englishYears_private' => array('filter' => 'filter_date_range'),

		'language-language'             => array('filter' => 'filter_generic'),
		'language-proficiency_writing'  => array('filter' => 'filter_proficiency'), // @TODO: filter using options
		'language-proficiency_reading'  => array('filter' => 'filter_proficiency'), // @TODO: filter using options
		'language-proficiency_speaking' => array('filter' => 'filter_proficiency') // @TODO: filter using options

	),



	/* --------- International --------- */

	ApplicationSection::international => array(

		'international-isInternationalStudent' => array('filter' => 'filter_boolean', 'isRequired' => true, 'requiredMessage'=>'You did not select if you are a US Citizen'),


		// TOEFL Exam
		'international-toefl_hasTaken'    => array('filter' => 'filter_boolean'),
		'international-toefl_hasReported' => array('filter' => 'filter_boolean'),
		'international-toefl_date'        => array('filter' => 'filter_short_date'),
		'international-toefl_score'       => array('filter' => 'filter_toefl_score'),


		// Future Plans
		'international-hasUSCareer'     => array('filter' => 'filter_boolean'),
		'international-usCareerDetails' => array('filter' => 'filter_generic'),

		'international-hasFurtherStudies'     => array('filter' => 'filter_boolean'),
		'international-furtherStudiesDetails' => array('filter' => 'filter_generic'),

		'international-hasHomeCareer'     => array('filter' => 'filter_boolean'),
		'international-homeCareerDetails' => array('filter' => 'filter_generic'),


		// Financial Details
		'international-financeDetails'       => array('filter' => 'filter_generic'),
		'international-usFriendsOrRelatives' => array('filter' => 'filter_generic'),


		// United States Emergency Contact
		'international-usEmergencyContact_name'               => array('filter' => 'filter_generic'),
		'international-usEmergencyContact_relationship'       => array('filter' => 'filter_generic'), // @TODO: filter using options	
		'international-usEmergencyContactInfo-primaryPhone'   => array('filter' => 'filter_phone'),
		'international-usEmergencyContactInfo-streetAddress1' => array('filter' => 'filter_generic'),
		'international-usEmergencyContactInfo-streetAddress2' => array('filter' => 'filter_generic'),
		'international-usEmergencyContactInfo-state'          => array('filter' => 'filter_state'),
		'international-usEmergencyContactInfo-city'           => array('filter' => 'filter_generic'),	
		'international-usEmergencyContactInfo-postal'         => array('filter' => 'filter_zipcode'),


		// Home Country Emergency Contact
		'international-homeEmergencyContact_name'               => array('filter' => 'filter_generic'),
		'international-homeEmergencyContact_relationship'       => array('filter' => 'filter_generic'), // @TODO: filter using options	
		'international-homeEmergencyContactInfo-primaryPhone'   => array('filter' => 'filter_phone'),
		'international-homeEmergencyContactInfo-streetAddress1' => array('filter' => 'filter_generic'),
		'international-homeEmergencyContactInfo-streetAddress2' => array('filter' => 'filter_generic'),
		'international-homeEmergencyContactInfo-state'          => array('filter' => 'filter_state'),
		'international-homeEmergencyContactInfo-city'           => array('filter' => 'filter_generic'),	
		'international-homeEmergencyContactInfo-postal'         => array('filter' => 'filter_zipcode'),
		'international-homeEmergencyContact-country'            => array('filter' => 'filter_country')

	),



	/* --------- Educational History --------- */

	ApplicationSection::educationalHistory => array(

		// Previous Appliction to UMaine
		'personal-prevUMGradApp_appExists'     => array('filter' => 'filter_boolean', 'isRequired' => true, 'requiredMessage'=>'You did not select whether you have previously applied to the University of Maine'),
		'personal-prevUMGradApp_gradAppExists' => array('filter' => 'filter_boolean'),
		'personal-prevUMGradApp_date'          => array('filter' => 'filter_short_date'),
		'personal-prevUMGradApp_dept'          => array('filter' => 'filter_generic'),		// @TODO: filter using options
		'personal-prevUMGradApp_degree'        => array('filter' => 'filter_generic'),
		'personal-prevUMGradApp_degreeDate'    => array('filter' => 'filter_short_date'),
		'personal-prevUMGradWithdraw_exists'   => array('filter' => 'filter_boolean'),
		'personal-prevUMGradWithdraw_date'     => array('filter' => 'filter_short_date'),


		// Previously Attended Institutions
		'previousSchool-name'              => array('filter' => 'filter_generic'),
		'previousSchool-city'              => array('filter' => 'filter_generic'),
		'previousSchool-state'             => array('filter' => 'filter_state'),
		'previousSchool-country'           => array('filter' => 'filter_country'),
		'previousSchool-code'              => array('filter' => 'filter_generic'),
		'previousSchool-startDate'         => array('filter' => 'filter_short_date'),
		'previousSchool-endDate'           => array('filter' => 'filter_short_date'),
		'previousSchool-major'             => array('filter' => 'filter_generic'),
		'previousSchool-degreeEarned_name' => array('filter' => 'filter_generic'),
		'previousSchool-degreeEarned_date' => array('filter' => 'filter_short_date'),


		// Grade Information
		'personal-undergradGPA'       => array('filter' => 'filter_gpa'),
		'personal-postbaccGPA'        => array('filter' => 'filter_gpa'),
		'personal-previousCourseWork' => array('filter' => 'filter_generic'),


		// Disciplinary Violations
		'personal-hasDisciplinaryViolation' => array('filter' => 'filter_boolean', 'isRequired' => true, 'requiredMessage'=>'You did not select a value for disciplinary violation history'),

		'disciplinaryViolation-date'    => array('filter' => 'filter_short_date'),
		'disciplinaryViolation-details' => array('filter' => 'filter_generic'),

		// Crime Information
		'personal-hasCivilViolation' => array('filter' => 'filter_boolean', 'isRequired' => true, 'requiredMessage'=>'You did not select a value for criminal violation history'),

		'civilViolation-date'    => array('filter' => 'filter_short_date'),
		'civilViolation-details' => array('filter' => 'filter_generic'),


		// Examinations
		'personal-hasTakenGRE' => array('filter' => 'filter_boolean'),
		'gre-date'             => array('filter' => 'filter_short_date'),
		'gre-verbal'           => array('filter' => 'filter_gre_verbal'),
		'gre-quantitative'     => array('filter' => 'filter_gre_quantitative'),
		'gre-analytical'       => array('filter' => 'filter_gre_analytical'),
		'gre-subject'          => array('filter' => 'filter_gre_subject'),
		'gre-hasBeenReported'  => array('filter' => 'filter_boolean'),
		'gre-score'            => array('filter' => 'filter_gre_score'),

		'personal-gmat_hasTaken'     => array('filter' => 'filter_boolean'),
		'personal-gmat_hasReported'  => array('filter' => 'filter_boolean'),
		'personal-gmat_date'         => array('filter' => 'filter_short_date'),
		'personal-gmat_quantitative' => array('filter' => 'filter_gmat_quantitative'),
		'personal-gmat_verbal'       => array('filter' => 'filter_gmat_verbal'),
		'personal-gmat_analytical'   => array('filter' => 'filter_gmat_analytical'),
		'personal-gmat_score'        => array('filter' => 'filter_gmat_score'),

		'personal-mat_hasTaken'    => array('filter' => 'filter_boolean'),
		'personal-mat_hasReported' => array('filter' => 'filter_boolean'),
		'personal-mat_date'        => array('filter' => 'filter_short_date'),
		'personal-mat_score'       => array('filter' => 'filter_mat_score'),


		// Work History and Awards
		'personal-presentOccupation' => array('filter' => 'filter_generic'),
		'personal-employmentHistory' => array('filter' => 'filter_generic'),
		'personal-academicHonors'    => array('filter' => 'filter_generic')

	),



	/* --------- Educational Objectives --------- */

	ApplicationSection::educationalObjectives => array(

		// Academic Programs
		'degree-academic_program' => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not select an academic program of study'), // @TODO: filter using options
		'degree-academic_plan'    => array('filter' => 'filter_generic'), // @TODO: filter using options
		'degree-academic_major'   => array('filter' => 'filter_generic'),
		'degree-academic_minor'   => array('filter' => 'filter_generic'),
		'degree-academic_load'    => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not select an attendance load'), // @TODO: filter using options
		'degree-studentType'      => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not select your student type'),	// @TODO: filter using options
		'startYear'               => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not select a start year', 'requirements'=>array('nonzero')), 	// @TODO: filter using options
		'startSemester'           => array('filter' => 'filter_generic', 'isRequired' => true, 'requiredMessage'=>'You did not select a start semester'), 	// @TODO: filter using options
		'desiredHousing'          => array('filter' => 'filter_generic'), 	// @TODO: filter using options

		// Assitantship Request
		'degree-isSeekingFinancialAid'            => array('filter' => 'filter_boolean'),
		'degree-isSeekingAssistantship'           => array('filter' => 'filter_boolean'),
		// 'degree-desiredAssitantshipDepartment' => 'filter_generic', // disabled for now

		// New England Regional Student Program
		'degree-isApplyingNebhe' => array('filter' => 'filter_boolean'),

		// Additional Information, Essay & Resume
		'hasUmaineCorrespondent'      => array('filter' => 'filter_boolean'),
		'umaineCorrespondentDetails'  => array('filter' => 'filter_generic')
	),



	/* --------- Letters of Recommmendation --------- */

	ApplicationSection::lettersOfRecommendation => array(

		'waiveReferenceViewingRights' => array('filter' => 'filter_boolean', 'isRequired' => true, 'requiredMessage'=>'You did not select a Waive Viewing Rights option'),

		'reference-firstName'          => array('filter' => 'filter_name'),
		'reference-lastName'           => array('filter' => 'filter_name'),
		'reference-email'              => array('filter' => 'filter_email'),
		'reference-relationship'       => array('filter' => 'filter_relationship'),
		'reference-isSubmittingOnline' => array('filter' => 'filter_boolean'),
		'reference-requestHasBeenSent' => array('filter' => 'filter_boolean'),
		'reference-submittedDate'      => array('filter' => 'filter_long_date'),
		'reference-filename'           => array('filter' => 'filter_generic'),
		
		'contactInformation-primaryPhone'   => array('filter' => 'filter_phone'),
		'contactInformation-streetAddress1' => array('filter' => 'filter_generic'),
		'contactInformation-streetAddress2' => array('filter' => 'filter_generic'),
		'contactInformation-state'          => array('filter' => 'filter_state'),
		'contactInformation-city'           => array('filter' => 'filter_generic'),
		'contactInformation-postal'         => array('filter' => 'filter_zipcode'),
		'contactInformation-country'        => array('filter' => 'filter_country')
	),



	/* --------- Other --------- */

	'other' => array(
		'hasAcceptedTermsOfAgreement' => array('filter' => 'filter_boolean')
	)



);