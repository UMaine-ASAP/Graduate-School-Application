<?php
/**
 * Graduate Application Version 2.0
 * @Author Tim Westbaker
 * @created 10-16-12
 */
if( !isset($_SESSION) )
{
	session_cache_limiter(false);
	session_start();
}

// Data
require_once __DIR__ . "/configuration.php";
// Models
require_once __DIR__ . "/models/Model.php";
require_once __DIR__ . '/models/Applicant.php';
require_once __DIR__ . '/models/Application.php';
require_once __DIR__ . '/models/ApplicationFieldReference.php';

// Controllers
require_once __DIR__ . '/controllers/ApplicationController.php';
require_once __DIR__ . '/controllers/ApplicantController.php';

// Libraries
require_once __DIR__ . '/libraries/Hash.php';
require_once __DIR__ . '/libraries/email.php';
require_once __DIR__ . '/libraries/inputSanitation.php';
require_once __DIR__ . '/libraries/errorTracker.php';

// Slim and Twig setup
require_once __DIR__ . '/libraries/Slim/Slim.php';
Slim\Slim::registerAutoloader();

require_once __DIR__ . '/libraries/Twig/Autoloader.php';
Twig_Autoloader::register();

require_once __DIR__ . '/libraries/Slim/Twig.php';

// Development only
if ($GLOBALS['SITEMODE'] == "DEVELOPMENT") {
	require_once __DIR__ . '/../development/chromephp/ChromePhp.php';
	require_once __DIR__ . '/../development/chromephp/ChromePhp.php';
}

// Get field information
require_once __DIR__ . "/models/databaseConfig.php";

// Start Slim and Twig
$app = new \Slim\Slim(array(
    'view' => new \Slim\Extras\Views\Twig()
));
$GLOBALS['app'] = $app;

// Configure Input Sanitation values
InputSanitation::initialize($GLOBALS['databaseFields']);


class internalErrors {
	// Saving data errors
	const ERROR_SAVING_FIELDNOTFOUND  = 1001;
	const ERROR_SAVING_FIELDNOTLOADED = 1002;

	static public function generateError($errorCode)
	{
		return "Internal Error " + $errorCode;
	}
}

/* =================================================== */
/* = Helper Functions
/* =================================================== */


// 
/**
 * Converts a relative url to an absolute url
 * 
 * @param    String    relativeURL    The relative url to convert
 * 
 * @return    String    The absolute url
 */
function makeLink($relativeURL)
{
	return $GLOBALS['WEBROOT'] . $relativeURL;
}


/**
 * Redirects user to relative url
 * 
 * @param    String    relativeURL    The relative url to convert
 * 
 * @return    void
 */
function redirect($relativeURL)
{
	$GLOBALS['app']->redirect( makeLink($relativeURL) );
}


/**
 * Renders a webpage
 * 
 * Wraps the default $app->render() function to provide global variables to every template
 * 
 * @param    String    templateName         The path to the template file relative to the templates directory
 * @param    Array     templateVariables    Variables available in the templates
 */
// 
function render($templateName, $templateVariables = array()) {
	$app = \Slim\Slim::getInstance();

	$templateVariables['WEBROOT']		= $GLOBALS['WEBROOT'];
	$templateVariables['GRADHOMEPAGE']	= $GLOBALS['GRADUATE_HOMEPAGE'];

	// Set path variables
	$templateVariables['CSSPATH']   = $GLOBALS['WEBROOT'] . '/views/css';
	$templateVariables['JSPATH']    = $GLOBALS['WEBROOT'] . '/views/javascript';
	$templateVariables['IMAGEPATH'] = $GLOBALS['WEBROOT'] . '/views/images';

	$fileNameArray = explode('.', basename($templateName));
	$templateVariables['TEMPLATENAME'] = $fileNameArray[0];

	$templateVariables['ISLOGGEDIN'] = ApplicantController::applicantIsLoggedIn();
	if( $templateVariables['ISLOGGEDIN'] )
	{
		$applicant = ApplicantController::getActiveApplicant();
		$templateVariables['EMAIL'] = $applicant->loginEmail;
	}

	return $app->render($templateName, $templateVariables);
}


/**
 * Outputs HTML for the associated section
 * 
 * Internal function only called by render_section
 * 
 * @param     String    name    The section name to render
 */ 
