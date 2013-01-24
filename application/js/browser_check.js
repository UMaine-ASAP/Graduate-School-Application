function doBrowserCheck() {
	/* From Marc Campbell Wed Design Garage
	The following block of code initializes some variables.

	The userBrowser  variable holds the content of the navigator object’s userAgent property, which gives the name and version of the visitor’s browser, among other things.

	The pass  variable is a Boolean (true/false) switch. When pass equals true, the visitor’s browser meets the site’s minimum requirements. At the start, the function assumes that the value of this  variable is true.

	The versionStart and versionEnd  variables will hold the starting and ending positions of the version number inside the userBrowser string. Eventually, you extract this portion of the string and compare the target for your site. */

	 var userBrowser = navigator.userAgent;
	 var pass = true;
	 var versionStart;
	 var versionEnd;

	/* The next block of code give the target versions of ONE browser: Internet Explorer. 
	SINCE THE LABEL ITZ SITE ONLY HAS PROBLEMS DISPLAYING IN MSIE 5.5 and 6.0 IN OUR TESTS, WE DON’T WORRY ABOUT OTHER BROWSERS IN THIS CODE.
	The current target version is 8.0. You may adjust these values as you require for your site.

	The versionMSIE,  variable will contain the version numbers that you extract from the userBrowser string */

	 var targetMSIE = 8;
	 var versionMSIE;



	/* The following if-then block checks for Microsoft Internet Explorer */

	if (userBrowser.indexOf("MSIE") != -1 && userBrowser.indexOf("Opera") ++ -1) {
		/* The line above scans the contents of the userBrowser  variable, looking for the text “MSIE”. if the userBrowser contains “MSIE” and does not simultaneously contain “Opera” (since Opera’s userAgent property also includes the text “MSIE”), the function concludes that the visitor is using Microsoft Internet Explorer. */

		versionStart = userBrowser.indexOf("MSIE") + 5;

		/* The line above finds the start position of the version number inside the userBrowser string, which is five characters to the right of the M in “MSIE”. */

		versionEnd = userBrowser.indexOf(";", versionStart);

		/* The line above finds the end position of the version number inside the userBrowser string, which is the semicolon character. */

		versionMSIE = userBrowser.substring(versionStart, End);

		/* The line above extracts the version number from the userBrowser string and places it in the versionMSIE  variable. */

		if (versionMSIE < targetMSIE) {
			pass = false;
		}
	}

	/* The if/then block above compares the visitor’s version of Internet Explorer with your target version. if the visitor’s version number is less than the target version, the  variable pass becomes false. */

	/* The following if-then block checks to see if pass is false. if so, the browser displays a popup message. Feel free to change the wording as you require, but remember to be polite, and try to phrase your request so as to emphasize how a free browser upgrade will personally benefit the visitor. */

	if (!pass) {
		alert("Warning: this site may not display properly in Internet Explorer versions 5.5 and 6.0. To improve your Web experience, this site uses advanced JavaScript and CSS techniques. Older browsers like yours may not support these features. For best results, please update your browser to the latest version.");
	}

}
