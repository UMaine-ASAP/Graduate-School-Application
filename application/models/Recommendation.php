<?php

require_once __DIR__ . '/../libraries/MPDF52/mpdf.php';

require_once __DIR__ . '/../controllers/ApplicationController.php';

/**
 * A single recommendation filled out by a reference
 */
class Recommendation extends Model
{
	protected static $tableName = 'APPLICATION_Recommendation';
	protected static $primaryKeys = array('referenceId', 'applicationId');


	protected static $availableProperties = array('application', 'fullName', 'contactInformation', 'requestHasBeenSent', 'isSubmittingOnline', 'pretty_academicAbility', 'pretty_motivation', 'pretty_programReuse', 'pretty_lifetime');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'fullName':
		 		return $this->firstName . ' ' . $this->lastName;
		 	break;
		 	case 'filename':
		 		$appId             = $this->applicationId;
		 		$refId             = $this->referenceId;
				$filteredLastName  = InputSanitation::replaceNonAlphanumeric( $this->lastName );		 	
				$filteredFirstName = InputSanitation::replaceNonAlphanumeric( $this->firstName );		 	
				return "UMGradRec_App_$appId\_Rec_$refId\_$filteredLastName\_$filteredFirstName.pdf";
			break;
			case 'application':
				return ApplicationController::getApplicationById((int)$this->applicationId);
			break;
			case 'pretty_academicAbility':
				if($this->academicAbility == -1) return '';
				$potentialScores = Recommendation::getOption('options_scores');
				return $potentialScores[ $this->academicAbility ];		
			break;
			case 'pretty_motivation':
				if($this->motivation == -1) return '';
				$potentialScores = Recommendation::getOption('options_scores');
				return $potentialScores[ $this->motivation ];		
			break;
			case 'pretty_programReuse':
				if($this->programReuse == -1) return '';
				$potentialScores = Recommendation::getOption('options_reuse');
				return $potentialScores[ $this->programReuse ];
			break;
			case 'pretty_lifetime':
				if($this->lifetime == -1) return '';
				$potentialScores = Recommendation::getOption('options_lifetime');
				return $potentialScores[ $this->lifetime ];
			break;
		 }

		 return parent::__get($name);
	}

	protected static $availableOptions = array('options_scores', 'options_scores_woNumbers', 'options_reuse', 'options_lifetime');	
	public static function getOption($optionName)
	{
		switch($optionName)
		{
			case 'options_scores':
				return array(  '1' => '1 - Below Average',
							'2' => '2 - Average',
							'3' => '3 - Somewhat Above Average',
							'4' => '4 - Good',
							'5' => '5 - Unusual',
							'6' => '6 - Outstanding',
							'7' => '7 - Truly Exceptional',
							'8' => 'Unable to Judge');
			break;
			case 'options_scores_woNumbers':
				return array(  '1' => 'Below Average',
							'2' => 'Average',
							'3' => 'Somewhat Above Average',
							'4' => 'Good',
							'5' => 'Unusual',
							'6' => 'Outstanding',
							'7' => 'Truly Exceptional',
							'8' => 'Unable to Judge');
			break;
			case 'options_reuse':
				return array('All UM Graduate Programs', 'The Following UM Graduate Programs:');
			break;
			case 'options_lifetime':
				return array( 'The current academic year', '2 years', 'Indefinitely');
			break;

		}
		return null; // nothing found
	}


	/**
	 * Creates a new recommendation
	 * 
	 * @param    int    The id of the application tied to this recommendation
	 * @param    int    The id of the reference tied to this recommendation
	 * 
	 * @return    object     A New Recommendation Object
	 */
	public static function create($applicationId, $referenceId)
	{

		Database::iquery("INSERT INTO APPLICATION_Recommendation(`referenceId`, `applicationId`) VALUES (%d, %d)", $referenceId, $applicationId);

		$result = Database::getFirst("SELECT * FROM APPLICATION_Recommendation WHERE referenceId = %d AND applicationId = %d", $referenceId, $applicationId);

		$entityName = get_called_class();
		$entity = new $entityName($entityName);
		$entity->loadFromDB($result);
		return $entity;
	}


	/**
	 * Get a recommendation
	 * 
	 * @param    int    The id of the application tied to this recommendation
	 * @param    int    The id of the reference tied to this recommendation
	 * 
	 * @return    object     The associated Recommendation Object if exists, null otherwise
	 */
	public static function retrieve($applicationId, $referenceId)
	{
		$dbObject = Database::getFirst("SELECT * FROM APPLICATION_Recommendation WHERE applicationId = %d AND referenceId = %d", $applicationId, $referenceId);

		$recommendation = Model::factory('Recommendation');
		$recommendation->loadFromDB($dbObject);
		return $recommendation;
	}
	
	/**
	 * Send Thank You Email
	 * 
	 * Emails the recommender a thank you email
	 * 
	 * @return void
	 */
	public function sendThankYouEmail()
	{
		$email = new Email();
		$applicationPersonal = $this->application->personal;
		$email->loadFromTemplate('referenceThankYou.email.php', 
									array('{{APPLICANT_FULL_NAME}}' => $applicationPersonal->fullName,
									'{{APPLICANT_GIVEN_NAME}}'      => $applicationPersonal->givenName));
		$email->setDestinationEmail( $this->email );
		$email->sendEmail();
	}


	/**
	 * Build PDF
	 * 
	 * Generates the final pdf and saves on server
	 * 
	 * @return void
	 */
	public function buildPDF()
	{
		//Set created date
		$this->dateCreated = date("m-d-Y");
		$this->save();

		// Get raw recommendation html
		$app = \Slim\Slim::getInstance();
		$app->view()->appendData(array('application' => $this->application, 'recommendation' => $this));
		$pdfHtml = $app->view()->render('letterOfRecommendation/recommendationPDF.twig');

		
		// convert html to pdf
		$mpdf = new mPDF();
		$mpdf->WriteHTML( utf8_encode($pdfHtml) );
		
		// Save pdf
		$fullRecommendationPath = $GLOBALS['recommendations_path'] . $this->filename;
		$mpdf->Output($fullRecommendationPath);
		chmod($fullRecommendationPath, 0664);

	}	
}