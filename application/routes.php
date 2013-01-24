<?php
/**
 * Graduate Application Version 2.0
 * @Author Tim Westbaker
 * @created 10-16-12
 */

session_cache_limiter(false);
session_start();

// Base Classes
require_once "models/backbone/Entity.php";
require_once "models/backbone/Manager.php";

// Models
require_once 'models/application.php';
require_once 'models/applicant.php';
require_once 'models/errorTracker.php';

// Libraries
require_once 'libs/email.php';

require_once 'libs/Slim/Slim.php';
Slim\Slim::registerAutoloader();

require_once 'libs/Twig/Autoloader.php';
Twig_Autoloader::register();

require_once 'libs/Slim/Twig.php';

// Start Slim and Twig



$app = new \Slim\Slim(array(
    'view' => new \Slim\Extras\Views\Twig()
));
// $app->add(new \Slim\Middleware\SessionCookie(array(
//     'expires' => '20 minutes',
//     'path' => '/',
//     'domain' => null,
//     'secure' => false,
//     'httponly' => false,
//     'name' => 'slim_session',
//     'secret' => 'CHANGE_ME',
//     'cipher' => MCRYPT_RIJNDAEL_256,
//     'cipher_mode' => MCRYPT_MODE_CBC
// )));


$WEBROOT = "http://gradapp";


/*----------------------------------------------------*/
/* Helper Functions
/*----------------------------------------------------*/
function render($path, $args = array()) {
	$app = \Slim\Slim::getInstance();

	$args['WEBROOT']		= $WEBROOT;
	$args['GRADHOMEPAGE']	= $GLOBALS['graduate_homepage'];
	$args['GRADIMAGESPATH']	= $GLOBALS['grad_images'];

	$args['ISLOGGEDIN']		= ApplicantManager::applicantIsLoggedIn();
	if( $args['ISLOGGEDIN'] )
	{
		$applicant = ApplicantManager::getActiveApplicant();
		$args['EMAIL']	= $applicant->loginEmail;
	}

	return $app->render($path, $args);
}

function createHTMLForSection($name, $currentLocation)
{
	$html = '';
	$link_title = ucwords( str_replace('-', ' ', $name));
	if( $currentLocation == $name ) {
		$html .= "<div class='isection active section-icon-$name'>";
	} else {
		$html .= "<div class='isection section-icon-$name'>";
	}
	$html .= "<a href='" . $_GLOBALS['WEBROOT'] . "/application/section/$name'>";
	$html .= "<p>$link_title</p>";
	$html .= "</a>";
	$html .= '</div>';
	return $html;
}

function render_section($path, $args = array(), $sections, $currentLocation)
{
	$_SESSION['current-application-section'] = $currentLocation;

	$sectionHTML = '';
	foreach($sections as $sectionName)
	{
		$sectionHTML .= createHTMLForSection($sectionName, $currentLocation);
	}

	$applicant = ApplicantManager::getActiveApplicant();
	$args['EMAIL']	= $applicant->loginEmail;

	$args['NAME'] = $applicant->givenName;
	$args['SECTION_LINKS'] = $sectionHTML;
	return render($path, $args);
}

/*----------------------------------------------------*/
/* Middleware Functions
/*----------------------------------------------------*/

/**
 * Authenticated
 * 
 * Middleware
 * 
 * Validate that user is logged in
 */
$authenticated = function() use ($app) {
	$isLoggedIn = ApplicantManager::applicantIsLoggedIn();
	if( !$isLoggedIn ) 
	{
		$app->redirect('/login');
	}
	return $isLoggedIn;
};


/**
 * Application Not Submitted
 * 
 * Middleware
 * 
 * Validate that application has not already been submitted
 */
$applicationNotSubmitted = function() {
	$application = ApplicationManager::getActiveApplication();
	// Redirect if application has already been submitted
	if( $application->hasBeenSubmitted )
	{
		header("location:./pages/lockout.php");
		return false;
	}
};


/*----------------------------------------------------*/
/* Initial Routing and Login/Registration
/*----------------------------------------------------*/

/**
 * Root
 * 
 * Get Request
 * 
 * Route logged in users to application page, otherwise send to login page
 */
