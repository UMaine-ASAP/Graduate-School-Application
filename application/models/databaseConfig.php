<?php

$GLOBALS['databaseFields'] = array(
	// Fields are divided according to application pages

	/* --------- Personal Information --------- */


	// Name
	'personal-givenName'       => 'filter_generic',
	'personal-middleName'      => 'filter_generic',
	'personal-familyName'      => 'filter_generic',
	'personal-suffix'          => 'filter_generic', // @TODO: filter using options
	'personal-alternativeName' => 'filter_generic',


	// Contact Information
	'personal-phonePrimary'   => 'filter_phone',
	'personal-phoneSecondary' => 'filter_phone',	
	'personal-email'          => 'filter_email',

	'personal-permanentMailing-streetAddress1' => 'filter_generic',
	'personal-permanentMailing-streetAddress2' => 'filter_generic',
	'personal-permanentMailing-city'           => 'filter_generic',
	'personal-permanentMailing-postal'         => 'filter_zipcode',
	'personal-permanentMailing-state'          => 'filter_state', 
	'personal-permanentMailing-country'        => 'filter_country',

	'personal-isMailingDifferentFromPermanent' => 'filter_boolean',
	'personal-mailing-streetAddress1'          => 'filter_generic',
	'personal-mailing-streetAddress2'          => 'filter_generic',
	'personal-mailing-city'                    => 'filter_generic',
	'personal-mailing-postal'                  => 'filter_zipcode',
	'personal-mailing-state'                   => 'filter_state',
	'personal-mailing-country'                 => 'filter_country',

	'personal-birth_date'           => 'filter_long_date',
	'personal-socialSecurityNumber' => 'filter_ssn',
	'personal-gender'               => 'filter_gender',	//@TODO filter using options

	'personal-birth_city'           => 'filter_generic',
	'personal-birth_state'          => 'filter_state',
	'personal-birth_country'        => 'filter_country',

	'personal-countryOfCitizenship' => 'filter_country',
	'personal-us_state'             => 'filter_state',
	'personal-residencyStatus'      => 'filter_residency', //@TODO filter using options
	'personal-greenCardLink'        => 'filter_generic',

	// TODO these should probably be booleans? 
	'personal-ethnicity_hispa'  => 'filter_boolean',
	'personal-ethnicity_amind'  => 'filter_boolean',
	'personal-ethnicity_asian'  => 'filter_boolean',
	'personal-ethnicity_black'  => 'filter_boolean',
	'personal-ethnicity_pacif'  => 'filter_boolean',
	'personal-ethnicity_white'  => 'filter_boolean',
	'personal-ethnicity_unspec' => 'filter_boolean',


	// Language Information
	'personal-isEnglishPrimary'     => 'filter_boolean',

	'personal-englishYears_school'  => 'filter_date_range',
	'personal-englishYears_univ'    => 'filter_date_range',
	'personal-englishYears_private' => 'filter_date_range',

	'language-language'             => 'filter_generic',
	'language-proficiency_writing'  => 'filter_proficiency', // @TODO: filter using options
	'language-proficiency_reading'  => 'filter_proficiency', // @TODO: filter using options
	'language-proficiency_speaking' => 'filter_proficiency', // @TODO: filter using options



	/* --------- International --------- */

	'international-isInternationalStudent' => 'filter_boolean',


	// TOEFL Exam
	'international-toefl_hasTaken'    => 'filter_boolean',
	'international-toefl_hasReported' => 'filter_boolean',
	'international-toefl_date'        => 'filter_short_date',
	'international-toefl_score'       => 'filter_toefl_score',


	// Future Plans
	'international-hasUSCareer'     => 'filter_boolean',
	'international-usCareerDetails' => 'filter_generic',

	'international-hasFurtherStudies'     => 'filter_boolean',
	'international-furtherStudiesDetails' => 'filter_generic',

	'international-hasHomeCareer'     => 'filter_boolean',
	'international-homeCareerDetails' => 'filter_generic',


	// Financial Details
	'international-financeDetails'       => 'filter_generic',
	'international-usFriendsOrRelatives' => 'filter_generic',


	// United States Emergency Contact
	'international-usEmergencyContact_name'               => 'filter_generic',
	'international-usEmergencyContact_relationship'       => 'filter_generic', // @TODO: filter using options	
	'international-usEmergencyContactInfo-primaryPhone'   => 'filter_phone',
	'international-usEmergencyContactInfo-streetAddress1' => 'filter_generic',
	'international-usEmergencyContactInfo-streetAddress2' => 'filter_generic',
	'international-usEmergencyContactInfo-state'          => 'filter_state',
	'international-usEmergencyContactInfo-city'           => 'filter_generic',	
	'international-usEmergencyContactInfo-postal'         => 'filter_zipcode',


	// Home Country Emergency Contact
	'international-homeEmergencyContact_name'               => 'filter_generic',
	'international-homeEmergencyContact_relationship'       => 'filter_generic', // @TODO: filter using options	
	'international-homeEmergencyContactInfo-primaryPhone'   => 'filter_phone',
	'international-homeEmergencyContactInfo-streetAddress1' => 'filter_generic',
	'international-homeEmergencyContactInfo-streetAddress2' => 'filter_generic',
	'international-homeEmergencyContactInfo-state'          => 'filter_state',
	'international-homeEmergencyContactInfo-city'           => 'filter_generic',	
	'international-homeEmergencyContactInfo-postal'         => 'filter_zipcode',
	'international-homeEmergencyContact-country'            => 'filter_country',



	/* --------- Educational History --------- */


	// Previous Appliction to UMaine
	'personal-prevUMGradApp_appExists'     => 'filter_boolean',
	'personal-prevUMGradApp_gradAppExists' => 'filter_boolean',
	'personal-prevUMGradApp_date'          => 'filter_short_date',
	'personal-prevUMGradApp_dept'          => 'filter_generic',		// @TODO: filter using options
	'personal-prevUMGradApp_degree'        => 'filter_generic',
	'personal-prevUMGradApp_degreeDate'    => 'filter_short_date',
	'personal-prevUMGradWithdraw_exists'   => 'filter_boolean',
	'personal-prevUMGradWithdraw_date'     => 'filter_short_date',


	// Previously Attended Institutions
	'previousSchool-name'              => 'filter_generic',
	'previousSchool-city'              => 'filter_generic',
	'previousSchool-state'             => 'filter_state',
	'previousSchool-country'           => 'filter_country',
	'previousSchool-code'              => 'filter_generic',
	'previousSchool-startDate'         => 'filter_short_date',
	'previousSchool-endDate'           => 'filter_short_date',
	'previousSchool-major'             => 'filter_generic',
	'previousSchool-degreeEarned_name' => "filter_generic",
	'previousSchool-degreeEarned_date' => "filter_short_date",


	// Grade Information
	'personal-undergradGPA' => 'filter_gpa',
	'personal-postbaccGPA'  => 'filter_gpa',
	'personal-previousCourseWork' => 'filter_generic',


	// Disciplinary Violations
	'personal-hasDisciplinaryViolation' => 'filter_boolean',

	'disciplinaryViolation-date'    => 'filter_short_date',
	'disciplinaryViolation-details' => 'filter_generic',

	// Crime Information
	'personal-hasCivilViolation' => 'filter_boolean',

	'civilViolation-date'    => 'filter_short_date',
	'civilViolation-details' => 'filter_generic',


	// Examinations
	'personal-hasTakenGRE' => 'filter_boolean',
	'gre-date'             => 'filter_short_date',
	'gre-verbal'           => 'filter_gre_verbal',
	'gre-quantitative'     => 'filter_gre_quantitative',
	'gre-analytical'       => 'filter_gre_analytical',
	'gre-subject'          => 'filter_gre_subject',
	'gre-hasBeenReported'  => 'filter_boolean',
	'gre-score'            => 'filter_gre_score',

	'personal-gmat_hasTaken'     => 'filter_boolean',
	'personal-gmat_hasReported'  => 'filter_boolean',
	'personal-gmat_date'         => 'filter_short_date',
	'personal-gmat_quantitative' => 'filter_gmat_quantitative',
	'personal-gmat_verbal'       => 'filter_gmat_verbal',
	'personal-gmat_analytical'   => 'filter_gmat_analytical',
	'personal-gmat_score'        => 'filter_gmat_score',

	'personal-mat_hasTaken'    => 'filter_boolean',
	'personal-mat_hasReported' => 'filter_boolean',
	'personal-mat_date'        => 'filter_short_date',
	'personal-mat_score'       => 'filter_mat_score',


	// Work History and Awards
	'personal-presentOccupation' => 'filter_generic',
	'personal-employmentHistory' => 'filter_generic',
	'personal-academicHonors'    => 'filter_generic',



	/* --------- Educational Objectives --------- */

	// Academic Programs
	'degree-academic_program'                 => 'filter_generic', // @TODO: filter using options
	'degree-academic_plan'                    => 'filter_generic', // @TODO: filter using options
	'degree-academic_major'                   => 'filter_generic',
	'degree-academic_minor'                   => 'filter_generic',
	'degree-academic_load'                    => 'filter_generic', // @TODO: filter using options
	'degree-studentType'                      => 'filter_generic',	// @TODO: filter using options
	'startYear'                   => 'filter_generic', 	// @TODO: filter using options
	'startSemester'               => 'filter_generic', 	// @TODO: filter using options
	'desiredHousing'              => 'filter_generic', 	// @TODO: filter using options

	// Assitantship Request
	'degree-isSeekingFinancialAid'            => 'filter_boolean',
	'degree-isSeekingAssistantship'           => 'filter_boolean',
	// 'degree-desiredAssitantshipDepartment' => 'filter_generic', // disabled for now

	// New England Regional Student Program
	'degree-isApplyingNebhe' => 'filter_boolean',

	// Additional Information, Essay & Resume
	'hasUmaineCorrespondent'      => 'filter_boolean',
	'umaineCorrespondentDetails'  => 'filter_generic',



	/* --------- Letters of Recommmendation --------- */

	'waiveReferenceViewingRights' => 'filter_boolean',

	'reference-firstName'          => 'filter_name',
	'reference-lastName'           => 'filter_name',
	'reference-email'              => 'filter_email',
	'reference-relationship'       => 'filter_relationship',
	'reference-isSubmittingOnline' => 'filter_boolean',
	'reference-requestHasBeenSent' => 'filter_boolean',
	'reference-submittedDate'      => 'filter_long_date',
	'reference-filename'           => 'filter_generic',	
	
	'contactInformation-primaryPhone'   => 'filter_phone',
	'contactInformation-streetAddress1' => 'filter_generic',
	'contactInformation-streetAddress2' => 'filter_generic',
	'contactInformation-state'          => 'filter_state',
	'contactInformation-city'           => 'filter_generic',
	'contactInformation-postal'         => 'filter_zipcode',
	'contactInformation-country'        => 'filter_country',
	


	/* --------- Other --------- */


	'hasAcceptedTermsOfAgreement' => 'filter_boolean'




);