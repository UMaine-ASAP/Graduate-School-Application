<?php

// Libraries
require_once __DIR__ . "/../libraries/database.php";
require_once __DIR__ . "/../libraries/MPDF52/mpdf.php";
require_once __DIR__ . "/../libraries/ordinalSuffix.php";

// Controllers
require_once __DIR__ . "/../controllers/ApplicationController.php";

// Models
require_once __DIR__ . "/Applicant.php";
require_once __DIR__ . "/ApplicationFieldReference.php";

// Application Subsections and repeatables
require_once __DIR__ . "/applicationComponents/Transaction.php";
require_once __DIR__ . "/applicationComponents/CivilViolation.php";
require_once __DIR__ . "/applicationComponents/DisciplinaryViolation.php";
require_once __DIR__ . "/applicationComponents/PreviousSchool.php";
require_once __DIR__ . "/applicationComponents/GRE.php";
require_once __DIR__ . "/applicationComponents/Degree.php";
require_once __DIR__ . "/applicationComponents/International.php";
require_once __DIR__ . "/applicationComponents/Language.php";
require_once __DIR__ . "/applicationComponents/Personal.php";
require_once __DIR__ . "/applicationComponents/Reference.php";
require_once __DIR__ . "/applicationComponents/ContactInformation.php";


/**
 * Application Type
 * 
 * Enumeration helper for different application types in Database
 */
class ApplicationType
{
	const DEGREE      = 1;
	const NONDEGREE   = 2;
	const CERTIFICATE = 3;
}


/**
 * Application Sections
 * 
 * Enumeration helper for different application sections
 */
class ApplicationSection
{
	const personalInformation     = 1;
	const international           = 2;
	const educationalHistory      = 3;
	const educationalObjectives   = 4;
	const lettersOfRecommendation = 5;

	public static $sectionDetails = array(
		self::personalInformation     => array('url'=>'/application/section/personal-information', 'name'=>'Personal Information'),
		self::international           => array('url'=>'/application/section/international', 'name'=>'International'),
		self::educationalHistory      => array('url'=>'/application/section/educational-history', 'name'=>'Educational History'),
		self::educationalObjectives   => array('url'=>'/application/section/educational-objectives', 'name'=>'Educational Objectives'),
		self::lettersOfRecommendation => array('url'=>'/application/section/letters-of-recommendation', 'name'=>'Letters of Recommendation')

		);
}


/**
 * Manages entire application data and business logic
 */
class Application extends Model
{
	protected static $tableName   = 'Application';
	protected static $primaryKeys = array('applicationId', 'applicantId');

 	// Register our available properties with Model.php
	protected $cache = array();
	protected static $availableProperties = array('typeCode', 'pretty_usState', 'pretty_birth_state', 'pretty_waiveReferenceViewingRights', 'startInfo', 'placeOfBirth', 'fullName', 'hasBeenSubmitted', 'degree', 'international', 'type', 'transaction', 'civilViolations', 'disciplinaryViolations', 'previousSchools', 'degreeInfo', 'preenrollCourses', 'GREScores', 'languages', 'references', 'progress', 'personal', 'sections', 'status', 'cost');


