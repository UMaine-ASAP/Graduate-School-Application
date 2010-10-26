<?php
//include 'includes/connection.php';
include 'includes/corefuncs.php';
include '../../application/libs/database.php';

if ($_POST) {
	if (isset($_POST['email']) && !isset($_POST['new_password'])) {
		$email = $_POST['email'];
		
		// check to see if the user already has a password reset request in progress

		$db = new Database();
		$db->connect();
		$user_result = $db->query("SELECT `forgot_password_code` FROM `applicants` WHERE `login_email` = '%s'", $email);
		$user = $user_result[0];
		$db->close();

/*
		$user_conn = dbConnect('admin');
		// prepare the query
		$user_sql  = "SELECT `forgot_password_code` FROM `applicants` WHERE `login_email` = '$email'";
		$user_result = $user_conn->query($user_sql) or die(mysql_error());
		mysqli_close($user_conn);
		// make key/value array of user
		$user = $user_result->fetch_assoc();
*/
		if ($user != '') {
			$code = $user['forgot_password_code'];
			if ($code == '') {
				$code = rand(0, 999999);
				$code .= $email;
				$code = sha1($code);
				
				// add the new hash to the database

				$db = new Database();
				$db->connect();
				$db->iquery("UPDATE `applicants` SET `forgot_password_code` = '%s' WHERE `login_email` = '%s'", $code, $email);
				$db->close();
				$OK = true;
/*
				$hash_conn = dbConnect('admin');
				$hash_sql  = "UPDATE `applicants` SET `forgot_password_code` = '$code' WHERE `login_email` = '$email'";
				// initialize prepared statement
				$hash_stmt = $hash_conn->stmt_init();
				if ($hash_stmt->prepare($hash_sql)) {
					$OK = $hash_stmt->execute();
				} //$hash_stmt->prepare($hash_sql)
*/
				if ($OK) {
					sendRecoverMessage($email, $code);
					// tell the user about it
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" href="../../application/templates/grad_favicon.ico" />
		<title>E-mail Sent</title>
		<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</head>
	<body>
		<div id="content">
			<a href="../../drupal6"><img src='../../drupal6/sites/default/files/acquia_marina_logo.png' /></a>
			<h1>E-mail Sent</h1>
			<div id="forgot">
				<div id="recoverForm">
					<div id="" class="">
						<p>An e-mail has been sent to the address you provided. It contains a link to reset your password. If you don&rsquo;t receive it, check your junk mail folder. You may now close this window.</p>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</body>
</html>	
					<?
				} //$OK
			} //$code == ''
			else {
				// resend the email
				sendRecoverMessage($email, $code);
				// tell the user there is a request pending 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" href="../../application/templates/grad_favicon.ico" />
		<title>Request Pending</title>
		<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</head>

	<body>
		<div id="content">
			<a href="../../drupal6"><img src='../../drupal6/sites/default/files/acquia_marina_logo.png' /></a>
			<h1>Request Pending</h1>
			<div id="forgot">
				<div id="recoverForm">
					<div id="" class="">
						<p>You have already requested a password reset. In case you did not receive an e-mail, it has been resent to the address you provided. It contains a link to reset your password. If you did not receive it, check your junk mail folder.</p>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</body>
</html>
				<?
			}
			// done telling the user about it
		} //$user != ''
		else {
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" href="../../application/templates/grad_favicon.ico" />
		<title>E-mail Not Found</title>
		<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</head>

	<body>
		<div id="content">
			<a href="../../drupal6"><img src='../../drupal6/sites/default/files/acquia_marina_logo.png' /></a>
			<h1>E-mail Not Found</h1>
			<div id="forgot">
				<div id="recoverForm">
					<div id="" class="">
						<p>The e-mail address you entered was not found. Please check your spelling or try a different e-mail address.</p>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</body>
</html>
			<?
		}
	} //isset($_POST['email']) && !isset($_POST['new_password'])
	else if (isset($_POST['new_password']) && isset($_POST['new_password_confirm']) && isset($_POST['email']) && isset($_POST['code'])) {
		// check to see if the email and code match in the database
		$email      = $_POST['email'];
		$code       = $_POST['code'];
		// connect to the database and make sure the email and code match a user

		$db = new Database();
		$db->connect();
		$check_result = $db->query("SELECT `login_email` FROM `applicants` WHERE  `login_email` = '%s' AND `forgot_password_code` = '%s'", $email, $code);
		$check_user = $check_result[0];
		$db->close();
/*
		$check_conn = dbConnect('admin');
		// prepare the query
		$check_sql  = "SELECT `login_email` FROM `applicants` WHERE  `login_email` = '$email' AND `forgot_password_code` = '$code'";
		// submit the query and capture the result
		$check_result = $check_conn->query($check_sql) or die(mysql_error());
		mysqli_close($check_conn);
		// make key/value array of user
		$check_user = $check_result->fetch_assoc();
*/

		if ($check_user['login_email'] != "" && $code != "") {
			// the request is valid. Hash the new password and add it to the database, and clear the old hash
			$password    = sha1($_POST['new_password']);


			$db = new Database();
			$db->connect();
			$db->iquery("UPDATE `applicants` SET `password` = '%s', `forgot_password_code` = '' WHERE `login_email` = '%s' LIMIT 1", $password, $email);
			$db->close();
			$OK = true;

/*
			$update_conn = dbConnect('admin');
			$update_sql  = "UPDATE `applicants` SET `password` = '$password', `forgot_password_code` = '' WHERE `login_email` = '$email' LIMIT 1";
			$update_stmt = $update_conn->stmt_init();
			if ($update_stmt->prepare($update_sql)) {
				$OK = $update_stmt->execute();
			} //$update_stmt->prepare($update_sql)
*/

			if ($OK) { // tell the user the password was successfully reset 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" href="../../application/templates/grad_favicon.ico" />
		<title>Password Reset</title>
		<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</head>
	<body>
		<div id="content">
			<a href="../../drupal6"><img src='../../drupal6/sites/default/files/acquia_marina_logo.png' /></a>
			<h1>Password Reset</h1>
			<div id="forgot">
				<div id="recoverForm">
					<div id="" class="">
						<p>Your password has successfully been reset. Please click <a href="./">here</a> to log in.</p>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</body>
</html>
		<?
			} //$OK
			else {
				echo $update_stmt->$error;
			}
		} //$check_user['login_email'] != "" && $code != ""
		else {
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" href="../../application/templates/grad_favicon.ico" />
		<title>Error</title>
		<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</head>

	<body>
		<div id="content">
			<a href="../../drupal6"><img src='../../drupal6/sites/default/files/acquia_marina_logo.png' /></a>
			<h1>Error</h1>
			<div id="forgot">
				<div id="recoverForm">
					<div id="" class="">
						<p>This link is no longer valid. Make sure you click the link in the most recent password reset email you received. If you did not receive the email, try submitting another password reset request and then check your email.</p>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</body>
</html>
		<?
		}
	} //isset($_POST['new_password']) && isset($_POST['new_password_confirm']) && isset($_POST['email']) && isset($_POST['code'])
} //$_POST
else if ($_GET) {
	if (isset($_GET['email']) && isset($_GET['code'])) {
		$email      = $_GET['email'];
		$code       = $_GET['code'];
		// connect to the database and make sure the email and code match


		$db = new Database();
		$db->connect();
		$check_result = $db->query("SELECT `login_email` FROM `applicants` WHERE  `login_email` = '%s' AND `forgot_password_code` = '%s'", $email, $code);
		$check_user = $check_result[0];
		$db->close();

/*
		$check_conn = dbConnect('admin');
		// prepare the query
		$check_sql  = "SELECT `login_email` FROM `applicants` WHERE  `login_email` = '$email' AND `forgot_password_code` = '$code'";
		// submit the query and capture the result
		$check_result = $check_conn->query($check_sql) or die(mysql_error());
		mysqli_close($check_conn);
		// make key/value array of user
		$check_user = $check_result->fetch_assoc();
*/

		if ($check_user['login_email'] != "") { // reset password form 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" href="../../application/templates/grad_favicon.ico" />
		<title>Reset Password</title>
		<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<script type="text/javascript" src="../../application/libs/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../jquery.validate.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("#recoverForm").validate({
					rules: {
						new_password_confirm: {equalTo: "#new_password"},
					}
				});
			});
		</script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("#recoverForm").validate();
			});
		</script>
		<link rel="stylesheet" href="forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</head>

	<body>
		<div id="content">
			<a href="../../drupal6"><img src='../../drupal6/sites/default/files/acquia_marina_logo.png' /></a>
			<h1>Reset Password</h1>
			<div style="clear:both"></div>
			<div id="forgot">
				<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" accept-charset="utf-8" id="recoverForm">
					<fieldset id="" class="">
						<legend>Password Recovery</legend>
						<p><label for="create_password">new password<br /><input class="required password" minLength="2" type="password" name="new_password" value="" id="new_password" size="27"/></p></label>
						<div style="clear:both"></div>
						<p><label for="create_password_confirm">confirm password<br /><input class="required password" minLength="2" type="password" name="new_password_confirm" value="" id="new_password_confirm" size="27"/></p></label>
						<div style="clear:both"></div>
						<input type="hidden" name="email" value="<?= $email ?>" id="email"/>
						<input type="hidden" name="code" value="<?= $code ?>" id="code"/>
						<p><input type="submit" value="Reset password"/></p>
					</fieldset>
				</form>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</body>
