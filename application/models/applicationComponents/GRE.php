<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";

class GRE extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_GRE';
	protected static $primaryKeys = array('GREId', 'applicationId');


	/**
	 * Get Option
	 * 
	 * Accessor for accepted values for enumerated DB fields. Includes values and display names.
	 * 
	 * @return array
	 */
	protected static $availableOptions = array('options_subject');
	
	public static function getOption($optionName)
	{
		switch($optionName)
		{
		 	case 'options_subject':
				return array(	'' => '- None -',
							'BCMB' 	=> 'Biochemistry, Cell and Molecular Biology',
							'BIO' 	=> 'Biology',
							'CHEM' 	=> 'Chemistry',
							"COS" 	=> 'Computer Science',
							"LIT" 	=> 'Literature in English',
							"MATH" 	=> 'Mathematics',
							"PHYS" 	=> 'Physics',
							"PSY" 	=> 'Psychology',
							);
		 	break;

		}
		return null; // nothing found
	}

}
