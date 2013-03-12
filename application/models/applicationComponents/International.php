<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";


class International extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_International';
	protected static $primaryKeys = array('applicationId');


	protected static $availableProperties = array('usEmergencyContactInfo', 'homeEmergencyContactInfo', 'pretty_toefl_hasTaken');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'usEmergencyContactInfo':
		 		return ContactInformation::getWithId($this->usEmergencyContact_contactInformationId);
		 	break;		 	
		 	case 'homeEmergencyContactInfo':
		 		return ContactInformation::getWithId($this->homeEmergencyContact_contactInformationId);
		 	break;
		 	case 'pretty_toefl_hasTaken':
		 		return ($this->toefl_hasTaken == 1) ? "Yes" : "No";
		 	break;
		 }

		 return parent::__get($name);
	}
}
