<?php

// Libraries
require_once __DIR__ . "/../libs/database.php";
require_once __DIR__ . "/../libs/corefuncs.php";

// Models
require_once __DIR__ . "/Applicant.php";

// Application Subsections and repeatables
require_once __DIR__ . "/application-sections/Transaction.php";
require_once __DIR__ . "/application-sections/CivilViolations.php";
require_once __DIR__ . "/application-sections/DisciplinaryViolations.php";
require_once __DIR__ . "/application-sections/PreviousSchool.php";
require_once __DIR__ . "/application-sections/GRE.php";
require_once __DIR__ . "/application-sections/Degree.php";
require_once __DIR__ . "/application-sections/Progress.php";
require_once __DIR__ . "/application-sections/International.php";
require_once __DIR__ . "/application-sections/Language.php";
require_once __DIR__ . "/application-sections/Personal.php";

class Application extends Model
{
	protected static $tableName   = 'Application';
	protected static $primaryKeys = array('applicationId');

	/**
	 * Class Constructor
	 * 
	 * @return void
	 */
	function Application($data=array())
	{
		self::loadFromDB($data);
	}


	protected static $availableProperties = array('degree', 'international', 'type', 'transaction', 'civilViolations', 'disciplinaryViolations', 'previousSchools', 'degreeInfo', 'preenrollCourses', 'GREScores', 'languages', 'references', 'progress', 'personal', 'sections', 'status');

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
		 	case 'degree':
			 	return Model::factory('Degree')->first($this->applicationId);
		 	break;
		 	case 'preenrollCourses':
		 	break;
		 	case 'GREScores':
		 	break;
		 	case 'international':
		 		return Model::factory('International')->whereEqual('applicationId', $this->applicationId)->first();
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
		 	case 'status':
		 		if( $this->hasBeenSubmitted == 1)
		 		{
		 			return 'Submitted';
		 		} else {
		 			return 'In Progress';
		 		}
		 	break;
		 }

		return parent::__get($name);
	}

	// override default model behavior -> we need to check for applicant id too!!!
	public function delete()
	{
		Database::iquery("DELETE FROM Application WHERE applicantId=%d AND applicationId=%d", $this->applicantId, $this->id);
	}

	// override default behavior -> we need to make sure we own the application!!!
	public function save()
	{
		$this->lastModified = Date('Y-m-d H:i:s');
		// Just to be save check for ownership
		if( ApplicationController::doesActiveUserOwnApplication($this->id) )
		{
			parent::save();
		}
	}

	/**
	 * Get Option
	 * 
	 * Accessor for accepted values for enumerated DB fields. Includes values and display names.
	 * 
	 * @return array
	 */
	protected static $availableOptions = array('options_studentType', 'options_startSemester', 'options_startYear', 'options_academicYear', 'options_country', 'options_gender', 'options_state', 'options_suffix', 'options_residencyStatus', 'options_type');
	
	public static function getOption($optionName)
	{
		switch($optionName)
		{
			case 'options_studentType':
				return array(	''      => '- None -',
							'IS'    => 'In-State',
							"OS"    => 'Out of State',
							'INTNL' => 'International',
							'CAN'   => 'Canadian',
							'NEBHE' =>'NEBHE program');
			break;
			case 'options_startSemester':
				return array(	''       => '- None -',
							'FALL'   => 'Fall',
							'SPRING' => 'Spring',
							'SUMMER' => 'Summer');
			break;
			case 'options_startYear':
				$curYear = date('Y');

				$result = array();
				for ($i=0; $i < 4; $i++) { 
					$result[$curYear + $i] = $curYear + $i;
				}
				return $result;
			break;
			case 'options_academicYear':
				return array(''=>'- None -',
						'F'=>'Full-Time',
						'P'=>'Part-Time');
			break;
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
				$typeDB = Database::query("SELECT * FROM APPLICATION_type");
				foreach ($typeDB as $type) {
					$result[ $type['applicationTypeId'] ] = $type['name'];
				}
				return $result;
			break;
		}
		return null; // nothing found
	}

}



