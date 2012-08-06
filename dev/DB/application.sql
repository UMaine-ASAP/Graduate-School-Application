



-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'Applicant'
-- 
-- ---

DROP TABLE IF EXISTS `Applicant`;
    
CREATE TABLE `Applicant` (
  `applicant_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `login_email` VARCHAR(128) NULL DEFAULT NULL,
  `password` VARCHAR(155) NULL DEFAULT NULL,
  `is_email_confirmed` TINYINT NULL DEFAULT NULL,
  `login_email_code` VARCHAR(40) NULL DEFAULT NULL,
  `forgot_password_code` VARCHAR(40) NULL DEFAULT NULL,
  PRIMARY KEY (`applicant_id`)
);

-- ---
-- Table 'Admin_accounts'
-- User accounts for application management system
-- ---

DROP TABLE IF EXISTS `Admin_accounts`;
    
CREATE TABLE `Admin_accounts` (
  `admin_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `username` VARCHAR(100) NULL DEFAULT NULL,
  `password` VARCHAR(160) NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) COMMENT 'User accounts for application management system';

-- ---
-- Table 'Application'
-- 
-- ---

DROP TABLE IF EXISTS `Application`;
    
CREATE TABLE `Application` (
  `application_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicant_id` INT NULL DEFAULT NULL,
  `application_type_id` INT NULL DEFAULT NULL,
  `application_degree_id` INT NULL DEFAULT NULL,
  `application_certificate_id` INT NULL DEFAULT NULL,
  `created_date` DATE NULL DEFAULT NULL,
  `last_modified` TIMESTAMP NULL DEFAULT NULL,
  `start_year` INT(4) NULL DEFAULT NULL,
  `start_semester` VARCHAR(10) NULL DEFAULT NULL,
  `desired_housing` VARCHAR(10) NULL DEFAULT NULL,
  `waive_reference_viewing_rights` TINYINT NULL DEFAULT NULL,
  `has_umaine_correspondent` TINYINT NULL DEFAULT NULL,
  `umaine_correspondent_details` MEDIUMTEXT NULL DEFAULT NULL,
  `has_accepted_terms_of_agreement` TINYINT NULL DEFAULT NULL,
  `transaction_id` VARCHAR(128) NULL DEFAULT NULL,
  `has_been_submitted` TINYINT NULL DEFAULT NULL,
  `submitted_date` DATETIME NULL DEFAULT NULL,
  `has_been_pushed` TINYINT NULL DEFAULT NULL,
  `pushed_date` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`application_id`)
);

-- ---
-- Table 'APPLICATION_DATA_gre'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_gre`;
    
CREATE TABLE `APPLICATION_DATA_gre` (
  `gre_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_id` INT NULL DEFAULT NULL,
  `d` VARCHAR(7) NULL DEFAULT NULL,
  `verbal` INT(3) NULL DEFAULT NULL,
  `quantitative` INT(3) NULL DEFAULT NULL,
  `analytical` DECIMAL(2,1) NULL DEFAULT NULL,
  `subject` VARCHAR(32) NULL DEFAULT NULL,
  `has_been_reported` TINYINT NULL DEFAULT NULL,
  `score` INT(3) NULL DEFAULT NULL,
  PRIMARY KEY (`gre_id`)
);

-- ---
-- Table 'APPLICATION_DATA_international'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_international`;
    
