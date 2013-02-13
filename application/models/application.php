<?php

// Libraries
require_once __DIR__ . "/../libs/database.php";
require_once __DIR__ . "/../libs/corefuncs.php";

require_once __DIR__ . "/applicant.php";

class ApplicationType
{
	const DEGREE 		= 0;
	const NONDEGREE 	= 1;
	const CERTIFICATE 	= 2;
}

class ApplicationInternalModel extends Model
{
	public static function createNew()
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null) { return null; }

		$appId = $application->applicationId;

		// Get current index
		$newIndex = -1;
		$temp = Database::getFirst("SELECT count(*) as count FROM %s WHERE applicationId = %d", static::$tableName, $appId);
		if( $temp == array())
		{
			$newIndex = 1;
		} else {
			$newIndex = (int) $temp['count'] + 1;
		}
		Database::iquery("INSERT INTO %s(%s, %s) VALUES (%d,%d)", static::$tableName, static::$columnId, 'applicationId', $newIndex, $appId);

		$result = Database::getFirst("SELECT * FROM %s WHERE %s=%d AND applicationId = %d", static::$tableName, static::$columnId, $newIndex, $appId);
		//$result['id'] = $result[static::$columnId];

		$entityName = get_called_class();
		$entity = new $entityName($entityName);
		$entity->loadFromDB($result);
		return $entity;
	}	

	public static function all($appId)
	{
		return Database::query("SELECT * FROM %s WHERE applicationID = %i", static::$tableName, $appId);


	}

}

class Transaction extends ApplicationInternalModel
{
	protected static $tableName = 'APPLICATION_Transaction';
	protected static $columnId  = 'transactionId';
}

class CivilViolations extends ApplicationInternalModel
{
	protected static $tableName = 'APPLICATION_CivilViolation';
	protected static $columnId  = 'civilViolationId';
}

class DisciplinaryViolations extends ApplicationInternalModel
{
	protected static $tableName = 'APPLICATION_DisciplinaryViolation';
	protected static $columnId  = 'disciplinaryVioliationId';
}

class PreviousSchool extends ApplicationInternalModel
{
	protected static $tableName = 'APPLICATION_PreviousSchool';
	protected static $columnId  = 'previousSchoolId';
}

class GRE extends ApplicationInternalModel
{
	protected static $tableName = 'APPLICATION_GRE';
	protected static $columnId  = 'GREId';
}


class Progress extends Model
{
	protected static $tableName = 'APPLICATION_Process';
	protected static $columnId  = 'ProcessId';
}

class Language extends ApplicationInternalModel
{
	protected static $tableName = 'APPLICATION_Language';
	protected static $columnId  = 'languageId';

	// We need to correctly overide __isset in order to use these our magic variables in Twig
	static private $magic_getters = array('options_proficiency');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'options_proficiency':
				return array(	''	  	=> '- None -',
							'Good' 	=> 'Good',
							'Fair' 	=> 'Non-Resident Alien',
							'Poor' 	=> 'Poor');
		 	break;
		 }
		return parent::__get($name);
	}

	public function __isset($name)
	{
		if ( in_array($name, self::$magic_getters) ) 
		{
			return true;
		}
		return parent::__isset($name);
	}

}

class Personal extends Model
{
	protected static $tableName = 'APPLICATION_Primary';
	protected static $columnId  = 'applicationId';
}


class Application extends Model
{
	protected static $tableName = 'Application';
	protected static $columnId  = 'applicationId';


	// We need to correctly overide __isset in order to use these our magic variables in Twig
	static private $magic_getters    = array('type', 'transaction', 'civilViolations', 'disciplinaryViolations', 'previousSchools', 'degreeInfo', 'preenrollCourses', 'GREScores', 'languages', 'references', 'progress', 'personal', 'sections');
	static private $availableOptions =array('options_country', 'options_gender', 'options_state', 'options_suffix', 'options_residencyStatus', 'options_type');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'type':
		 		$result = Database::getFirst('SELECT name FROM APPLICATION_type WHERE applicationTypeId = %d', $this->applicationTypeId);
		 		return $result['name'];
		 	case 'transaction':
			 	return Model::factory('Transaction')->first($this->transactionId);
		 	break;
		 	case 'civilViolations':
			 	return Model::factory('CivilViolation')->whereEqual('applicationId', $this->id)->get();
		 	break;
		 	case 'disciplinaryViolations':
			 	return Model::factory('DisciplinaryViolation')->whereEqual('applicationId', $this->id)->get();
		 	break;
		 	case 'previousSchools':
			 	return Model::factory('PreviousSchool')->whereEqual('applicationId', $this->id)->get();
		 	break;
		 	case 'degreeInfo':
			 	return Model::factory('Degree')->first($this->id);
		 	break;
		 	case 'preenrollCourses':
		 	break;
		 	case 'GREScores':
		 	break;
		 	case 'languages':
		 		return Model::factory('Language')->whereEqual('applicationId', $this->applicationId)->get();
		 	break;
		 	case 'references':
		 	break;
		 	case 'progress':
		 	break;
		 	case 'personal':
		 		return Model::factory('Personal')->whereEqual('applicationId', $this->applicationId)->first();
		 	break;
		 	case 'sections':
			 	return array('personal-information', 'international', 'educational-history', 'educational-objectives', 'letters-of-recommendation');
		 	break;
		 }

		// Check if this is a request for available options
		if( strpos($name, 'options_') !== false) {
			$result = self::getOption($name);
			if( ! is_null($result) )
			{
				return $result;
			}
		}


		return parent::__get($name);
	}

	public function __isset($name)
	{
		if ( in_array($name, self::$magic_getters) || in_array($name, self::$availableOptions) ) 
		{
			return true;
		}
		return parent::__isset($name);
	}

	/**
	 * Get Option
	 * 
	 * Accessor for accepted values for enumerated DB fields. Includes values and display names.
	 * 
	 * @returns array
	 */
	public static function getOption($optionName)
	{
		switch($optionName)
		{
			case 'options_country':
				return self::getOptionsFromDB('Country');
			break;
			case 'options_gender':
				return array(  ''  => '- None -',
							'M' => 'Male',
							'F' => 'Female',
							'O' => 'Other' );
			break;
			case 'options_state':
				return self::getOptionsFromDB('State');
			break;
			case 'options_suffix':
				return array(	''	  =>	'- None -', 
							'ESQ.' =>	'ESQ', 
							'II'	  =>	'II', 
							'III'  =>	'III', 
							'IV'	  =>	'IV', 
							'V'	  =>	'V',
							'JR'	  => 'Jr', 
							'SR'	  => 'Sr');
			break;
			case 'options_residencyStatus':
				return array(	''	  					=> '- None -',
							'US resident' 				=> 'US Resident',
							'non-resident alien' 		=> 'Non-Resident Alien',
							'resident alien green card' 	=> 'Resident Alien (Green Card)');
			break;
			case 'options_type':
				$result = array();
				$typeDB = Database::get("SELECT * FROM APPLICATION_type");
				foreach ($typeDB as $type) {
					$result[ $type['applicationTypeId'] ] = $type['name'];
				}
				return $result;
			break;
		}
		return null; // nothing found
	}

	/**
	 * Class Constructor
	 * 
	 * @return void
	 */
	function Application($data=array())
	{
		self::loadFromDB($data);
		// @TODO: load data from APPLICATION_Primary
	}

	function submitWithPayment($paymentIsHappeningNow)
	{
		ApplicationController::submitWithPayment($this, $paymentIsHappeningNow);
	}
}



