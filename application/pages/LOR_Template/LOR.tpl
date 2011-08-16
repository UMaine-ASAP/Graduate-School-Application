<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

	  <title>UMaine Graduate School Recommendation</title>
	  <style type="text/css">
		body {
			width:7in;
			margin: 0 auto;
			padding:0;
			font-family: "Times New Roman";
			color: #333;
			font-size: 10pt;
			line-height:10pt;
		}
	
		div.fieldset { /* not to be confused with <fieldset>, this is just a structural div */
			margin-top:10pt
		}
		
		.section { 
			text-align: center;
			margin-top: 30px;
			font-size: 18px;
		}
	
		h1 {
			text-align:center;
			font-size:16pt;
		}
	
		h3 {
			margin-bottom:4pt;
			font-size:10pt;
		}
	
		h4 {
			font-size:13pt;
			text-align:center;
			font-weight:bold;
			width:380pt;
			margin-left:auto;
			margin-right:auto;
		}
	
		div.fieldset table {
			width:100%;
			border-collapse: collapse;
		}

		div.fieldset table tr td {
			width:50%;
			padding-right:12pt;
			padding-bottom:12pt;
		}

		span.field { /* a form field title */
		
		}

		span.value { /* a form field value entry */
			font-weight:bold;
			font-size: 11pt;
		}
	
		p.right {
			text-align:right;
		}
	
		p.center {
			text-align:center;
		}
	
		.email {
			color: blue;
			text-decoration: underline;
		}

	  </style>

	</head>

	<body>
		<h1>UMAINE GRADUATE SCHOOL RECOMMENDATION</h1>
	
		<p class="right"><strong><span class="field">Date Received:</span> <span class="value">{{RECOMMENDATION_DATE_RECEIVED}}</span></strong></p>
		<h2 class='section'>Applicant Information</h2>
		<div class="fieldset">
			<table>
				<tr>
					<td><span class="field">Name:</span> <span class="value">{{APPLICANT_NAME}}</span></td>
					<td><span class="field">Date of Birth:</span> <span class="value">{{APPLICANT_DOB}}</span></td>
				</tr>
				<tr>
					<td><span class="field">Maiden/Former Name:</span> <span class="value">{{APPLICANT_FORMER_NAME}}</span></td>
					<td><span class="field">Email Address:</span> <span class="value email">{{APPLICANT_EMAIL}}</span></td>
				</tr>
			</table>
		</div>
	
		<h4><b>This applicant {{STATUS_WAIVED_VIEW_RECOMMENDATION}} waived their rights to view this recommendation.</b></h4>

		<h2 class='section'>Recommender Information</h2>
		<div class="fieldset">
			<table>
				<tr>
					<td><span class="field">Name:</span> <span class="value">{{RECOMMENDER_NAME}}</span></td>
					<td><span class="field">Title:</span> <span class="value">{{RECOMMENDER_TITLE}}</span></td>
				</tr>
				<tr>
					<td><span class="field">Employer:</span> <span class="value">{{RECOMMENDER_EMPLOYER}}</span></td>
					<td><span class="field">Email Address:</span> <span class="value email">{{RECOMMENDER_EMAIL}}</span></td>
				</tr>
				<tr>
					<td><span class="field">Phone Number:</span> <span class="value">{{RECOMMENDER_PHONE}}</span></td>
					<td></td>
				</tr>
			</table>
		</div>

		<h4><b>This recommender allows this recommendation to be used for any program
		the applicant may apply to.</b></h4>

		<h2 class='section'>Summary Evaluation Ratings (1 = lowest, 7 = highest)</h2>
	
		<p><span class="field">Academic Ability and Potential for Graduate Work:</span> <strong><span class="value">{{RECOMMENDATION_ABILITY}}</span></strong></p>
	
		<p><span class="field">Motivation for the Proposed Program of Study:</span> <strong><span class="value">{{RECOMMENDATION_MOTIVATION}}</span></strong></p>
	
		  <p>&nbsp;</p>
	
		<p>Recommendation:</p>
	
		<p><b>{{RECOMMENDATION_TEXT}}</b></p>
	</body>
</html>
