<?php

// Libraries
require_once __DIR__ . "/../libs/database.php";
require_once __DIR__ . "/../libs/corefuncs.php";


class ApplicationType
{
	const DEGREE 		= 0;
	const NONDEGREE 	= 1;
	const CERTIFICATE 	= 2;
}

class ApplicationInternalEntity extends Entity
{
	public static function createNew()
	{
		$application = ApplicationManager::getActiveApplication();
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

// 


class Transaction extends ApplicationInternalEntity
{
	protected static $tableName = 'APPLICATION_Transaction';
	protected static $columnId  = 'transactionId';
	
}

class CivilViolations extends ApplicationInternalEntity
{
	protected static $tableName = 'APPLICATION_CivilViolation';
	protected static $columnId  = 'civilViolationId';
}

class DisciplinaryViolations extends ApplicationInternalEntity
{
	protected static $tableName = 'APPLICATION_DisciplinaryViolation';
	protected static $columnId  = 'disciplinaryVioliationId';
}

class PreviousSchool extends ApplicationInternalEntity
{
	protected static $tableName = 'APPLICATION_PreviousSchool';
	protected static $columnId  = 'previousSchoolId';
}

class GRE extends ApplicationInternalEntity
{
	protected static $tableName = 'APPLICATION_GRE';
	protected static $columnId  = 'GREId';
}


class Progress extends Entity
{
	protected static $tableName = 'APPLICATION_Process';
	protected static $columnId  = 'ProcessId';
}

class Language extends ApplicationInternalEntity
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

class Personal extends Entity
{
	protected static $tableName = 'APPLICATION_Primary';
	protected static $columnId  = 'applicationId';
}


class Application extends Entity
{
	protected static $tableName = 'Application';
	protected static $columnId  = 'ApplicationId';


	// We need to correctly overide __isset in order to use these our magic variables in Twig
	static private $magic_getters = array('transaction', 'civilViolations', 'disciplinaryViolations', 'previousSchools', 'degreeInfo', 'preenrollCourses', 'GREScores', 'languages', 'references', 'progress', 'personal', 'sections', 'options_country', 'options_gender', 'options_state', 'options_suffix', 'options_residencyStatus');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'transaction':
			 	return Entity::factory('Transaction')->first($this->transactionId);
		 	break;
		 	case 'civilViolations':
			 	return Entity::factory('CivilViolation')->whereEqual('applicationId', $this->id)->get();
		 	break;
		 	case 'disciplinaryViolations':
			 	return Entity::factory('DisciplinaryViolation')->whereEqual('applicationId', $this->id)->get();
		 	break;
		 	case 'previousSchools':
			 	return Entity::factory('PreviousSchool')->whereEqual('applicationId', $this->id)->get();
		 	break;
		 	case 'degreeInfo':
			 	return Entity::factory('Degree')->first($this->id);
		 	break;
		 	case 'preenrollCourses':
		 	break;
		 	case 'GREScores':
		 	break;
		 	case 'languages':
		 		return Entity::factory('Language')->whereEqual('applicationId', $this->applicationId)->get();
		 	break;
		 	case 'references':
		 	break;
		 	case 'progress':
		 	break;
		 	case 'personal':
		 		return Entity::factory('Personal')->whereEqual('applicationId', $this->applicationId)->first();
		 	break;
		 	case 'sections':
			 	return array('personal-information', 'international', 'educational-history', 'educational-objectives', 'letters-of-recommendation');
		 	break;
		 }

		// Options
		switch($name)
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
		}

		return parent::__get($name);
	}

	public function __isset($name)
	{
		if ( in_array($name, self::$magic_getters) ) 
		{
			return true;
		}
		return false;
	}

	static private function getOptionsFromDB($optionName)
	{
		$options = Database::query("SELECT * FROM %s Order BY id", 'OPTIONS_' . $optionName);
		// convert to associative array
		$result = array();
		foreach($options as $option) {
			$result[$option['value']] = $option['title'];
		}
		return $result;
	}

	/**
	 * Class Constructor
	 * 
	 * @return void
	 */
	function Application($data)
	{
		self::loadData($data);
		// @TODO: load data from APPLICATION_Primary
	}

	function submitWithPayment($paymentIsHappeningNow)
	{
		ApplicationManager::submitWithPayment($this, $paymentIsHappeningNow);
	}
}


class ApplicationManager extends Manager
{
	public static function getCivilViolations($applicationId)
	{
		Database::getFirst("SELECT * FROM APPLICATION_DATA_civil_violation WHERE transactionId = %d", $transactionId);
	}



	public static function getTransaction($transactionId)
	{
		Database::getFirst("SELECT * FROM APPLICATION_DATA_transaction WHERE transactionId = %d", $transactionId);
	}

