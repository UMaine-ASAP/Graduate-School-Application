<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	
<head>
	<title>{{TITLE}}</title>

	<link rel="shortcut icon" href="{{FAVICON}}" />

    	<!-- charset must remain utf-8 to be handled properly by Processing -->
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

	<link rel="stylesheet" type="text/css" href="styles/app_manager_style.css" media="screen,print" />
	<link rel="stylesheet" type="text/css" href="styles/sub_manager_style.css" media="screen,print" />

	<!-- JQUERY -->
	<link type="text/css" href="styles/jquery/redmond/jquery-ui-1.8.2.custom.css" rel="Stylesheet" />
	<script type="text/javascript" src="libs/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="libs/jquery/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript" src="libs/jquery/jquery.validate.js"></script>
	<!-- END JQUERY -->

	<script type="text/javascript" src="libs/ajaxupload.3.5.js" ></script>

	<script type="text/javascript" src="libs/form_helper.js"></script>
	<script type="text/javascript" src="libs/state.js"></script>
	<script type="text/javascript" src="libs/country.js"></script>
	<script type="text/javascript" src="libs/academics.js"></script>
</head>

<body>
	<div id="mainbody">
		<div class="gradHeader"><a href="{{GRADHOMEPAGE}}">&nbsp;</a></div>
		<div class="topHeader">Welcome <span style="font-weight:bold;">{{EMAIL}}</span>&nbsp;&nbsp;&nbsp;[&nbsp;<a href="pages/logout.php" style="color:#FFF;">Sign Out</a>&nbsp;]</div>
		<div id="sidebar_pane">
			<div id="header">
				Welcome, {{NAME}}
			</div>
			<div id="progress">
				Click on any section to return to editing your application.
			</div>
			<div id="sections">
				{{SECTION_CONTENT}}
			</div>
		</div>
			
		<div id="form_pane">

			{{PERSONAL_INFO}}
			
			<p style="font-size:1.2em;" class="title">You applied to the following programs:</p>

			{{PROGRAM_LIST}}
			
			<div class="programTop">
				<div class="iprogram_notes">&nbsp;</div>
				<div class="iprogram_name">You applied to {{PROGRAM_COUNT}} academic program{{S}}.</div>
				<div class="iprogram_status"><strong>{{TOTAL_COST}}</strong></div>
			</div>
			
			<div style="display:clear;"></div>
<!--				
			<label class="one_line2" for="paynow" style="width:100%;font-size:1.2em;">
				<p class="title">Pay online now or pay by cash or check later?</p>
				<div style="display:clear;"></div>
				<label for="paynow_yes" style="float:left;"><input style="float:left;" type="radio" id="paynow_yes" name="paynow" value="1" onclick="document.getElementById('submitButton').innerHTML = 'Proceed to payment'; /*document.getElementById('pay_later_text').innerHTML = ''*/" />Pay now</label>
				<label for="paynow_no"><input style="float:left;" type="radio" id="paynow_no" name="paynow" value="" onclick="document.getElementById('submitButton').innerHTML='Submit Application'; /*document.getElementById('pay_later_text').innerHTML = 'APPLICATIONS WILL NOT BE PROCESSED OR REVIEWED WITHOUT THE REQUIRED APPLICATION FEE. Admissions decisions cannot be made until the complete application is received.'*/"/>Pay later</label>

				<div style="display:clear;"></div>
			</label>
