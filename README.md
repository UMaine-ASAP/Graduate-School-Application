# Graduate School Application

## About
The **Graduate School Application** is the prospective student online application form for the University of Maine's Graduate School. The system is 

## Configuration
** Server Configuration**  

	PHP > 5.3  
	MySQL
	Apache 2.0 or equivalent


**Setup**
	
	1. setup the database using the sql script "tests/gradschool_application.sql"
	2. Remove tests/
	3. Check privleges and ownership on the folders within applicant_files (if necessary move these outside the web root)
	4. Configure the variables within application/libs/extern_variables.php
	5. move application/libs/extern_variables.php outside the webroot and set the directory information inside application/libs/variables.php to point to extern_variables
	6. Schedule cron.php to run slightly after midnight
	7. Complete an application and test submission as well as the payment process

## File Structure
- **applicant_files/** 		Contains folders for all applicant related files (essay, resume, recommendations, and pdf generated applications). This same folder is also used by the application management system

- **application/** 				Contains all of the logic for the system

	- **controllers/** 				Object classes for controlling applicant and application data access
	- **images/** 					Images for the website
	- **libs/** 					External and developed libraries in the system
	- **js/** 					All javascript libraries
	- **models/** 					All data-specific scripts used in other locations
	- **pages/** 					Web pages
	- **scripts/** 				Various system scripts to perform actions and get data
	- **styles/**					CSS styles for the site
	- **templates/** 				Templates for forms and pages
	- **app_manager.php** 			Runs all general application pages (form pages)
	- **index.php** 				points to app_manager.php
	- **submission_manager.php** 	Runs the final submission page


- **cron/**				Scripts to be schedule for daily execution (sometime after midnight)

	- **cron.php** 			The primary cron script which runs all other specialized scripts (schedule ONLY this script to be run daily)
	- **mainestreet.php** 	Script run by cron.php which sends completed applications to Mainstreet as tab-separated text  
	- **app_cleanup.php** 	Script run by cron.php to delete applications older than 6 months (currently not used and needs work)


- **tests/**			Unit tests, migration from v1.0, and initial database setup script

- **robots.txt** 		Exclude directories for search engines

- **README.md**		This readme file


## Notes
fields in the applicant table named fieldname_repeatable are processed as repeatable fields in app_manager.php under the assumption that "fieldname" is actually the table name holding the repeatable data and the value in the field is the number of repeatable element