CREATE TABLE `APPLICATION_DATA_international` (
  `international_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_id` INT NULL DEFAULT NULL,
  `toefl_date` VARCHAR(7) NULL DEFAULT NULL,
  `toefl_score` INT(3) NULL DEFAULT NULL,
  `has_us_career` TINYINT NULL DEFAULT NULL,
  `us_career_details` MEDIUMTEXT NULL DEFAULT NULL,
  `has_further_studies` TINYINT NULL DEFAULT NULL,
  `further_studies_details` MEDIUMTEXT NULL DEFAULT NULL,
  `has_home_career` TINYINT NULL DEFAULT NULL,
  `home_career_details` MEDIUMTEXT NULL DEFAULT NULL,
  `finance_details` MEDIUMTEXT NULL DEFAULT NULL,
  `us_emergency_contact_name` VARCHAR(55) NULL DEFAULT NULL,
  `us_emergency_contact_relationship` VARCHAR(30) NULL DEFAULT NULL,
  `us_emergency_contact_information_id` INT NULL DEFAULT NULL,
  `home_emergency_contact_name` VARCHAR(55) NULL DEFAULT NULL,
  `home_emergency_contact_relationship` VARCHAR(30) NULL DEFAULT NULL,
  `home_emergency_contact_information_id` INT NULL DEFAULT NULL,
  `is_toefl_taken` TINYINT NULL DEFAULT NULL,
  `is_toefl_reported` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`international_id`)
);

-- ---
-- Table 'APPLICATION_DATA_civil_violation'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_civil_violation`;
    
CREATE TABLE `APPLICATION_DATA_civil_violation` (
  `civil_violations_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_id` INT NULL DEFAULT NULL,
  `type` VARCHAR(12) NULL DEFAULT NULL,
  `date` VARCHAR(7) NULL DEFAULT NULL,
  `details` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`civil_violations_id`)
);

-- ---
-- Table 'APPLICATION_DATA_disciplinary_violation'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_disciplinary_violation`;
    
CREATE TABLE `APPLICATION_DATA_disciplinary_violation` (
  `disciplinary_violation_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_id` INT NULL DEFAULT NULL,
  `type` VARCHAR(12) NULL DEFAULT NULL,
  `date` VARCHAR(7) NULL DEFAULT NULL,
  `details` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`disciplinary_violation_id`)
);

-- ---
-- Table 'Academic_program'
-- 
-- ---

DROP TABLE IF EXISTS `Academic_program`;
    
CREATE TABLE `Academic_program` (
  `academic_program_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `program_code` VARCHAR(10) NULL DEFAULT NULL,
  `plan_code` VARCHAR(10) NULL DEFAULT NULL,
  `department_code` VARCHAR(3) NULL DEFAULT NULL,
  `department` VARCHAR(64) NULL DEFAULT NULL,
  `department_heading` VARCHAR(64) NULL DEFAULT NULL,
  `degree_code` VARCHAR(10) NULL DEFAULT NULL,
  `degree_name` VARCHAR(4) NULL DEFAULT NULL,
  `description` VARCHAR(30) NULL DEFAULT NULL,
  `nebhe_ct` TINYINT NULL DEFAULT NULL,
  `nebhe_ma` TINYINT NULL DEFAULT NULL,
  `nebhe_nh` TINYINT NULL DEFAULT NULL,
  `nebhe_ri` TINYINT NULL DEFAULT NULL,
  `nebhe_vt` TINYINT NULL DEFAULT NULL,
  `isActive` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`academic_program_id`)
);

-- ---
-- Table 'APPLICATION_Structure'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_Structure`;
    
CREATE TABLE `APPLICATION_Structure` (
  `structure_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_type_id` INT NULL DEFAULT NULL,
  `name` VARCHAR(128) NULL DEFAULT NULL,
  `path` VARCHAR(128) NULL DEFAULT NULL,
  `isIncluded` TINYINT NULL DEFAULT NULL,
  `order` INT(3) NULL DEFAULT NULL,
  PRIMARY KEY (`structure_id`)
);

-- ---
-- Table 'APPLICATION_type'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_type`;
    
CREATE TABLE `APPLICATION_type` (
  `application_type_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(128) NULL DEFAULT NULL,
  PRIMARY KEY (`application_type_id`)
);

-- ---
-- Table 'APPLICATION_DATA_progress'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_progress`;
    
CREATE TABLE `APPLICATION_DATA_progress` (
  `application_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `structure_id` INT NULL DEFAULT NULL,
  `status` ENUM('INCOMPLETE','IN PROGRESS','PENDING','COMPLETE') NULL DEFAULT NULL,
  `notes` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`application_id`)
);