$app->get('/', function() {
	$app = Slim\Slim::getInstance();
	// route to correct page
	if( ApplicantManager::applicantIsLoggedIn() ) {
		$app->redirect('/application');
	} else {
		$app->redirect('/login');
	}
});


/**
 * Login
 * 
 * Get Request
 * 
 * Render login page
 */
$app->get('/login', function() use($app) {
	//SUCESSS_MESSAGE -> $signin_msg.$success_msg
	//CREATE_MESSAGE
	render('account/login.twig', array());
});


/**
 * Login - Submission
 * 
 * Post Request
 * 
 * Validate log in attempt and direct to application
 */
$app->post('/login', function() use ($app) {
	$submittingForm = $app->request()->post('form_name');

	switch($submittingForm) 
	{
		case 'signIn':
			$email 	= $app->request()->post('email');
			$password = $app->request()->post('password');

			// Validate Data
			$error_messages = new ErrorTracker();
			if( empty($email) or $email == 'e-mail address') 	{ $error_messages->add('You did not enter an email address'); }
			if( empty($password) ) 						{ $error_messages->add('You did not enter a password'); }


			// Login
			if( !$error_messages->hasErrors() )
			{
				$applicantLoggedInErrorMessage = ApplicantManager::loginApplicant($email, $password);
				if( $applicantLoggedInErrorMessage == '' )
				{
					$app->redirect('/application/section/personal-information');				
				} else {
					$error_messages->add( $applicantLoggedInErrorMessage );
					
					// @TODO: How can you check this without using to see if someone has an account at Grad School? 
					//$error_messages->add('Please check your email for a link to confirm your e-mail address.');					
				}

			}
			// If we've arrived here we didn't redirect after login -> i.e. there was an error.
			// Display Errors
			$app->flash('errors', $error_messages->render() );
			//print_r($app->flash);
			$app->redirect('/login');

		break;
		case 'createAccount':
			$email 			= $app->request()->post('create_email');
			$email_confirm 	= $app->request()->post('create_email_confirm');
			$password 		= $app->request()->post('create_password');
			$password_confirm 	= $app->request()->post('create_password_confirm');			

			// Validate Data
			$error_messages = new ErrorTracker();
			if( empty($email) or $email == 'e-mail address') 	{ $error_messages->add('You did not enter an email address'); }
			if( empty($email_confirm) ) 	 				{ $error_messages->add('You did not confirm your email address'); }
			if( empty($password) ) 		 				{ $error_messages->add('You did not enter a password'); }
			if( empty($password_confirm) ) 				{ $error_messages->add('You did not confirm your password choice'); }
			if( $email != $email_confirm ) 				{ $error_messages->add('The email address you provided did not match'); }
			if( $password != $password_confirm ) 			{ $error_messages->add('The passwords you provided did not match'); }
			if( ApplicantManager::accountAlreadyExists($email) ) 	{ $error_messages->add("A user with that name already exists. If you forgot your password, you can recover it <a href='forgot.php'>here</a>."); }

			// Create new Application
			if( !$error_messages->hasErrors() ) 
			{
				ApplicantManager::createAccount($email, $password);

				// @TODO: This should be called here, but implemented somewhere else ..
				sendSuccessMessage($email, $code);				

				$app->flash('success', 'Account created. Please check your email for a link to confirm your email address.' );
				$app->redirect('/login');
			} else {
				// Display Errors
				$app->flash('account-creation-errors', $error_messages->render() );
				$app->redirect('/login');
			}
		break;
		default:
			$app->flash('warning', 'Internal Error 601');			
			$app->redirect('/login');
		break;
	}
});


/**
 * Account - Confirm
 * 
 * Script
 * 
 * Validate new account from emailed link
 */
$app->get('/account/confirm', function() use ($app) {

	$email = $app->request('email');
	$code  = $app->request('code');

	// Make sure data is passed in
	if( !isset($_GET['email']) or !isset($_GET['code']) )
	{
		$app->flash('warning', 'Malformed Link');
		$app->redirect('/login');
		exit(0);
	}

	// validate data
	$email = str_replace('%40','@',$email); // Clean email
	$accountValidates = Applicant::doesAccountValidate($email, $code);
	if ( !$accountValidates )
	{
		$app->flash('warning', 'Malformed Link');
		$app->redirect('/login');
		exit(0);		
	}

	// Process Account
	$accountValidated = Applicant::isAccountAlreadyValidated($email, $code);		
	if ( $accountValidated ) {
		// Account has already been confirmed
		$app->flash('warning', 'You have already confirmed your email address. Please sign in below.');
		$app->redirect('/login');
		exit(0);
	} else {
		// Account Confirmed
		Applicant::validateAccount($email, $code);
		$app->flash('success', 'You have been confirmed. Please sign in.');
		$app->redirect('/login');
		exit(0);
	}
});