function createHTMLForSection($name, $currentLocation) {
	$html = '';
	$link_title = ucwords( str_replace('-', ' ', $name));
	if( $currentLocation == $name ) {
		$html .= "<div class='isection active section-icon-$name'>";
	} else {
		$html .= "<div class='isection section-icon-$name'>";
	}
	$html .= "<a href='" . $GLOBALS['WEBROOT'] . "/application/section/$name'>$link_title</a>";
	$html .= '</div>';
	return $html;
}

// Render section
function render_section($path, $args = array(), $sections, $currentLocation) {
	$_SESSION['current-application-section'] = $currentLocation;

	$sectionHTML = '';
	foreach($sections as $sectionName)
	{
		$sectionHTML .= createHTMLForSection($sectionName, $currentLocation);
	}

	$applicant = ApplicantController::getActiveApplicant();
	$args['EMAIL']	= $applicant->loginEmail;

	$args['NAME'] = $applicant->givenName;
	$args['SECTION_LINKS'] = $sectionHTML;
	$args['sectionDetails'] = ApplicationSection::$sectionDetails;
	return render($path, $args);
}



/* =================================================== */
/* = Middleware Functions
/* =================================================== */


/**
 * Generic authentication middleware function
 * 
 * Validates that the user is logged in. Otherwise redirects to the login page
 * 
 * @return    bool   True if user is logged in, otherwise false
 */
$authenticated = function() use ($app) 
{
	$isLoggedIn = ApplicantController::applicantIsLoggedIn();
	if( !$isLoggedIn ) 
	{
		redirect('/login');
	}
	return $isLoggedIn;
};


/**
 * Script-based authentication middleware function
 * 
 * Does the same as $authenticated except displays an error message
 * 
 * @return    bool|void    True if user is logged in, otherwise void
 */
$authenticatedScript = function() use ($app) 
{
	$isLoggedIn = ApplicantController::applicantIsLoggedIn();
	if( !$isLoggedIn ) 
	{
		echo "Please login again";
		exit();
	}
	return $isLoggedIn;
};


/**
 * Application submitted middleware check
 * 
 * Validate that application has not already been submitted
 *
 * @return    bool   True if user is logged in, otherwise false
 */
$applicationNotSubmitted = function() 
{
	$application = ApplicationController::getActiveApplication();

	// Redirect if application has already been submitted
	if( $application->hasBeenSubmitted )
	{
		$app->flash('warning', 'Application already submitted');
		redirect('/my-applications');
	}

	return $application->hasBeenSubmitted;
};



/* =================================================== */
/* = Initial Routing and Login/Registration
/* =================================================== */


/**
 * Root Access
 * 
 * Route logged in users to application page, otherwise send to login page
 */
$app->get('/', function()
{
	$app = Slim\Slim::getInstance();
	// route to correct page
	if( ApplicantController::applicantIsLoggedIn() ) {
		redirect('/my-applications');
	} else {
		redirect('/login');
	}
});


/**
 * Render Login Page
 * 
 * Redirects the user to my-applications if already logged in, otherwise renders the application login page
 */
$app->get('/login', function() use($app) 
{

	if( ApplicantController::applicantIsLoggedIn()) {
		redirect('/my-applications');
	}

	render('account/login.twig', array());
});


/**
 * Attempt Login
 * 
 * Validate login attempt and direct to application if successful, otherwise back to login screen
 */
$app->post('/login', function() use ($app) 
{
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
				$applicantLoggedInErrorMessage = ApplicantController::loginApplicant($email, $password);
				if( $applicantLoggedInErrorMessage == '' )
				{
					redirect('/my-applications');
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
			redirect('/login');
		break;
		default:
			$app->flash('warning', 'Internal Error 601');			
			redirect('/login');
		break;
	}
});


/**
 * Attempt to confirm account
 * 
 * Validates a new account from emailed link, redirecting to the login page after processing.
 */
