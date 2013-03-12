<?php

require_once __DIR__ . "/ApplicationComponent.php";
//require_once __DIR__ . "/../application.php";

class Reference extends ApplicationComponent
{
	protected static $tableName = 'APPLICATION_Reference';
	protected static $primaryKeys = array('referenceId', 'applicationId');

	protected static $availableProperties = array('fullName', 'contactInformation', 'requestHasBeenSent', 'isSubmittingOnline');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'fullName':
		 		return $this->firstName . ' ' . $this->lastName;
		 	break;
		 	case 'contactInformation':
		 		return ContactInformation::getWithId($this->contactInformationId);
		 	break;
		 	case 'requestHasBeenSent':
		 		return parent::__get('requestHasBeenSent') == 1;
		 	break;
		 	case 'isSubmittingOnline':
		 		return parent::__get('isSubmittingOnline') == 1;
		 	break;
		 }

		 return parent::__get($name);
	}

	protected static $availableOptions = array('options_relationship', 'options_state', 'options_country', 'options_scores', 'options_scores_woNumbers');	
	public static function getOption($optionName)
	{
		switch($optionName)
		{
			case 'options_relationship':
				return array(''    => '- None -',
						'Work'   => 'Work',
						'School' => 'School',
						'Family' => 'Family',
						'Friend' => 'Friend');
			break;			
			case 'options_country':
				return Application::getOption('options_country');
			break;
			case 'options_state':
				return Application::getOption('options_state');
			break;
			case 'options_scores':
				return array(  '1' => '1 - Below Average',
							'2' => '2 - Average',
							'3' => '3 - Somewhat Above Average',
							'4' => '4 - Good',
							'5' => '5 - Unusual',
							'6' => '6 - Outstanding',
							'7' => '7 - Truly Exceptional',
							'8' => 'Unable to Judge');
			break;
			case 'options_scores_woNumbers':
				return array(  '1' => 'Below Average',
							'2' => 'Average',
							'3' => 'Somewhat Above Average',
							'4' => 'Good',
							'5' => 'Unusual',
							'6' => 'Outstanding',
							'7' => 'Truly Exceptional',
							'8' => 'Unable to Judge');
			break;			

		}
		return null; // nothing found
	}

	public static function createNew()
	{
		$reference = parent::createNew();

		$reference->contactInformationId = ContactInformation::createAndGetId();
		$reference->save();
		
		return $reference;
	}


	public static function getWithId($referenceId)
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}
		$dbObject = Database::getFirst("SELECT * FROM APPLICATION_Reference WHERE applicationId = %d AND referenceId = %d", $application->id, $referenceId);

		$reference = Model::factory('reference');
		$reference->loadFromDB($dbObject);
		return $reference;
	} 

}