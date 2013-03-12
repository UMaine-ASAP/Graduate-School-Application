<?php

require_once __DIR__ . '/../libraries/mpdf54/mpdf.php';

/**
 * A single recommendation filled out by a reference
 */
class Recommendation extends Model
{
	protected static $tableName = 'APPLICATION_Recommendation';
	protected static $primaryKeys = array('recommendationId', 'referenceId', 'applicationId');


	protected static $availableProperties = array('fullName', 'contactInformation', 'requestHasBeenSent', 'isSubmittingOnline');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'fullName':
		 		return $this->firstName . ' ' . $this->lastName;
		 	break;
		 	case 'filename':
				return "UMGradRec_". $this->applicationId ."_".$this->lastName.$this->firstName."_". $this->dateCreated .".pdf";
			break;
		 }

		 return parent::__get($name);
	}

		
	public function sendThankYouEmail($application)
	{
		$email = new Email();
		$email->loadFromTemplate('referenceThankYou.email.php', 
							array('{{APPLICANT_FULL_NAME}}' => $application->personal->fullName,
								 '{{REFERENCE_GIVEN_NAME}}' => $application->personal->givenName));
		$email->setDestinationEmail( $this->email );
		$email->sendEmail();
	}

	public function buildPDF()
	{
		$potentialScores = Reference::getOption('options_scores');

		// Get ability description from score
		$abilityDescription = '';
		if ( isset($_POST['ability']) ) {
			$abilityDescription = $potentialScores[ $_POST['ability'] ];
		}

		// Get motivation description from score
		$motiviationDescription = '';
		if ( isset($_POST['motiviation']) ) {
			$motiviationDescription = $potentialScores[ $_POST['motiviation'] ];
		}

		// Get reuse description from score
		$reuse_value      = ( isset($_POST['recommendation-reuse']) ) ? $_POST['recommendation-reuse'] : -1;
		$reuseDescription = "";
		switch( $reuse_value ) {
			case 'all':
			$reuse = "All UM Graduate Programs";
			break;
			case 'select':
			$reuse = "The Following UM Graduate Programs:";
			break;
		}

		// Get lifespan description from score
		$lifespan_value      = ( isset($_POST['recommendation-lifespan']) ) ? $_POST['recommendation-lifespan'] : -1;
		$lifespanDescription = "";

		switch( $lifespan_value ) {
			case '1year':
			$lifespan = 'The current academic year';
			break;
			case '2years':
			$lifespan = '2 years';
			break;
			case 'any':
			$lifespan = 'Indefinitely';
			break;
		}

		// Get reuse programs
		$reuse_programs = isset($_POST['recommendation-reuse-programs'] ) ? $_POST['recommendation-reuse-programs'] : '';

		$recommender_firstName = $_POST['rfname'];
		$recommender_lastName  = $_POST['rlname'];


		$replace_data = Array(
			'RECOMMENDATION_DATE_RECEIVED' => date("m-d-Y"),

			'APPLICANT_NAME'                    => $_POST["applicant_name"],
			'APPLICANT_DOB'                     => $applicant_data['date_of_birth'],
			'APPLICANT_FORMER_NAME'             => $applicant_data['alternate_name'],
			'APPLICANT_EMAIL'                   => $_POST['uemail'],
			'STATUS_WAIVED_VIEW_RECOMMENDATION' => $_POST['waived'],

			'RECOMMENDER_NAME'     => $recommender_firstName . " " . $recommender_lastName,
			'RECOMMENDER_TITLE'    => $_POST['rtitle'],
			'RECOMMENDER_EMPLOYER' => $_POST['remployer'],
			'RECOMMENDER_EMAIL'    => $_POST['remail'],
			'RECOMMENDER_PHONE'    => $_POST['rphone'],

			'RECOMMENDATION_ABILITY'    => $ability,
			'RECOMMENDATION_MOTIVATION' => $motivation,

			'RECOMMENDATION_REUSE'          => $reuse,
			'RECOMMENDATION_REUSE_PROGRAMS' => $reuse_programs,
			'RECOMMENDATION_LIFESPAN'       => $lifespan,
			'RECOMMENDATION_TEXT'           => $_POST['essay']
		);

		// Get raw recommendation html
		$app = \Slim\Slim::getInstance();
		$app->view()->appendData(array('application' => $this));
		$html = $app->view()->render('letterOfRecommendation/recommendationPDF.twig', $replace_data);

		//Set created date
		$this->dateCreated = date("m-d-Y");
		$this->save();
		
		// convert html to pdf
		$mpdf = new mPDF();
		$mpdf->WriteHTML( utf8_encode($pdfhtml) );
		
		// Save pdf
		$fullRecommendationPath = $GLOBALS['recommendations_path'] . $this->filename;
		$mpdf->Output($fullRecommendationPath);
		chmod($fullRecommendationPath, 0664);

	}	
}