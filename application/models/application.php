<?php

// Libraries
require_once __DIR__ . "/../libs/database.php";
require_once __DIR__ . "/../libs/corefuncs.php";

// Models
require_once __DIR__ . "/Applicant.php";

// Application Subsections and repeatables
require_once __DIR__ . "/applicationComponents/Transaction.php";
require_once __DIR__ . "/applicationComponents/CivilViolation.php";
require_once __DIR__ . "/applicationComponents/DisciplinaryViolation.php";
require_once __DIR__ . "/applicationComponents/PreviousSchool.php";
require_once __DIR__ . "/applicationComponents/GRE.php";
require_once __DIR__ . "/applicationComponents/Degree.php";
require_once __DIR__ . "/applicationComponents/Progress.php";
require_once __DIR__ . "/applicationComponents/International.php";
require_once __DIR__ . "/applicationComponents/Language.php";
require_once __DIR__ . "/applicationComponents/Personal.php";
require_once __DIR__ . "/applicationComponents/Reference.php";
require_once __DIR__ . "/applicationComponents/ContactInformation.php";

class ApplicationType
{
	const DEGREE      = 1;
	const NONDEGREE   = 2;
	const CERTIFICATE = 3;
}

class Application extends Model
{
	protected static $tableName   = 'Application';
	protected static $primaryKeys = array('applicationId', 'applicantId');

	protected $cache = array();
	protected static $availableProperties = array('hasBeenSubmitted', 'degree', 'international', 'type', 'transaction', 'civilViolations', 'disciplinaryViolations', 'previousSchools', 'degreeInfo', 'preenrollCourses', 'GREScores', 'languages', 'references', 'progress', 'personal', 'sections', 'status');

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
		 	case 'hasBeenSubmitted':

			 	return ( parent::get('hasBeenSubmitted') == 1 );
		 	break;
		 	case 'GREScores':
			 	return Model::factory('GRE')->whereEqual('applicationId', $this->id)->get();
		 	break;
		 	case 'international':
		 		return Model::factory('International')->whereEqual('applicationId', $this->applicationId)->first();
		 	break;
		 	case 'languages':
		 		return Model::factory('Language')->whereEqual('applicationId', $this->applicationId)->get();
		 	break;
		 	case 'references':
		 		return Model::factory('Reference')->whereEqual('applicationId', $this->applicationId)->get();
		 	break;
		 	case 'progress':
		 	break;
		 	case 'personal':
		 		if( array_key_exists($name, $this->cache)) {
		 			return $this->cache[$name];
		 		} else {
		 			return $this->cache[$name] = Model::factory('Personal')->whereEqual('applicationId', $this->applicationId)->first();
		 		}
		 	break;
		 	case 'fullName':
		 		$personal = $this->personal;
				return $personal->givenName . " " . $personal->middleName . " " . $personal->familyName;
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

	public function emailAllReferences()
	{
		$references = Database::query("SELECT * FROM APPLICATION_Reference WHERE applicationId = %d", $this->applicationId);
		foreach ($references as $reference) {
			$this->emailReference( (int) $reference['referenceId']);
		}
	}

	public function emailReference($referenceId)
	{
		$reference = Reference::getWithId($referenceId);

		// Check if reference email has already been submitted
		if( $reference->requestHasBeenSent || ! $reference->isSubmittingOnline) {
			return "ERROR: Email already sent or not an online reference";
		}

		$applicant = Reference::getWithId($referenceId);

		// send email
		$email = new EmailSystem();
		$email->loadFromTemplate('referenceRequest.email.php', 
								array('APPLICANT_FULL_NAME' => $this->fullName,
									 'REFERENCE_FULL_NAME' => $reference->fullName,
									 'RECOMMENDATION_LINK' => $GLOBALS['WEBROOT'] . '/reference/?q=' . $this->applicationHash,
									 'GRADUATE_HOMEPAGE'   => $GLOBALS['GRADUATE_HOMEPAGE']));
		$email->setDestinationEmail( $reference_email );
		$email->sendEmail();

		// update database
		$reference->requestHasBeenSent = 1;
		$reference->save();
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
			$app_cost       = $app_cost_query[0][0];
			
			//Build request
			$data ='UPAY_SITE_ID=' . $GLOBALS["touchnet_site_id"].'&';
			$data .='UMS_APP_ID='   . $GLOBALS["touchnet_app_id"].'&';
			$data .='EXT_TRANS_ID=' . $trans_id.'&';
			$data .='AMT=' . $app_cost;
			
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

	public function generateClientPDF() {
		self::generate_application_pdf("USER");
	}

	public function generateServerPDF() {
		self::generate_application_pdf("SERVER");
	}	


}



