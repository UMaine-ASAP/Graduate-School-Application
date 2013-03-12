<?php

// Libraries
require_once __DIR__ . "/../../libraries/database.php";


// Models
require_once __DIR__ . "/ApplicationComponent.php";


class ContactInformation extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_ContactInformation';
	protected static $primaryKeys = array('contactInformationId', 'applicationId');


	protected static $availableProperties = array('pretty_state', 'fullStreetAddress', 'fullAddress');


	/**
	 * Magic Getter
	 * 
	 * Gets the data for special properties
	 * 
	 * @return any
	 */
	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'fullStreetAddress':
		 		return $this->streetAddress1 . ' ' . $this->streetAddress2;
		 	break;
		 	case 'fullAddress':
		 		return $this->fullStreetAddress . '<br />' . $this->city . ', ' . $this->pretty_state . ' ' . $this->postal . ' ' . $this->country;
		 	break;
		 	case 'pretty_state':
		 		// International choice should return blank
		 		if ($this->state == 'IT') {
		 			return '';
		 		}
		 		return $this->state;
		 	break;
		 }

		return parent::__get($name);
	}

	public static function getWithId($contactInformationId)
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}
		$dbObject = Database::getFirst("SELECT * FROM APPLICATION_ContactInformation WHERE applicationId = %d AND contactInformationId = %d", $application->id, $contactInformationId);

		$contactInfo = Model::factory('ContactInformation');

		$contactInfo->loadFromDB($dbObject);
		return $contactInfo;
	} 

	public static function createAndGetId()
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}		
		Database::iquery("INSERT INTO APPLICATION_ContactInformation(applicationId) VALUES (%d)", $application->id);
		$tmp = Database::getFirst('SELECT LAST_INSERT_ID() as id');
		return $tmp['id'];
	}

}
