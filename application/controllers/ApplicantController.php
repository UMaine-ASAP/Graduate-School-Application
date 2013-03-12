<?php

// Libraries
require_once __DIR__ . "/../configuration.php";
require_once __DIR__ . "/../libraries/Hash.php";
require_once __DIR__ . "/../libraries/email.php";

// Models
require_once __DIR__ . "/../models/Applicant.php";

/**
 * Applicant Controller
 * 
 * Manages access to the applicant model
 * 
 * Performs all necessary applicant functions including logging the user in/out,
 * getting the active applicant if set, Creating an account, and validating user
 */
class ApplicantController
{

	/**
	 * Get Active Applicant
	 * 
	 * Get applicant object from current session data
	 * 
	 * @return 	Object 		Applicant object
	 */
	public static function getActiveApplicant()
	{
		$id = self::check_ses_vars();
		if($id == 0) { 
			return NULL;
		} else {
			return ApplicantController::getApplicant($id);
		}
	}

	/**
	 * Login Applicant
	 * 
	 * Logs the specified username into the system.
	 * 
	 * @param    string    email       Applicant's username (email address)
	 * @param    string    password    Applicant's account password
	 * 
	 * @return    string    Empty string if successful, otherwise an error message describing the issue
	 */
	public static function loginApplicant($email, $password)
	{

		$user_result = Database::getFirst("SELECT `applicantId`, `password`, `isEmailConfirmed` FROM `Applicant` WHERE `loginEmail` = '%s' LIMIT 1", $email);

		// validate results
		if ($user_result == null) { return 'Incorrect email/password combination'; }

		$storedPassword = $user_result['password'];
		$id             = $user_result['applicantId'];
		$confirmed      = $user_result['isEmailConfirmed'];

		// Check password and email is confirmed
		if ($confirmed != 1) {
			return 'Email not confirmed. Please check your email.';
		}
		if ($storedPassword == sha1($password) && $confirmed == 1) {

			self::user_login($id);
			if (self::check_ses_vars() != 0) {
				return '';
			}
		}
		return 'Incorrect email/password combination';
	}


	/**
	 * Log Out Active Applicant
	 * 
	 * Logs the active applicant out of thse system.
	 * 
	 * @return    void
	 */	
	public static function logOutActiveApplicant()
	{
		self::user_logout();
	}


	/**
	 * Applicant Is Logged In
	 * 
	 * Check whether an applicant is logged in or not
	 * 
	 * @return    bool    True if an applicant is logged in, false otherwise 
	 */
   	public static function applicantIsLoggedIn()
   	{
		$user = self::check_ses_vars();
		if( $user == 0) {
			return false;
		}
		return true;
	}

	/**
	 * Create Account
	 * 
	 * @param   string    email       Applicant email (username)
	 * @param   string    password    Applicant's account password
	 * 
	 * @return    void
	 */
	public static function createAccount($email, $password)
	{
		$last_index = Database::getFirst("SELECT applicantId FROM Applicant ORDER BY applicantId DESC LIMIT 1");

		$hash_index = time() + $last_index['applicantId'] + 1; // get a unique id
		$code = Hash::create($hash_index); // use this code for the confirmation email

		Database::iquery("INSERT INTO `Applicant` (`loginEmail`, `password`, `loginEmailCode`) VALUES('%s', '%s', '%s')", $email, sha1($password), $code);


		$recipient_email = str_replace('@','%40',$email);
		$recipient_email = str_replace('+','%2B',$recipient_email);
		$confirm_url = $GLOBALS['WEBROOT']."/account/confirm";

		$emailSender = new Email();
		$emailSender->loadFromTemplate('accountConfirmation.email.php', 
									array('{{RECIPIENT_EMAIL}}' => $recipient_email,
									'{{CONFIRM_URL}}'           => $confirm_url,
									'{{CODE}}'                  => $code));


		$emailSender->setDestinationEmail($email);
		$emailSender->sendEmail();
	}


	/**
	 * Account Already Exists
	 * 
	 * Checks whether the specified username (email address) exists in the system
	 * 
	 * @param    string    User's email (username) to test
	 * 
	 * @return   bool     Whether the account already exists or not
	 */
	public static function accountAlreadyExists($email)
	{
		if($email == '') return false;
		$dupe_result = Database::getFirst("SELECT `loginEmail` FROM `Applicant` WHERE `loginEmail` = '%s'", $email);
		return $dupe_result != NULL;
	}


	/**
	 * Does Account Validate
	 * 
	 * Checkes whether an account validates from email address and validation code (emailed to user)
	 * 
	 * @param   string    email             Applicant email (username)
	 * @param   string    validationCode    The validation code sent to user
	 * 
	 * @return    bool    True if the user can be validated, false otherwise
	 */
	public static function doesAccountValidate($email, $validationCode)
	{
		$check_user = Database::getFirst("SELECT `applicantId` FROM `Applicant` WHERE `loginEmail` = '%s' AND `loginEmailCode` = '%s'", $email, $validationCode);
		return ($check_user != null);
	}


	/**
	 * Is Account Already Validated
	 * 
	 * Check whether account is already validated or not
	 * 
	 * @param   string    email             Applicant email (username)
	 * @param   string    validationCode    The validation code sent to user
	 * 
	 * @return    bool    True if the account has already been validated, false otherwise
	 */
	public static function isAccountAlreadyValidated($email, $validationCode)
	{
		//make sure that user and that hash exist and match, and if they've already confirmed
		$check_user = Database::getFirst("SELECT `isEmailConfirmed` FROM `Applicant` WHERE `loginEmail` = '%s' AND `loginEmailCode` = '%s'", $email, $validationCode);

		// Ensure user exists
		if($check_user == null)
		{
			return false;
		}

		return ($check_user['isEmailConfirmed'] == 1);
	}


