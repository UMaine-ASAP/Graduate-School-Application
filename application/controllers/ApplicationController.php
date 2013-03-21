<?php

require_once __DIR__ . "/ApplicantController.php";
require_once __DIR__ . "/../models/Application.php";


class ApplicationController
{

	/**
	 * Create Application
	 * 
	 * Creates an application of the specified type
	 * 
	 * @param   int    typeId    The numerical reference to the application type
	 * 
	 * @return    Object    The new application, null if unsuccessful
	 */
	public static function createApplication($typeId)
	{
		// double check type is valid
		$types = Application::getOption('options_type');

		if( array_key_exists($typeId, $types) )
		{
			// ensure applicant is logged in
			if( !ApplicantController::applicantIsLoggedIn() ) {
				return null;
			}
			$applicant = ApplicantController::getActiveApplicant();
			$result    = Database::getFirst("SELECT applicationId FROM Application ORDER BY applicationId DESC");
			$applicationId  = $result['applicationId'] + 1;


			Database::iquery("INSERT INTO Application(applicationId, applicantId, applicationTypeId) VALUES (%d, %d, %d)", $applicationId, $applicant->id, $typeId);

			$application = ApplicationController::getApplication($applicationId);

			// We will be opening this application. Set as active in case any database operations pull from the active application :)
			if( ! ApplicationController::setActiveApplication($applicationId) ) 
			{
				error_log("Could not set active application");
				return null; // error
			}

			// Create hash
			$hashIndex = time() + $application->id; // get a unique id
			$hashCode  = Hash::create($hashIndex); // generate hash value

			$application->hashReference = $hashCode;

			// Create common sub-sections
			Database::iquery("INSERT INTO APPLICATION_Primary(applicationId) VALUES (%d)", $applicationId);
			$personal = $application->personal;


			// Set first and last name
			$personal->givenName  = $applicant->givenName;
			$personal->familyName = $applicant->familyName;

			// Build application type specific sub-sections
			switch( $application->applicationTypeId )
			{
				case ApplicationType::DEGREE:
					/** General table updates **/
					Database::iquery("INSERT INTO APPLICATION_International(applicationId) VALUES (%d)", $applicationId);
					Database::iquery("INSERT INTO APPLICATION_Degree(applicationId) VALUES (%d)", $applicationId);
					Database::iquery("INSERT INTO APPLICATION_PreviousSchool(applicationId) VALUES (%d)", $applicationId);
					Database::iquery("INSERT INTO APPLICATION_CivilViolation(applicationId) VALUES (%d)", $applicationId);
					Database::iquery("INSERT INTO APPLICATION_DisciplinaryViolation(applicationId) VALUES (%d)", $applicationId);
					Database::iquery("INSERT INTO APPLICATION_GRE(applicationId) VALUES (%d)", $applicationId);

					// Create 3 default recommendations
					Reference::createNew();
					Reference::createNew();
					Reference::createNew();

					/*** Update Personal contact info ***/

					// mailing contact info
					Database::iquery("INSERT INTO APPLICATION_ContactInformation(applicationId) VALUES (%d)", $applicationId);
					$tmp = Database::getFirst('SELECT LAST_INSERT_ID() as id FROM APPLICATION_ContactInformation');
					$personal->mailing_contactInformationId = $tmp['id'];

					// permanent address contact info
					Database::iquery("INSERT INTO APPLICATION_ContactInformation(applicationId) VALUES (%d)", $applicationId);					
					$tmp = Database::getFirst('SELECT LAST_INSERT_ID() as id');
					$personal->permanentMailing_contactInformationId = $tmp['id'];

					// update application
					$personal->save();

					/*** Update International contact info ***/
					$international = $application->international;

					// US contact info
					Database::iquery("INSERT INTO APPLICATION_ContactInformation(applicationId) VALUES (%d)", $applicationId);					
					$tmp = Database::getFirst('SELECT LAST_INSERT_ID() as id');
					$international->usEmergencyContact_contactInformationId = $tmp['id'];

					// Home contact info
					Database::iquery("INSERT INTO APPLICATION_ContactInformation(applicationId) VALUES (%d)", $applicationId);					
					$tmp = Database::getFirst('SELECT LAST_INSERT_ID() as id');
					$international->homeEmergencyContact_contactInformationId = $tmp['id'];

					// Specify current progress
					// $sections = $db->query("SELECT * FROM structure WHERE include=1 ORDER BY `order`");
					// $sectionCount = count($sections);
					// for($i = 0; $i < $sectionCount ;$i++) {
					// 	$db->iquery("INSERT INTO progress VALUES(%d, %s, 'INCOMPLETE','') ", $user, $sections[$i]['id']);
					// }

					// Update application
					$international->save();

				break;
				case ApplicationType::NONDEGREE:
				break;
				case ApplicationType::CERTIFICATE:
				break;
				default:
					throw new Exception("Application Type $application->type not found when creating application");
				break;

			}

			$personal->save();
			$application->save(); // save any changes to application
			return $application;

		} else {
			error_log("Application type: $typeID was not found in the database");
			return null;
		}
	}