/**
 * Logout
 * 
 * Get Request
 * 
 * Logs out user
 */
$app->get('/logout', function() use ($app) {
	user_logout();
	$app->redirect('/login');
});


/**
 * Register - Submission
 * 
 * Post Request
 * 
 * Register User
 */
$app->post('/account/register', function() {
});


/**
 * Forgot Password
 * 
 * Get Request
 * 
 * Forgot password page
 */
$app->get('/account/forgot-password', function() {
	echo "forgot password";
});


/**
 * Reset Password
 * 
 * Get Request
 * 
 * Reset password page
 */
$app->get('/account/reset-password', function() {
	render('account/reset_password.twig');
});


/**
 * Lock Out
 * 
 * Get Request
 * 
 * Application already submitted - lockout page
 */
$app->get('/lockout', function() {
	echo "lockout";
});


/**
 * No Javascript
 * 
 * Get Request
 * 
 * Javascript not detected, alerting user
 */
$app->get('/no-javascript', function() {
	render('no_javascript.twig', 
		array('GRADHOMEPAGE' => $GLOBALS['graduate_homepage'],
			'GRADIMAGESPATH' => $GLOBALS['grad_images']));	
});

/*----------------------------------------------------*/
/* Application
/*----------------------------------------------------*/

/**
 * Application
 * 
 * Save Data
 * 
 * Save a field from the application
 */
$app->post('/saveData', $authenticated, $applicaitonNotSubmitted, function() use ($app) {
	$field = $app->request()->post('field');
	$value = $app->request()->post('value');

	$application = ApplicationManager::getActiveApplication();

	// Field stores the path within the application to the data in the form location1.location2. ... .fieldName
	$fieldNames = explode('.', $field);
	if( count($fieldNames) == 1) {
		$application->$fieldNames[0] = $value;
	} else if( count($fieldNames) == 2) {
		print_r($application->$fieldNames[0]);
//		$application->$fieldNames[0]->$fieldNames[1] = $value;
//		echo $application->$fieldNames[0]->$fieldNames[1];
	}


});

/**
 * Application
 * 
 * Get Request
 * 
 * Render application section personal-information
 */
$app->get('/application/section/personal-information', $authenticated, $applicationNotSubmitted, function () {

	// USER
	// GIVEN_NAME
	// MIDDLE_NAME
	// FAMILY_NAME
	// SUFFIX
	// ALTERNATE_NAME
	// PRIMARY_PHONE
	// SECONDARY_PHONE
	// EMAIL
	// PERMANENT_ADDR1
	// PERMANENT_ADDR2
	// PERMANENT_CITY
	// PERMANENT_STATE
	// PERMANENT_POSTAL
	// PERMANENT_COUNTRY

	// MAILING_PERM
	// MAiLING_ADDR1
	// MAILING_ADDR2
	// MAILING_CITY
	// MAILING_STATE
	// MAILING_POSTAL
	// MAILING_COUNTY
	
	// DATE_OF_BIRTH
	// GENDER
	// SOCIAL_SECURITY_NUMBER
	// BIRTH_CITY
	// BIRTH_STATE
	// BIRTH_COUNTRY

	// COUNTRY_OF_CITIZENSHIP

	$applicant 	= ApplicantManager::getActiveApplicant();
	$application 	= ApplicationManager::getActiveApplication();

	//print_r($application);
	render_section('application/personal-information.twig', array('application' => $application, 'applicant'=>$applicant), $application->sections, 'personal-information');

});

