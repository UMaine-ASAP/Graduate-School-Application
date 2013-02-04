<?php
include_once '../libs/corefuncs.php';
include_once '../libs/database.php';
include_once '../libs/variables.php';
      
// connect to database    
$db = new Database();
$db->connect();

$signin_msg = "";
$success_msg = "";	


function getHash( $index )
{
  $validCharacters = 'abcdefghijklmnopqerstuv0123456789';
  $mod = strlen($validCharacters);
  $hash = '';
  $tmp = $index;

  while( $tmp > $mod )
  {
    $hash .= substr($validCharacters, $tmp%$mod,1);
    $tmp = floor( $tmp / $mod );
  }
  return $hash;
} 
 
if ($_GET) {
	if (isset($_GET['email']) && isset($_GET['code'])) {
		
		$get_email = str_replace('%40','@',$_GET['email']); 
		$get_code  = $_GET['code'];
		
		// make sure that user and that hash exist and match, and if they've already confirmed
		$check_result = $db->query("SELECT `applicant_id`, `login_email_confirmed` FROM `applicants` WHERE `login_email` = '%s' AND `login_email_code` = '%s'", $get_email, $get_code);
		$check_user = $check_result[0];

		// validate results
		if ($check_user != null) {
			$check_id = $check_user['applicant_id'];
			$check_login_email_confirmed = $check_user['login_email_confirmed'];
			
			if ($check_login_email_confirmed == 1) {
				$signin_msg .= "<p class='warning'>You have already confirmed your email address. Please sign in below.</p>";
			} else {
				$success_msg .= "<p class='success'>You have been confirmed. Please sign in.</p>";
				
				// connect to the database, verify the information, and flip the 'confirmed' bit
				// prepare the query
				
				$db->iquery("UPDATE `applicants` SET `login_email_confirmed` = 1 WHERE `applicant_id` = '%s'", $check_id);
				//sendSuccessMessage($email, $code);
			}
		} else {
			$signin_msg .= "<p class='warning'>Malformed Link</p>";
		}
	}
}

if ($_POST) {
	if ($_POST['whichform'] == 'signin') {
		// the user wants to sign in
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		if ($email == 'e-mail address') {
			$signin_msg .= "<p class='warning'>you did not enter an email address</p>";
		} else {
			$user_result = $db->query("SELECT `applicant_id`, `password`, `login_email_confirmed` FROM `applicants` WHERE `login_email` = '%s' LIMIT 1", $email);
			$user_result = $user_result[0];

			// validate results
			if ($user_result != null) {
				$hash = $user_result['password'];
				$id = $user_result['applicant_id'];
				$confirmed = $user_result['login_email_confirmed'];
				if ($hash == sha1($password) && $confirmed == 1) {
					user_login($id);
					if (check_ses_vars()) {
						header('Location: ../app_manager.php');
					}
				} else if ($hash != sha1($password)){
					$signin_msg .= "<p class='warning'>Incorrect email/password combination</p>";
				} else if ($confirmed == 0) {
					$signin_msg .= "<p class='warning'>Please check your email for a link to confirm your e-mail address.</p>";
				}
			} else {

				$signin_msg .= "<p class='warning'>Incorrect email/password combination</p>";
			}
		}
		
	} else if ($_POST['whichform'] == 'create_account') {	
		$create_msg = "";
		
		// the user wants to create a new account
		$given_name 		=$_POST['create_given_name'];
		$last_name 		= $_POST['create_last_name'];
		$email 			  = $_POST['create_email'];
		$email_confirm    = $_POST['create_email_confirm'];
		$password 		  = $_POST['create_password']; 
		$password_confirm = $_POST['create_password_confirm'];

		// see if that email address already exists in the database, and if it does, throw an error

		$dupe_result = $db->query("SELECT `login_email` FROM `applicants` WHERE `login_email` = '%s'", $email);
		$dupe = (is_array($dupe_result)) ? $dupe_result[0] : '';
		
		// validate form
		if (empty($email) || empty($given_name) || empty($last_name) || empty($email_confirm) || empty($password) || empty($password_confirm) || $email != $email_confirm || $password != $password_confirm || $dupe != '') {
			// errors
			if (empty($email) || $email == 'e-mail address') {
				$create_msg .= "<p class='warning'>You did not enter an email address</p>";
			}
			if (empty($given_name)) {
				$create_msg .= "<p class='warning'>You did not enter a given name</p>";
			}
			if (empty($last_name)) {
				$create_msg .= "<p class='warning'>You did not enter a last name</p>";
			}
			if (empty($email_confirm)) {
				$create_msg .= "<p class='warning'>You did not confirm your email address</p>";
			}
			if (empty($password)) {
				$create_msg .= "<p class='warning'>You did not enter a password</p>";
			}
			if (empty($password_confirm)) {
				$create_msg .= "<p class='warning'>You did not confirm your password choice</p>";
			}
			if ($email != $email_confirm) {
				$create_msg .= "<p class='warning'>The email addresses you provided did not match</p>";
			}
			if ($password != $password_confirm) {
				$create_msg .= "<p class='warning'>The passwords you provided did not match</p>";
			}
			if ($dupe != '') {
				$create_msg .= "<p class='warning'>A user with that name already exists. If you forgot your password, you can recover it <a href='forgot.php'>here</a>.</p>";
			}
		} else {
			// no errors. proceed with account creation

			$result = $db->query("SELECT applicant_id FROM applicants ORDER BY applicant_ID DESC LIMIT 1");
			$last_index = $result[0]['applicant_id'];

			$code = getHash(time()+$last_index+1); // use this code for the confirmation email

			$db->iquery("INSERT INTO `applicants` (`login_email`, `password`, `login_email_code`, `given_name`, `family_name`) VALUES('%s', '%s', '%s', '%s', '%s')", $email, sha1($password), $code, $given_name, $last_name);

			$success_msg .= "<p class='success'>Account created. Please check your email for a link to confirm your email address.</p>";

			sendSuccessMessage($email, $code);
		}
		
	}
}

