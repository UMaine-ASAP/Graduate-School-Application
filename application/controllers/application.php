<?php

// Libraries
require_once __DIR__ . "/../libs/database.php";
require_once __DIR__ . "/../libs/corefuncs.php";

class Application
{
	private $application_id;
	private $db;

	function Application()
	{
       $this->db = Database::getInstance();
	}

   function __destruct()
   {
   		$this->db->close();
   }

	/** Get a new application object from current session data **/
	public static function getActiveApplication()
	{
		$id = check_ses_vars();
		if($id == 0) { 
			return NULL;
		} else {
			return Application::getApplication($id);
		}
	}

	/** Get a new application object by passed in id **/
	public static function getApplication($id)
	{
		$instance = new self();
       if( is_integer($id) ) {
	       $instance->application_id = $id;
	       return $instance;
       } else {
       		throw new Exception("Passed in application identifier is not an integer.", 1);
       }		
	}

	public function hasBeenSubmitted()
	{
		$result = $this->db->query('SELECT has_been_submitted FROM applicants WHERE applicant_id=%d', $this->application_id);
		if( !is_array($result) ) {
			return TRUE;
		}
		$has_been_submitted = ($result[0]['has_been_submitted']) ? TRUE : FALSE;
		return $has_been_submitted;
	}


}

