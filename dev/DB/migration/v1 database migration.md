Application Database v1 to v2 Migration
=======================================

This file outlines the conversion process between the Graduate Application version 1 database and Application version 2 database. Details of each conversion step are first described in pseudo code, followed by the sql conversion code. The final code should be implemented by running:

1. START TRANSACTION
1. all conversions queries
1. COMMIT if successful or ROLLBACK if failured


Academic Programs Migration
---------------------------

Converts the academic program information from the old database to the new database. Specifically, it maps the old um_academic table to the new AcademicProgram table.

**Table Conversions**

- um_academic -> AcademicProgram


### Conversion Details ###

**um_academic -> AcademicProgram**

	academic_index 		-> academicProgramId

	active == 'yes'|'no' 	-> isActive 1 | 0

	academic_program 		-> academic_programCode
	academic_plan 			-> academic_planCode
	description_list 		-> academic_planName

	academic_dept_code 		-> department_code
	academic_dept 			-> department_nameShort
	academic_dept_heading 	-> department_nameFull

	academic_degree 		-> degree_code
	academic_degree_heading 	-> degree_name

	nebhe_ct == 'X' | '' 	-> nebhe_ct 1 | 0
	nebhe_ma == 'X' | '' 	-> nebhe_ma 1 | 0
	nebhe_nh == 'X' | '' 	-> nebhe_nh 1 | 0
	nebhe_ri == 'X' | '' 	-> nebhe_ri 1 | 0
	nebhe_vt == 'X' | '' 	-> nebhe_vt 1 | 0


### SQL Query ###

	TRUNCATE TABLE  `gradschool-application-2`.AcademicProgram;
	
	INSERT INTO
	   `gradschool-application-2`.AcademicProgram (
	   academicProgramId,
	   isActive,
	   academic_programCode, academic_planCode, academic_planName, 
	   department_code, department_nameShort, department_nameFull, 
	   degree_code, degree_name, 
	   nebhe_ct, nebhe_ma, nebhe_nh, nebhe_ri, nebhe_vt)
	SELECT
	   academic_index, 
	
	   case when active='yes' then 1 when active='no' then 0 end, 
	
	   academic_program, academic_plan, description_list, 
	
	   academic_dept_code, academic_dept, academic_dept_heading, 
	
	   academic_degree, academic_degree_heading,
	
	   case when nebhe_ct='X' then 1 when nebhe_ct='' then 0 end,   
	   case when nebhe_ma='X' then 1 when nebhe_ma='' then 0 end,   
	   case when nebhe_nh='X' then 1 when nebhe_nh='' then 0 end,   
	   case when nebhe_ri='X' then 1 when nebhe_ri='' then 0 end,
	   case when nebhe_vt='X' then 1 when nebhe_vt='' then 0 end
	FROM
	   gradschool_application.um_academic;


Applications Migration
----------------------

Maps application data from/to appropriate tables.

**Table Conversions**

- applicants 		-> Application and others @TODO
- appliedprograms 	-> Application
- dvioloations & cviolations 	-> APPLICATION_Violation
- gre 			-> APPLICATION_GRE
- international  	-> APPLICATION_International
- languages		-> APPLICATION_Language
- previousschools	-> APPLICATION_PreviousSchool
- progress		-> APPLICATION_Progress


References Migration
--------------------

Maps reference data from/toto appropriate tables.

**Table Conversions**

- applicants 		-> APPLICATION_Reference
- extrareferences 	-> APPLICATION_Reference


Interface Username Migration
----------------------------

Loads application administration username.

**Table Conversions**

- admin -> AdminAccount


Todo
----

- is the v1 table \`structure\` important?
- Does the new structure support the needs of the table views? (applicant\_academic, applicant\_references, reference\_data)



