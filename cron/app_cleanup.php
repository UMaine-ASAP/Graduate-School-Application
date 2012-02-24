<?PHP

	include_once "../application/libs/variables.php";
	include_once "../application/libs/database.php";
	include_once "../application/libs/corefuncs.php";

	//Function to delete records from all tables
	function delete_records($id){
		$db = new Database();
		$db->connect();

		$db->iquery("DELETE FROM progress WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM previousschools WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM languages WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM international WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM gre WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM extrareferences WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM dviolations WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM cviolations WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM appliedprograms WHERE applicant_id=%d", $id);
		$db->iquery("DELETE FROM applicants WHERE applicant_id=%d LIMIT 1", $id);
	}

	function safeUnlink($filepath) {
		if ( file_exists($filepath) ) {
			unlink($filepath);
		}
		return TRUE;
	}

	function delete_essays($id){
		global $essays_path;
		global $resumes_path;

		$db = new Database();
		$db->connect();

		$openEssays = opendir($essays_path) or die ('Could not open '.$essays_path);

		$result = $db->query("SELECT * FROM applicants WHERE applicant_id = %d LIMIT 1", $id);
		$info   = $result[0];

			$exDOB  	= explode("/", $info['date_of_birth']);
			$newDOB 	= $exDOB[0].$exDOB[1].$exDOB[2];
			$name 		= $info['given_name'];
			$familyname = $info['family_name'];
			$id 		= $info['applicant_id'];

		$resume = "resume_" . $id . "_" . $name . "_" . $familyname . "_" . $newDOB . ".pdf";
		$essay  = "essay_"  . $id . "_" . $name . "_" . $familyname . "_" . $newDOB . ".pdf";

		$essay_link  = $essays_path  . $essay;
		$resume_link = $resumes_path . $resume;

		if(!safeUnlink($essay_link)){
			//echo "Essay Delete Failed";
		}
		if(!safeUnlink($resume_link)){
			//echo "Resume Delete Failed";
		}
	}

	function delete_references($id) {
		global $recommendations_path;

		$db = new Database();
		$db->connect();


		$openReferences = opendir($recommendations_path) or die ('Could not open '.$recommendations_path);

		$result = $db->query("SELECT reference1_filename, reference2_filename, reference3_filename FROM applicants WHERE applicant_id = %d LIMIT 1", $id);
		$info   = $result[0];

		$reference1 = $info['reference1_filename'];
		$reference2 = $info['reference2_filename'];
		$reference3 = $info['reference3_filename'];
						
		if($reference1 != '') { safeUnlink($recommendations_path.$reference1); }
		if($reference2 != '') { safeUnlink($recommendations_path.$reference2); }
		if($reference3 != '') { safeUnlink($recommendations_path.$reference3); }

		$result = $db->query("SELECT * FROM extrareferences WHERE applicant_id = %d", $id);
		foreach( $result as $extraReference) {
			$filename = $extraReference['reference_filename'];
			if($filename != '') {
				safeUnlink($recommendations_path.$filename);
			}
		}
	}


$db = new Database();
$db->connect();

//Because the date's aren't stored as unix timecodes, we have to do all the checking PHP side
$query = "SELECT family_name, given_name, date_of_birth, applicant_id, application_submit_date FROM applicants";
$find_users = $db->query($query);

//Find all applications older than 180 days based on creation date and current time
foreach($find_users as $values) {
	$id = $values['applicant_id'];
	$submit_date =  strtotime($values['application_submit_date']);
	$six = strtotime("-180 days");

	if($six > $submit_date && $submit_date != null ){
		//echo "<div>ID: $id with date: $submit_date</div>";
		delete_essays($id);
		delete_references($id);

		//Delete completed application
		$exDOB     = explode("/", $values['date_of_birth']);
		$newDOB    = $exDOB[0].$exDOB[1].$exDOB[2];
		$pdftitle = $id."_".$values['family_name']."_".$values['given_name']."_".$newDOB.".pdf";	

		safeUnlink($GLOBALS['completed_pdfs_path'] . $pdftitle);


		//Delete database information
		delete_records($id);
		//echo "<div>Delete successful</div>";
	}
}





/*

// Set Vars for Essays + Resumes and PDFs
$essays_path = './essays/';
$completed_pdfs_path = './pdf_export/completed_pdfs/';

// Set vars for opening both directories
$openEssays = opendir($essays_path) or die ('Could not open '.$essays_path);
$openApps = opendir($completed_pdfs_path) or die ('Could not open '.$completed_pdfs_path);

// Remove Essays and Resumes
// Iterate through each item in the directory
while ($oldFile = readdir($openEssays)) {
	if( $oldFile == '.svn') { continue; }	
	// If the date of creation on the file is older than 180 days, delete it
	if ((filemtime($essays_path.$oldFile)) < (strtotime('-180 days'))) {
		unlink($essays_path.$oldFile);
		echo "<div>file: $essays_path.$oldFile deleted</div>";
	}
}

// Remove Applications
// Iterate through each item in the directory
while ($oldFile = readdir($openApps)) {
	if( $oldFile == '.svn') { continue; }
	// If the date of creation on the file is older than 180 days, delete it
	if ((filemtime($application_old.$oldFile)) < (strtotime('-180 days'))) {
	  	unlink($application_old.$oldFile);
		echo "<div>file: $application_old.$oldFile deleted</div>";	  	
 	}
}

// Close Directories
closedir($essays_path);
closedir($completed_pdfs_path);
*/

?>