	/**
	 * Delete Application
	 * 
	 * Completely remove the identified application
	 * 
	 * @param    int    applicationId    The id of the application to remove
	 * 
	 * @return    void
	 */
	public static function deleteApplication($applicationId)
	{
		if( !ApplicantController::applicantIsLoggedIn() )
		{
			return null;
		}

		$application = ApplicationController::getApplication( (int) $applicationId);

		// different application types require different data to be deleted. 

		switch( $application->applicationTypeId )
		{
			case ApplicationType::DEGREE:
				Database::iquery("DELETE FROM APPLICATION_International where applicationId = %d", $applicationId);
				Database::iquery("DELETE FROM APPLICATION_Degree where applicationId = %d", $applicationId);
			break;
			case ApplicationType::NONDEGREE:
			break;
			case ApplicationType::CERTIFICATE:
			break;
			default:
				throw new Exception("Application Type $application->type not found when deleting application");
			break;
		}

		// Complete the process
		Database::iquery("DELETE FROM APPLICATION_Primary where applicationId = %d", $applicationId);
		$application->delete();

		// remove all contact information
		Database::iquery("DELETE FROM APPLICATION_ContactInformation where applicationId = %d", $applicationId);

		// Remove all previous institutations
		Database::iquery("DELETE FROM APPLICATION_PreviousSchool where applicationId = %d", $applicationId);

		// Remove all violations
		Database::iquery("DELETE FROM APPLICATION_CivilViolation where applicationId = %d", $applicationId);
		Database::iquery("DELETE FROM APPLICATION_DisciplinaryViolation where applicationId = %d", $applicationId);
		Database::iquery("DELETE FROM APPLICATION_GRE where applicationId = %d", $applicationId);
		Database::iquery("DELETE FROM APPLICATION_Reference where applicationId = %d", $applicationId);

		// Keep transaction information?
		//Database::iquery("DELETE FROM APPLICATION_Transaction where transactionId = %d", $application->transactionId);

	}


	/**
	 * Get Active Application
	 * 
	 * Get a new application object from current session data
	 * 
	 * @return    Object    The active application if set, false otherwise
	 */
	public static function getActiveApplication()
	{
		// Ensure session data is set
		if( !isset($_SESSION) ) 
		{ 
			session_start(); 
		}

		$id = $_SESSION['active-application'];

		return ApplicationController::getApplication($id);
	}


	/**
	 * Get Application from Hash
	 * 
	 * @param    String    $hashReference    Encoded value for accessing the application
	 * 
	 * Get an application object with the unique code provided. Used for access to application for recommendation
	 * 
	 * @return    Object    The associated application if exists, false otherwise
	 */
	public static function getApplicationFromHash($hashReference)
	{
     	if( $hashReference == '' ) { return null; }

		// Grab application
		$applicationDB = Database::getFirst("SELECT * FROM `Application` WHERE hashReference = %s", $hashReference);

		if ($applicationDB == array()) {
			return null;
		}
		$application = new Application();
		$application->loadFromDB($applicationDB);

       	return $application;
	}


