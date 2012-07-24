<?php
// Libraries
include_once __DIR__ . "/../libs/variables.php";
include_once __DIR__ . "/../libs/database.php";

class Applicant {
	private $applicant_id;
	private $db;

	/**
	 * Applicant
	 * 
	 * Class constructor. Not intended for direct use. Use getActiveApplicant() or getApplicant() instead.
	 * 
	 * @return void
	 */
	private function Applicant()
	{
       $this->db = Database::getInstance();
	}

	/**
	 * __destruct
	 * 
	 * Class deconstructor
	 * 
	 * @return void
	 */
   function __destruct()
   {
   		$this->db->close();
   }


	/**
	 * getActiveApplicant
	 * 
	 * Static function to get applicant object from current session data
	 * 
	 * @return 	Object 		Applicant object
	 */
	public static function getActiveApplicant()
	{
		$id = check_ses_vars();
		if($id == 0) { 
			return NULL;
		} else {
			return Applicant::getApplicant($id);
		}
	}

	/**
	 * getApplicant
	 * 
	 * Static function returning applicant with given id
	 * 
	 * @param 	int 		identifier of applicant to create object for
	 * 
	 * @return 	Object 		Applicant object for given identifier
	 */
	public static function getApplicant($id)
	{
		$instance = new self();
       if( is_integer($id) ) {
	       $instance->applicant_id = $id;
	       return $instance;
       } else {
       		throw new Exception("Passed in applicant identifier is not an integer.", 1);
       }		
	}

	/**
	 * getID
	 * 
	 * Returns identifier of applicant. This is the unique id in the database.
	 * 
	 * @return 	Int 		Identifier of applicant
	 */
	public function getID()
	{
		return $this->applicant_id;
	}

	/**
	 * getFullName
	 * 
	 * @return 	String 		Full name of applicant
	 */
	public function getFullName()
	{
		return $this->getGivenName . " " . $this->getMiddleName . " " . $this->getFamilyName;
	}

	/**
	 * getGivenName
	 * 
	 * @return 	String 		Given name (first name) of applicant
	 */
	public function getGivenName()
	{
		return $this->db->getFirst("SELECT given_name FROM applicants WHERE applicant_id=%d", $this->applicant_id);
	}

	/**
	 * getMiddleName
	 * 
	 * @return 	String 		Middle name of applicant
	 */
	public function getMiddleName()
	{
		return $this->db->getFirst("SELECT middle_name FROM applicants WHERE applicant_id=%d", $this->applicant_id);
	}

	/**
	 * getFamilyName
	 * 
	 * @return 	String 		Family name (last name) of applicant
	 */
	public function getFamilyName()
	{
		return $this->db->getFirst("SELECT family_name FROM applicants WHERE applicant_id=%d", $this->applicant_id);
	}

	/**
	 * getEmail
	 * 
	 * @return 	String 		email of applicant
	 */
	public function getEmail()
	{
		return $this->db->getFirst("SELECT login_email FROM applicants WHERE applicant_id=%d", $this->applicant_id);
	}


}
