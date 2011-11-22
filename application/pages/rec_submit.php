<?php
if (count($_POST) == 0 && count($_GET) == 0){
	header("Location: signin");
}
if 	( isset($_POST['submit']) 
		&& isset($_GET['userid']) 
		&& ( isset($_GET['ref_id']) || isset($_GET['xref_id'])) 
		){
		if ($_POST['submit'] == "Submit Recommendation") {
			// validate (we're not using client-side validation on this one because 
			// they might not have javascript)
			// save stuff to database
			
			include_once "../../pdf_export/lib/tcpdf/tcpdf.php";
			include_once "../libs/variables.php";
			include_once "../libs/template.php";
			include_once "../libs/database.php";
			include_once "../libs/corefuncs.php";

			$userid = $_GET['userid'];
			$ref_id = $_GET['ref_id'];
			
			//connects to database
			$db = new Database();
			$db->connect();
			
			//*********************************************************************************************
			// Initialize PDF Writer
			//*********************************************************************************************	

			// Create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

			// Set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('University of Maine Graduate School');
			$pdf->SetTitle('Recommendation');
			$pdf->SetSubject('Grad School Recommendation');
			$pdf->SetKeywords('PDF, umaine, application, graduate school, recommendation');
		
			// Remove default header/footer
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);

			// Set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// Set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

			// Set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// Set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

			// Set some language-dependent strings
			//$pdf->setLanguageArray($l); 

			// Set font
			$pdf->SetFont('times', '', 10);
			
			//create pdf content here -----------------------------------------
			$pdfhtml = "<html><head><title>UMaine Graduate School Recommendation</title></head><body>";
			
			//applicant information
			$pdfhtml .= "<br/><br/><strong>Applicant Information: </strong><br/>";
			$pdfhtml .= "Name: ". $_POST["applicant_name"]. "<br/>";
			$pdfhtml .= "Email Address: ". $_POST['uemail']. "<br/>";
			// $pdfhtml .= "Department(s) Applied to and Degree(s) Sought:<br/> ";
			// 			
			// 			//get programs names and degrees sought
			// 			$userid = $_POST['userid'];
			// 			//queries applied program data
			// 			$qry = "SELECT * FROM appliedprograms WHERE applicant_id = ". $userid;
			// 			$result = $db->query($qry);
			// 			$programs = array();
			// 			foreach ($result as $program) {
			// 				array_push($programs, $program['academic_program']);
			// 			}
			
			//query for department headings and degrees seeking using applied program results
			// $program_names = array();
			// 			foreach ($programs as $program_code) {
			// 				$qry = "SELECT description_app FROM um_academic WHERE academic_program = '". $program_code ."'";
			// 				$result = $db->query($qry);
			// 				array_push($program_names, $result[0][0]);
			// 			}
			// 			
			// 		 	if (count($program_names) == 1) {
			// 				$pdfhtml .= "–". $program_names[0];
			// 				$pdfhtml .= "<br/>";
			// 			} else {
			// 				foreach ($program_names as $program) {
			// 				$pdfhtml .= "–". $program;
			// 				$pdfhtml .= "<br/>";
			// 				}
			// 			}
						
			//waiver of rights
			$pdfhtml .= "<strong>This applicant ". $_POST['waived'] ." waived their rights to view this recommendation</strong><br/>";
			
			//recommender information
			$pdfhtml .= "<br/><strong>Recommender Information: </strong><br/>";
			$pdfhtml .= "Name: ". $_POST['rfname'] ." ". $_POST['rlname'] ."<br/>";
			$pdfhtml .= "Title: ". $_POST['rtitle'] ."<br/>";
			$pdfhtml .= "Employer: ". $_POST['remployer'] ."<br/>";
			$pdfhtml .= "Email Address: ". $_POST['remail'] ."<br/>";
			$pdfhtml .= "Phone Number: ". $_POST['rphone'] ."<br/><br/>";
			
			//summary evaluation
			switch ($_POST['ability']) {
				case 1:
				$ability = "1 - Below Average";
				break;
				case 2:
				$ability = "2 - Average";
				break;
				case 3:
				$ability = "3 - Somewhat Above Average";
				break;
				case 4:
				$ability = "4 - Good";
				break;
				case 5:
				$ability = "5 - Unusual";
				break;
				case 6:
				$ability = "6 - Outstanding";
				break;
				case 7:
				$ability = "7 - Truly Exceptional";
				break;
				case 8:
				$ability = "Unable to Judge";
				break;
			}
			switch ($_POST['motivation']) {
				case 1:
				$motivation = "1 - Below Average";
				break;
				case 2:
				$motivation = "2 - Average";
				break;
				case 3:
				$motivation = "3 - Somewhat Above Average";
				break;
				case 4:
				$motivation = "4 - Good";
				break;
				case 5:
				$motivation = "5 - Unusual";
				break;
				case 6:
				$motivation = "6 - Outstanding";
				break;
				case 7:
				$motivation = "7 - Truly Exceptional";
				break;
				case 8:
				$motivation = "Unable to Judge";
				break;
			}
			$pdfhtml .= "<strong>Summary Evaluation Ratings: </strong>(1 = lowest, 7 = highest)<br/>";
			$pdfhtml .= "Academic Ability and Potential for Graduate Work: <strong>". $ability ."</strong><br/>";
			$pdfhtml .= "Motivation for the Proposed Program of Study: <strong>". $motivation ."</strong><br/><br/>" ;  
			
			//recommendation body
			$pdfhtml .= "<strong>Recommendation: </strong><br/>";
			$pdfhtml .= $_POST['essay'];
			
			$pdfhtml .= "</body></html>";

			$today = date("m-d-Y");			
			
			$results = $db->query("SELECT date_of_birth, alternate_name FROM applicants where applicant_id = %d", $_POST['userid']);
			$applicant_data = $results[0];

			$replace_data = Array(
				'RECOMMENDATION_DATE_RECEIVED' => $today,

				'APPLICANT_NAME' 			 => $_POST["applicant_name"],
				'APPLICANT_DOB'   			 => $applicant_data['date_of_birth'],
				'APPLICANT_FORMER_NAME' 	 => $applicant_data['alternate_name'],
				'APPLICANT_EMAIL'			 => $_POST['uemail'],
				'STATUS_WAIVED_VIEW_RECOMMENDATION' => $_POST['waived'],
	
				'RECOMMENDER_NAME' 	  => $_POST['rfname'] . " " . $_POST['rlname'],
				'RECOMMENDER_TITLE' 	  => $_POST['rtitle'],
				'RECOMMENDER_EMPLOYER' => $_POST['remployer'],
				'RECOMMENDER_EMAIL'	  => $_POST['remail'],
				'RECOMMENDER_PHONE'	  => $_POST['rphone'],

				'RECOMMENDATION_ABILITY' 	=> $ability,
				'RECOMMENDATION_MOTIVATION' => $motivation,
				'RECOMMENDATION_TEXT' 	=> $_POST['essay']
			);

			$process_template = new Template();
			$process_template->changeTemplate("LOR_Template/LOR.tpl");
			$process_template->changeArray($replace_data);
			$pdfhtml = $process_template->parse();


			//-----------------------------------------------------------------
			
			$pdf->AddPage();
			//$pdf->writeHTML($pdfhtml, true, 0, true, 0);

			$user = $_POST["applicant_name"];
			
			//Update Database with filename
			$updateQuery = "";
			if( isset($_GET['ref_id']) ) {
				$updateQuery .= "UPDATE applicants SET ";
				if( $_GET['ref_id'] == 'reference1' || $_GET['ref_id'] == 'reference2' || $_GET['ref_id'] == 'reference3') {
					$updateQuery .= $_GET['ref_id'] . "_filename = '%s'  WHERE applicant_id=%i";
				}
			} else {
				$updateQuery .= "UPDATE extrareferences SET reference_filename = '%s' WHERE applicant_id=%i";
			}
			$result = $db->query("SELECT applicant_id FROM applicants WHERE login_email_code='%s'", $userid);
			$result = $result[0];
			$id = $result['applicant_id'];
			$pdftitle = "UMGradRec_". $id ."_".$_POST['rlname'].$_POST['rfname']."_". $today .".pdf";

			$db->iquery($updateQuery, $pdftitle, $id);
			
			/*==== MPDF ====*/
			include('../../pdf_export/lib/MPDF52/mpdf.php');
			$mpdf = new mPDF();
			$mpdf->WriteHTML( utf8_encode($pdfhtml) );
			
			$mpdf->Output($recommendations_path.$pdftitle);
			/*==============*/

			$cwd = $recommendations_path . $pdftitle;
			chmod($cwd, 0664);

			//send recommender a thank you email
			$sender_name = "University of Maine Graduate School"; // sender's name
			$sender_email = "noreply@umaine.edu"; // sender's e-mail address

			///////////////////////Get Email//////////////////////////////////////////////////////////////////
			$result = $db->query("SELECT * FROM applicants WHERE login_email_code = '%s'", $userid);
			$userarray = $result[0];
			
			$fullname = $userarray['given_name']. " " .$userarray['family_name'];
			
			$ref_email = $ref_id. "_email";
			$ref_email = $userarray[$ref_email];
			
			//check for any additional references (beyond 3)
			if(isset($_GET['xref_id'])){
				$xref_id = $_GET['xref_id'];

				// Queries applicant data
				$result = $db->query("SELECT * FROM extrareferences WHERE login_email_code = '%s' ", $userid);
				$xrefarray = $result;

				foreach($xrefarray as $xref){
					if($xref_id == $xref['extrareferences_id']){
						//build recommender information 
						$ref_email = $xref['reference_email'];
					}
				}
			}
			///////////////////////End Get Email////////////////////////////////////////////////////////////
			$subject = "Thank You from the University of Maine Graduate School"; //subject
			$header  = "From: $sender_name <$sender_email>\r\nMIME-Version: 1.0\nContent-type: text/plain; charset=iso-8859-1";

			$body  = "Thank you for writing a letter on behalf of " . $fullname . "'s application to the Graduate School at the University of Maine.  We regard letters of recommendation as one of the most crucial pieces of information in evaluating an applicant's potential for success in graduate study.  We deeply appreciate your effort in supporting " . $userarray['given_name'] . "'s application and hope that as you mentor other promising students that you will encourage them to consider the University of Maine.\n\n";
			$body .= "Information on our 70 master's degree programs and 30 doctoral programs may be found at www.umaine.edu/graduate.  Please feel free to contact the Graduate School office if you would like to request additional information on any of our programs.\n\n";
			$body .= "Thanks again!\n\n";
			$body .= "Sincerely,\n\n";
			$body .= "Scott G. Delcourt\n";
			$body .= "Associate Dean\n";
			$body .= "Graduate School\n";
			$body .= "University of Maine\n";
			$body .= "(207) 581-3291\n";
			
			mail($ref_email, $subject, $body, $header);
			
			header("Location: rec_submitted.php");
	}
} else {
	
	if 	(isset($_GET['userid']) && 
			(isset($_GET['ref_id']) || isset($_GET['xref_id'])) 
		){
		include_once "../libs/corefuncs.php";
	 	include_once "../libs/database.php";
		$userid = $_GET['userid'];
		$ref_id = $_GET['ref_id'];
		
		// Connects to database
		$db = new Database();
		$db->connect();
		
		// Queries applicant data
		$result = $db->query("SELECT * FROM applicants WHERE login_email_code = '%s'", $userid);
		$userarray = $result[0];
		$userid = $userarray['applicant_id'];
		
		//build users full name
		$fullname = $userarray['given_name']. " " .$userarray['family_name'];
		
		//determine if user has waived rights to see recommendations
		$waive_view_rights = $userarray['waive_view_rights'];
		if ($waive_view_rights == 1){
			$waive_view_rights = "has";
		} else {
			$waive_view_rights = "has not";
		}
		
		//build recommender information 
		$ref_fname = $ref_id. "_first";
		$ref_fname = $userarray[$ref_fname];
		$ref_lname = $ref_id. "_last";
		$ref_lname = $userarray[$ref_lname];
		$ref_email = $ref_id. "_email";

		$ref_email = $userarray[$ref_email];
		$ref_phone = $ref_id. "_phone";
		$ref_phone = $userarray[$ref_phone];

		//queries applied program data
		$result = $db->query("SELECT * FROM appliedprograms WHERE applicant_id = %d", $userid);
		$programs = array();
		foreach ($result as $program) {
			array_push($programs, $program['academic_program']);
		}
		
		//query for department headings and degrees seeking using applied program results
		$program_names = array();
		foreach ($programs as $program_code) {
			$qry = "SELECT description_app FROM um_academic WHERE academic_program = ''";
			$result = $db->query("SELECT description_app FROM um_academic WHERE academic_program = '%s'", $program_code);
			array_push($program_names, $result[0][0]);
		}
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
		//check for any additional references (beyond 3)
		///////////////////////////////////////////////////////////////////////////////////////////////
		if(isset($_GET['xref_id'])){
			$xref_id = $_GET['xref_id'];

			// Queries applicant data
			$result = $db->query("SELECT * FROM extrareferences WHERE applicant_id = %d", $userid);
			$xrefarray = $result;
					
			foreach($xrefarray as $xref){
				if($xref_id == $xref['extrareferences_id']){
					//build recommender information 
					$ref_fname = $xref['reference_first'];
					$ref_lname = $xref['reference_last'];
					$ref_email = $xref['reference_email'];
					$ref_phone = $xref['reference_phone'];
				}
			}
		}
	} 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>Letter of Recommedation</title>
		<link rel="shortcut icon" href="<?php echo $GLOBALS['grad_images'];?>grad_favicon.ico" />
	</head>
	<link rel="stylesheet" href="../styles/reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	<link rel="stylesheet" href="../styles/forgot.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	<script type="text/javascript" charset="utf-8">
		// stop the return/enter key from accidentally submitting the form
		// the last field is a textarea anyway, which you wouldn't submit with the Return key,
		// so this won't interfere wth normal user operation at all
		function stopRKey(evt) { 
		  var evt = (evt) ? evt : ((event) ? event : null); 
		  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
		  if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
		}
		document.onkeypress = stopRKey;
	</script>
	<style type="text/css" media="screen">
		#forgot {
			width:50em;
		}
		
		textarea {
			width:48em;
			max-width:48em;
			height:20em;
		}
		
		td, th {
			border: 1px solid black;
		}
		
		table {
			font-size:.7em;
		}
		
		td, th {
			padding:4px;
			text-align:center;
		}
		
		tr:first-child, td:first-child {
			text-align:left;
		}
		
		#firstcell {
			border:none;
		}
		
		.note {
			font-size:.8em;
		}
		
		.important {
			color:#BC0000;
		}
		
		input[type="submit"] {
			float:right;
		}
		
		h1 {
			left:100px
		}
		
	</style>
	<body>
		<div id="content">
			<a href="<?php echo($GLOBALS['graduate_homepage']);?>"><img src='<?php echo $GLOBALS["grad_images"];?>grad_logo.png' /></a>
			<h1>Submit Recommendation</h1>
			<div id="forgot">
				<div id="recoverform">
					<div id="" class="">
						<p>Letter of Recommendation to: <br/> <strong>THE GRADUATE SCHOOL,</strong><br />
						The University of Maine, 5755 Stodder Hall, Room 42, Orono, ME 04469-5755<br />
						Telephone: (207) 581-3220<br/> 
						Email: <a href="mailto:graduate@maine.edu">graduate@maine.edu</a></p>
					</div>
				</div>
				<form method="post" id="recform" name="recform">
					<fieldset>
						<legend>
							Applicant&rsquo;s Information
						</legend>
													
						<p><strong>Name:</strong> <?php print $fullname; ?> </p>
						<p><strong>Email Address:</strong> <?php print $userarray['email']; ?> </p>
						
						
				
						<p><strong>Waiver of Viewing Rights</strong></p>
						<p>The Family Educational Rights and Privacy Act of 1974 (P.L. 93-380) gives students access to information in their application files. However, to ensure that references will be free to write a candid letter of recommendation, an applicant may waive the right to see letters of reference.</p>
						<p class="important">The applicant <?php print $waive_view_rights; ?> waived the right to view this recommendation.</p>
					</fieldset>
				
					<fieldset>
						<legend>
							Recommender&rsquo;s Information
						</legend>
					
						<p><label for="rfname">First Name<br />
							<input type="text" id="rfname" name="rfname" maxlength="40" size="40" value="<?php print ucwords($ref_fname); ?>"/>
						</label></p>
					
						<p><label for="rlname">Last Name<br />
							<input type="text" id="rlname" name="rlname" maxlength="40" size="40" value="<?php print ucwords($ref_lname); ?>"/>
						</label></p>
					
						<p><label for="rtitle">Title<br />
							<input type="text" id="rtitle" name="rtitle" maxlength="40" size="40"/>
						</label></p>
					
						<p><label for="remployer">Employer<br />
							<input type="text" id="remployer" name="remployer" maxlength="40" size="40"/>
						</label></p>
					
						<p><label for="remail">Email<br />
							<input type="text" id="remail" name="remail" maxlength="40" size="40" value="<?php print $ref_email; ?>"/>
						</label></p>
					
						<p><label for="rphone">Phone<br />
							<input type="text" id="rphone" name="rphone" maxlength="20" size="20" value="<?php print $ref_phone; ?>"/>
						</label></p>
				
					</fieldset>
					<fieldset>
						<legend>Summary Evaluation</legend>
					
						<p>In comparison with a representative group of students in the same field who have had approximately the same amount of experience and training, how do you rate the applicant in:</p>
					
						<table>
							<tr>
								<th rowspan="2" span="col" id="firstcell"></th>
								<th span="col">
									Below Average
								</th>
								<th span="col">
									Average
								</th>
								<th span="col">
									Somewhat<br />Above<br />Average
								</th>
								<th span="col">
									Good
								</th>
								<th span="col">
									Unusual
								</th>
								<th span="col">
									Outstanding
								</th>
								<th span="col">
									Truly Exceptional
								</th>
								<th span="col">
									Unable to Judge
								</th>
							</tr>
							<tr>
								<td>
									Lowest 40%
								</td>
								<td>
									Middle 20%
								</td>
								<td>
									Next 15%
								</td>
								<td>
									Next Highest 15%
								</td>
								<td colspan="3">
									Highest 10%
								</td>
								<td></td>
							</tr>
							<tr>
								<td>
									<strong>Academic Ability<br /> and Potential<br /> for Graduate Work</strong>
								</td>
								<td>
									<input type="radio" value="1" name="ability" title="Below Average"/>
								</td>
								<td>
									<input type="radio" value="2" name="ability" title="Average"/>
								</td>
								<td>
									<input type="radio" value="3" name="ability" title="Somewhat Above Average"/>
								</td>
								<td>
									<input type="radio" value="4" name="ability" title="Good"/>
								</td>
								<td>
									<input type="radio" value="5" name="ability" title="Unusual"/>
								</td>
								<td>
									<input type="radio" value="6" name="ability" title="Outstanding"/>
								</td>
								<td>
									<input type="radio" value="7" name="ability" title="Truly Exceptional"/>
								</td>
								<td>
									<input type="radio" value="8" name="ability" title="Unable to Judge"/>
								</td>
							</tr>
							<tr>
								<td>
									<strong>Motivation for the<br /> Proposed Program of Study<strong>
								</td>
								<td>
									<input type="radio" value="1" name="motivation" title="Below Average"/>
								</td>
								<td>
									<input type="radio" value="2" name="motivation" title="Average"/>
								</td>
								<td>
									<input type="radio" value="3" name="motivation" title="Somewhat Above Average"/>
								</td>
								<td>
									<input type="radio" value="4" name="motivation" title="Good"/>
								</td>
								<td>
									<input type="radio" value="5" name="motivation" title="Unusual"/>
								</td>
								<td>
									<input type="radio" value="6" name="motivation" title="Outstanding"/>
								</td>
								<td>
									<input type="radio" value="7" name="motivation" title="Truly Exceptional"/>
								</td>
								<td>
									<input type="radio" value="8" name="motivation" title="Unable to Judge"/>
								</td>
							</tr>
						</table>
					</fieldset>
					<fieldset>
						<legend>
							Recommendation
						</legend>
						<p>What is your estimate of the applicant&rsquo;s promise as a graduate student and promise of professional success? What are the applicants greatest strengths and weaknesses? Please state the extent of your acquaintance with the applicant. If possible, please compare the student with any others in the same field at a similar stage in his/her career. Please give your evaluation of the applicant&rsquo;s qualifications for an assistantship.</p>
				
						<p class="note"><strong>Please Note:</strong> Please provide the same kind of recommendation you would supply were you to print and mail your letter. Hence, we strongly recommend that you compose your letter normally, save it, then copy it into the space provided in this form. We also strongly recommend that you save your written recommendation in a text editor such as Notepad before copying and pasting it into the space provided on the form.</p>
				
						<!-- <p class="note important"><strong>Please Note:</strong> If copying/pasting into the text boxes below, please paste UNFORMATTED text. To do this, please save original source of text as a TEXT DOCUMENT and copy and paste from that file. If you try to copy and paste FORMATTED text from a word processing program (i.e. WORD or WORDPERFECT) you could receive an error and your nomination will NOT submit correctly.</p> -->
					
						<textarea name="essay" id="essay"></textarea>
						<div style="clear:both"></div>
				
					<p class="note important"><strong>Please Note:</strong> If you follow the directions above and still receive an error when submitting, please send your recommendation within an email (or as an attachment within an email) to the University of Maine Graduate School at <a href="mailto:graduate@maine.edu">graduate@maine.edu</a></p>
					</fieldset>
					<input type="hidden" name="userid" value="<?php print $userid; ?>" id="userid"/>
					<!--<input type="hidden" name="rec_id" value="<?php //print $rec_id; ?>" id="rec_id"/>-->
					<input type="hidden" name="applicant_name" value="<?php print $fullname; ?>" id="applicant_name"/>
					<input type="hidden" name="program_names" value="<?php print $program_names; ?>" id="program_names"/>
					<input type="hidden" name="waived" value="<?php print $waive_view_rights; ?>" id="waived"/>
					<input type="hidden" name="uemail" value="<?php print $userarray['email']; ?>" id="uemail"/>
					<input type="submit" name="submit" value="Submit Recommendation" id="submit"/>
				</form>
			</div>
		</div>
		<div style="clear:both"></div>
		<div class="gradFooter">
			The University of Maine, Orono, Maine 04469<br />
			(207) 581-3291<br />
			A Member of the University of Maine System
		</div>
	</body>
</html>

<?php } ?>
