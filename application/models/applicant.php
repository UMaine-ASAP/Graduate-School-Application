<?php

// Libraries
include_once __DIR__ . "/../libs/corefuncs.php";
include_once __DIR__ . "/../config.php";
include_once __DIR__ . "/../libs/database.php";


/**
* Session Creation
**/
function set_ses_vars($ID) {
	$_SESSION['UMGradSession'] = $ID;
	$_SESSION['lastAccess'] = time();
}

function check_ses_vars() {
	if( !isset($_SESSION) ) { session_start(); }
	if(isset($_SESSION['UMGradSession']) && isset($_SESSION['lastAccess'])) {
		$latestAccess = time();
		if($latestAccess - $_SESSION['lastAccess'] > $GLOBALS["session_timeout"]) {
			user_logout();
			return 0;
		}
		//Make sure user is valid
		$user_check = Database::query("SELECT applicantId FROM Applicant WHERE applicantId = %d", $_SESSION['UMGradSession']);

		if( is_array($user_check) ) {
			$_SESSION['lastAccess'] = $latestAccess;
			return $_SESSION['UMGradSession'];	
		} else {
			return 0;
		}
	}
	return 0;
}

/**
* Login
**/
function user_login($id) {
	if( !isset($_SESSION) ) { session_start(); }
	set_ses_vars($id);
}

function user_logout() {
	if( !isset($_SESSION) ) { session_start(); }

	session_unset();
	unset($_SESSION['UMGradSession']);
	unset($_SESSION['lastAccess']);
}



class Applicant extends Model
{
	protected static $tableName = 'applicants';
	protected static $columnId  = 'applicant_id';


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
		self::loadData($data);
	}

}


