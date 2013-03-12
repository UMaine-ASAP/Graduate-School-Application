<?php



		if ($_POST['submit'] == "Submit Recommendation") {
			require_once "../libriaries/database.php";
			require_once "../libriaries/MPDF52/mpdf.php";

			
			// Scores
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


			// Get HTML of recommendation output
			
			$app = \Slim\Slim::getInstance();
			$app->view()->appendData(array('application' => $this));
			$html = $app->view()->render('letterOfRecommendation/recommendationPDF.twig', $replace_data);


			$pdftitle = "UMGradRec_". $application->id ."_".$recommender_lastName.$recommender_firstName."_". date("m-d-Y") .".pdf";

			//Update Database with filename
			if( isset($_GET['ref_id']) ) {
				$ref_id = $_GET['ref_id'];

				$updateQuery = "UPDATE applicants SET ";

				if( $ref_id == 'reference1' || $ref_id == 'reference2' || $ref_id == 'reference3') {
					$updateQuery .= $ref_id . "_filename = '%s'  WHERE applicant_id=%i";
				}
				$db->iquery($updateQuery, $pdftitle, $id);
			} else {
				$updateQuery = "UPDATE extrareferences SET reference_filename = '%s' WHERE applicant_id=%i and extrareferences_id = %d";
				$db->iquery($updateQuery, $pdftitle, $id, $_GET['xref_id']);
			}

			
			// convert html to pdf and save
			$mpdf = new mPDF();
			$mpdf->WriteHTML( utf8_encode($pdfhtml) );
			
			$fullRecommendationPath = $GLOBALS['recommendations_path'] . $pdftitle;
			$mpdf->Output($fullRecommendationPath);
			chmod($fullRecommendationPath, 0664);

			


	}
}