</html>
		<?
		} //$check_user['login_email'] != ""
		else {
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" href="../../application/templates/grad_favicon.ico" />
		<title>Error</title>
		<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href="forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</head>

	<body>
		<div id="content">
			<a href="../../drupal6"><img src='../../drupal6/sites/default/files/acquia_marina_logo.png' /></a>
			<h1>Error</h1>
			<div id="forgot">
				<div id="recoverForm">
					<div id="" class="">
						<p>This link is no longer valid. Make sure you click the link in the most recent password reset email you received. If you did not receive the email, try submitting another password reset request and then check your email.</p>
					</div>
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</body>
</html>
			<?
		}
	} //isset($_GET['email']) && isset($_GET['code'])
} //$_GET
else {
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" href="../../application/templates/grad_favicon.ico" />
		<title>Forgot Password</title>
		<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<script type="text/javascript" src="../../application/libs/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="../jquery.validate.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("#recoverForm").validate();
			});
		</script>
		<link rel="stylesheet" href="forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	</head>
	<body>
		<div id="content">
			<a href="../../drupal6"><img src='../../drupal6/sites/default/files/acquia_marina_logo.png' /></a>
			<h1>Forgot Password</h1>
			<div style="clear:both"></div>
			<div id="forgot">
				<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" accept-charset="utf-8" id="recoverForm">
					<fieldset id="" class="">
						<legend>Password Recovery</legend>
						<label for="email"><p>e-mail address</p></label><input type="text" spellcheck="false" class="required email" name="email" id="email" size="27"/>
						<div style="clear:both"></div>
						<p><input type="submit" value="Reset password"/></p>
						<p>If you forgot your password, type your email address and we&rsquo;ll send you an email with instructions to reset your password.</p>
					</fieldset>
				</form>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
		</div>
	</body>
</html>
<? } ?>