$app->get('/account/confirm', function() use ($app)
{

	$email = $app->request()->get('email');
	$code  = $app->request()->get('code');

	// Make sure data is passed in
	if( !$email or !$code )
	{
		$app->flash('warning', 'Malformed Link');
		redirect('/login');
	}

	// validate data
	$email = str_replace('%40','@',$email); // Clean email
	$accountValidates = ApplicantController::doesAccountValidate($email, $code);
	if ( !$accountValidates )
	{
		$app->flash('warning', 'Malformed Link');
		redirect('/login');
	}

	// Process Account
	$accountValidated = ApplicantController::isAccountAlreadyValidated($email, $code);		
	if ( $accountValidated ) {
		// Account has already been confirmed
		$app->flash('warning', 'You have already confirmed your email address. Please sign in below.');
		redirect('/login');
	} else {
		// Account Confirmed
		ApplicantController::validateAccount($email, $code);
		$app->flash('success', 'You have been confirmed. Please sign in.');
		redirect('/login');
	}
});


/**
 * Logout User
 * 
 * Logs the user out of the system
 */
$app->get('/logout', function() use ($app) 
{
	ApplicantController::logOutActiveApplicant();
	$app->flash('success', 'You have been logged out successfully.');
	redirect('/login');
});


/**
 * Attempt Account Registration
 * 
 * Processes a new user submission
 */
$app->post('/account/register', function() use ($app)
{
	$firstName        = $app->request()->post('create_firstName');
	$lastName         = $app->request()->post('create_lastName');
	$email            = $app->request()->post('create_email');
	$email_confirm    = $app->request()->post('create_email_confirm');
	$password         = $app->request()->post('create_password');
	$password_confirm = $app->request()->post('create_password_confirm');			

	// Validate Data
	$error_messages = new ErrorTracker();
	if( empty($email) or $email == 'e-mail address') 		 { $error_messages->add('You did not enter an email address'); }
	if( empty($email_confirm) ) 	 					 { $error_messages->add('You did not confirm your email address'); }
	if( empty($password) ) 		 					 { $error_messages->add('You did not enter a password'); }
	if( empty($password_confirm) ) 					 { $error_messages->add('You did not confirm your password choice'); }
	if( $email != $email_confirm ) 					 { $error_messages->add('The email address you provided did not match'); }
	if( $password != $password_confirm ) 				 { $error_messages->add('The passwords you provided did not match'); }
	if( ApplicantController::accountAlreadyExists($email) ) { $error_messages->add("A user with that name already exists. If you forgot your password, you can recover it <a href='" . $GLOBALS['WEBROOT'] . "/account/forgot-password'>here</a>."); }

	// Create new Application
	if( !$error_messages->hasErrors() ) 
	{
		ApplicantController::createAccount($email, $password);

		$app->flash('account_created_success', 'Account created. Please check your email for a link to confirm your email address.' );
		redirect('/login');
	} else {
		// Display Errors
		$app->flash('account-creation-errors', $error_messages->render() );
		redirect('/login');
	}
});


/**
 * Render Forgot Password Page
 * 
 * Shows the forgot password page.
 */
$app->get('/account/forgot-password', function() use ($app)
{  
	ApplicantController::logOutActiveApplicant();

	$email = $app->request()->get('email');
	$code  = $app->request()->get('code');	

	if ($email != '' && $code != '') {
		$email = $_GET['email'];
		$code  = $_GET['code'];

		// connect to the database and make sure the email and code match
		$check_user = Database::getFirst("SELECT `login_email` FROM `Applicant` WHERE  `login_email` = '%s' AND `forgot_password_code` = '%s'", $email, $code);

		if ($check_user['login_email'] != "") {
			return render('account/forgotten-resetPassword.twig');
		} else {
			return render('account/forgotten-error.twig');
		}

	} else {
		return render('account/forgotten-password.twig');
	}
});


/**
 * Attempt to submit Forgot Password
 * 
 * Shows the forgot password page.
 */
