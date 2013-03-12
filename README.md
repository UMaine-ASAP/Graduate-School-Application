Graduate School Application
===========================

The **Graduate School Application** is the online application form for applying to the University of Maine's Graduate School.


Setup
=====

Server Configuration
--------------------

- PHP > 5.3
- MySQL
- Apache 2.0 or equivalent
	- rewrite module enabled
	- support for .htaccess files or server configuration to redirect all url requests to routes.php


Instructions
------------

1. setup the database using the sql script "tests/gradschool_application.sql"
2. Remove tests/
3. Check privleges and ownership on the folders within applicant_files (if necessary move these outside the web root)
4. Configure the variables within application/libs/extern_variables.php
5. move application/libs/extern_variables.php outside the webroot and set the directory information inside application/libs/variables.php to point to extern_variables
6. Schedule cron.php to run slightly after midnight
7. Complete an application and test submission as well as the payment process

Code Syntax
===========

1. functions are in camel case
1. Database field names are referenced using <parent>-<parent>-<fieldName> format. e.g. application-personal-givenName
	1. Field name and parent name's are in camelcase


Architecture
============

The application system uses the model view controller <abbr>(MVC)</abbr> pattern ([wikipedia definition](http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)) to structure the system.

File Structure
--------------

- **README.md**                          This readme file
- **Application 2.0 Project Summary.md** A brief project overview document
- **.gitignore**                         Git file describing files to ignore from repository