-- ---
-- Table 'APPLICATION_DATA_previous_school'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_previous_school`;
    
CREATE TABLE `APPLICATION_DATA_previous_school` (
  `previous_school_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_id` INT NULL DEFAULT NULL,
  `name` VARCHAR(55) NULL DEFAULT NULL,
  `city` VARCHAR(30) NULL DEFAULT NULL,
  `state` VARCHAR(30) NULL DEFAULT NULL,
  `country` VARCHAR(3) NULL DEFAULT NULL,
  `code` VARCHAR(4) NULL DEFAULT NULL,
  `start_date` VARCHAR(7) NULL DEFAULT NULL,
  `end_date` VARCHAR(7) NULL DEFAULT NULL,
  `major` VARCHAR(30) NULL DEFAULT NULL,
  `degree_earned` VARCHAR(30) NULL DEFAULT NULL,
  `degree_earned_date` VARCHAR(7) NULL DEFAULT NULL,
  PRIMARY KEY (`previous_school_id`)
);

-- ---
-- Table 'APPLICATION_DATA_language'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_language`;
    
CREATE TABLE `APPLICATION_DATA_language` (
  `language_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_id` INT NULL DEFAULT NULL,
  `language` VARCHAR(30) NULL DEFAULT NULL,
  `writing_proficiency` VARCHAR(4) NULL DEFAULT NULL,
  `reading_proficiency` VARCHAR(4) NULL DEFAULT NULL,
  `speaking_proficiency` VARCHAR(4) NULL DEFAULT NULL,
  PRIMARY KEY (`language_id`)
);

-- ---
-- Table 'APPLICATION_DATA_references'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_references`;
    
CREATE TABLE `APPLICATION_DATA_references` (
  `reference_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_id` INT NULL DEFAULT NULL,
  `first_name` VARCHAR(32) NULL DEFAULT NULL,
  `last_name` VARCHAR(32) NULL DEFAULT NULL,
  `email` VARCHAR(128) NULL DEFAULT NULL,
  `relationship` VARCHAR(10) NULL DEFAULT NULL,
  `contact_information_id` INT NULL DEFAULT NULL,
  `is_submitting_online` TINYINT NULL DEFAULT NULL,
  `request_has_been_sent` TINYINT NULL DEFAULT NULL,
  `submitted_date` DATE NULL DEFAULT NULL,
  `filename` VARCHAR(128) NULL DEFAULT NULL,
  PRIMARY KEY (`reference_id`)
);

-- ---
-- Table 'APPLICATION_cost'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_cost`;
    