$app->post('/account/forgot-password', function()
{
if ($_POST) {
	if (isset($_POST['email']) && !isset($_POST['new_password'])) {
		$email = $_POST['email'];
		
		// check to see if the user already has a password reset request in progress
		$user = Database::getFirst("SELECT `forgot_password_code` FROM `applicants` WHERE `login_email` = '%s'", $email);

		if ($user != '') {
			$code = $user['forgot_password_code'];
			if ($code == '') {
				$code = rand(0, 999999);
				$code .= $email;
				$code = sha1($code);
				
				// add the new hash to the database
				Database::iquery("UPDATE `applicants` SET `forgot_password_code` = '%s' WHERE `login_email` = '%s'", $code, $email);

				ApplicantController::sendForgotPasswordEmail($email, $code);


				// tell the user about it
				render('account/forgotten-emailSent.twig');
			}
			else {
				// resend the email
				ApplicantController::sendForgotPasswordEmail($email, $code);

				// tell the user there is a request pending 
				render('account/forgotten-requestPending.twig');
			}
		} // user is invalid
		else {
			render('account/forgotten-emailNotFound.twig');
		}
	} //isset($_POST['email']) && !isset($_POST['new_password'])
	else if (isset($_POST['new_password']) && isset($_POST['new_password_confirm']) && isset($_POST['email']) && isset($_POST['code'])) {
		// check to see if the email and code match in the database
		$email      = $_POST['email'];
		$code       = $_POST['code'];

		// connect to the database and make sure the email and code match a user
		$check_user = Database::getFirst("SELECT `login_email` FROM `applicants` WHERE  `login_email` = '%s' AND `forgot_password_code` = '%s'", $email, $code);

		if ($check_user['login_email'] != "" && $code != "") {
			// the request is valid. Hash the new password and add it to the database, and clear the old hash
			$password = sha1($_POST['new_password']);

			Database::iquery("UPDATE `applicants` SET `password` = '%s', `forgot_password_code` = '' WHERE `login_email` = '%s' LIMIT 1", $password, $email);
			
			// tell the user the password was successfully reset 
			render('account/forgotten-passwordResetSuccessful.twig');
		}
		else {
			render('account/forgotten-error.twig');
		}
	} //isset($_POST['new_password']) && isset($_POST['new_password_confirm']) && isset($_POST['email']) && isset($_POST['code'])
} //$_POST

});


/**
 * Reset Password Form
 * 
 * The form for submitting a new password. This page should only be accessed by following the url sent to the applicant when filling out the forgot-password form. See /account/forgot-password for more details.
 */
$app->get('/account/reset-password', function() 
{
	render('account/forgotten_resetPasswordForm.twig');
});


/**
 * Reset Password Submission
 * 
 * Processes a reset password request
 */
$app->post('/account/reset-password', function() 
{
	render('account/reset_password.twig');
});


/**
 * Render No Javascript Page
 * 
 * If Javascript is not detected in the users browser, they should be redirected to this url, alerting the user that javascript is required for the application to run properly.
 */
$app->get('/no-javascript', function()
{
	render('noJavascript.twig');	
});



/* =================================================== */
/* = Application
/* =================================================== */


$app->get('/my-applications', $authenticated, function() use ($app)
{	
	$applications = ApplicationController::allMyApplications();
	$types = Database::query("SELECT * FROM APPLICATION_Type");

	render('account/myApplications.twig', array('applications'=>$applications, 'types'=>$types));
});



/* ------------------------ */
/* - Application Actions
/* ------------------------ */


/**
 * Create a new application
 */
$app->get('/create-application', $authenticated, function() use ($app) {

	$typeId = (int) $app->request()->get('application-type');

	$application = ApplicationController::createApplication($typeId);

	if( ! is_null($application) )
	{
		$application->createdDate = Date('Y-m-d H:i:s');
		$application->save();

		redirect('/edit-application/' . $application->id);
	} else {
		// @TODO set error message
		redirect('/my-applications');
	}
});


/**
 * Delete an application
 */
$app->get('/delete-application/:applicationId', $authenticated, function($applicationId) use ($app) {

	ApplicationController::deleteApplication($applicationId);

	// @TODO set result message
	redirect('/my-applications');
});


/**
 * Begin Editing application
 * 
 * Sets active application and redirects to the correct starting 
 * page for the current application
 */
$app->get('/edit-application/:id', $authenticated, function($id) use ($app) {
	$id = (int) $id;
	$isValidApplication = ApplicationController::setActiveApplication($id);

	if ( ! $isValidApplication )
	{
		// application either does not exist or doesn't belong to user
		redirect('/my-applications');
	}

	// Reset current section
	unset($_SESSION['current-application-section']);
	redirect('/application/section/next');
});


/**
 * Save Data
 *  
 * Save a field from the application
 */
