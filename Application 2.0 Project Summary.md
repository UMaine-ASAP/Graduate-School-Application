# Project Summary

**Project Name:** Graduate Application 2.0

**Project Goal:** The goal is to make the system stand-alone as well as integrate support for multiple application submissions and submitting non-degree and certificate applications.
	
**Additional Objectives:**

- Refactor the application code base to be more stable, modular, and maintainable
- Add support for submitting multiple applications
- Provide documentation of code and application structure
- Migrate Data from old database into new system
- Update administrative interface to support new application


**Constraints**

- Completed by end of February
- Must pass UMaine IT code review
- Resulting code is managable by a new ASAPer with no training


**Risks**

- UMaine IT rejects much of the code base 		Unlikely, Medium
- Development goes past schedule				Likely, Medium


**Assumptions**

- Application won't require anymore work after completion



# Deliverables

- Completed Application Code
- Completed Interface Code
- Database SQL
- Database Migration SQL
- Documentation



# Scope Statement

**In Scope:**

- updating administrative interface to support code


**Out of Scope:**

- Rebuilding or enhancing administrative interface



# Tasks

- Develop Application Code
	- Migrate old code to a new, stable foundation
		- <del>Implement inheriting templates</del>
			- <del>Define core templates</del>
			- <del>Fix css</del>
			- <del>Use macros for expanding input fields</del>
			- <del>Add correct database names and default values</del>
		- <del>Change routing methods</del>
		- Ensure saving fields works correctly
		- Ensure applications validate correctly
		- Ensure payment works correctly
		- Ensure reference process works
			- emailing references
			- Submitting references

	- Implement submitting multiple applications
		- Change payment for 2nd time degree application within last 6 months?

	- Implement non-degree application
		- Change required fields
		- Change payment amount

	- Implement certificate application
		- Change required fields
		- Change payment amount

- <del>Create the new database SQL</del>
- Update application administration to support the the new database
- Create SQL code to migrate data from application 1.0 to application 2.0 *(see dev/db/migration.md)*
- Write system documentation and create supporting diagrams
- Submit to IT and resolve any issues

Specific Tasks
--------------

- Questions
	- Certificate and non-degree academic program selection infos
	- Certificate program list
	- application type descriptions
	- exact cost structure?

- Database migration script

- Application
	- certificate BUA requires 2 recomendations and GIS requires 1
	- application status
	- application costs
	- make sure mainestreet fields sent have the same values (like isSeekingAssitantship should be 1 or yes? -> check carefully)
	- copy application data from last application
	- upload script and remove old file

- Finish README comments

- pdf output
	- perfect?
	- display program information

- testing
	- test in IE 6+, Safari, Firefox, Chrome, Opera
	- make sure images work on a server where the project is not at the root of the document
	- cron script
	- touchnet payment process
	- check if all fields are working

### Someday
- fix app_cleanup.php  (currently not used and needs work)


# Project Details

**Important Locations:**

- *Project Hosted on Github:* https://github.com/UMaine-ASAP/Graduate-School-Application
	- *on branch:* application\_version\_2
- *Test Server:* mcp.asap.um.maine.edu/gradschool/application2.0



