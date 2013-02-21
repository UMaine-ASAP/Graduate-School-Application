<?php

// Libraries
require_once __DIR__ . "/../../libs/database.php";


// Models
require_once __DIR__ . "/ApplicationInternalModel.php";


class Degree extends ApplicationInternalModel
{
	protected static $tableName   = 'APPLICATION_Degree';
	protected static $primaryKeys = array('applicationId');

	protected static $availableOptions = array('options_academicDeptCode', 'options_academicPlan');
	
	public static function getOption($optionName)
	{
		switch($optionName)
		{
			case 'options_academicDeptCode':
				$options = Database::query("SELECT * FROM AcademicProgram ORDER BY department_code ASC");

				$result = array();
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

				$result = array();
				foreach($options as $option) {
					$result[$option['degree_code']] = $option['degree_name'];
				}

				return $result;

			break;
		}
		return null; // nothing found
	}

}
