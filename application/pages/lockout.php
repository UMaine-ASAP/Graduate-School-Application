<?php include_once '../libs/variables.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	
	<head>
		<link rel="shortcut icon" href="../images/grad_favicon.ico" />
	    <!-- charset must remain utf-8 to be handled properly by Processing -->
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>UMaine Graduate Application</title>
		<link rel="stylesheet" type="text/css" href="../styles/app_manager_style.css" media="screen,print" />

		<!-- Data -->		
		<style type="text/css">
			a:link, a:visited {
				color:#008AA3;
			}
			
			a:hover, a:active {
				color:#008AD6;
				text-decoration:underline;
			}
			#form_pane {
				padding:0 3em;
			}
		</style>
	</head>
	
	<body>
		<div id="mainbody">
			<div class="gradHeader"><a href="<?php echo($GLOBALS['graduate_homepage']);?>">&nbsp;</a></div>
			<div class="topHeader"></div>
			<div id="form_pane" style="min-height:500px; margin:0px auto; max-width:650px;">
				<h1 style="text-align:center;">You have already submitted an application</h1>
				<br/>
				<br/>
				<form id="pdf_export" method="post" action="../scripts/downloadApplication.php">
					<input style="margin:0px auto;" type="submit" id="final_submit_app" name="final_submit_app" value="Download Printable Application" />
				</form>
				<p>Note: the application will download to your computer as a PDF file which contains personal information. If you are on a public computer, please make sure to delete this PDF when you are done with it.</p>
				<br/>
				<a href="<?php echo($GLOBALS['graduate_homepage']);?>">Click here to return to the Graduate School homepage</a>
				<br/>
				<br/>
				<p style="text-align:center;">If you wish to apply again, please contact the University of Maine Graduate School 
				<br/> at (207) 581-3291 or email <a href="mailto:graduate@maine.edu">graduate@maine.edu</a></p>
				
			</div>
			
			<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
			</div>
			
		</div>
	</body>
</html>
