<?php

require_once __DIR__ . "/Model.php";

/**
 * Applicant Model
 * 
 * The applicant model defines the data and functions associated with a single applicant of the system
 */
class Applicant extends Model
{
	protected static $tableName = 'Applicant';
	protected static $primaryKeys  = array('applicantId');

 	// Register our available properties with Model.php
	protected static $availableProperties = array('fullname');

	/**
	 * Gets the data for special properties
	 */
	function __get($name)
	{
		switch($name)
		{
			case 'fullName':
				return $this->givenName . " " . $this->middleName . " " . $this->familyName;
			break;
		}
		return parent::__get($name);
	}
}


