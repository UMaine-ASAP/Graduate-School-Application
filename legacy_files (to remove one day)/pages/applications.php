<?php
include_once '../libs/corefuncs.php';
include_once '../libs/database.php';
include_once '../libs/variables.php';

// connect to database
$db = new Database();
$db->connect();
$applications = array();
$db->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<title>Apply to the University of Maine Graduate School</title>
	<link rel="stylesheet" href="../styles/reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	<link type="text/css" href="../styles/jquery/redmond/jquery-ui-1.8.2.custom.css" rel="Stylesheet" />


	<!-- Javascript libraries -->
	<script type="text/javascript" src="../libs/js/browser_check.js" charset="utf-8"></script>
	<script type="text/javascript" src="../libs/js/sha1-min.js" charset="utf-8"></script>
	<script type="text/javascript" src="../libs/js/jquery/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../libs/js/jquery/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript" src="../libs/js/jquery/jquery.validate.js"></script>


	<style type="text/css" media="screen">
	
	html {
		background-image:url('../images/background-tile2.png');
	}
	
	body {
		text-align:center;
		font-family: Verdana, Arial, sans-serif;
	}
	
	.gradHeader {
		width:100%;
		height:85px;
		margin-top:10px;
		background-image:url('../images/grad_logo.png');
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
	
	#applications {
		text-align: center;
		background: #DFE9ED;
		text-align: top;
		padding: 5px;
		-moz-border-radius: 8px;
		-webkit-border-radius: 8px;
	}
	#application-data {
		margin: 1em auto 1em auto;
	}
	
	#application-data thead {
		font-weight: bolder;
	}
	
	
	#application-data td {
		padding-top: 5px;
		padding-left: 30px;
	}
	
	</style>
	<link rel="shortcut icon" href="../images/grad_favicon.ico" />
</head>

<body onLoad="doBrowserCheck();">
	<div id="content">
	<!--	<div class="gradHeader"></div><br/> -->
		<a href="<?php echo $GLOBALS['graduate_homepage'];?>"><img src="../images/grad_logo.png" /></a>

		<div style="clear:both"></div>

		<div id="applications">
			<div id="submit_message"><?php print $submit_message; ?></div>
			<a href='create_application.php'>Create Application</a>
			<table id='application-data'>
				<thead>
					<td>Semester</td>
					<td>Type</td>
					<td>Program</td>
					<td>Status</td>
					<td></td>
				</thead>
				<?php foreach( $applications as $application ) { ?>
					<tr></tr>
					
				<?php }
				if ( count($applications) == 0 ) {
					echo "<tr><td colspan='5'>No Applications</td></tr>";
				}
				?>
			</table>
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
