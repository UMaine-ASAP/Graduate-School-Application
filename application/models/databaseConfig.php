<?php

$databaseFields = array(

	// Personal

	'personal.givenName'		=> 'filter_generic',
	'personal.middleName'		=> 'filter_generic',
	'personal.familyName'		=> 'filter_generic',		
	'personal.primaryPhone' 		=> 'filter_phone',
	'personal.secondaryPhone' 	=> 'filter_phone',
	'personal.suffix' 			=> 'filter_suffix',
	'personal.email' 			=> 'filter_email',

	'personal.permanentMail.postal' 	=> 'filter_zipcode',
	'personal.permanentMail.state' 	=> 'filter_state', 
	'personal.permanentMail.country' 	=> 'filter_country',

	'personal.isMailingDifferentFromPermanent' 	=> 'filter_boolean',
	'personal.mailing.postal' 				=> 'filter_postal',
	'personal.mailing.state'  				=> 'filter_state',
	'personal.mailing.country' 				=> 'filter_country',

	'personal.dateOfBirth' 			=> 'filter_long_date',
	'personal.socialSecurityNumber'	=> 'filter_ssn',
	'personal.gender'				=> 'filter_gender',
	'personal.birthState'			=> 'filter_state',
	'personal.birthCountry'			=> 'filter_country',
	'personal.countryOfCitizenship'	=> 'filter_country',
	'personal.usState'				=> 'filter_state',
	'personal.residencyStatus'		=> 'filter_residency',
	'personal.greenCardLink' 		=> 'filter_generic',

	'personal.undergradGPA'		=> 'filter_gpa',
	'personal.postbaccGPA'		=> 'filer_gpa',

	'personal.gmat_hasTaken' 		=> 'filter_boolean',
	'personal.gmat_hasReported'		=> 'filter_boolean',
	'personal.gmat_date'			=> 'filter_short_date',
	'personal.gmat_quantitative' 	=> 'filter_gmat_quantitative',
	'personal.gmat_verbal'			=> 'filter_gmat_verbal',
	'personal.gmat_analytical'		=> 'filter_gmat_analytical',
	'personal.gmat_score'			=> 'filter_gmat_score',

	'personal.mat_hasTaken'		=> 'filter_boolean',
	'personal.mat_hasReported'	=> 'filter_boolean',
	'personal.mat_date'			=> 'filter_short_date',
	'personal.mat_score'		=> 'filter_score',

	'personal.prevUMApp_exists'	=> 'filter_boolean',

	'personal.prevUMGradApp_exists'		=> 'filter_boolean',
	'personal.prevUMGradApp_date' 		=> 'filter_short_date',
	'personal.prevUMGradApp_dept'		=> 'filter_short_date',
	'personal.prevUMGradApp_degree'		=> 'filter_boolean',
	'personal.prevUMGradApp_degreeDate'	=> 'filter_short_date',
	'personal.prevUMGradWithdraw_exists' => 'filter_boolean',
	'personal.prevUMGradWithdraw_date'	=> 'filter_short_date',

	// Ethnicity
	// TODO these should probably be booleans? 
	'personal.ethnicity_hispa'	=> 'equals_HISPA',
	'personal.ethnicity_amind'	=> 'equals_amind',
	'personal.ethnicity_asian'	=> 'equals_asian',
	'personal.ethnicity_black'	=> 'equals_black',
	'personal.ethnicity_pacif'	=> 'equals_pacif',
	'personal.ethnicity_white'	=> 'equals_white',
	'personal.ethnicity_unspec'	=> 'equals_unspec',

	// Language
	'personal.isEnglishPrimary'		=> 'filter_boolean',
	'language.proficiency_writing'	=> 'filter_proficiency',
	'language.proficiency_reading'	=> 'filter_proficiency',
	'language.proficiency_speaking'	=> 'filter_proficiency',

	// International
	'international.isInternationalStudent'	=> 'filter_boolean',
	'international.toefl_hasTaken'		=> 'filter_boolean',
	'international.toefl_hasReported'		=> 'filter_boolean',
	'international.toefl_date'			=> 'filter_short_date',

	'international.toefl_score'			=> 'filter_toefl_score',
	'international.usEmergencyContact.primaryPhone' => 'filter_phone',
	'international.usEmergencyContact.state' => 'filter_state',	
	'international.usEmergencyContact.zip'	=> 'filter_zipcode',
	'international.homeEmergencyContact.phone'	=> 'filter_phone',
	'international.homeEmergencyContact.country' => 'filter_country',
	'international.hasFurtherStudies'	=> 'filter_boolean',
	'international.hasUSCareer'			=> 'filter_boolean',
	'international.hasHomeCareer'		=> 'filter_boolean',

	// Previous School
	'previousSchool.name'		=> 'filter_generic',
	'previousSchool.city'		=> 'filter_generic',
	'previousSchool.state'		=> 'filter_state',
	'previousSchool.country'	=> 'filter_country',
	'previousSchool.code'		=> 'filter_generic',
	'previousSchool.startDate' 	=> 'filter_short_date',
	'previousSchool.endDate'	=> 'filter_short_date',
	'previousSchool.major'		=> 'filter_generic',
	'previousSchool.degreeEarned_name' => "filter_generic",
	'previousSchool.degreeEarned_date' => "filter_short_date",

	//GRE
	'gre.hasTaken'			=> 'filter_boolean',
	'gre.date'				=> 'filter_short_date',
	'gre.verbal'			=> 'filter_gre_verbal',
	'gre.quantitative'		=> 'filter_gre_quantitative',
	'gre.analytical'		=> 'filter_gre_analytical',
	'gre.subject'			=> 'filter_gre_subject',
	'gre.hasBeenReported'	=> 'filter_boolean',
	'gre.score'				=> 'filter_gre_score',

	// References
	'reference.firstName'			=> 'filter_name',
	'reference.lastName'			=> 'filter_name',
	'reference.email'				=> 'filter_email',
	'reference.relationship'		=> 'filter_relationship',
	'reference.isSubmittingOnline'	=> 'filter_boolean',
	'reference.requestHasBeenSent'	=> 'filter_boolean',
	'reference.submittedDate'		=> 'filter_long_date',
	'reference.phone'				=> 'filter_phone',
	'reference.state'				=> 'filter_state',
	'reference.postal'				=> 'filter_zipcode',
	'reference.country'				=> 'filter_country',
	'reference.englishYearsSchool'	=> 'filter_date_range',
	'reference.englishYearsUniv'	=> 'filter_date_range',
	'reference.englishYearsPrivate'	=> 'filter_date_range',

	'degree.attendanceLoad'			=> 'filter_attendance_load',
	'degree.studentType'			=> 'filter_student_type',
	'degree.isSeekingAssistantship'	=> 'filter_boolean',
	'degree.isApplyingNebhe'		=> 'filter_boolean',

	'application.startYear'			=> 'filter_generic',
	'application.startSemester'		=> 'filter_semester',
	'application.hasUmaineCorrespondent'	=> 'filter_boolean',
	'application.waiveReferenceViewingRights'	=> 'filter_boolean',
	'application.asAcceptedTermsOfAgreement'	=> 'filter_boolean',


	'disciplinaryViolation.exists'	=> 'filter_boolean',
	'disciplinaryViolation.date'	=> 'filter_short_date',

	'criminalViolation.exists'		=> 'filter_boolean',
	'criminalViolation.date'		=> 'fileter_date'

);