	/**
	 * Magic Getter
	 * 
	 * Gets the data for special properties
	 * 
	 * @return any
	 */
	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'applicant':
		 		return ApplicantController::getActiveApplicant();
		 	case 'type':
				$result = Database::getFirst('SELECT name FROM APPLICATION_Type WHERE applicationTypeId = %d', $this->applicationTypeId);
		 		return $result['name'];
		 	case 'typeCode':
		 	{
		 		// Mainestreet type code
		 		switch($this->applicationTypeId) 		
		 		{
		 			case ApplicationType::DEGREE:
		 				return "DEG";
		 			case ApplicationType::NONDEGREE:
		 				return "NDG";
		 			case ApplicationType::CERTIFICATE:
		 				return "CER";
		 			default:
		 				error_log("Application typecode not found");
		 				break;
		 		}
		 		return "";
		 	}
		 	case 'transaction':
		 		if ($this->transactionId == 0) {
		 			return null;
		 		}
			 	return Model::factory('Transaction')->whereEqual('transactionId', $this->transactionId)->first();
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
		 	case 'personal':
		 		if( array_key_exists($name, $this->cache)) {
		 			return $this->cache[$name];
		 		} else {
		 			return $this->cache[$name] = Model::factory('Personal')->whereEqual('applicationId', $this->applicationId)->first();
		 		}
		 	break;
		 	case 'fullName':
				return $this->personal->fullName;
			break;
			case 'startInfo':
				return $this->startSemester . ' ' . $this->startYear;
			break;
			case 'placeOfBirth':
			 	return $this->birth_city . ' ' . $this->pretty_birth_state . ' ' . $this->birth_country;
			break;
		 	case 'pretty_waiveReferenceViewingRights':
		 		return ($this->waiveReferenceViewingRights == 1) ? "has" : "has not";
		 	break;
			case 'pretty_birth_state':
		 		// International choice should return blank
		 		if ($this->birth_state == 'IT') {
		 			return '';
		 		}
		 		return $this->birth_state;
		 	break;
		 	case 'pretty_usState':
		 		// International choice should return blank
		 		if ($this->usState == 'IT') {
		 			return '';
		 		}
		 		return $this->usState;
		 	break;
		 	case 'sections':
		 		switch($this->applicationTypeId)
		 		{
		 			case ApplicationType::DEGREE: case ApplicationType::CERTIFICATE:
			 			return array('personal-information', 'international', 'educational-history', 'educational-objectives', 'letters-of-recommendation');
		 			break;
		 			case ApplicationType::NONDEGREE:
				 		return array('personal-information', 'international', 'educational-history', 'educational-objectives');
		 			break;
		 		}
		 	break;
		 	case 'status':
		 		if( $this->hasBeenSubmitted == 1)
		 		{
		 			return 'Submitted';
		 		} else {
		 			return 'In Progress';
		 		}
		 	break;
		 	case 'submissionFolderName':
				$personal           = $this->personal;		 	
				$id                 = $this->id;
				$filteredFamilyName = InputSanitation::replaceNonAlphanumeric( $personal->familyName );
				$filteredGivenName  = InputSanitation::replaceNonAlphanumeric( $personal->givenName );

		 		return "$id\_$filteredFamilyName\_$filteredGivenName\_/";
		 	break;

		 	case 'fileNamePDF':
				$personal           = $this->personal;
				$id                 = $this->id;
				$filteredFamilyName = InputSanitation::replaceNonAlphanumeric( $personal->familyName );
				$filteredGivenName  = InputSanitation::replaceNonAlphanumeric( $personal->givenName );
		 
		 		return "UMGradApp_$id\_$filteredFamilyName\_$filteredGivenName.pdf";
		 	break;
		 	case 'displayNamePDF':
				$filteredFamilyName = InputSanitation::replaceNonAlphanumeric( $personal->familyName );
				$filteredGivenName  = InputSanitation::replaceNonAlphanumeric( $personal->givenName );

				return "UMGradApp_$filteredGivenName\_$filteredFamilyName.pdf";

