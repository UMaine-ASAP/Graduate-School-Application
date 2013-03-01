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
	 * @return Application 	The new application, null if unsuccessful
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
				return; // error
			}

			// Build application type specific sub-sections

			// Create common sub-sections
			Database::iquery("INSERT INTO APPLICATION_Primary(applicationId) VALUES (%d)", $applicationId);

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
					$personal = $application->personal;

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

			$application->save(); // save any changes to application
			return $application;

		} else {
			return null;
		}
	}

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
	}

	/** Get a new application object from current session data **/
	public static function getActiveApplication()
	{
		// Get id
		if( !isset($_SESSION) ) 
		{ 
			session_start(); 
		}
		$id = $_SESSION['active-application'];

		return ApplicationController::getApplication($id);
	}

	/** Get a new application object by passed in id **/
	public static function getApplication($applicationId)
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

       	return Model::factory('Application')->whereEqual('applicationId', $applicationId)->first();
	}

	public static function doesActiveUserOwnApplication($applicationId)
	{
		$applicant = ApplicantController::getActiveApplicant();
		$result    = Database::getFirst("SELECT * FROM `Application` WHERE applicationId = %d AND applicantId = %d", $applicationId, $applicant->id);
		return ( $result != array() ); 
	}

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
	 * @return bool 	whether operation was successful or not
	 */
	public static function setActiveApplication($applicationId)
	{
		// make sure this is an application of the current user
		$applicant   = ApplicantController::getActiveApplicant();
		$application = Database::getFirst("SELECT * FROM `Application` WHERE applicantId = %d AND applicationId = %d", $applicant->id, $applicationId);
		
		if( $application == array() ) {
			return false;
		}

		if( !isset($_SESSION) ) 
		{ 
			session_start(); 
		}
		$_SESSION['active-application'] = $applicationId;
		return true;
	}


}