$app->get('/application/section/next', $authenticated, $applicationNotSubmitted, function() {
	$current_section = $_SESSION['current-application-section'];

	$application 	= ApplicationManager::getActiveApplication();
	$sections 	= $application->sections;

	$index=array_search($current_section, $sections);
	if( $index !== False ) {
		if($index == count($sections) - 1) {
			// goto app review page
		} else {
			$next_section = $sections[$index+1];
			return $app->redirect('/application/section' . $next_section);
		}
	}
	// Error, return to first page with warning
	return $app->redirect('/application/section/' . $sections[0]);

});

/**
 * Application
 * 
 * Get Request
 * 
 * Render application section international
 */
$app->get('/application/section/international', $authenticated, $applicationNotSubmitted, function () {

	$applicant 	= ApplicantManager::getActiveApplicant();
	$application 	= ApplicationManager::getActiveApplication();

	render_section('application/international.twig', array('application' => $application), $application->sections, 'international');

});

/**
 * Application - Download
 * 
 * Script/Get Request
 * 
 * Downloads pdf of current application
 */
$app->get('/application/download', $authenticated, $applicationNotSubmitted, function ($page) {
	$application = Application::getActiveApplication();
	$application->generateClientPDF();
});


/**
 * Application - Submit with payment
 * 
 * Script/Get Request
 * 
 * Submit applicaiton with payment
 */
$app->get('/application/submit-with-payment', $authenticated, $applicationNotSubmitted, function ($page) {
	// Data
	$application 	= Application::getActiveApplication();
	$applicant 	= Applicant::getActiveApplicant();
	$db 		 	= Database::getInstance();
	
	// Process Submission
	if( ! $application->hasBeenSubmitted() ){
	
		//Send Recommendations
		require "emailRecommenders.php";
	
		$application->generateServerPDF();
	
		//Send Payment
		$application->submitWithPayment(true);
	} else {
		header('Location: ../application/pages/lockout.php');	
	}
});


/**
 * Application - Submit without payment
 * 
 * Script/Get Request
 * 
 * Submit applicaiton without payment
 */
$app->get('/application/submit-without-payment', $authenticated, $applicationNotSubmitted, function ($page) {

	$application = Application::getActiveApplication();
	
	// process application
	if( ! $application->hasBeenSubmitted() ){
	
		// Finish Submission
		require 'emailRecommenders.php';  // Submit recommendation emails
		require 'mailPayLater.php'; 	  // Send Email to Applicant
	
		$application->generateServerPDF();
	
		// Update application
		$application->submitWithPayment(false);
	
		header('Location: ../application/success.php');
	} else {
		header('Location: ../application/pages/lockout.php');
	}
});


/*----------------------------------------------------*/
/* Application Payment
/*
/* Touchnet (the external payment system) redirects or calls these pages. They are not used directly by the application
/*----------------------------------------------------*/


/**
 * Payment - Success
 * 
 * Get Request
 * 
 * Payment was successful message
 */
$app->get('/payment/success', function() {
	render(
		'payment_response.twig', 
		array('GRADHOMEPAGE' 			=> $GLOBALS['graduate_homepage'],
			'TITLE' 					=> 'Transaction Successful',
			'APPLICATION_RESULT_MESSAGE' 	=> 'Your application was submitted successfully.',
			'ADDITIONAL_MESSAGE' 		=> 'As soon as your payment has been received your application will be reviewed.'
			)
		);	
});


/**
 * Payment - Cancel
 * 
 * Get Request
 * 
 * Payment was cancelled message
 */
$app->get('/payment/cancel', function() {
	render(
		'payment_response.twig', 
		array('GRADHOMEPAGE' 			=> $GLOBALS['graduate_homepage'],
			'TITLE' 					=> 'Transaction Canceled',
			'APPLICATION_RESULT_MESSAGE' 	=> 'You have successfully submitted an online application to The University of Maine Graduate School, however your application fee payment transaction has been cancelled. Please contact the Graduate School office at 207-581-3291 to pay the application fee. Applications are not processed until an application fee has been received.',
			'ADDITIONAL_MESSAGE' 		=> ''
			)
		);
});


/**
 * Payment - Failure
 * 
 * Get Request
 * 
 * Payment process failed message
 */
$app->get('/payment/failed', function() {
	render(
		'payment_response.twig', 
		array('GRADHOMEPAGE' 			=> $GLOBALS['graduate_homepage'],
			'TITLE' 					=> 'Transaction Failed',
			'APPLICATION_RESULT_MESSAGE' 	=> 'Your transaction has failed.',
			'ADDITIONAL_MESSAGE' 		=> ''
			)
		);	
});