- **application/** The entire application codebase

	- **move\_outside\_webroot/** Files to place outside the webroot. Don't forget to update extern_configuration.php and configuration.php!

		- **externalConfiguration.default.php** default configuration information for application.
		- **externalConfiguration.php**         The configuration information for this site. Be sure to copy the defaults in *externalConfiguration.default.php* and change values as necessary.
		- **applicant_files/**                  Contains folders for all applicant related files (essay, resume, recommendations, and pdf generated applications). This same folder is also used by the application management system for viewing data


	- **controllers/** Object classes for controlling applicant and application data access

		- **applicantController.php**   Applicant manager with functions for logging in, registration, getting the active user, etc...
		- **applicationController.php** Application manager for creating / deleting applications, getting the active application


	- **cron/** Scripts to be schedule for daily execution (sometime after midnight)

		- **cron.php**         The primary cron script which runs all other specialized scripts (schedule ONLY this script to be run daily)
		- **mainestreet.php**  Script run by cron.php which sends completed applications to Mainstreet as tab-separated text
		- **app_cleanup.php**  Script run by cron.php to delete applications older than 6 months


	- **libraries/** External and internal code libraries used in the system

		- **MPDF52** External Library for generating pdf from html [MPDF](http://www.mpdf1.com/mpdf/index.php)
		- **Slim**   External Library for processing routing requests in application/routes.php [Slim](http://www.slimframework.com/)
		- **Twig**   External Library for rendering views using templates in application/views/templates [Twig](twig.sensiolabs.org)

		- **Database**        Library for running sql queries on the database. Database configuration information is automatically loaded from configuration.php
		- **Email**           Library for sending emails. Works loosely like Twig by parsing email templates in application/views/templates/emails
		- **Error**           Library for gathering and rendering errors. Mostly used when registering or processing login requests
		- **ErrorTracker**    Library for tracking errors
		- **Hash**            Library for encoding data
		- **InputSanitation** Library for filtering data. Used to filter data before storing in the database. Note that sql injection protection is performed by the Database function provided input is passed as 


	- **models/** 	All data objects used in the application

		- **Model.php**              A generic data object providing functions for loading/saving data from the database. If a model is tied to a database table, all of the fields for that database table are accessible as object properties.
		- **ApplicationModel.php**   Application model used to access/save database fields
		- **applicationComponents/** Models representing components of the application
		- **ApplicantModel.php**     Applicant model with basic applicant data
		- **databaseConfig.php**     Not a model object. Describes which  but a configuration script. Because this serves a database function and corresponds to the apps logic it falls within the models category.


	- **views/** Templates and supporint code for displaying data to the user

		- **javascript/** All javascript code
		- **images/**     Images for the website
		- **css/**        CSS styles for the site
		- **templates/**  [Twig](http://twig.sensiolabs.org) templates (HTML) for forms, emails, and pages

			- **account/**     Templates for account-based views
			- **application/** All application templates

				- **repeatable/**          Templates for application components that can repeat
				- **paymentResponse.twig** Template used when user returns to application from the Touchnet payment process
				- **section.macro.twig**   Helper template functions for application views
				- **section.twig**         Template structure for all major application sections

			- **emails/**                 All email templates. This does not use Twig -> see application/libraries/Email.php for more details.
			- **letterOfRecommendation/** Templates for forms and pages relating to references filling out a letter of recommendation
			- **pageLayout.twig**         Template structure that all other templates derive from
			- **forms.macro.twig**        Helper template functions for forms
			- **noJavascript.twig**       Template for when user does not have javascript enabled


	- **.htaccess**         Tells Apache to redirect all url requests to routes.php
	- **configuration.php** Configuration file pointing to the externalConfiguration.php file. This file is referenced when configuration data is needed instead of externalConfiguration.php
	- **robots.txt**        List describing which directories for search engines to exclude
	- **routes.php**        The core of the application and where every request for the application comes first.



- **development/** Development information including database details and code tests

	- **chromephp/** Development library for outputing php data to chrome console. See [http://www.chromephp.com/](http://www.chromephp.com/).
	- **database/**  Database definitions and conversion from app v1 to app v2

		- **application1.0.mwb** MySQL workbench file describing the database schema for application v1 [MySQL Workbench](http://dev.mysql.com/downloads/workbench/)
		- **application2.0.mwb** MySQL workbench file describing the database schema and default data for application v2 [MySQL Workbench](http://dev.mysql.com/downloads/workbench/)

	- **migration/** Migration guides when moving from application v1 to application v2

	- **tests/** Unit tests made using [PHP Unit](http://www.phpunit.de/manual/current/en/index.html)



Routes.php
----------

Routes.php is the core of the application and where every request for the application comes first.

Requests made to the application come in two two flavors:

### Page Requests

*Page requests* retrieve viewable webpages from the application for the user to explore in their browser.

All *page requests* are implemented in routes.php and are HTTP get requests programmed using the following format:


		$app->get('<url here>', $optionalAuthenticationFunction, function() { 

			// Logic here
			// May use controllers to retrieve data

			render('templatePath', $arrayHoldingTemplateReplacementData ); 
		});


### Data Submissions

*Data submissions* are responses from the user sending data to the server for processing. Examples include submitting form data, saving database fields, deleting application sections, and payment response processing.

All *data submissions* are implemented in routes.php and are HTTP post requests programmed using the following format:


		$app->post('<url here>', $optionalAuthenticationFunction, function() { 

			// Logic here
			// May use controllers to submit data

			// may or may not return a response or render a view for user depending
			echo "<response>"; 
			// or 
			render('templatePath', $arrayHoldingTemplateReplacementData ); 			
		});


### Note
As part of the site configuration, the .htaccess file's exists to redirect all url requests to routes.php according to Apache 2's rewrite rules. If application url requests do not function at all, the server needs to be configured to either allow the .htacess files, or redirect url requests to routes.php. If this is not an option, alternatively you can change the webroot in externalConfiguration.php to http://<webroot_here>/routes.php. The urls will not be as pretty but the application should function properly.

Templates
---------

Templates (also known as views) are the display containers of the application.

Templates are implemented using the Twig framework. Twig allows inheriting templates, variable replacement, basic looping and conditional statements, data filtering, and much more.  See [Twig Home](http://twig.sensiolabs.org/) for additional details.

The Twig library can be found found in /libs/Twig and /libs/Slim/Twig.php. /libs/Slim/Twig.php allows Twig's to be rendered by the slim instance $app using the $app->render function. In this application, $app->render is not called directly. Instead, a custom function, render, is used to make the following variables available to every template:

- **WEBROOT** - The full url path to the root directory of the application. This is used for correctly building all links and references in templates.
- **GRADHOMEPAGE** - the full url to the graduate school's website
- **GRADIMAGESPATH** - The full url to the application's images
- **ISLOGGEDIN** - A boolean value indicating if the user is currently logged
- **EMAIL** - The email of the current user. This value is only available if the user is logged in.


### Notes

- Templates are either used directly or define a structure to be used by a child template (layout templates). Layout templates are defined using the syntax <name>Layout.twig where <name> is a camel case descriptive template name. For example, forgottenLayout.twig defines the forgotten template used by templates describing specific responses during the process of retrieving a forgotten password.

- Template names follow the same format as database field names. For example, the template account/forgotten-error.twig belongs to the account category, is descended from the forgotten template file (called forgottenLayout), and is called error.



Notes
=====

