<?php

include_once __DIR__ . "/../models/applicant.php";

/** 
 * Manages any database interaction
 */
class ApplicantController extends Controller
{


	/**
	 * Get applicant object from current session data
	 * 
	 * @return 	Object 		Applicant object
	 */
	public static function getActiveApplicant()
	{
		$id = check_ses_vars();
		if($id == 0) { 
			return NULL;
		} else {
			return ApplicantController::getApplicant($id);
		}
	}

	/**
	 * Get applicant with given id
	 * 
	 * @param 	int 		identifier of applicant to create object for
	 * 
	 * @return 	Object 	Applicant object for given identifier
	 */
	private static function getApplicant($applicantId)
	{
     	if( ! is_integer($applicantId) ) { throw new Exception("Passed in applicant identifier is not an integer.", 1); }

		$applicantDB = Database::getFirst("SELECT * FROM `Applicant` WHERE applicantId = %d", $applicantId);

       	return new Applicant( $applicantDB );
	}


   	public static function applicantIsLoggedIn()
   	{
		$user = check_ses_vars();
		if( $user == 0) {
			return false;
		}
		return true;
	}


	public static function loginApplicant($email, $password)
	{

		$user_result = Database::getFirst("SELECT `applicantId`, `password`, `isEmailConfirmed` FROM `Applicant` WHERE `loginEmail` = '%s' LIMIT 1", $email);

		// validate results
		if ($user_result == null) { return 'Incorrect email/password combination'; }

		$hash 	 = $user_result['password'];
		$id 		 = $user_result['applicantId'];
		$confirmed = $user_result['isEmailConfirmed'];

		// Check password and email is confirmed
		if ($confirmed != 1) {
			return 'Email not confirmed. Please check your email.';
		}
		if ($hash == sha1($password) && $confirmed == 1) {

			user_login($id);
			if (check_ses_vars() != 0) {
				return '';
			}
		}
		return 'Incorrect email/password combination';
	}

	public static function createAccount($email, $password)
	{
		$last_index = Database::getFirst("SELECT applicantId FROM Applicant ORDER BY applicantId DESC LIMIT 1");
		$code = getHash(time()+$last_index+1); // use this code for the confirmation email

		Database::iquery("INSERT INTO `Applicant` (`loginEmail`, `password`, `loginEmailCode`) VALUES('%s', '%s', '%s')", $email, sha1($password), $code);
	}

	public static function accountAlreadyExists($email)
	{
		if($email == '') return false;
		$dupe_result = Database::getFirst("SELECT `loginEmail` FROM `Applicant` WHERE `loginEmail` = '%s'", $email);
		return $dupe_result != NULL;
	}


	/**
	 * Does Account Validate
	 * 
	 * Validate account from email address and validation code (emailed to user)
	 */
	public static function doesAccountValidate($email, $validation_code)
	{
		//make sure that user and that hash exist and match, and if they've already confirmed
		$check_user = Database::getFirst("SELECT `applicantId` FROM `Applicant` WHERE `loginEmail` = '%s' AND `loginEmailCode` = '%s'", $email, $validation_code);
		return ($check_user != null);
	}


	/**
	 * Is Account Already Validated
	 * 
	 * Determine whether account is already validated or not
	 */
	public static function isAccountAlreadyValidated($email, $validation_code)
	{
		//make sure that user and that hash exist and match, and if they've already confirmed
		$check_user = Database::getFirst("SELECT `loginEmailConfirmed` FROM `Applicant` WHERE `loginEmail` = '%s' AND `loginEmailCode` = '%s'", $email, $validation_code);
		return ($check_user['loginEmailConfirmed'] == 1);
	}


	/**
	 * Validate Account
	 * 
	 * Validate Email from email and validation code
	 */
	public static function validateAccount($email, $validation_code)
	{
		// Verify the information and flip the 'confirmed' bit
		$result = Database::iquery("UPDATE `Applicant` SET `loginEmailConfirmed` = 1 WHERE `loginEmail` = '%s' AND `loginEmailCode` = '%s'", $email, $validation_code);		
		return ($result != null);
	}


}