CREATE TABLE `APPLICATION_cost` (
  `cost_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `application_type_id` INT NULL DEFAULT NULL,
  `cost` INT NULL DEFAULT NULL,
  `is_price_for_application_after_first` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`cost_id`)
);

-- ---
-- Table 'APPLICATION_DATA_primary'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_primary`;
    
CREATE TABLE `APPLICATION_DATA_primary` (
  `application_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `given_name` VARCHAR(30) NULL DEFAULT NULL,
  `middle_name` VARCHAR(30) NULL DEFAULT NULL,
  `family_name` VARCHAR(30) NULL DEFAULT NULL,
  `suffix` VARCHAR(10) NULL DEFAULT NULL,
  `alternate_name` VARCHAR(55) NULL DEFAULT NULL,
  `email` VARCHAR(100) NULL DEFAULT NULL,
  `primary_phone` VARCHAR(30) NULL DEFAULT NULL,
  `secondary_phone` VARCHAR(30) NULL DEFAULT NULL,
  `mailing_contact_information_id` INT NULL DEFAULT NULL,
  `has_mailing_perm` TINYINT NULL DEFAULT NULL,
  `permanent_contact_information_id` INT NULL DEFAULT NULL,
  `date_of_birth` VARCHAR(10) NULL DEFAULT NULL,
  `birth_city` VARCHAR(30) NULL DEFAULT NULL,
  `birth_state` VARCHAR(30) NULL DEFAULT NULL,
  `birth_country` CHAR(3) NULL DEFAULT NULL,
  `gender` CHAR(1) NULL DEFAULT NULL,
  `is_us_citizen` TINYINT NULL DEFAULT NULL,
  `us_state` CHAR(2) NULL DEFAULT NULL,
  `residency_status` VARCHAR(30) NULL DEFAULT NULL,
  `green_card_link` VARCHAR(128) NULL DEFAULT NULL,
  `country_of_citizenship` CHAR(3) NULL DEFAULT NULL,
  `social_security_number` MEDIUMTEXT NULL DEFAULT NULL,
  `ethnicity_amind` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_asian` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_black` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_hispa` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_pacif` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_white` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_unspec` VARCHAR(10) NULL DEFAULT NULL,
  `english_years_school` VARCHAR(15) NULL DEFAULT NULL,
  `english_years_univ` VARCHAR(15) NULL DEFAULT NULL,
  `english_years_private` VARCHAR(15) NULL DEFAULT NULL,
  `present_occupation` VARCHAR(55) NULL DEFAULT NULL,
  `undergrad_gpa` DECIMAL(3,2) NULL DEFAULT NULL,
  `postbacc_gpa` DECIMAL(3,2) NULL DEFAULT NULL,
  `extracurricular_activities` MEDIUMTEXT NULL DEFAULT NULL,
  `academic_honors` MEDIUMTEXT NULL DEFAULT NULL,
  `employment_history` MEDIUMTEXT NULL DEFAULT NULL,
  `has_taken_gmat` TINYINT NULL DEFAULT NULL,
  `has_reported_gmat` TINYINT NULL DEFAULT NULL,
  `gmat_date` VARCHAR(7) NULL DEFAULT NULL,
  `gmat_quantitative` INT(2) NULL DEFAULT NULL,
  `gmat_verbal` INT(2) NULL DEFAULT NULL,
  `gmat_analytical` DECIMAL(2,1) NULL DEFAULT NULL,
  `gmat_score` INT(3) NULL DEFAULT NULL,
  `has_taken_mat` TINYINT NULL DEFAULT NULL,
  `has_reported_mat` TINYINT NULL DEFAULT NULL,
  `mat_date` VARCHAR(7) NULL DEFAULT NULL,
  `mat_score` VARCHAR(3) NULL DEFAULT NULL,
  `has_prev_um_app` TINYINT NULL DEFAULT NULL,
  `prev_um_grad_app_date` VARCHAR(7) NULL DEFAULT NULL,
  `prev_um_grad_app_dept` VARCHAR(30) NULL DEFAULT NULL,
  `prev_um_grad_degree` VARCHAR(30) NULL DEFAULT NULL,
  `prev_um_grad_degree_date` VARCHAR(7) NULL DEFAULT NULL,
  `has_prev_um_grad_withdraw` TINYINT NULL DEFAULT NULL,
  `prev_um_grad_withdraw_date` VARCHAR(7) NULL DEFAULT NULL,
  PRIMARY KEY (`application_id`)
);

-- ---
-- Table 'APPLICATION_DATA_transaction'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_transaction`;
    
CREATE TABLE `APPLICATION_DATA_transaction` (
  `transaction_id` VARCHAR(128) NULL DEFAULT NULL,
  `status` CHAR(1) NULL DEFAULT NULL,
  `type` VARCHAR(6) NULL DEFAULT NULL,
  `completed_date` DATE NULL DEFAULT NULL,
  `amount` DECIMAL(5,2) NULL DEFAULT NULL,
  `payment_method` TINYINT NULL DEFAULT NULL,
  `is_completed` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`transaction_id`)
);

-- ---
-- Table 'APPLICATION_DATA_TYPE_degree'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_TYPE_degree`;
    
CREATE TABLE `APPLICATION_DATA_TYPE_degree` (
  `application_degree_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `academic_program` VARCHAR(10) NULL DEFAULT NULL,
  `academic_plan` VARCHAR(10) NULL DEFAULT NULL,
  `academic_major` VARCHAR(30) NULL DEFAULT NULL,
  `academic_minor` VARCHAR(30) NULL DEFAULT NULL,
  `student_type` VARCHAR(5) NULL DEFAULT NULL,
  `academic_load` CHAR(1) NULL DEFAULT NULL,
  `is_seeking_financial_aid` TINYINT NULL DEFAULT NULL,
  `is_seeking_assistantship` TINYINT NULL DEFAULT NULL,
  `desired_assistantship_department` VARCHAR(30) NULL DEFAULT NULL,
  `is_applying_nebhe` TINYINT NULL DEFAULT NULL,
  `resume_id` INT NULL DEFAULT NULL,
  `essay_id` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`application_degree_id`)
);

