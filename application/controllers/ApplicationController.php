<?php

require_once __DIR__ . "/ApplicantController.php";
require_once __DIR__ . "/../models/Application.php";

class ApplicationController
{

	/**
	 * Create Application
	 * 
	 * Creates an application of the specified type
	 * 
	 * @return Application 	The new application, null if unsuccessful
	 */
	public static function createApplication($typeId)
	{
		// double check type is valid
		$types = Application::getOption('options_type');

		if( array_key_exists($typeId, $types) )
		{
			// ensure applicant is logged in
			if( !ApplicantController::applicantIsLoggedIn() ) {
				return null;
			}
			$applicant = ApplicantController::getActiveApplicant();
			$result    = Database::getFirst("SELECT applicationId FROM Application ORDER BY applicationId DESC");
			$applicationId  = $result['applicationId'] + 1;


			Database::iquery("INSERT INTO Application(applicationId, applicantId, applicationTypeId) VALUES (%d, %d, %d)", $applicationId, $applicant->id, $typeId);

			// Get application
			$result = Database::getFirst("SELECT * FROM Application WHERE applicantId = %d AND applicationId = %d", $applicant->id, $applicationId);

			if($result == array())
			{
				return null;
			}
			$application = new Application($result);


			// Build application type specific sub-sections
			switch( $application->type )
			{
				case 'Degree':
					Database::iquery("INSERT INTO APPLICATION_International(applicationId) VALUES (%d)", $applicationId);
					Database::iquery("INSERT INTO APPLICATION_Degree(applicationId) VALUES (%d)", $applicationId);
				break;
				case 'Non-Degree':
				break;
				case 'Certificate':
				break;
				default:
					throw new Exception("Application Type $application->type not found when deleting application");
				break;

			}

			// Create common sub-sections
			Database::iquery("INSERT INTO APPLICATION_Primary(applicationId) VALUES (%d)", $applicationId);

			return $application;

		} else {
			return null;
		}
	}

	public static function deleteApplication($applicationId)
	{
		if( !ApplicantController::applicantIsLoggedIn() )
		{
			return null;
		}

		$application = ApplicationController::getApplication( (int) $applicationId);

		// different application types require different data to be deleted. 

		switch( $application->type )
		{
			case 'Degree':
				$application->international->delete();
				$application->degree->delete();				
			break;
			case 'Non-Degree':
			break;
			case 'Certificate':
			break;
			default:
				throw new Exception("Application Type $application->type not found when deleting application");
			break;
		}

		// Complete the process
		$application->personal->delete();
		$application->delete();
	}

	/** Get a new application object from current session data **/
	public static function getActiveApplication()
	{
		// Get id
		$id = $_SESSION['active-application'];

		return ApplicationController::getApplication($id);
	}

	/** Get a new application object by passed in id **/
	public static function getApplication($applicationId)
	{
     	if( ! is_integer($applicationId) ) { ERROR::fatal("Passed in application identifier is not an integer."); }

		// ensure applicant is logged in
		$applicant = ApplicantController::getActiveApplicant();
		if( $applicant == null )
		{
			return null;
		}

		// make sure the user owns the application
		$applicationDB = Database::getFirst("SELECT * FROM `Application` WHERE applicationId = %d AND applicantId = %d", $applicationId, $applicant->id);

       	return new Application( $applicationDB );
	}

	public static function doesActiveUserOwnApplication($applicationId)
	{
		$applicant = ApplicantController::getActiveApplicant();
		$result    = Database::getFirst("SELECT * FROM `Application` WHERE applicationId = %d AND applicantId = %d", $applicationId, $applicant->id);
		return ( $result != array() ); 
	}

	public static function allMyApplications()
	{
		$applicant = ApplicantController::getActiveApplicant();

		// Ensure applicant is valid
		if ($applicant == null)
		{
			return null;
		}

		// Retrieve applications
		$applicationsDB = Database::query("SELECT * FROM `Application` WHERE applicantId = %d ORDER BY lastModified DESC", $applicant->id);
		
		// ensure data exists
		if($applicationsDB == array())
		{
			return array();
		}

		// build results
		$result = array();
		foreach ($applicationsDB as $applicationDBData) {
			$result[] = new Application($applicationDBData);
		}
		return $result;
	}

	/**
	 * Set Active Application
	 * 
	 * Sets the application currently being edited
	 * 
	 * @return bool 	whether operation was successful or not
	 */
	public static function setActiveApplication($applicationId)
	{
		// make sure this is an application of the current user
		$applicant   = ApplicantController::getActiveApplicant();
		$application = Database::getFirst("SELECT * FROM `Application` WHERE applicantId = %d AND applicationId = %d", $applicant->id, $applicationId);
		
		if( $application == array() ) {
			return false;
		}

		if( !isset($_SESSION) ) 
		{ 
			session_start(); 
		}
		$_SESSION['active-application'] = $applicationId;
		return true;
	}


	// ********************************************* //
	// * Unused

	public function hasBeenSubmitted()
	{
		$result = $this->db->query('SELECT has_been_submitted FROM applicants WHERE applicant_id=%d', $this->application_id);
		if( !is_array($result) )
		{
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

	public static function getCivilViolations($applicationId)
	{
		Database::getFirst("SELECT * FROM APPLICATION_DATA_civil_violation WHERE transactionId = %d", $transactionId);
	}


	
	public static function getTransaction($transactionId)
	{
		Database::getFirst("SELECT * FROM APPLICATION_DATA_transaction WHERE transactionId = %d", $transactionId);
	}


}