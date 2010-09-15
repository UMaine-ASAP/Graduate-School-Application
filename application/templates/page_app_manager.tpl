<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	
	<head>
		<title>{{TITLE}}</title>

		<link rel="shortcut icon" href="images/grad_favicon.ico" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />

		<link rel="stylesheet" type="text/css" href="styles/app_manager_style.css" media="screen,print" />
		<!--[if IE]><link rel="stylesheet" type="text/css" href="styles/ie_app_manager_style.css" media="screen,print" /><![endif]--> 

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
		<script type="text/javascript" charset="utf-8">
			// stop the Return/Enter key from submitting the form
			document.onkeypress = stopRKey;
		</script>
	</head>
	
	<body>
		<div id="mainbody">
			<div class="gradHeader"><a href="../drupal6">&nbsp;</a></div>

			<noscript>
				<meta http-equiv="refresh" content="5; URL=pages/no_javascript.php">
				<style type="text/css" media="screen">#form_pane, .topHeader, #sidebar_pane {display:none;}</style>
				<div id="noscript"><p>You do not have javascript enabled. In a moment you will be redirected to a page with instructions to enable JavaScript. If you are not redirected in five seconds, click <a href="pages/no_javascript.php">here</a>.</p>
				</div>
			</noscript>

			<div class="topHeader">Welcome <span style="font-weight:bold;">{{EMAIL}}</span>&nbsp;&nbsp;&nbsp;[&nbsp;<a href="pages/logout.php" style="color:#FFF;">Sign Out</a>&nbsp;]</div>
			<div id="sidebar_pane">
				<div id="header">
					Welcome{{NAME}}
				</div>
				<div id="progress">
					{{WARNINGS}}
				</div>
				<div id="sections">
					{{SECTION_CONTENT}}
				</div>
				<div id="footer">
				<form id="submit_form" method="post">
					<input type="submit" id="submit_app" name="submit_app" value="Review Application"/>
				</form>
				<form id="sign_out" method="link" action="pages/logout.php">
					<input type="submit" id="sign_out" name="sign_out" value="Sign Out"/>
				</form>
				<input type="button" name="save_button" value="Save" id="save_button" onClick="alert('Your progress has been saved')" />
				<!--
				<form name="footer_options" method="post">
					<div style="float:left;width:33%;"><input type="submit" id="prev_button" value="Prev" /></div>
					<div style="float:left;width:34%;;" ><input type="submit" id="save_button" value="Save" /></div>
					<div style="float:left;width:33%;"><input type="submit" id="next_button" value="Name" /></div>
					<div style="clear:both;"></div>
				</form>-->
				</div>
			</div>					
			<div id="form_pane">
				{{FORM}}
			</div>
			<div style="clear:both"></div>
			
			<div class="gradFooter">
			The University of Maine, Orono, Maine 04469 <br />
			(207) 581-3291 <br />
			A Member of the University of Maine System
			</div>
		</div>
	</body>
</html>
