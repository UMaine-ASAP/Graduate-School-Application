<?php include_once '../libs/variables.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	
	<head>
		<link rel="shortcut icon" href="../application/images/grad_favicon.ico" />
	    <!-- charset must remain utf-8 to be handled properly by Processing -->
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Recommendation Submittal Successful</title>
		<link rel="stylesheet" type="text/css" href="../styles/app_manager_style.css" media="screen,print" />
		<link rel="stylesheet" type="text/css" href="../styles/sub_manager_style.css" media="screen,print" />

		<!-- Javascript libraries -->
		<script type="text/javascript" src="../application/libs/js/form_helper.js"></script>
	</head>
	
	<body>
		<div id="mainbody">
			<div class="gradHeader"><a href="<?php echo $GLOBALS['graduate_homepage'];?>">&nbsp;</a></div>
			<div class="topHeader"></div>
			<!--
			<div id="sidebar_pane">
				<div id="header">
					Welcome,
				</div>
				<div id="progress">
				</div>
				<div id="sections">
				</div>
				<div id="footer">
			
				</div>
			</div>
			-->
			<div id="form_pane" style="min-height:500px; margin:0px auto; max-width:650px;">
				<h1 style="text-align:center;">Your recommendation was submitted successfully.
				<br/>
				<p>Thank you for supporting the University of Maine's graduate mission!</p>
				<br/>
				<p>
				<a href="<?php echo($GLOBALS['graduate_homepage']);?>">Click here to return to the Graduate School homepage</a>
				</p></h1>
				<br/>
				<br/>
				
			</div>
			
			<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
			</div>
			
		</div>
	</body>
</html>