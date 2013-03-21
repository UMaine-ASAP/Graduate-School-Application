<?php

// Libraries
require_once __DIR__ . "/../../libraries/database.php";


// Models
require_once __DIR__ . "/ApplicationComponent.php";


class Degree extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Degree';
	protected static $primaryKeys = array('applicationId');

	protected static $availableOptions = array('options_academicDeptCode', 'options_academicPlan');

	protected static $availableProperties = array('appliedProgram', 'pretty_isSeekingAssistantship', 'pretty_isApplyingNebhe', 'pretty_academic_load');


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
		 	case 'appliedProgram':
		 		$dept = Database::getFirst("SELECT * FROM AcademicProgram WHERE department_code = %s", $this->academic_program);
		 		return $dept['department_nameFull'];
		 	break;
		 	case 'pretty_isSeekingAssistantship':
		 		return ($this->isSeekingAssistantship == 1) ? "Yes" : "No";
		 	break;
		 	case 'pretty_isApplyingNebhe':
		 		return ($this->isApplyingNebhe == 1) ? "Yes" : "No";
		 	break;
		 	case 'pretty_academic_load':
		 		switch ($this->academic_load) {
		 			case 'F':
		 				return "FULL-Time";
		 				break;
		 			case 'P':
		 				return "PART-TIME";
		 				break;
		 			default:
		 				return $this->academic_load;
		 		}
		 	break;
		 	case 'academic_planName':
		 		$result = $db->getFirst("SELECT academic_planName FROM um_academic WHERE academic_program=%s", $this->academic_program);
		 		return $result['academic_planName'];
		 	break;
		 }

		return parent::__get($name);
	}

	public static function getOption($optionName)
	{
		switch($optionName)
		{
			case 'options_academicDeptCode':
				$options = Database::query("SELECT * FROM AcademicProgram ORDER BY department_code ASC");

				$result = array(''=>'- None -');
				foreach($options as $option) {
					$result[$option['department_code']] = $option['department_code'] . ' - ' . $option['department_nameFull'];
				}

				return $result;
			break;
			case 'options_academicPlan':

				// Get current department
				$application    = ApplicationController::getActiveApplication();
				$departmentCode = $application->degree->academic_program;

				$options = Database::query("SELECT * FROM AcademicProgram WHERE department_code = %s ORDER BY degree_name ASC", $departmentCode);

				$result = array(''=>'- None -');
				foreach($options as $option) {
					$result[$option['degree_code']] = $option['degree_name'];
				}

				return $result;

			break;
		}
		return null; // nothing found
	}

}