-->
			<div style="display:clear;"></div>

			<div id="disclaimer">
				<p class="message">ALL APPLICATION MATERIALS BECOME PART OF THE PERMANENT RECORDS OF THE GRADUATE SCHOOL AND ARE NOT RETURNED. It is your responsibility to be sure your application material (including application fee) are complete and have all been received by the Graduate School.</p>

				<em><p class="message required" id="pay_later_text">APPLICATIONS WILL NOT BE PROCESSED OR REVIEWED WITHOUT THE REQUIRED APPLICATION FEE. Admissions decisions cannot be made until the complete application is received.</p></em>

				<p class="message">In complying with the letter and spirit of applicable laws and in pursuing its own goals of pluralism, the University of Maine shall not discriminate on the grounds of race, color, religion, sex, sexual orientation, national origin or citizenship status, age, disability, or veterans status in employment, education, and other areas of the University. The University provides reasonable accommodations to qualified individuals with disabilities upon request. Questions and complaints about discrimination in any area of the University should be directed to the Director of Equal Opportunity, 101 N. Stevens, 207-581-1226. Inquiries about discrimination may also be referred to the Maine Human Rights Commission, U.S. Equal Employment Opportunity Commission, Office for Civil Rights for U.S. Department of Education or other appropriate federal or state agencies.</p>

			</div>

			<div id="terms">
				<p class="message terms">I verify that all information submitted in this application is true to the best of my knowledge and that I understand that falsifying information will result in denial of admission and possible prosecution as appropriate under the law.</p>
				<label for="accept_terms" class="one_line"> 
					<input type="checkbox" id="accept_terms" name="accept_terms" value="1" onchange="saveCheckValue(event,{{USER}});" onclick="visibility('buttons',this.checked?'block':'none');" />
					<span class="required title">Yes, I accept the Terms of Conditions</span>
				</label>
				<script type="text/javascript">checkInitValue('accept_terms',"{{ACCEPT_TERMS}}");</script>
				<div style="display:clear;"></div>
			</div>

			<div style="display:clear;"></div>

			<!-- Creates button -->
			<div id="buttons" class="hidden" style="margin-left:3px;padding-left:6px;">

				<form id="pdf_export" method="post" action="../pdf_export/pdf_export_user.php">
					<input type="submit" id="final_submit_app" name="final_submit_app" value="Download Printable Application" target="_blank"/>
				</form>
				<p>Note: the application will download to your computer as a PDF file which contains personal information. If you are on a public computer, please make sure to delete this PDF when you are done with it.</p>

				<br/>
				
				<button name="submitPayNow" id="submitPayNow">Submit Application with Payment</button>
				<button name="submitPayLater" id="submitPayLater">Submit Application without Payment</button>
<!--				<button name="submitButton" id="submitButton">Select a Payment Option</button> -->

<script type="text/javascript">
// on click this button sends you to payment or a contact gradschool for payment page, makes the pdf/locks account, and sends emails to recommenders
	$("#submitPayNow").click(function() {
			$("#submitPayNow").attr("disabled", "true");
			$("#submitPayLater").attr("disabled", "true");
			$.post("../pdf_export/pdf_export_server.php");
			$.post("../application/recommender.php");
			var url = "../application/send_payment.php";    
			$(location).attr('href',url);	
});
	$("#submitPayLater").click(function() {
			$("#submitPayLater").attr("disabled", "true");
			$("#submitPayNow").attr("disabled", "true");
			$.post("../application/recommender.php", function(data) {
				$.post("../application/mailPayLater.php", function(data) {
					$.post("../pdf_export/pdf_export_server.php", function(data) {
						var url = "../application/success.php";
						$(location).attr('href',url);
					});
				});
			});
});

//	$("#submitButton").click(function() {
//		var paynow = document.getElementById("paynow_yes");
//		var paylater = document.getElementById("paynow_no");
//		if(paynow.checked) {
//			$("#submitButton").attr("disabled", "true");
//			$.post("../pdf_export/pdf_export_server.php");
//			$.post("../application/recommender.php");
//			var url = "../application/send_payment.php";    
//			$(location).attr('href',url);
//		} else if(paylater.checked){
//			$("#submitButton").attr("disabled", "true");
//			$.post("../application/recommender.php", function(data) {
//				$.post("../application/mailPayLater.php", function(data) {
//					$.post("../pdf_export/pdf_export_server.php", function(data) {
//						window.location = "../application/success.php";
//					});
//				});
//			});
//		}
//	});
</script>
			
			</div> <!-- buttons div -->
		</div><!--end form_pane div-->
		
		<div class="gradFooter">
		The University of Maine, Orono, Maine 04469 <br />
		(207) 581-3291 <br />
		A Member of the University of Maine System
		</div>
		
	</div> <!-- end main body div-->
</body>
</html>