$app->post('/application/saveField', $authenticatedScript, function() use ($app)
{

	if ( ! ApplicantController::applicantIsLoggedIn() )
	{
		echo "Please <a href='".$GLOBALS['WEBROOT']."/logout'>Log in</a> Again";
		return;
	}

	// Get data
	$fieldPath = $app->request()->post('field');
	$value     = $app->request()->post('value');

	$fieldReference = new ApplicationFieldReference($fieldPath);

	$errorMessage = '';
	$value        = InputSanitation::cleanInput($value);

	$isValidValue = InputSanitation::isValid($fieldReference->fieldPath, $value, $errorMessage);

	// check for social security number - we need to encrypt before storing in DB
	if( $fieldPath == 'personal-socialSecurityNumber')
	{
		throw new Exception("social security number needs to be encrypted");
	}

	if($isValidValue)
	{
		$fieldReference->save($value);
	} else {
		echo $errorMessage;
	}

});


/**
 * Create a new item of type $name and pass the template back
 */
$app->get('/application/getTemplate/:name', $authenticatedScript, function($name) use ($app)
{
	switch($name)
	{
		case 'language':
			$language = Language::createNew();
			$data = array(
				'language' => $language,
				'hide'     => false);
			return $app->render("application/repeatable/language.twig", $data);
		break;
		case 'previousSchool':
			$previousSchool = PreviousSchool::createNew();
			$data = array(
				'previousSchool' => $previousSchool,
				'hide'           => false);
			return $app->render("application/repeatable/previousSchool.twig", $data);
		break;
		case 'civilViolation':
			$violation = CivilViolation::createNew();
			$data = array(
				'violation' => $violation,
				'hide'      => false,
				'type'      => 'civil');
			return $app->render("application/repeatable/violation.twig", $data);
		break;
		case 'disciplinaryViolation':
			$violation = DisciplinaryViolation::createNew();
			$data = array(
				'violation' => $violation,
				'hide'      => false,
				'type'      => 'disciplinary');
			return $app->render("application/repeatable/violation.twig", $data);
		break;
		case 'GRE':
			$gre = GRE::createNew();
			$data = array(
				'gre'  => $gre,
				'hide' => false);
			return $app->render("application/repeatable/gre.twig", $data);
		break;
		case 'reference':
			$reference = Reference::createNew();
			$data = array(
				'reference'  => $reference,
				'hide' => false);
			return $app->render("application/repeatable/reference.twig", $data);
		break;
		default:
		return '';
	}

});


/**
 * Deletes a repeatable element
 */
$app->post('/application/delete-repeatable', $authenticatedScript, function() use ($app)
{
	$id = $app->request()->post('id');
	$application = ApplicationController::getActiveApplication();

	// id's are of the form <name>-#<id-number>
	$tmp  = explode('-', $id);
	$name = $tmp[0];
	$id   = substr($tmp[1], 1);

	switch($name)
	{
		case 'language':
			$object = Language::getWithId($id);

			if( $object != null )
			{
				$object->delete();
			}
		break;
		case 'previousSchool':
			$object = PreviousSchool::getWithId($id);

			if( $object != null )
			{
				$object->delete();
			}
		break;
		case 'civilViolation':
			$object = CivilViolation::getWithId($id);
			if( $object != null )
			{
				$object->delete();
			}
		break;
		case 'disciplinaryViolation':
			$object = DisciplinaryViolation::getWithId($id);
			if( $object != null )
			{
				$object->delete();
			}
		break;
		case 'GRE':
			$object = GRE::getWithId($id);
			if( $object != null )
			{
				$object->delete();
			}
		break;
		case 'reference':
			// the first 3 references are required
			if($id <= 3) return;
			
			$object = Reference::getWithId($id);
			if( $object != null )
			{
				$object->delete();
			}
		break;
		default:
		return '';
	}

});


/**
 * Application
 * 
 * Render next application section
 */
