<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Reset Password</title>
	<link rel="stylesheet" href="reset-min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	<style type="text/css" media="screen">
	
	body {
		text-align:center;
	}
	
	#content {
		width:50em;
		text-align:left;
		margin-left:auto;
		margin-right:auto;
	}
		
	h1 {
		margin-top:1em;
		margin-bottom:1.5em;
		font-size: 2em;
		font-family: "Lucida Grande", Verdana, Arial, sans-serif;
	}
	</style>
</head>

<body>
	<div id="content">
		<h1>Reset Password</h1>
		<p>Your identity has been confirmed successfully. Type your new password here.</p>
		<form action="self" method="post" accept-charset="utf-8">
			<p><input type="text" name="password" value="password" id="password" onFocus="if (this.value == 'password') {this.value = ''; this.type='password'}" onBlur="if (this.value == '') {this.value='password'; this.type = 'text'} else this.type='password'"/></p>
			<p><input type="text" name="password_confirm" value="confirm password" id="password_confirm" onFocus="if (this.value == 'confirm password') {this.value = ''; this.type='password'}" onBlur="if (this.value == '') {this.value='confirm password'; this.type = 'text'} else this.type='password'"/></p>
			<p><input type="submit" value="Reset password"/></p>
		</form>
	</div>
	
</body>
</html>
