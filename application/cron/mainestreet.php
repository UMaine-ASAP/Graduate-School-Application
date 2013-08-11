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
 * @authors: Lukas Jordan // edits: jonathan simpson. Tim Westbaker
 */
function buildMainestreetOutputForApplicant($applicationId) {
	/* --- Helper variables --- */
	$application      = ApplicationController::getApplicationByIdWithoutAnActiveUser($applicationId);
	$personal         = $application->personal;
	$degree           = $application->degree;
	$transaction      = $application->transaction;
	$permanentMailing = $personal->permanentMailing;
	$mailing          = $personal->mailing;
	
	/* --- Gather data for submission --- */
	// see "MaineStreet Outgoing Data Format.xls" in documentation/ for exact format 
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

		// Ethnicities
		$personal->ethnicityCodeHisp,
		$personal->ethnicityCodeAmind,
		$personal->ethnicityCodeAsian,
		$personal->ethnicityCodeBlack,
		$personal->ethnicityCodePacif,
		$personal->ethnicityCodeWhite,



		// Social Security Number
		InputSanitation::replaceNonDigits($personal->socialSecurityNumber), // strip out non-numbers

		// Application Submission Date
		$application->submittedDate
	);

	// Country Code
	$data[] = ($permanentMailing->country == 'USA') ? '1' : '4';	


	// Seeking Financial Aid
	$data[] = ($degree->isSeekingFinancialAid == 1) ? 'Y' : '';

	
	// Program of Study	
	$data[] = $degree->startSemester;
	$data[] = $degree->startYear;
	$data[] = $personal->alternateName;
	$data[] = InputSanitation::replaceNonDigits($personal->phoneSecondary);
	$data[] = $degree->academic_load;//($degree->academic_load == 'F' || $degree->academic_load == 'P') ? 'F':'';

	// Add program
	$data[] = $degree->academic_program;
	$data[] = $degree->academic_plan;

	// Mainestreet expects 4 subplan codes. As there are no subplans, need 4 blanks here
	$data[] = '';
	$data[] = '';
	$data[] = '';
	$data[] = '';

	// Mainestreet expects 4 programs of study in one application. Since now you need to submit separate applications, we need to send blank spaces for 3 of them
	// There are six slots. First is program, second plan, 3-6 are subplans
	for ($a = 0; $a < 3; $a++){
		$data[] = "";
		$data[] = "";
		$data[] = "";
		$data[] = "";
		$data[] = "";
		$data[] = "";
	}

	// Housing interest. No option in application. default to 'F'
	$data[] = "F";
	

	// Previously Attended Schools
	// Supposed to send previous school orgId but it is never set in application. Need to find database to generate codes
	// Up to 11 schools could be sent so we need 11 blanks
	for ($a = 0; $a < 11; $a++)
	{
		$data[] = '';		
	}

	// residency status and student type
	$data[] = $degree->studentType;


	// Output application payment data
	$data[] = ($transaction->isComplete) ? 'Y':'N';

	if ( $transaction->isComplete ) {
		$data[] = ($transaction->isPayingOnline) ? 'Online':'';

		$transactionDateItems = explode("-", $transaction->completedDate);
		$data[] = $transactionDateItems[2]."/".$transactionDateItems[1]."/".$transactionDateItems[0];

		$data[] = $transaction->amount;
		$data[] = $transaction->externalTransactionId;

		$data[] = $transaction->paymentMethod; // application fee method
		$data[] = $application->typeCode; // application type	
	} else {
		// Use all blanks
		$data[] = ''; //transaction type
		$data[] = ''; //date
		$data[] = ''; //transaction amount
		$data[] = ''; //transaction external Transaction Id

		$data[] = ''; // application fee method
		$data[] = ''; // application type		
	}

	return implode("\t", $data);
}
