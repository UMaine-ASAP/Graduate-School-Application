<?
//include 'includes/connection.php';
include '../../application/libs/database.php';
include 'includes/corefuncs.php';

// connect to database
$db = new Database();
$db->connect();

if ($_GET) {
	if ($_GET['type'] == 'signin') {
		// check the database to see if the user has logged in. Return LOGGEDIN if the user has logged in, MISMATCH if the user exists but the password doesn't match, and NOUSER if the user does not exist
		$email = $_GET['email'];
		$password = $_GET['password'];
		
		$user_result = $db->query("SELECT `applicant_id`, `password` FROM `applicants` WHERE `login_email` = '%s'", $email);
		$user = $user_result[0];

/*
		// connect to MySQL
		$signin_conn = dbConnect('admin');
		// prepare the query
		$user_sql = "SELECT `applicant_id`, `password` FROM `applicants` WHERE `login_email` = '%s'";
		// submit the query and capture the result
		$user_result = $signin_conn->query($user_sql, $email) or die(mysqli_error());
		mysqli_close($signin_conn);
		// make key/value array of user
		$user = $user_result->fetch_assoc();
*/

		// validate results
		if ($user != '') {
			$hash = $user['password'];
			$id = $user['applicant_id'];
			if ($hash == sha1($password)) {
				user_login($id);
				if (check_ses_vars()) {
					echo "LOGGEDIN";
				}
			} else {
				echo "MISMATCH";
			}
		} else {
			echo "NOUSER";
		}
		
		
		
	} else if ($_GET['type'] == 'create') {
		$email = $_GET['email'];
		// check the database to see if the user already exists. Return YES if they do and NO if they don't


		$dupe_result = $db->query("SELECT `applicant_id`, `password` FROM `applicants` WHERE `login_email` = '%s'", $email);
		$dupe = $dupe_result[0];

/*
		$dupe_conn = dbConnect('admin');
		// prepare the query
		$dupe_sql = "SELECT `login_email` FROM `applicants` WHERE `login_email` = '%s'";
		// submit the query and capture the result
		$dupe_result = $dupe_conn->query($dupe_sql, $email) or die(mysqli_error());
		mysqli_close($dupe_conn);
		// make key/value array
		$dupe = $dupe_result->fetch_assoc();
*/

		if ($dupe != '') {
			echo "YES";
		} else {
			echo "NO";
		}
	}
}

$db->close();
?>
