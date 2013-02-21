<?php

$databaseFields = array(

	// Application
	'startYear'                   => 'filter_generic', 	// @TODO: filter using options
	'startSemester'               => 'filter_semester', 	// @TODO: filter using options
	'desiredHousing'              => 'filter_generic', 	// @TODO: filter using options
	'waiveReferenceViewingRights' => 'filter_boolean',
	'hasUmaineCorrespondent'      => 'filter_boolean',
	'umaineCorrespondentDetails'  => 'filter_generic',
	'hasAcceptedTermsOfAgreement' => 'filter_boolean',


	// Personal
	'personal-givenName'      => 'filter_generic',
	'personal-middleName'     => 'filter_generic',
	'personal-familyName'     => 'filter_generic',		
	'personal-primaryPhone'   => 'filter_phone',
	'personal-secondaryPhone' => 'filter_phone',
	'personal-suffix'         => 'filter_suffix',
	'personal-email'          => 'filter_email',

	'personal-permanentMail-postal'  => 'filter_zipcode',
	'personal-permanentMail-state'   => 'filter_state', 
	'personal-permanentMail-country' => 'filter_country',

	'personal-isMailingDifferentFromPermanent' => 'filter_boolean',
	'personal-mailing-postal'                  => 'filter_postal',
	'personal-mailing-state'                   => 'filter_state',
	'personal-mailing-country'                 => 'filter_country',

	'personal-dateOfBirth'          => 'filter_long_date',
	'personal-socialSecurityNumber' => 'filter_ssn',
	'personal-gender'               => 'filter_gender',

	'personal-birth_city'           => 'filter_generic',
	'personal-birth_state'          => 'filter_state',
	'personal-birth_country'        => 'filter_country',
	'personal-countryOfCitizenship' => 'filter_country',
	'personal-us_state'             => 'filter_state',
	'personal-residencyStatus'      => 'filter_residency',
	'personal-greenCardLink'        => 'filter_generic',

	'personal-undergradGPA' => 'filter_gpa',
	'personal-postbaccGPA'  => 'filer_gpa',

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
	'personal-mat_score'       => 'filter_score',

	'personal-prevUMApp_exists' => 'filter_boolean',

	'personal-prevUMGradApp_exists'      => 'filter_boolean',
	'personal-prevUMGradApp_date'        => 'filter_short_date',
	'personal-prevUMGradApp_dept'        => 'filter_short_date',
	'personal-prevUMGradApp_degree'      => 'filter_boolean',
	'personal-prevUMGradApp_degreeDate'  => 'filter_short_date',
	'personal-prevUMGradWithdraw_exists' => 'filter_boolean',
	'personal-prevUMGradWithdraw_date'   => 'filter_short_date',


	// Ethnicity
	// TODO these should probably be booleans? 
	'personal-ethnicity_hispa'  => 'equals_HISPA',
	'personal-ethnicity_amind'  => 'equals_amind',
	'personal-ethnicity_asian'  => 'equals_asian',
	'personal-ethnicity_black'  => 'equals_black',
	'personal-ethnicity_pacif'  => 'equals_pacif',
	'personal-ethnicity_white'  => 'equals_white',
	'personal-ethnicity_unspec' => 'equals_unspec',


	// Language
	'personal-isEnglishPrimary'     => 'filter_boolean',

	'language-language'             => 'filter_generic',
	'language-proficiency_writing'  => 'filter_proficiency', // @TODO: filter using options
	'language-proficiency_reading'  => 'filter_proficiency', // @TODO: filter using options
	'language-proficiency_speaking' => 'filter_proficiency', // @TODO: filter using options


	// International
	'international-isInternationalStudent' => 'filter_boolean',

	'international-toefl_hasTaken'    => 'filter_boolean',
	'international-toefl_hasReported' => 'filter_boolean',
	'international-toefl_date'        => 'filter_short_date',
	'international-toefl_score'       => 'filter_toefl_score',

	'international-hasUSCareer'     => 'filter_boolean',
	'international-usCareerDetails' => 'filter_generic',

	'international-hasFurtherStudies'     => 'filter_boolean',
	'international-furtherStudiesDetails' => 'filter_generic',

	'international-hasHomeCareer'     => 'filter_boolean',
	'international-homeCareerDetails' => 'filter_generic',

	'international-financeDetails' => 'filter_generic',

	'international-usEmergencyContact_name'         => 'filter_generic',
	'international-usEmergencyContact_relationship' => 'filter_relationship', // @TODO: filter using options	
	'international-usEmergencyContact-primaryPhone' => 'filter_phone',
	'international-usEmergencyContact-state'        => 'filter_state',
	'international-usEmergencyContact-zip'          => 'filter_zipcode',

	'international-homeEmergencyContact_name'         => 'filter_generic',
	'international-homeEmergencyContact_relationship' => 'filter_relationship', // @TODO: filter using options	
	'international-homeEmergencyContact-primaryPhone' => 'filter_phone',
	'international-homeEmergencyContact-country'      => 'filter_country',


	// Previous School
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


	//GRE
	'personal-hasTakenGRE' => 'filter_boolean',
	'gre-date'             => 'filter_short_date',
	'gre-verbal'           => 'filter_gre_verbal',
	'gre-quantitative'     => 'filter_gre_quantitative',
	'gre-analytical'       => 'filter_gre_analytical',
	'gre-subject'          => 'filter_gre_subject',
	'gre-hasBeenReported'  => 'filter_boolean',
	'gre-score'            => 'filter_gre_score',


	// degree
	'degree-academic_program'                 => 'filter_generic', // @TODO: filter using options
	'degree-academic_plan'                    => 'filter_generic', // @TODO: filter using options
	'degree-academic_major'                   => 'filter_generic',
	'degree-academic_minor'                   => 'filter_generic',
	'degree-academic_load'                    => 'filter_generic', // @TODO: filter using options
	'degree-studentType'                      => 'filter_generic',	// @TODO: filter using options
	'degree-isSeekingFinancialAid'            => 'filter_boolean',
	'degree-isSeekingAssistantship'           => 'filter_boolean',
	// 'degree-desiredAssitantshipDepartment' => 'filter_generic', // disabled for now
	'degree-isApplyingNebhe'                  => 'filter_boolean',


	// References
	'reference-firstName'          => 'filter_name',
	'reference-lastName'           => 'filter_name',
	'reference-email'              => 'filter_email',
	'reference-relationship'       => 'filter_relationship',
	'reference-isSubmittingOnline' => 'filter_boolean',
	'reference-requestHasBeenSent' => 'filter_boolean',
	'reference-submittedDate'      => 'filter_long_date',
	'reference-filename'           => 'filter_generic',	
	
	'reference-phone'               => 'filter_phone',
	'reference-state'               => 'filter_state',
	'reference-postal'              => 'filter_zipcode',
	'reference-country'             => 'filter_country',
	'reference-englishYearsSchool'  => 'filter_date_range',
	'reference-englishYearsUniv'    => 'filter_date_range',
	'reference-englishYearsPrivate' => 'filter_date_range',


	// Disciplinary Violation
	'disciplinaryViolation-exists' => 'filter_boolean',

	'disciplinaryViolation-date'    => 'filter_short_date',
	'disciplinaryViolation-details' => 'filter_generic',


	// Civil Violation
	'criminalViolation-exists' => 'filter_boolean',

	'criminalViolation-date'    => 'fileter_date',
	'criminalViolation-details' => 'fileter_date'
);