	/**
	 * Does Active User Own Application
	 * 
	 * Checks whether the currently logged in user owns the specified application
	 * 
	 * @param    int    applicationId    The id of the application to test
	 * 
	 * @return    bool    True if active applicant owns the specified application, false otherwise
	 */
	public static function doesActiveUserOwnApplication($applicationId)
	{
		$applicant = ApplicantController::getActiveApplicant();
		$result    = Database::getFirst("SELECT * FROM `Application` WHERE applicationId = %d AND applicantId = %d", $applicationId, $applicant->id);
		return ( $result != array() ); 
	}


	/**
	 * All My Applications
	 * 
	 * Retrieves all of the applications owned by the logged in user
	 * 
	 * @return    array    Array of applicant objects associated with the logged in user, null if user is not logged in
	 */

	public static function allMyApplications()
	{
		$applicant = ApplicantController::getActiveApplicant();

		// Ensure applicant is valid
		if ($applicant == null)
		{
			return null;
		}

		// Retrieve applications
		$ids = Database::query("SELECT applicationId as id FROM `Application` WHERE applicantId = %d ORDER BY lastModified DESC", $applicant->id);

		// ensure data exists
		if($ids == array())
		{
			return array();
		}

		// build results
		$result = array();
		foreach ($ids as $id) {
			$result[] = ApplicationController::getApplication($id['id']);
		}
		return $result;
	}


	/**
	 * Set Active Application
	 * 
	 * Sets the application currently being edited
	 * 
	 * @param    int    applicationId    The id of the application to set
	 * 
	 * @return    bool    True if operation was successful, false otherwise
	 */
	public static function setActiveApplication($applicationId)
	{
		// make sure this is an application of the current user
		$applicant   = ApplicantController::getActiveApplicant();
		$application = Database::getFirst("SELECT * FROM `Application` WHERE applicantId = %d AND applicationId = %d", $applicant->id, $applicationId);
		
		if( $application == array() ) {
			return false;
		}

		// Ensure session data is set
		if( !isset($_SESSION) ) 
		{ 
			session_start(); 
		}
		$_SESSION['active-application'] = $applicationId;

		return true;
	}


	/**
	 * Get Application
	 * 
	 * Get the application object specified by the applicationId
	 * 
	 * @param    int    applicationId    The id of the application to get
	 *
	 * @return    Object    The new application, null if application does not exist
	 */
	public static function getApplication($applicationId)
	{
		return getApplicationById($applicationId);
	}

	public static function getApplicationById($applicationId)
	{
     	if( ! is_integer($applicationId) ) { ERROR::fatal("Passed in application identifier is not an integer."); }

		// ensure applicant is logged in
		$applicant = ApplicantController::getActiveApplicant();
		if( $applicant == null )
		{
			return null;
		}

		// make sure the user owns the application
		$applicationDB = Database::getFirst("SELECT * FROM `Application` WHERE applicationId = %d AND applicantId = %d", $applicationId, $applicant->id);
		if ($applicationDB == array()) {
			return null;
		}

       	return Model::factory('Application')->whereEqual('applicationId', $applicationId)->first();
	}


	/**
	 * Get Application by id Without An Active User
	 * 
	 * Get the application object specified by the applicationId without a logged in user. Use carefully!
	 * 
	 * @param    int    applicationId    The id of the application to get
	 *
	 * @return    Object    The new application, null if application does not exist
	 */
	public static function getApplicationByIdWithoutAnActiveUser($applicationId)
	{
     	if( ! is_integer($applicationId) ) { ERROR::fatal("Passed in application identifier is not an integer."); }


		$applicationDB = Database::getFirst("SELECT * FROM `Application` WHERE applicationId = %d", $applicationId);
		if ($applicationDB == array()) {
			return null;
		}

       	return Model::factory('Application')->whereEqual('applicationId', $applicationId)->first();
	}


	/* ================================ */
	/* = Internal Functions
	/* ================================ */


}