	/** Get a new application object from current session data **/
	public static function getActiveApplication()
	{
		$id = check_ses_vars();
		if($id == 0) { 
			return NULL;
		} else {
			return ApplicationManager::getApplication($id);
		}
	}

	/** Get a new application object by passed in id **/
	public static function getApplication($applicantId)
	{
     	if( ! is_integer($applicantId) ) { ERROR::fatal("Passed in application identifier is not an integer."); }

     	// @TODO: get application from database
		$applicantDB = Database::getFirst("SELECT * FROM `Application` WHERE applicationId = %d", $applicantId);

       	return new Application( $applicantDB );

     	return new Application(array('applicationId'=>$applicantId));
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

	public function submitWithPayment($application, $paymentIsHappeningNow)
	{
		// Set payment method
		$payment_method = ($paymentIsHappeningNow) ? "PAYNOW" : "PAYLATER";
		$db->iquery("UPDATE applicants SET application_payment_method='%s' WHERE applicant_id=%d", $payment_method, $this->application_id );

		// Update database to show that application has been submitted
		$db->iquery("UPDATE `applicants` SET `has_been_submitted` = '1' WHERE `applicant_id` = %d LIMIT 1", $this->application_id);
	
		// Set application submit date
		$date = date("Y-m-d");
		$db->iquery("UPDATE `applicants` SET `application_submit_date` = '%s' WHERE `applicant_id` = %d LIMIT 1", $date, $this->application_id);		


		// Submitting with payment
		if( $paymentIsHappeningNow ) {
			//Generate transaction ID
			$trans_id = 'UMGRAD' . '*' . $this->applicant_id . '*' . time();
			
			//update database transaction_id
			$db->iquery("UPDATE applicants SET application_fee_transaction_number='%s' WHERE applicant_id=%d", $trans_id, $this->applicant_id);
			
			//Fetch application cost
			$app_cost_query = $db->query("SELECT application_fee_transaction_amount FROM applicants WHERE applicant_id=%d", $this->applicant_id);
			$app_cost = $app_cost_query[0][0];
			
			//Build request
			$data ='UPAY_SITE_ID=' . $GLOBALS["touchnet_site_id"].'&';
			$data.='UMS_APP_ID='   . $GLOBALS["touchnet_app_id"].'&';
			$data.='EXT_TRANS_ID=' . $trans_id.'&';
			$data.='AMT=' . $app_cost;
			
			$header = array("MIME-Version: 1.0","Content-type: application/x-www-form-urlencoded","Contenttransfer-encoding: text");
			
			//Execute request
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_URL, $GLOBALS["touchnet_proxy_url"]);
			curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
			curl_setopt($ch, CURLPROTO_HTTPS, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); //Max time to connect
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //Put result in variable
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			
			if ( ! $result = curl_exec($ch) ) {
				trigger_error(curl_error($ch));
			}
			curl_close($ch);
			
			/** print curl results **/
			
			//Insert base url into result after head tag so that redirects are correct
			$baseTag = "<base href='" . $GLOBALS['touchnet_proxy_url'] . "' >";
			$pos = strpos($result, '<head>') + strlen('<head>');
			$result = substr_replace($result, $baseTag, $pos, 0);
			
			print $result;						
		} else {
			// Payment is not happening now
			$db->iquery("UPDATE applicants SET application_fee_payment_status='N' WHERE applicants.applicant_id=%d", $user);
		
			// email
			$email = new Email();
			$email->loadFromTemplate('mailPayLater.email.php');
			$email->setDestinationEmail( $this->applicant->getEmail() );
			$email->sendEmail();

		}
	}


	public function emailReference($reference_id)
	{
		// email already sent?

		// Check if reference email has already been submitted
		$result = Database::query("SELECT is_submitting_online, is_request_sent FROM references WHERE reference_id=%d AND application_id = %d", $reference_id, $this->application_id);

		$request_sent = $result[0]['is_request_sent'] == 1;
		$isSubmittingOnline = $result[0]['is_submitting_online'];

		if( $request_sent || $isSubmittingOnline) {
			return "ERROR: Email already sent or not an online reference"; //email already sent or not an online reference
		}

		// send email
		$email = new EmailSystem();
		$email->loadFromTemplate('referenceRequest.email.php', 
								array('APPLICANT_FULL_NAME' => '',
									 'REFERENCE_FULL_NAME' => '',
									 'RECOMMENDATION_LINK' => '',
									 'GRADUATE_HOMEPAGE'   => ''));
		$email->setDestinationEmail( $reference_email );
		$email->sendEmail();

		// update database
		$db->iquery('UPDATE references SET is_request_sent = 1 WHERE reference_id = %d, application_id = %d', $reference_id, $this->application_id);
	}

	public function generateClientPDF() {
		self::generate_application_pdf("USER");
	}

	public function generateServerPDF() {
		self::generate_application_pdf("SERVER");
	}	

}

