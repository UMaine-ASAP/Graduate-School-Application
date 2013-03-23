<?php

require_once __DIR__ . "/ApplicationComponent.php";
//require_once __DIR__ . "/../application.php";

require_once __DIR__ . "/ContactInformation.php";
require_once __DIR__ . "/../Recommendation.php";

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

	protected static $availableOptions = array('options_relationship', 'options_state', 'options_country', );
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


}