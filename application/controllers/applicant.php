<?php

include "../libs/variables.php";
include "../libs/database.php";

class Applicant {
	private $applicant_id;
	private $db;
	function Applicant()
	{
       $this->db = Database::getInstance();
	}

	/** Get a new applicant object from current session data **/
	public static function getActiveApplicant() {
		$id = check_ses_vars();
		if($id == 0) { 
			return NULL;
		} else {
			return Applicant::getApplicant($id);
		}
	}

	/** Get a new applicant object by passed in id **/
	public static function getApplicant($id) {
		$instance = new self();
       if( is_integer($id) ) {
	       $instance->applicant_id = $id;
	       return $instance;
       } else {
       		throw new Exception("Passed in applicant identifier is not an integer.", 1);
       }		
	}

	public function getFullName()
	{
		$result  = $this->db->query('SELECT given_name, middle_name, family_name FROM applicants WHERE applicant_id=%d', $this->applicant_id);
		$fullName = $result[0]['given_name'] . " " . $result[0]['middle_name'] . " " . $result[0]['family_name'];
		return $fullName;
	}

   function __destruct() {
   		print "destroyed applicant<br>";
   		$this->db->close();
   }
}


$applicant = Applicant::getApplicant(2);
print $applicant->getFullName();
print "<br><br>";

$applicant2 = Applicant::getApplicant(1);
print $applicant2->getFullName();
print "<br><br>";

unset($applicant);

$applicant3 = Applicant::getApplicant(3);
print $applicant3->getFullName();
print "<br><br>";

unset($applicant2);

echo "<br>end<br>";