$app->get('/application/section/next', $authenticated, $applicationNotSubmitted, function() use ($app)
{
	$current_section = '';
	if( ! isset($_SESSION['active-application']) )
	{
		return redirect('/my-applications');
	}

	if( isset($_SESSION['current-application-section']) ) {
		$current_section = $_SESSION['current-application-section'];
	}


	$application 	= ApplicationController::getActiveApplication();
	$sections 	= $application->sections;
	$sections[] = 'review';

	$index=array_search($current_section, $sections);
	if( $index !== False ) {
		if($index == count($sections) - 1) {
			// goto app review page
		} else {
			$next_section = $sections[$index+1];
			return redirect('/application/section/' . $next_section);
		}
	}
	// Error, return to first page with warning
	return redirect('/application/section/' . $sections[0]);
});


/**
 * Application
 * 
 * Render previous application section
 */
$app->get('/application/section/previous', $authenticated, $applicationNotSubmitted, function() use ($app)
{
	$current_section = $_SESSION['current-application-section'];

	$application 	= ApplicationController::getActiveApplication();
	$sections 	= $application->sections;

	$index=array_search($current_section, $sections);
	if( $index !== False ) {
		if($index == 0) {
			// error, there is no previous for the first section
		} else {
			$next_section = $sections[$index-1];
			return redirect('/application/section/' . $next_section);
		}
	}
	// Error, return to first page with warning
	return redirect('/application/section/' . $sections[0]);
});



/* ------------------------ */
/* - Application Pages
/* ------------------------ */


/**
 * Render application section personal-information
 */
$app->get('/application/section/personal-information', $authenticated, $applicationNotSubmitted, function ()
{
	$applicant 	= ApplicantController::getActiveApplicant();
	$application 	= ApplicationController::getActiveApplication();

	render_section('application/section-personalInformation.twig', array('application' => $application, 'applicant'=>$applicant), $application->sections, 'personal-information');
});


/**
 * Render application section international
 */
$app->get('/application/section/international', $authenticated, $applicationNotSubmitted, function ()
{
	$applicant 	= ApplicantController::getActiveApplicant();
	$application 	= ApplicationController::getActiveApplication();

	render_section('application/section-international.twig', array('application' => $application), $application->sections, 'international');
});


/**
 * Render application section educational history
 */
$app->get('/application/section/educational-history', $authenticated, $applicationNotSubmitted, function ()
{

	$applicant 	= ApplicantController::getActiveApplicant();
	$application 	= ApplicationController::getActiveApplication();

	render_section('application/section-educationalHistory.twig', array('application' => $application), $application->sections, 'educational-history');
});


/**
 * Render application section educational objectives
 */
$app->get('/application/section/educational-objectives', $authenticated, $applicationNotSubmitted, function ()
{

	$applicant 	= ApplicantController::getActiveApplicant();
	$application 	= ApplicationController::getActiveApplication();

	render_section('application/section-educationalObjectives.twig', array('application' => $application), $application->sections, 'educational-objectives');
});


/**
 * Render application section Letters of recommendation
 */
$app->get('/application/section/letters-of-recommendation', $authenticated, $applicationNotSubmitted, function ()
{

	$applicant 	= ApplicantController::getActiveApplicant();
	$application 	= ApplicationController::getActiveApplication();

	render_section('application/section-lettersOfRecommendation.twig', array('application' => $application), $application->sections, 'letters-of-recommendation');
});

/**
 * Send an email to a reference
 */
$app->post('/application/section/letters-of-recommendation/email-reference/:referenceId', $authenticated, $applicationNotSubmitted, function($referenceId) {
	$application = ApplicationController::getActiveApplication();

	$errorMessage = $application->emailReference($referenceId);
	if ($errorMessage == '') {
		echo "Success: Recommendation email sent!";
	} else {
		echo $errorMessage;
	}
});

/**
 * Render application section Letters of recommendation
 */
$app->get('/application/section/review', $authenticated, $applicationNotSubmitted, function () use ($app)
{

	$applicant   = ApplicantController::getActiveApplicant();
	$application = ApplicationController::getActiveApplication();

	// check for errors
	$errors = $application->checkRequiredFields();

	if( count($errors) > 0 )
	{
		// Display errors
		$app->flash('errors', $errors);

		if( ! isset($_SESSION) ) { session_start(); }

		if( isset($_SESSION['current-application-section']) )
		{
			$currentSection = $_SESSION['current-application-section'];

			$sections = $application->sections;

			$index=array_search($currentSection, $sections);
			if( $index !== False ) {
				redirect('/application/section/' . $sections[$index] );
			}
		} else {
			redirect('/application/section/personal-information');
		}
	} else {
		// Show review section
		render_section('application/section-review.twig', array('application' => $application), $application->sections, 'review');
	}
});