-- ---
-- Table 'APPLICATION_DATA_TYPE_certficate'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_TYPE_certficate`;
    
CREATE TABLE `APPLICATION_DATA_TYPE_certficate` (
  `application_certificate_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `preenroll_courses` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`application_certificate_id`)
);

-- ---
-- Table 'APPLICATION_DATA_Supporting_document'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_Supporting_document`;
    
CREATE TABLE `APPLICATION_DATA_Supporting_document` (
  `document_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `filename` VARCHAR(70) NULL DEFAULT NULL,
  `type` VARCHAR(128) NULL DEFAULT NULL,
  PRIMARY KEY (`document_id`)
);

-- ---
-- Table 'APPLICATION_DATA_contact_information'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DATA_contact_information`;
    
CREATE TABLE `APPLICATION_DATA_contact_information` (
  `contact_information_id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `street_address_1` VARCHAR(55) NULL DEFAULT NULL,
  `street_address_2` VARCHAR(55) NULL DEFAULT NULL,
  `city` VARCHAR(30) NULL DEFAULT NULL,
  `state` VARCHAR(30) NULL DEFAULT NULL,
  `postal` VARCHAR(15) NULL DEFAULT NULL,
  `primary_phone` VARCHAR(30) NULL DEFAULT NULL,
  `secondary_phone` VARCHAR(30) NULL DEFAULT NULL,
  `country` VARCHAR(3) NULL DEFAULT NULL,
  PRIMARY KEY (`contact_information_id`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `Application` ADD FOREIGN KEY (applicant_id) REFERENCES `Applicant` (`applicant_id`);
ALTER TABLE `Application` ADD FOREIGN KEY (application_type_id) REFERENCES `APPLICATION_type` (`application_type_id`);
ALTER TABLE `Application` ADD FOREIGN KEY (application_degree_id) REFERENCES `APPLICATION_DATA_TYPE_degree` (`application_degree_id`);
ALTER TABLE `Application` ADD FOREIGN KEY (application_certificate_id) REFERENCES `APPLICATION_DATA_TYPE_certficate` (`application_certificate_id`);
ALTER TABLE `Application` ADD FOREIGN KEY (transaction_id) REFERENCES `APPLICATION_DATA_transaction` (`transaction_id`);
ALTER TABLE `APPLICATION_DATA_gre` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_DATA_international` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_DATA_international` ADD FOREIGN KEY (us_emergency_contact_information_id) REFERENCES `APPLICATION_DATA_contact_information` (`contact_information_id`);
ALTER TABLE `APPLICATION_DATA_international` ADD FOREIGN KEY (home_emergency_contact_information_id) REFERENCES `APPLICATION_DATA_contact_information` (`contact_information_id`);
ALTER TABLE `APPLICATION_DATA_civil_violation` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_DATA_disciplinary_violation` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_Structure` ADD FOREIGN KEY (application_type_id) REFERENCES `APPLICATION_type` (`application_type_id`);
ALTER TABLE `APPLICATION_DATA_progress` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_DATA_progress` ADD FOREIGN KEY (structure_id) REFERENCES `APPLICATION_Structure` (`structure_id`);
ALTER TABLE `APPLICATION_DATA_previous_school` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_DATA_language` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_DATA_references` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_DATA_references` ADD FOREIGN KEY (contact_information_id) REFERENCES `APPLICATION_DATA_contact_information` (`contact_information_id`);
ALTER TABLE `APPLICATION_cost` ADD FOREIGN KEY (application_type_id) REFERENCES `APPLICATION_type` (`application_type_id`);
ALTER TABLE `APPLICATION_DATA_primary` ADD FOREIGN KEY (application_id) REFERENCES `Application` (`application_id`);
ALTER TABLE `APPLICATION_DATA_primary` ADD FOREIGN KEY (mailing_contact_information_id) REFERENCES `APPLICATION_DATA_contact_information` (`contact_information_id`);
ALTER TABLE `APPLICATION_DATA_primary` ADD FOREIGN KEY (permanent_contact_information_id) REFERENCES `APPLICATION_DATA_contact_information` (`contact_information_id`);
ALTER TABLE `APPLICATION_DATA_TYPE_degree` ADD FOREIGN KEY (resume_id) REFERENCES `APPLICATION_DATA_Supporting_document` (`document_id`);
ALTER TABLE `APPLICATION_DATA_TYPE_degree` ADD FOREIGN KEY (essay_id) REFERENCES `APPLICATION_DATA_Supporting_document` (`document_id`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `Applicant` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `Admin_accounts` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `Application` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_gre` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_international` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_civil_violation` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_disciplinary_violation` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `Academic_program` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_Structure` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_type` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_progress` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_previous_school` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_language` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_references` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_cost` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_primary` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_transaction` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_TYPE_degree` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_TYPE_certficate` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_Supporting_document` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DATA_contact_information` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `Applicant` (`applicant_id`,`login_email`,`password`,`is_email_confirmed`,`login_email_code`,`forgot_password_code`) VALUES
-- ('','','','','','');
-- INSERT INTO `Admin_accounts` (`admin_id`,`username`,`password`) VALUES
-- ('','','');
-- INSERT INTO `Application` (`application_id`,`applicant_id`,`application_type_id`,`application_degree_id`,`application_certificate_id`,`created_date`,`last_modified`,`start_year`,`start_semester`,`desired_housing`,`waive_reference_viewing_rights`,`has_umaine_correspondent`,`umaine_correspondent_details`,`has_accepted_terms_of_agreement`,`transaction_id`,`has_been_submitted`,`submitted_date`,`has_been_pushed`,`pushed_date`) VALUES
-- ('','','','','','','','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_DATA_gre` (`gre_id`,`application_id`,`d`,`verbal`,`quantitative`,`analytical`,`subject`,`has_been_reported`,`score`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `APPLICATION_DATA_international` (`international_id`,`application_id`,`toefl_date`,`toefl_score`,`has_us_career`,`us_career_details`,`has_further_studies`,`further_studies_details`,`has_home_career`,`home_career_details`,`finance_details`,`us_emergency_contact_name`,`us_emergency_contact_relationship`,`us_emergency_contact_information_id`,`home_emergency_contact_name`,`home_emergency_contact_relationship`,`home_emergency_contact_information_id`,`is_toefl_taken`,`is_toefl_reported`) VALUES
-- ('','','','','','','','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_DATA_civil_violation` (`civil_violations_id`,`application_id`,`type`,`date`,`details`) VALUES
-- ('','','','','');
-- INSERT INTO `APPLICATION_DATA_disciplinary_violation` (`disciplinary_violation_id`,`application_id`,`type`,`date`,`details`) VALUES
-- ('','','','','');
-- INSERT INTO `Academic_program` (`academic_program_id`,`program_code`,`plan_code`,`department_code`,`department`,`department_heading`,`degree_code`,`degree_name`,`description`,`nebhe_ct`,`nebhe_ma`,`nebhe_nh`,`nebhe_ri`,`nebhe_vt`,`isActive`) VALUES
-- ('','','','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_Structure` (`structure_id`,`application_type_id`,`name`,`path`,`isIncluded`,`order`) VALUES
-- ('','','','','','');
-- INSERT INTO `APPLICATION_type` (`application_type_id`,`name`) VALUES
-- ('','');
-- INSERT INTO `APPLICATION_DATA_progress` (`application_id`,`structure_id`,`status`,`notes`) VALUES
-- ('','','','');
-- INSERT INTO `APPLICATION_DATA_previous_school` (`previous_school_id`,`application_id`,`name`,`city`,`state`,`country`,`code`,`start_date`,`end_date`,`major`,`degree_earned`,`degree_earned_date`) VALUES
-- ('','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_DATA_language` (`language_id`,`application_id`,`language`,`writing_proficiency`,`reading_proficiency`,`speaking_proficiency`) VALUES
-- ('','','','','','');
-- INSERT INTO `APPLICATION_DATA_references` (`reference_id`,`application_id`,`first_name`,`last_name`,`email`,`relationship`,`contact_information_id`,`is_submitting_online`,`request_has_been_sent`,`submitted_date`,`filename`) VALUES
-- ('','','','','','','','','','','');
-- INSERT INTO `APPLICATION_cost` (`cost_id`,`application_type_id`,`cost`,`is_price_for_application_after_first`) VALUES
-- ('','','','');
-- INSERT INTO `APPLICATION_DATA_primary` (`application_id`,`given_name`,`middle_name`,`family_name`,`suffix`,`alternate_name`,`email`,`primary_phone`,`secondary_phone`,`mailing_contact_information_id`,`has_mailing_perm`,`permanent_contact_information_id`,`date_of_birth`,`birth_city`,`birth_state`,`birth_country`,`gender`,`is_us_citizen`,`us_state`,`residency_status`,`green_card_link`,`country_of_citizenship`,`social_security_number`,`ethnicity_amind`,`ethnicity_asian`,`ethnicity_black`,`ethnicity_hispa`,`ethnicity_pacif`,`ethnicity_white`,`ethnicity_unspec`,`english_years_school`,`english_years_univ`,`english_years_private`,`present_occupation`,`undergrad_gpa`,`postbacc_gpa`,`extracurricular_activities`,`academic_honors`,`employment_history`,`has_taken_gmat`,`has_reported_gmat`,`gmat_date`,`gmat_quantitative`,`gmat_verbal`,`gmat_analytical`,`gmat_score`,`has_taken_mat`,`has_reported_mat`,`mat_date`,`mat_score`,`has_prev_um_app`,`prev_um_grad_app_date`,`prev_um_grad_app_dept`,`prev_um_grad_degree`,`prev_um_grad_degree_date`,`has_prev_um_grad_withdraw`,`prev_um_grad_withdraw_date`) VALUES
-- ('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_DATA_transaction` (`transaction_id`,`status`,`type`,`completed_date`,`amount`,`payment_method`,`is_completed`) VALUES
-- ('','','','','','','');
-- INSERT INTO `APPLICATION_DATA_TYPE_degree` (`application_degree_id`,`academic_program`,`academic_plan`,`academic_major`,`academic_minor`,`student_type`,`academic_load`,`is_seeking_financial_aid`,`is_seeking_assistantship`,`desired_assistantship_department`,`is_applying_nebhe`,`resume_id`,`essay_id`) VALUES
-- ('','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_DATA_TYPE_certficate` (`application_certificate_id`,`preenroll_courses`) VALUES
-- ('','');
-- INSERT INTO `APPLICATION_DATA_Supporting_document` (`document_id`,`filename`,`type`) VALUES
-- ('','','');
-- INSERT INTO `APPLICATION_DATA_contact_information` (`contact_information_id`,`street_address_1`,`street_address_2`,`city`,`state`,`postal`,`primary_phone`,`secondary_phone`,`country`) VALUES
-- ('','','','','','','','','');

