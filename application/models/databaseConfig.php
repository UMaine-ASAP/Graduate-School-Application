<?php

$databaseFields = array(

	// Personal
	'personal-givenName'		=> 'filter_generic',
	'personal-middleName'		=> 'filter_generic',
	'personal-familyName'		=> 'filter_generic',		
	'personal-primaryPhone' 		=> 'filter_phone',
	'personal-secondaryPhone' 	=> 'filter_phone',
	'personal-suffix' 			=> 'filter_suffix',
	'personal-email' 			=> 'filter_email',

	'personal-permanentMail-postal' 	=> 'filter_zipcode',
	'personal-permanentMail-state' 	=> 'filter_state', 
	'personal-permanentMail-country' 	=> 'filter_country',

	'personal-isMailingDifferentFromPermanent' 	=> 'filter_boolean',
	'personal-mailing-postal' 				=> 'filter_postal',
	'personal-mailing-state'  				=> 'filter_state',
	'personal-mailing-country' 				=> 'filter_country',

	'personal-dateOfBirth' 			=> 'filter_long_date',
	'personal-socialSecurityNumber'	=> 'filter_ssn',
	'personal-gender'				=> 'filter_gender',
	'personal-birthState'			=> 'filter_state',
	'personal-birthCountry'			=> 'filter_country',
	'personal-countryOfCitizenship'	=> 'filter_country',
	'personal-us_state'				=> 'filter_state',
	'personal-residencyStatus'		=> 'filter_residency',

	'personal-greenCardLink' => '',

	// Ethnicity
	// TODO these should probably be booleans? 
	'personal-ethnicity_hispa'	=> 'filter_boolean',
	'personal-ethnicity_amind'	=> 'filter_boolean',
	'personal-ethnicity_asian'	=> 'filter_boolean',
	'personal-ethnicity_black'	=> 'filter_boolean',
	'personal-ethnicity_pacif'	=> 'filter_boolean',
	'personal-ethnicity_white'	=> 'filter_boolean',
	'personal-ethnicity_unspec'	=> 'filter_boolean',

	// Language
	'personal-isEnglishPrimary'		=> 'filter_boolean',
	'language-proficiency_writing'	=> 'filter_proficiency',
	'language-proficiency_reading'	=> 'filter_proficiency',
	'language-proficiency_speaking'	=> 'filter_proficiency',

	// International
	'international-isInternationalStudent'	=> 'filter_boolean',
	'international-toefl_hasTaken'		=> 'filter_boolean',
	'international-toefl_hasReported'		=> 'filter_boolean',
	'international-toefl_date'			=> 'filter_short_date',
	'international-toefl_score'			=> 'filter_toefl_score',
	'international-usEmergencyCotact.primaryPhone' => 'filter_phone',
	'international-usEmergencyCotact.state' => 'filter_state',	

	// Previous School
	'previousSchool-name'	=> 'filter_generic'

);