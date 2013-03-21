<?php

require_once __DIR__ . "/../libraries/InputSanitation.php";
require_once __DIR__ . "/../controllers/ApplicationController.php";

/**
 * Build Mainestreet Output For Applicant
 * 
 * Generates tab-delimited data for Mainestreet import for a single completed applicant
 * 
 * This file is fine-tuned for the import - don't change without versioning!
 * 
 * @authors: Lukas Jordan //edits: jonathan simpson. Tim Westbaker
 */
// 
function buildMainestreetOutputForApplicant($applicationId) {

	/* --- Application variables --- */
	$application      = ApplicationController::getApplicationById($applicationId);
	$personal         = $application->personal;
	$degree           = $application->degree;
	$transaction      = $application->transaction;
	$permanentMailing = $personal->permanentMailing;
	$mailing          = $personal->mailing;
	
	/* --- Gather data for submission --- */
	$data = array(

		// Personal Data
		$application->id,
		$personal->givenName,
		$personal->familyName,
		$personal->middleName,
		$personal->suffix,
		$permanentMailing->streetAddress1,
		$permanentMailing->streetAddress2,
		$permanentMailing->city,
		$permanentMailing->country,
		$permanentMailing->pretty_state,
		$permanentMailing->postal,
		InputSanitation::replaceNonDigits($personal->phonePrimary), // strip out non-numbers
		$personal->email,
		$personal->gender,
		$personal->birth_date,
		$mailing->streetAddress1,
		$mailing->streetAddress2,
		$mailing->city,
		$mailing->pretty_state,
		$mailing->country,
		$mailing->postal,
		$personal->ethnicity_hispa,
		$personal->ethnicity_amind,
		$personal->ethnicity_asian,
		$personal->ethnicity_black,
		$personal->ethnicity_pacif,
		$personal->ethnicity_white,

		// Social Security Number
		InputSanitation::replaceNonDigits($personal->socialSecurityNumber), // strip out non-numbers

		// Application Submission Date
		$application->submittedDate
	);

	// Country Code
	$data[] = ($permanentMailing->country == 'USA') ? '1' : '4';	


	// Seeking Financial Aid
	$data[] = $degree->isSeekingFinancialAid;

	
	// Program of Study	
	$data[] = $degree->startSemester;
	$data[] = $degree->startYear;
	$data[] = $personal->alternateName;
	$data[] = InputSanitation::replaceNonDigits($personal->phoneSecondary);
	$data[] = ($degree->academic_load == 'F' || $degree->academic_load == 'P') ? 'F':'';
	$data[] = $degree->academic_program;
	$data[] = $degree->academic_plan;


	// Grandfathered code -> need 4 blanks here
	$data[] = '';
	$data[] = '';
	$data[] = '';
	$data[] = '';


	// Grandfathered code -> supposed to allow 4 programs of study in one application. Since now you need to submit separate applications, we need to send blank spaces for 3 of them
	for ($a = 0; $a < 3; $a++){
		$data[] = "";
		$data[] = "";
		$data[] = "";
		$data[] = "";
		$data[] = "";
		$data[] = "";
	}

	$data[] = "F"; // not sure what this signified
	

	// Previously Attended Schools
	// Grandfathered code -> supposed to send previous school code but it is never set in application.
	// Up to 10 schools could be sent so we need 10 blanks
	for ($a = 0; $a < 10; $a++)
	{
		$data[] = '';		
	}


	// residency status and student type
	$data[] = ''; // supposed to be blank
	$data[] = $degree->studentType;


	// Output application payment data
	$data[] = ($transaction->isComplete) ? 'Y':'N';

	if ( $transaction->isComplete ) {
		$data[] = ($transaction->isPayingOnline) ? 'PAYNOW':'PAYLATER';

		$transactionDateItems = explode("-", $transaction->completedDate);
		$data[] = $transactionDateItems[2]."/".$transactionDateItems[1]."/".$transactionDateItems[0];

		$data[] = $transaction->amount;
		$data[] = $transaction->paymentMethod;
	} else {
		// Use all blanks
		$data[] = ''; //transaction type
		$data[] = ''; //date
		$data[] = ''; //transaction amount
		$data[] = ''; //transaction payment method
	}

	return implode("\t", $data);
}