$db->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>Apply to the University of Maine Graduate School</title>
	<link rel="stylesheet" href="../styles/reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	<script type="text/javascript" src="../libs/sha1-min.js" charset="utf-8"></script>
	<script type="text/javascript" src="../libs/browser_check.js" charset="utf-8"></script>

	<!-- JQUERY -->
	<link type="text/css" href="../styles/jquery/redmond/jquery-ui-1.8.2.custom.css" rel="Stylesheet" />
	<script type="text/javascript" src="../libs/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../libs/jquery/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript" src="../libs/jquery/jquery.validate.js"></script>
	<!-- END JQUERY -->


	<!-- <script type="text/javascript" charset="utf-8">
		function validateSignin (email, password) {
			$.ajax({
				type: "POST",
				url: "checkuser.php",
				data: "type=signin&email="+email+"&password="+password,
				success: function(msg){
					return msg
				}
			})
		}
	</script> -->
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$("#createForm").validate({
				rules: {
					create_email_confirm: {equalTo: "#create_email"},
					create_password: "required",
					create_password_confirm: {equalTo: "#create_password", required: true}
				}
			});
			
			$("#signinForm").validate({
				rules: {
					
				}
			})
		});
	</script>
	<style type="text/css" media="screen">
	
	html {
		background-image:url('<?php echo($GLOBALS["grad_images"]);?>background-tile2.png');
	}
	
	body {
		text-align:center;
		font-family: Verdana, Arial, sans-serif;
	}
	
	.gradHeader {
		width:100%;
		height:85px;
		margin-top:10px;
		background-image:url('<?php echo($GLOBALS["grad_images"]);?>grad_logo.png');
		background-repeat:no-repeat;
	}
	
	#content {
		width:50em;
		text-align:left;
		margin-left:auto;
		margin-right:auto;
	}
	
	#signin, #create_account {
		width:20em;
		float:left;
		padding:1em;
	}
	
	#signin {
		background:#dfe9ed;
		-moz-border-radius:8px;
		-webkit-border-radius:8px;
		margin:0px 4em 0px .7em;
	}
	
	#signin * [type='text'], #signin * [type='password'] {
		padding:2px;
	}
	
	#create_account {
		background:#dfe9ed;
		-moz-border-radius:8px;
		-webkit-border-radius:8px;
	}
	
	strong {
		font-weight:bold;
	}
		
	h1 {
		color:#ffffff;
		margin-top:1em;
		margin-bottom:1.5em;
		font-size: 2.4em;
		font-family: Verdana, Arial, sans-serif;
		display:inline;
		position:relative;
		bottom:40px;
		left:157px;
	}
	
	p {
		padding-top:.5em;
	}
	
	fieldset {

		height:100%;
		min-height:50px;

		margin:3px;
		padding:10px 9px 7px;

		background: #b4c8cf;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		border-left:1px solid #9FAEB3;
		border-top:1px solid #9FAEB3;
	}
	
	legend {
		background-color:transparent;
		padding-bottom:10px;
		font-weight:bold;
		background: #b4c8cf;
		padding: 4px;
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		border:1px solid #9FAEB3;
	}

	input {
		padding: 1px 6px;
	}
	
	label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
	
	.warning{
		color: #ad5147;
		font-size: 0.8em;
	}
	
	.success{
		color: #51ad47;
		font-size: 1.2em;
	}
	
	.gradFooter{
		color:#fff;
		text-align:center;
		margin-top:15px;
		font-family:verdana,geneva,arial,helvetica,sans-serif;
		font-size:0.7em;
	}
	
	</style>
	<link rel="shortcut icon" href="../images/grad_favicon.ico" />