		 	break;
		 	case 'fileNameResume':
		 		return $this->id . "_resume";
		 	break;
		 	case 'fileNameEssay':
		 		return $this->id . "_essay";
		 	break;
		 	case 'cost':
		 		// Split on application type
		 		switch($this->applicationTypeId)
		 		{
		 			case ApplicationType::DEGREE:
		 				// Check if another application has been submitted for the same year & semester
		 				$result = Database::query("SELECT applicationId FROM Application WHERE startYear=%d AND startSemester=%d AND applicationId<>%d", $this->startYear, $this->startSemester, $this->id);
		 				if( $result != array() )
		 				{
			 				return 10;
		 				}

		 				return 65;
		 			break;
		 			case ApplicationType::NONDEGREE:
		 				return 35;
		 			break;
		 			case ApplicationType::CERTIFICATE:
		 				return 35;
		 			break;
		 		}
		 	break;
		 }

		return parent::__get($name);

	}

	// override default model behavior -> we need to check for applicant id too!!!
	public function delete()
	{
		Database::iquery("DELETE FROM Application WHERE applicantId=%d AND applicationId=%d LIMIT 1", $this->applicantId, $this->id);
	}

	// override default model behavior -> we need to make sure we own the application!!!
	public function save()
	{
		// Just to be safe, check for ownership
		if( !ApplicationController::doesActiveUserOwnApplication($this->id) )
		{
			return;
		}

		// save application
		$this->lastModified = Date('Y-m-d H:i:s');
		parent::save();
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

				$result = array(''=>'- None -');
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
				$typeDB = Database::query("SELECT * FROM APPLICATION_Type");
				foreach ($typeDB as $type) {
					$result[ $type['applicationTypeId'] ] = $type['name'];
				}
				return $result;
			break;
		}
		return null; // nothing found
	}


	/**
	 * Email All References
	 * 
	 * sends emails to all references
	 * 
	 * @return void
	 */
	public function emailAllReferences()
	{
		$references = Model::factory('Reference')->whereEqual('applicationId', $this->applicationId)->get();
		foreach ($references as $reference) {
			$this->emailReference( $reference->id );
		}
	}


	/**
	 * Email Reference
	 * 
	 * sends email to a single reference provided that reference is an online reference and an email hasn't already been sent
	 * 
	 * @param    int    reference Id
	 * 
	 * @return    string    Empty string if successful, otherwise error message
	 */
	public function emailReference($referenceId)
	{
		$reference = Reference::getWithId($referenceId);
		if ( is_null($reference) ) {
			return "ERROR: reference doesn't exist!";
		}

		// Check if reference email has already been submitted
		if( $reference->requestHasBeenSent || ! $reference->isSubmittingOnline) {
			return "ERROR: Email already sent or not an online reference";
		}

		// send email
		$email = new Email();
		$email->loadFromTemplate('referenceRequest.email.php', 
								array('{{APPLICANT_FULL_NAME}}' => $this->fullName,
									 '{{REFERENCE_FULL_NAME}}' => $reference->fullName,
									 '{{RECOMMENDATION_LINK}}' => $GLOBALS['WEBROOT'] . '/recommendation/' . $this->hashReference . '/' . $reference->id,
									 '{{GRADUATE_HOMEPAGE}}'   => $GLOBALS['GRADUATE_HOMEPAGE']));
		$email->setDestinationEmail( $reference->email );
		$email->sendEmail();

		// update database
		$reference->requestHasBeenSent = 1;
		$reference->save();
	}


	/**
	 * Get Reference with Id
	 * 
	 * @param    int    reference id
	 * 
	 * @return     object     The associated reference or null if not found
	 */
	public function getReferenceWithId($referenceId)
	{
		$result = null;
		foreach ($this->references as $reference) {
			if($reference->id == $referenceId)
			{
				$result = $reference;
				break;
			}
		}
		return $result;
	}


	/**
	 * Check Required Fields
	 * 
	 * Checkes whether required fields are filled in or not. Returns an error of messages for missing fields
	 * 
	 * @return    array    Array of error messages for missing required fields
	 */
	public function checkRequiredFields()
	{
		$errors = array();

		/* ---- Test database fields ---- */
		foreach ($GLOBALS['databaseFields'] as $sectionName => $fields) {
			foreach ($fields as $fieldName=>$field) {
				if( isset($field['isRequired']) && $field['isRequired'] && in_array($this->applicationTypeId, $field['exceptRequirementFromApplicationTypes']))
				{
					// Check if error occurred
					$fieldReference = new ApplicationFieldReference($fieldName);
					$hasError = false;
					if( $fieldReference->value() == '')
					{
						$hasError = true;
						$errors[] = array('message' => $field['requiredMessage'], 'section'=>$sectionName);
					}

					// check any additional requirements
					if( isset($field['requirements']) )
					{
						foreach ($field['requirements'] as $requirement) {
							if($hasError) break;
							switch ($requirement) {
								case 'nonzero':
									if($fieldReference->value() == 0)
									{
										$hasError = true;
										$errors[] = array('message' => $field['requiredMessage'], 'section'=>$sectionName);
									}
									break;
								
								default:
									break;
							}
						}
					}
				}
			}
		}

		/* ---- General Tests ---- */

		// Any reference marked as online must have an email
		foreach ( array_reverse($this->references) as $reference) {
			if ($reference->isSubmittingOnline && $reference->email == '') {
				$errors[] = array('message' => "If your reference will submit online, you must enter an Email Address", 'section'=>ApplicationSection::lettersOfRecommendation);
			}
			
			// references must inclue first name
			if ($reference->firstName == '' && $this->applicationTypeId == ApplicationType::DEGREE) {
				$errors[] = array('message' => "You did not enter a First Name for your ". addOrdinalNumberSuffix($reference->id) . " reference", 'section'=>ApplicationSection::lettersOfRecommendation);
			}

			// references must inclue first last
			if ($reference->lastName == '' && $this->applicationTypeId == ApplicationType::DEGREE) {
				$errors[] = array('message' => "You did not enter a last Name for your ". addOrdinalNumberSuffix($reference->id) . " reference", 'section'=>ApplicationSection::lettersOfRecommendation);
			}
		}


		/* ---- Application specific test ---- */
		switch ($application->applicationTypeId) {
			case ApplicationType::DEGREE:
				// at least 3 references must exist with first and last names
				if( count($this->references) < 3 )
				{
					$errors[] = array('message' => "For a degree application, you must provide 3 references", 'section'=>ApplicationSection::lettersOfRecommendation);
				}
				break;
			case ApplicationType::CERTIFICATE:
				//@TODO: require recommendations for BUA (2 recommendations)
				//@TODO: require recommendation for GIS (1 recommendation)
				break;			
		}

		return $errors;
	}


	/**
	 * Submit an application with or without payment now
	 * 
	 * @param    bool    Whether payment is happening now or not
	 * 
	 * @return    void
	 */
	public function submitWithPayment($paymentIsHappeningNow)
	{
		// Create transaction
//		Database::iquery("INSERT INTO APPLICATION_Transaction(transactionId) VALUES (NULL)");

		Database::iquery("INSERT INTO APPLICATION_Transaction(transactionId) VALUES (NULL)");
		$transactionId = Database::getFirst("SELECT LAST_INSERT_ID() as transactionId FROM APPLICATION_Transaction");
		$this->transactionId = $transactionId['transactionId'];
		$this->save();

		$transaction = $this->transaction;
		$transaction->amount = $this->cost;

		$transaction->save();


		// Set payment method
		$transaction->isPayingOnline = ($paymentIsHappeningNow) ? 1 : 0;
		$transaction->save();

		// Update database to show that application has been submitted
		$this->hasBeenSubmitted = 1;
		$this->submittedDate = date("m/d/Y");
		$this->save();
		
		$this->emailAllReferences();
		$this->_generateFinalPDF();

		// Submitting with payment
		if( $paymentIsHappeningNow ) {
			//Generate transaction ID
			$externalTransactionId = 'UMGRAD' . '*' . $this->id . '*' . time();
			
			//update database transaction_id			
			$transaction->externalTransactionId = $externalTransactionId;
			$transaction->save();
			
			//Build request
			$data = array( 
				'UPAY_SITE_ID' => $GLOBALS["touchnet_site_id"],
				'UMS_APP_ID'   => $GLOBALS["touchnet_app_id"],
				'EXT_TRANS_ID' => $externalTransactionId,
				'AMT'          => $transaction->amount);
			
			$header = array("MIME-Version: 1.0","Content-type: application/x-www-form-urlencoded","Contenttransfer-encoding: text");

			//Execute request
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_URL, $GLOBALS["touchnet_proxy_url"]);
			curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data) ); 
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

			// Send mail pay later email
			$email = new Email();
			$email->loadFromTemplate('mailPayLater.email.php', array('{{APPLICATION_FEE}}'=>$transaction->amount));
			$email->setDestinationEmail( $this->applicant->loginEmail );
			$email->sendEmail();
		}
	}


	/**
	 * Build PDF (Internal Method)
	 * 
	 * Helper function for generating the pdf
	 * 
	 * @return    mpdf    An mpdf object with the application pdf loaded
	 */
	private function _buildPDF()
	{
		// render html
		$app = \Slim\Slim::getInstance();
		$app->view()->appendData(array('application' => $this));
		$html = $app->view()->render('application/applicationPDF.twig');

		// convert html to pdf
		$mpdf = new mPDF();
		$mpdf->AddPage();
		$mpdf->WriteHTML($html);
		return $mpdf;		
	}


	/**
	 * Display PDF
	 * 
	 * Generates and sends to the http client the application as a pdf
	 * 
	 * @return void
	 */
	public function displayPDF() {
		$this->_buildPDF()->Output( $this->displayNamePDF, 'D');
	}


	/**
	 * Generate Final PDF (Internal Method)
	 * 
	 * Renders the application pdf and stores on the server
	 * 
	 * @return void
	 */
	private function _generateFinalPDF() {

		$pdfFullPath = $GLOBALS['completed_pdfs_path'] . $this->fileNamePDF;

		$this->_buildPDF()->Output($pdfFullPath);

		chmod($pdfFullPath, 0664);
	}

}
