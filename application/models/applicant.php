<?php

class Applicant extends Model
{
	protected static $tableName = 'applicants';
	protected static $primaryKeys  = array('applicantId');


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

	/**
	 * Class Constructor
	 * 
	 * @return void
	 */
	function Applicant($data = array())
	{
		$id = $data['applicantId'];
		self::loadFromDB($data);
	}

}