/**
 * Payment - Callback Update
 * 
 * Script / Get Request
 * 
 * Indicates payment was successful. Data about payment details is sent to application for records.
 */
$app->post('/payment/callback-update', function() {
     $db = Database::getInstance();
	
	// Parse data from Touchnet
	$key 	= $app->request('posting_key');
	$status   = $app->request('pmt_status');
	$trans_id = $app->request('EXT_TRANS_ID');
	
	$identifier_array = explode("*", $trans_id);
	
	$upaysiteID  = $identifier_array[0];
	$applicantID = $identifier_array[1];
	$transID 	   = $identifier_array[2];
	
	$result = $db->query('SELECT application_fee_transaction_number FROM applicants WHERE applicant_id=%d', $applicantID);
	$stored_transaction_id = $result[0];
	
	//mail("timothy.d.baker@umit.maine.edu", "SYSTEM-GRAD-APPLICATION: Touchnet payment made", "payment data:\n " . json_encode($_POST));
	
	// Process successful payments
	if ($status == "success" 
		&& $key == $GLOBALS['touchnet_posting_key']
		&& $stored_transaction_id == $trans_id
		) 
	{
	
		// Set Payment method
		$payment_method = "";
	
		if( isset( $_REQUEST['card_type'] )) {
			$payment_method = "CREDIT";
		} else {
			$payment_method = "ACH";	
		}
	
		// Update Database 
		// @TODO: do this using the model!
		$db->iquery("UPDATE applicants SET application_fee_payment_status='Y' WHERE applicants.applicant_id=%d", $applicantID);
		$db->iquery("UPDATE applicants SET application_fee_transaction_date='%s' WHERE applicants.applicant_id=%d", date("Y-m-d"), $applicantID);
		$db->iquery("UPDATE applicants SET application_fee_transaction_type='Online' WHERE applicants.applicant_id=%d", $applicantID);
	
		$db->iquery("UPDATE applicants SET application_fee_transaction_payment_method='%s' WHERE applicants.applicant_id=%d", $payment_method, $applicantID);
	
	} else {
		$error_message  = "A touchnet payment was made unsucessfully ";
		$error_message .= " *** Payment Status: $status ";
		$error_message .= ($key != $GLOBALS['touchnet_posting_key']) ? " *** Passed in key '$key' does not match actual key" : '';
		$error_message .= ($stored_transaction_id == $trans_id) ? " *** Passed in transaction id '$trans_id' does not match stored transaction id" : "";
		error_log($error_message);
	}
	
	$db->close();
});


/*----------------------------------------------------*/
/* Recommendation
/*----------------------------------------------------*/


/**
 * Recommendation
 * 
 * Get Request
 * 
 * Render recommendation page
 */
$app->get('/recommendation/:application_id/:reference_id', function($application_id, $reference_id) {

});


/**
 * Recommendation - Submission
 * 
 * Post Request
 * 
 * Process recommendation
 */
$app->post('/recommendation/:application_id/:reference_id', function($application_id, $reference_id) {
	
});


/**
 * Recommendation - Thank You
 * 
 * Get Request
 * 
 * Render thank you page for recommendation
 */
$app->get('/recommendation/thank-you', function() {
	render(
		'/letter_of_recommendation/thank_you.twig', 
		array('GRADHOMEPAGE' => $GLOBALS['graduate_homepage']
			)
		);	
});

/*----------------------------------------------------*/
/* Testing
/*----------------------------------------------------*/

$app->get('/test/emailSystem', function() {
	$email = new EmailSystem();
	$email->loadFromTemplate('mailPayLater.email.php');
	$email->setDestinationEmail('timbone945@gmail.com');
	$email->sendEmail();
});

$app->get('/test/entity', function() {

//	$dbArray = Database::query("SELECT * FROM applicants");
//	$entity = createFromDatabase($dbArray[0]);

//	print_r($entity);
	$entity = Entity::factory('Applicant')->first(1);

});


/*----------------------------------------------------*/
/* Run App
/*----------------------------------------------------*/

$app->run();

