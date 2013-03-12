<!-- Template for two-column form field table structure:

<table class="two">
	<tr>
		<td><span class="field">FIELD:</span> <span class="value">VALUE</span></td>
		<td><span class="field">FIELD:</span> <span class="value">VALUE</span></td>
	</tr>
</table>

<table class="three">
	<tr>
		<td><span class="field">FIELD:</span> <span class="value">VALUE</span></td>
		<td><span class="field">FIELD:</span> <span class="value">VALUE</span></td>
		<td><span class="field">FIELD:</span> <span class="value">VALUE</span></td>
	</tr>
</table>

 -->

<html xmlns="htt/*  */p://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=macintosh"/>
<style type="text/css">

body {
	width:7.5in;
	margin: 0 auto;
	padding:0;
	font-family: "Times New Roman";
	font-size: 10pt;
	line-height:10pt;
	color: #333;
}


.center { text-align: center; }

h1 {
font-size: 22px;
}

h2 {
font-size: 16px;
margin:.5em;
}

h3 {
	margin-top: 16pt;
	margin-bottom:8pt;
}
ul {
	list-style:none;
	padding-left:6pt;
}

a:link{
	color:blue;
	text-decoration:underline;
	text-underline:single;
}
a:visited {
	color:purple;
	text-decoration:underline;
	text-underline:single;
}

div.fieldset {
	border:0.1mm solid #000000;
	padding: 4px;
}

div.fieldset p {
	line-height:130%
	page-break-before:avoid;
	margin: 0 auto 6pt auto;
}

p.note {
	font-size:8pt;
	line-height:100%;
}

p.center {
	text-align:center;
}

p.bold {
	font-weight:bold;
}

div.fieldset table , table.box{
	width:100%;
	border-collapse: collapse;
}

table.box td{
	border:0.1mm solid #000000;
	padding: 5px;
}

div.fieldset table.two tr td {
	width:50%;
	padding-right:12pt
}

div.fieldset table.three tr td {
	width:33%;
	padding-right:12pt;
	padding-bottom:12pt;
}

td {
}

tr {
}

span.field { /* a form field title */

}

span.value, pre { /* a form field value entry */
	font-family: "Times New Roman";
	font-weight:bold;
	font-size: 12pt;
	line-height: normal;
}

span.tab {
	margin-right:12pt;
}
</style>
</head>
<body>
  <h2 class='center'>UNIVERSITY OF MAINE</h2>
  <h2 class='center'>APPLICATION FOR ADMISSION TO THE GRADUATE SCHOOL</h2>
  <p align=center><b><span>Graduate School, 42 Stodder Hall, University of Maine, Orono, ME 04469-5755</span></b></p>
  <div align=right style='margin: .5em;'><span>This application was submitted on: </span><b><span>{{SUBMISSION_DATE}}</span></b></div>
  <div align=right style='margin: .2em;'><span>External Application ID: </span><b><span>{{APPLICANT_ID}}</span></b></div>
  <div align=right style='margin: .2em;'><span>Method:</span><b><span>{{APPLICANT_PAYMENT_METHOD}}</span></b></div>
  <h3>Basic Information</h3>
  <div class="fieldset">
	<table class="two">
		<tr>
			<td><span class="field">Full Name:</span> <span class="value">{{FAMILY_NAME}}, {{GIVEN_NAME}}, {{MIDDLE_NAME}}</span></td>
			<td><span class="field">Gender:</span> <span class="value">{{GENDER}}</span></td>
		</tr>
		<tr>
			<td><span class="field">Maiden/Former Name:</span> <span class="value">{{ALTERNATE_NAME}}</span></td>
			<td><span class="field">Date of Birth: <span class="value">{{DATE_OF_BIRTH}}</span></td>
		</tr>
		<tr>
			<td><span class="field">Social Security Number:</span> <span class="value">{{SOCIAL_SECURITY_NUMBER}}</span></td>
			<td><span class="field">Place of Birth:</span> <span class="value">{{BIRTH_CITY}} {{BIRTH_STATE}} {{BIRTH_COUNTRY}}</span></td>
		</tr>
	</table>
     <hr size=1>
	<table class="two">
		<tr>
			<td><span class="field">Present Occupation:</span> <span class="value">{{PRESENT_OCCUPATION}}</span></td>
			<td><span class="field"></span> <span class="value"></span></td>
		</tr>
	</table>
    
      <hr size=1>
    <p class="note center bold">If you are a resident alien, please enclose a copy of your green card with your application.</p>

	<table class="two">
		<tr>
			<td><span class="field">Country of Citizenship:</span> <span class="value">{{COUNTRY_OF_CITIZENSHIP}}</span></td>
			<td><span class="field">Residency Status:</span> <span class="value">{{RESIDENCY_STATUS}}</span></td>
		</tr>
		<tr>
			<td><span class="field">If US Citizen, Legal State of Residence:</span> <span class="value">{{US_STATE}}</span></td>
			<td><span class="field"></span> <span class="value"></span></td>
		</tr>
	</table>
  </div>

  <h3>Ethnicity</h3>
  <div class="fieldset">
    <p class="note">Note on Ethnicity: Colleges and universities are asked by many, including the federal government, accrediting associations, college guides, newspapers, and our own college/university communities, to describe the racial/ethnic backgrounds of our students and employees. In order to respond to these requests, we ask you to answer the following two questions:</span></p>
	<table class="two">
		<tr>
			<td><span class="field">Hispanic/Latino:</span> <span class="value">{{HISPANIC}}</span></td>
			<td><span class="field">Other Ethnicity:</span> <span class="value">{{ETHNICITY}}</span></td>
		</tr>
	</table>
  </div>

  <h3>Contact Information</h3>
  <div class="fieldset">
	<table class="two">
		<tr>
			<td><span class="field">Primary Phone Number:</span> <span class="value">{{PRIMARY_PHONE}}</span></td>
			<td><span class="field">Work Phone Number:</span> <span class="value">{{SECONDARY_PHONE}}</span></td>
		</tr>
		<tr>
			<td><span class="field">E-mail address:</span> <span class="value">{{EMAIL}}</span></td>
			<td><span class="field"></span> <span class="value"></span></td>
		</tr>
	</table>
	<hr size=1>
	<table class='two'>
		<tr>
			<td><span class="field">Permanent Address:</span> <span class="value"><br />{{PERMANENT_ADDR1}} {{PERMANENT_ADDR2}}<br />{{PERMANENT_CITY}}, {{PERMANENT_STATE}} {{PERMANENT_POSTAL}} {{PERMANENT_COUNTRY}}</span></td>
			<td><span class="field">Mailing Address:</span> <span class="value"><br />{{MAILING_ADDR1}} {{MAILING_ADDR2}}<br/>{{MAILING_CITY}}, {{MAILING_STATE}} {{MAILING_POSTAL}} {{MAILING_COUNTRY}}</span></td>

		</tr>
	</table>
</div>

  <h3>Academic/Criminal History</h3>
  <div class="fieldset">
    <p><span class="field">Have you ever been found responsible for a disciplinary violation at a post-secondary educational institution you have attended (or the international equivalent) whether related to academic misconduct or behavioral misconduct, that resulted in your suspension, removal, dismissal or expulsion from the institution? </span><span class="value">{{DISCIPLINARY_VIOLATION}}</span></p>
	
	{{DVIOLATIONS}}

    <p><span class="field">Have you ever been convicted of a misdemeanor felony or
      other crime, or adjudicated of committing a juvenile crime? </span><span class="value">{{CRIMINAL_VIOLATION}}</span></p>
	  
	
	{{CVIOLATIONS}}
		  
  </div>