/**
 * Downloads pdf of current application
 */
$app->get('/application/download', $authenticated, $applicationNotSubmitted, function ()
{
	$application = ApplicationController::getActiveApplication();
	$application->displayPDF();
});


/**
 * Submit applicaiton with payment
 */
$app->get('/application/submit-with-payment', $authenticated, $applicationNotSubmitted, function ()
{
	$application = ApplicationController::getActiveApplication();

	//Send Payment - redirects to Touchnet
	$application->submitWithPayment(true);
});


/**
 * Submit applicaiton without payment
 */
$app->get('/application/submit-without-payment', $authenticated, $applicationNotSubmitted, function ()
{
	$application = ApplicationController::getActiveApplication();
	
	// Submit
	$application->submitWithPayment(false);
	
	render(
		'application/payment_response.twig', 
		array('TITLE' 					=> 'Application Submitted',
			'APPLICATION_RESULT_MESSAGE' 	=> 'Your application was submitted successfully.',
			'ADDITIONAL_MESSAGE' 		=> 'As soon as your payment has been received your application will be reviewed.'
			)
		);	
});


/* =================================================== */
/* = Application Payment
/* = 
/* = Touchnet (the external payment system) redirects the user to these pages. They are not used directly by the application. for "payment/callback-update", instead of redirecting the user, Touchnet sends payment results for processing. 
/* =================================================== */


/**
 * Payment was successful message
 */
$app->get('/payment/success', function()
{
	render(
		'application/payment_response.twig', 
		array('TITLE' 					=> 'Transaction Successful',
			'APPLICATION_RESULT_MESSAGE' 	=> 'Your application was submitted successfully.',
			'ADDITIONAL_MESSAGE' 		=> 'As soon as your payment has been received your application will be reviewed.'
			)
		);	
});


/**
 * Payment was cancelled message
 */
$app->get('/payment/cancel', function()
{
	render(
		'application/payment_response.twig', 
		array('TITLE' 					=> 'Transaction Canceled',
			'APPLICATION_RESULT_MESSAGE' 	=> 'You have successfully submitted an online application to The University of Maine Graduate School, however your application fee payment transaction has been cancelled. Please contact the Graduate School office at 207-581-3291 to pay the application fee. Applications are not processed until an application fee has been received.',
			'ADDITIONAL_MESSAGE' 		=> ''
			)
		);
});


/**
 * Payment process failed message
 */
$app->get('/payment/failed', function()
{
	render(
		'application/payment_response.twig', 
		array('TITLE' 					=> 'Transaction Failed',
			'APPLICATION_RESULT_MESSAGE' 	=> 'Your transaction has failed.',
			'ADDITIONAL_MESSAGE' 		=> ''
			)
		);	
});


/**
 * Payment Update Script
 * 
 * Indicates payment was successful. Data about payment details is sent to application for records.
 */
$app->post('/payment/callback-update', function()
{
     $db = Database::getInstance();
	
	// Parse data from Touchnet
	$key 	= $app->request('posting_key');
	$status   = $app->request('pmt_status');
	$trans_id = $app->request('EXT_TRANS_ID');
	
	$identifier_array = explode("*", $trans_id);
	
	$upaysiteID  = $identifier_array[0];
	$applicantID = $identifier_array[1];
	$transID 	   = $identifier_array[2];
	
	$result = Database::getFirst('SELECT application_fee_transaction_number FROM Application WHERE applicant_id=%d', $applicantId);
	$stored_transaction_id = $result['application_fee_transaction_number'];
	
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
		Database::iquery("UPDATE applicants SET application_fee_payment_status='Y' WHERE applicants.applicant_id=%d", $applicantID);
		Database::iquery("UPDATE applicants SET application_fee_transaction_date='%s' WHERE applicants.applicant_id=%d", date("Y-m-d"), $applicantID);
		Database::iquery("UPDATE applicants SET application_fee_transaction_type='Online' WHERE applicants.applicant_id=%d", $applicantID);
	
		Database::iquery("UPDATE applicants SET application_fee_transaction_payment_method='%s' WHERE applicants.applicant_id=%d", $payment_method, $applicantID);
	
	} else {
		$error_message  = "A touchnet payment was made unsucessfully ";
		$error_message .= " *** Payment Status: $status ";
		$error_message .= ($key != $GLOBALS['touchnet_posting_key']) ? " *** Passed in key '$key' does not match actual key" : '';
		$error_message .= ($stored_transaction_id == $trans_id) ? " *** Passed in transaction id '$trans_id' does not match stored transaction id" : "";
		error_log($error_message);
	}
	
	$db->close();
});



