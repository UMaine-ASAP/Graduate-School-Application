<?php

require_once __DIR__ . "/../controllers/ApplicationController.php";

/**
 * Access an application-based field in the database using a reference string
 * 
 * Format is: parent1-parent2- ... -fieldName
 * Used to communicate between the client and server when saving and validating fields
 */
class ApplicationFieldReference
{

	public $fieldPath;

	private $parent;
	private $field;
	private $id;

	/**
	 * Initialize Field Reference
	 * 
	 * @param    String    $fieldPath    The path within the application to the data in the form location1-location2- ... -fieldName
	 * 
	 * @return void
	 */
	public function ApplicationFieldReference($fieldPath)
	{
		$this->id = -1;
		$this->fieldPath = $fieldPath;

		$pathDetails = explode('-', $fieldPath);
		$application = ApplicationController::getActiveApplication();
		if( is_null($application) ) {
			return;
		}

		if( count($pathDetails) == 1) {
			$this->parent = $application;
			$this->field  = $pathDetails[0];
		} else if( count($pathDetails) == 2) {
			$this->parent = $application->$pathDetails[0];
			$this->field  = $pathDetails[1];
		} else if( count($pathDetails) == 3) {

			// the last value may be an id
			if( strpos($pathDetails[2], '#') !== false )
			{
				// id is associated with the first item and indicates a repeatable
				$this->id        = (int) substr($pathDetails[2], 1);

				$parentClass  = ucwords($pathDetails[0]);
				$this->parent = $parentClass::getWithId($this->id);

				$this->field     = $pathDetails[1];
				$this->fieldPath = $pathDetails[0].'-'.$pathDetails[1];
			} else {
				$this->parent = $application->$pathDetails[0]->$pathDetails[1];
				$this->field  = $pathDetails[2];
			}
		}

		if ($this->parent == null ) {
			return;
		}		
	}

	/**
	 * Save
	 * 
	 * Set the field's value and update the database
	 * 
	 * @param    mixed    $value    Value to store in field
	 * 
	 * @return void
	 */
	public function save($value)
	{
		$this->parent->__set($this->field, $value);
		$this->parent->save();


		// just in case we missed anything. Also updates last modified timestamp
		$application = ApplicationController::getActiveApplication();
		if( is_null($application) ) {
			return;
		}
		$application->save();
	}

	/**
	 * Value
	 * 
	 * Retrieve the field's value
	 * 
	 * @return mixed
	 */
	public function value()
	{
		return $this->parent->__get($this->field);
	}
	
}