</head>

<body onLoad="doBrowserCheck();">
	<div id="content">
	<!--	<div class="gradHeader"></div><br/> -->
		<a href="<?php echo $GLOBALS['graduate_homepage'];?>"><img src="<?php echo $GLOBALS['grad_images'];?>grad_logo.png" /></a>
		<h1>Online Application</h1>

		<div style="clear:both"></div>

		<div id="signin">
			<div id="success_msg"><?php print $signin_msg.$success_msg; ?></div>
			<form id="signinForm" method="post" accept-charset="utf-8">
				<fieldset id="" class="">
					<legend>Sign In</legend>
					<label for="email">
						<p class="title">e-mail address</p>
						<input type="text" spellcheck="false" class="required email" name="email" id="email" size="27"/>
					</label>
					<label for="password">
						<p class="title">password</p>
						<input type="password" class="required" name="password" id="password" size="27"/>
					</label>

					<div style="clear:both; height: 6px;"></div>

					<input type="hidden" name="whichform" value="signin" id="whichform"/>
					<input type="submit" name="signin" value="Sign In"/>
					<a href="forgot.php">forgot password?</a>

					<div style="clear:both"></div>
				</fieldset>
				<p class="message"><strong>Note:</strong> if you are not applying for a degree, please use the <a href="<?php echo $GLOBALS['graduate_homepage'];?>documents/file/Nondegree.pdf">graduate nondegree application/registration form</a>.</p>
			</form>
		</div>

		<div id="create_account">
			<form method="post" accept-charset="utf-8" id="createForm">
				<fieldset id="" class="">
					<legend>Create a New Account</legend>
					<label for="create_given_name">
						<p class="title">given name</p>
						<input class="required given" type="text" name="create_given_name" value="" id="create_given_name" size="27"/>
					</label>
					<label for="create_last_name">
						<p class="title">last name</p>
						<input class="required last_name" type="text" name="create_last_name" value="" id="create_last_name" size="27"/>
					</label>
					<label for="create_email">
						<p class="title">e-mail address</p>
						<input class="required email" type="text" name="create_email" value="" id="create_email" size="27"/>
					</label>
					<label for="create_email_confirm">
						<p class="title">confirm e-mail</p>
						<input class="required email" type="text" name="create_email_confirm" value="" id="create_email_confirm" size="27"/>
					</label>
					<label for="create_password">
						<p class="title">create password</p>
						<input class="create_password" minLength="2" type="password" name="create_password" value="" id="create_password" size="27"/>
					</label>
					<label for="create_password_confirm">
						<p class="title">confirm password</p>
						<input class="create_password_confirm" minLength="2" type="password" name="create_password_confirm" value="" id="create_password_confirm" size="27"/>
					</label>

					<div style="clear:both; height: 6px;"></div>

					<input type="hidden" name="whichform" value="create_account" id="whichform"/>
					<input type="submit" name="create" value="Create Account"/>

					<div style="clear:both"></div>
				</fieldset>
			</form>
			<?php if( isset($create_msg) ) { ?>
				<div id="create_msg"><?php  print $create_msg; ?></div>				
			<?php } ?>

		</div>
		
		<div style="clear:both;"></div>

		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
				(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</div>
</body>
</html>