/* =================================================== */
/* = Recommendation
/* =================================================== */

/**
 * Check Recommendation URL
 * 
 * Helper function to make sure the url is valid. Renders an error 
 * 
 * @param    string    applicaitonHashReference    The hash value representing the application
 * @param    int       referenceId                 The id of the reference
 * 
 * @return void
 */
function checkRecommendationURL($applicationHashReference, $referenceId)
{
	// check Application
	$application = ApplicationController::getApplicationFromHash($applicationHashReference);

	// make sure application is valid
	if ( is_null($application) ) {
		render('basicPage.twig', array('title'=>'', 'content'=>'URL does not exist. please check the url or contact <a href="mailto:graduate@maine.edu">graduate@maine.edu</a>'));
		exit(0);
	}

	// make sure reference is valid
	$reference = $application->getReferenceWithId($referenceId);

	if( is_null($reference) ) {
		render('basicPage.twig', array('title'=>'', 'content'=>'URL does not exist. please check the url or contact <a href="mailto:graduate@maine.edu">graduate@maine.edu</a>'));
		exit(0);
	}	
}

/**
 * Render recommendation page
 */
$app->get('/recommendation/:applicationHashReference/:referenceId', function($applicationHashReference, $referenceId)
{
	// Ensure url is valid, displays error page otherwise
	checkRecommendationURL($applicationHashReference, $referenceId);

	$application = ApplicationController::getApplicationFromHash($applicationHashReference);
	$reference   = $application->getReferenceWithId($referenceId);

	print_r($application->personal->fullName);
	render('letterOfRecommendation/recommendationForm.twig', array('application'=>$application, 'reference'=>$reference));

});



/**
 * Process recommendation
 */
$app->post('/recommendation/:applicationHashReference/:referenceId', function($applicationHashReference, $referenceId)
{
	// Ensure url is valid, displays error page otherwise
	checkRecommendationURL($applicationHashReference, $referenceId);

	$application = ApplicationController::getApplicationFromHash($applicationHashReference);

	// validate (we're not using client-side validation on this one because 
	// they might not have javascript)
	RecommendationController::validateRecommendation( $app->request()->post() );

	// Save data
	$data = $app->request()->post();

	$recommendation = Recommendation::createNew();

	$recommendation->recommendationId = $data['recommendationId'];
	$recommendation->referenceId      = $data['referenceId'];
	$recommendation->applicationId    = $data['applicationId'];
	$recommendation->firstName        = $data['firstName'];
	$recommendation->lastName         = $data['lastName'];
	$recommendation->title            = $data['title'];
	$recommendation->employer         = $data['employer'];
	$recommendation->email            = $data['email'];
	$recommendation->phone            = $data['phone'];
	$recommendation->academicAbility  = $data['academicAbility'];
	$recommendation->motivation       = $data['motivation'];
	$recommendation->programReuse     = $data['programReuse'];
	$recommendation->reusablePrograms = $data['reusablePrograms'];
	$recommendation->lifetime         = $data['lifetime'];
	$recommendation->recommendation   = $data['recommendation'];

	$recommendation->save();	

	// build pdfs
	$recommendation->buildPDF();

	// Thank reference
	$recommendation->sendThankYouEmail($application);

	redirect('/recommendation/thank-you');

});


/**
 * Render thank you page for recommendation
 */
$app->get('/recommendation/thank-you', function()
{
	render(
		'/letter_of_recommendation/thank_you.twig', 
		array('GRADHOMEPAGE' => $GLOBALS['GRADUATE_HOMEPAGE']
			)
		);	
});


/* =================================================== */
/* = Run App
/* =================================================== */



$app->run();