	/**
	 * Validate Account
	 * 
	 * Validate the account for the provided email (username) and validation code
	 * 
	 * @param   string    email             Applicant email (username)
	 * @param   string    validationCode    The validation code sent to user
	 * 
	 * @return    bool    True if the account was validated, false otherwise
	 */
	public static function validateAccount($email, $validationCode)
	{
		// Verify the information and flip the 'confirmed' bit
		$result = Database::iquery("UPDATE `Applicant` SET `isEmailConfirmed` = 1 WHERE `loginEmail` = '%s' AND `loginEmailCode` = '%s'", $email, $validationCode);		
		return ($result != null);
	}


	/**
	 * Send Forgot Password Email
	 * 
	 * Sends a password recovery email to the specified address with a url to reset the password
	 * 
	 * @param   string    email   Applicant email (username) to send the recovery link to
	 * @param   string    code    Hash used for recovery process
	 * 
	 * @return    void
	 */
	public static function sendForgotPasswordEmail($email, $code)
	{
		$emailObject = new EmailSystem();

		$encodedEmail = str_replace('+','%2B',$email);

		$code = ''; //@TODO: create code
		$confirmUrl = $GLOBALS['WEBROOT'] . "/forgot ?email=$encodedEmail&code=$code";

		$emailObject->loadFromTemplate('forgotPassword.email.php', 
										array('CONFIRM_URL' => $confirmUrl,
										'APPLICANT_EMAIL'   => $email,
										'ADMIN_EMAIL'       => $GLOBALS['ADMIN_EMAIL'],
										'GRADUATE_HOMEPAGE' => $GLOBALS['GRADUATE_HOMEPAGE']));
		$emailObject->setDestinationEmail( $email );
		$emailObject->sendEmail();
	}



	/* ================================ */
	/* = Internal Functions
	/* ================================ */

	/**
	 * Get applicant with given id
	 * 
	 * @param 	int 		identifier of applicant to create object for
	 * 
	 * @return 	Object 	Applicant object for given identifier
	 */
	private static function getApplicant($applicantId)
	{
     	if ( ! is_integer($applicantId) ) { throw new Exception("Passed in applicant identifier is not an integer.", 1); }

//		$applicantDB = Database::getFirst("SELECT * FROM `Applicant` WHERE applicantId = %d", $applicantId);

       	return Model::factory('Applicant')->whereEqual('applicantId', $applicantId)->first();
//       	return new Applicant( $applicantDB );
	}


	/* ---------- */
	/* - Session Management
	/* -
	/* - All session-based functions for logging user in/out and 
	/* - checking the currently logged in user
	/* ---------- */


	/**
	 * Set Session Variables
	 * 
	 * Sets important applicant session values
	 * UMGradSession -> the current applicant id
	 * LastAccess    -> The last time the applicant was accessed
	 * 
	 * @param int $applicantId     Id of the current applicant
	 * 
	 * @return void
	 **/
	private static function set_ses_vars($applicantId) {
		$_SESSION['UMGradSession'] = $applicantId;
		$_SESSION['lastAccess']    = time();
	}


	/**
	 * Check Session Variables
	 * 
	 * Gets the current applicant id if available, otherwise returns 0.
	 *
	 * The applicant id is available only if the applicant id has previously been set
	 * and the time interval since the last time the applicant id was requested is 
	 * less than the session timeout.
	 * 
	 * @return int  The current applicant id or 0 if not available
	 **/
	private static function check_ses_vars() {
		// start session if necessary
		if( !isset($_SESSION) )
		{
			session_start(); 
		}

		// Return if values are not set
		if( !isset($_SESSION['UMGradSession']) || ! isset($_SESSION['lastAccess']))
		{
			return 0;
		}

		// Check last access time
		$latestAccess = time();
		$timeIntervalSinceLastAccess = $latestAccess - $_SESSION['lastAccess'];
		if( $timeIntervalSinceLastAccess > $GLOBALS["session_timeout"])
		{
			self::user_logout();
			return 0;
		}

		//Make sure user is valid in system
		$user_check = Database::query("SELECT applicantId FROM Applicant WHERE applicantId = %d", $_SESSION['UMGradSession']);

		if( is_array($user_check) )
		{
			// user is valid
			$_SESSION['lastAccess'] = $latestAccess;
			
			return $_SESSION['UMGradSession'];	
		} else {
			// user found is invalid
			return 0;
		}
	}


	/**
	 * User Login
	 * 
	 * Logs user in by setting user session data
	 * 
	 * @param int applicantId    Id of the current applicant
	 * 
	 * @return void
	 **/
	private static function user_login($applicantId) {
		// Ensure session is set
		if( !isset($_SESSION) )
		{ 
			session_start();
		}

		self::set_ses_vars($applicantId);
	}


	/**
	 * User Logout
	 * 
	 * Logs user out by unsetting user session data
	 * 
	 * @return void
	 **/
	private static function user_logout() {
		// Ensure session is set
		if( !isset($_SESSION) )
		{ 
			session_start();
		}

		// unset session
		session_unset();

		// unset variables just to be on the safe-side
		unset($_SESSION['UMGradSession']);
		unset($_SESSION['lastAccess']);
	}

}