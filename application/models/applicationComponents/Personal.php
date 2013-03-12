<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";


class Personal extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Primary';
	protected static $primaryKeys = array('applicationId');


	protected static $availableProperties = array('mailing', 'permanentMailing');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'mailing':
		 		return ContactInformation::getWithId($this->mailing_contactInformationId);
		 	break;		 	
		 	case 'permanentMailing':
		 		return ContactInformation::getWithId($this->permanentMailing_contactInformationId);
		 	break;		
		 }

		 return parent::__get($name);
	}

}
