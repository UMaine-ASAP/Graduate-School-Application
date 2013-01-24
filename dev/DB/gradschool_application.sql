



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
  `applicantId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `loginEmail` VARCHAR(128) NULL DEFAULT NULL,
  `password` VARCHAR(155) NULL DEFAULT NULL,
  `isEmailConfirmed` TINYINT NULL DEFAULT NULL,
  `loginEmailCode` VARCHAR(40) NULL DEFAULT NULL,
  `forgotPasswordCode` VARCHAR(40) NULL DEFAULT NULL,
  `givenName` VARCHAR(255) NULL DEFAULT NULL,
  `lastName` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`applicantId`)
);

-- ---
-- Table 'Admin_accounts'
-- User accounts for application management system
-- ---

DROP TABLE IF EXISTS `Admin_accounts`;
    
CREATE TABLE `Admin_accounts` (
  `adminId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `username` VARCHAR(100) NULL DEFAULT NULL,
  `password` VARCHAR(160) NULL DEFAULT NULL,
  PRIMARY KEY (`adminId`)
) COMMENT 'User accounts for application management system';

-- ---
-- Table 'Application'
-- 
-- ---

DROP TABLE IF EXISTS `Application`;
    
CREATE TABLE `Application` (
  `applicationId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicantId` INT NULL DEFAULT NULL,
  `applicationTypeId` INT NULL DEFAULT NULL,
  `createdDate` DATE NULL DEFAULT NULL,
  `lastModified` TIMESTAMP NULL DEFAULT NULL,
  `startYear` INT(4) NULL DEFAULT NULL,
  `startSemester` VARCHAR(10) NULL DEFAULT NULL,
  `desiredHousing` VARCHAR(10) NULL DEFAULT NULL,
  `waiveReferenceViewingRights` TINYINT NULL DEFAULT NULL,
  `hasUmaineCorrespondent` TINYINT NULL DEFAULT NULL,
  `umaineCorrespondentDetails` MEDIUMTEXT NULL DEFAULT NULL,
  `hasAcceptedTermsOfAgreement` TINYINT NULL DEFAULT NULL,
  `transactionId` VARCHAR(128) NULL DEFAULT NULL,
  `hasBeenSubmitted` TINYINT NULL DEFAULT NULL,
  `submittedDate` DATETIME NULL DEFAULT NULL,
  `hasBeenPushed` TINYINT NULL DEFAULT NULL,
  `pushedDate` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`applicationId`)
);

-- ---
-- Table 'APPLICATION_GRE'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_GRE`;
    
CREATE TABLE `APPLICATION_GRE` (
  `GREId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationId` INT NULL DEFAULT NULL,
  `d` VARCHAR(7) NULL DEFAULT NULL,
  `verbal` INT(3) NULL DEFAULT NULL,
  `quantitative` INT(3) NULL DEFAULT NULL,
  `analytical` DECIMAL(2,1) NULL DEFAULT NULL,
  `subject` VARCHAR(32) NULL DEFAULT NULL,
  `hasBeenReported` TINYINT NULL DEFAULT NULL,
  `score` INT(3) NULL DEFAULT NULL,
  PRIMARY KEY (`GREId`)
);

-- ---
-- Table 'APPLICATION_International'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_International`;
    
CREATE TABLE `APPLICATION_International` (
  `internationalId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationId` INT NULL DEFAULT NULL,
  `toeflDate` VARCHAR(7) NULL DEFAULT NULL,
  `toeflScore` INT(3) NULL DEFAULT NULL,
  `hasUSCareer` TINYINT NULL DEFAULT NULL,
  `usCareerDetails` MEDIUMTEXT NULL DEFAULT NULL,
  `hasFurtherStudies` TINYINT NULL DEFAULT NULL,
  `furtherStudiesDetails` MEDIUMTEXT NULL DEFAULT NULL,
  `hasHomeCareer` TINYINT NULL DEFAULT NULL,
  `homeCareerDetails` MEDIUMTEXT NULL DEFAULT NULL,
  `financeDetails` MEDIUMTEXT NULL DEFAULT NULL,
  `usEmergencyContact_name` VARCHAR(55) NULL DEFAULT NULL,
  `usEmergencyContact_relationship` VARCHAR(30) NULL DEFAULT NULL,
  `usEmergencyContact_contactInformationId` INT NULL DEFAULT NULL,
  `homeEmergencyContact_name` VARCHAR(55) NULL DEFAULT NULL,
  `homeEmergencyContact_relationship` VARCHAR(30) NULL DEFAULT NULL,
  `homeEmergencyContact_contactInformationId` INT NULL DEFAULT NULL,
  `isToeflTaken` TINYINT NULL DEFAULT NULL,
  `isToeflReported` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`internationalId`)
);

-- ---
-- Table 'APPLICATION_CivilViolation'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_CivilViolation`;
    
CREATE TABLE `APPLICATION_CivilViolation` (
  `civilViolationsId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationId` INT NULL DEFAULT NULL,
  `type` VARCHAR(12) NULL DEFAULT NULL,
  `date` VARCHAR(7) NULL DEFAULT NULL,
  `details` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`civilViolationsId`)
);

-- ---
-- Table 'APPLICATION_DisciplinaryViolation'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_DisciplinaryViolation`;
    
CREATE TABLE `APPLICATION_DisciplinaryViolation` (
  `disciplinaryViolationId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationId` INT NULL DEFAULT NULL,
  `type` VARCHAR(12) NULL DEFAULT NULL,
  `date` VARCHAR(7) NULL DEFAULT NULL,
  `details` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`disciplinaryViolationId`)
);

-- ---
-- Table 'Academic_program'
-- 
-- ---

DROP TABLE IF EXISTS `Academic_program`;
    
CREATE TABLE `Academic_program` (
  `academicProgramId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `isActive` TINYINT NULL DEFAULT NULL,
  `programCode` VARCHAR(10) NULL DEFAULT NULL,
  `planCode` VARCHAR(10) NULL DEFAULT NULL,
  `department_name` VARCHAR(64) NULL DEFAULT NULL,
  `department_code` VARCHAR(3) NULL DEFAULT NULL,
  `department_heading` VARCHAR(64) NULL DEFAULT NULL,
  `degree_code` VARCHAR(10) NULL DEFAULT NULL,
  `degree_name` VARCHAR(4) NULL DEFAULT NULL,
  `degree_description` VARCHAR(30) NULL DEFAULT NULL,
  `nebhe_ct` TINYINT NULL DEFAULT NULL,
  `nebhe_ma` TINYINT NULL DEFAULT NULL,
  `nebhe_nh` TINYINT NULL DEFAULT NULL,
  `nebhe_ri` TINYINT NULL DEFAULT NULL,
  `nebhe_vt` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`academicProgramId`)
);

-- ---
-- Table 'APPLICATION_Structure'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_Structure`;
    
CREATE TABLE `APPLICATION_Structure` (
  `structureId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationTypeId` INT NULL DEFAULT NULL,
  `name` VARCHAR(128) NULL DEFAULT NULL,
  `path` VARCHAR(128) NULL DEFAULT NULL,
  `isIncluded` TINYINT NULL DEFAULT NULL,
  `order` INT(3) NULL DEFAULT NULL,
  PRIMARY KEY (`structureId`)
);

-- ---
-- Table 'APPLICATION_type'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_type`;
    
CREATE TABLE `APPLICATION_type` (
  `applicationTypeId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(128) NULL DEFAULT NULL,
  PRIMARY KEY (`applicationTypeId`)
);

-- ---
-- Table 'APPLICATION_Progress'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_Progress`;
    
CREATE TABLE `APPLICATION_Progress` (
  `progressId` INT NULL DEFAULT NULL,
  `applicationId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `structureId` INT NULL DEFAULT NULL,
  `status` ENUM('INCOMPLETE','IN PROGRESS','PENDING','COMPLETE') NULL DEFAULT NULL,
  `notes` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`applicationId`)
);

-- ---
-- Table 'APPLICATION_PreviousSchool'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_PreviousSchool`;
    
CREATE TABLE `APPLICATION_PreviousSchool` (
  `previousSchoolId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationId` INT NULL DEFAULT NULL,
  `name` VARCHAR(55) NULL DEFAULT NULL,
  `city` VARCHAR(30) NULL DEFAULT NULL,
  `state` VARCHAR(30) NULL DEFAULT NULL,
  `country` VARCHAR(3) NULL DEFAULT NULL,
  `code` VARCHAR(4) NULL DEFAULT NULL,
  `startDate` VARCHAR(7) NULL DEFAULT NULL,
  `endDate` VARCHAR(7) NULL DEFAULT NULL,
  `major` VARCHAR(30) NULL DEFAULT NULL,
  `degreeEarned_name` VARCHAR(30) NULL DEFAULT NULL,
  `degreeEarned_date` VARCHAR(7) NULL DEFAULT NULL,
  PRIMARY KEY (`previousSchoolId`)
);

-- ---
-- Table 'APPLICATION_Language'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_Language`;
    
CREATE TABLE `APPLICATION_Language` (
  `languageId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationId` INT NULL DEFAULT NULL,
  `language` VARCHAR(30) NULL DEFAULT NULL,
  `proficiency_writing` VARCHAR(4) NULL DEFAULT NULL,
  `proficiency_reading` VARCHAR(4) NULL DEFAULT NULL,
  `proficiency_speaking` VARCHAR(4) NULL DEFAULT NULL,
  PRIMARY KEY (`languageId`)
);

-- ---
-- Table 'APPLICATION_References'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_References`;
    
CREATE TABLE `APPLICATION_References` (
  `referenceId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationId` INT NULL DEFAULT NULL,
  `firstName` VARCHAR(32) NULL DEFAULT NULL,
  `lastName` VARCHAR(32) NULL DEFAULT NULL,
  `email` VARCHAR(128) NULL DEFAULT NULL,
  `relationship` VARCHAR(10) NULL DEFAULT NULL,
  `contactInformationId` INT NULL DEFAULT NULL,
  `isSubmittingOnline` TINYINT NULL DEFAULT NULL,
  `requestHasBeenSent` TINYINT NULL DEFAULT NULL,
  `submittedDate` DATE NULL DEFAULT NULL,
  `filename` VARCHAR(128) NULL DEFAULT NULL,
  PRIMARY KEY (`referenceId`)
);

-- ---
-- Table 'APPLICATION_Cost'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_Cost`;
    
CREATE TABLE `APPLICATION_Cost` (
  `costId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `applicationTypeId` INT NULL DEFAULT NULL,
  `cost` INT NULL DEFAULT NULL,
  PRIMARY KEY (`costId`)
);

-- ---
-- Table 'APPLICATION_Primary'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_Primary`;
    
CREATE TABLE `APPLICATION_Primary` (
  `applicationId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `givenName` VARCHAR(30) NULL DEFAULT NULL,
  `middleName` VARCHAR(30) NULL DEFAULT NULL,
  `familyName` VARCHAR(30) NULL DEFAULT NULL,
  `suffix` VARCHAR(10) NULL DEFAULT NULL,
  `alternateName` VARCHAR(55) NULL DEFAULT NULL,
  `gender` CHAR(1) NULL DEFAULT NULL,
  `email` VARCHAR(100) NULL DEFAULT NULL,
  `phonePrimary` VARCHAR(30) NULL DEFAULT NULL,
  `phoneSecondary` VARCHAR(30) NULL DEFAULT NULL,
  `mailing_contactInformationId` INT NULL DEFAULT NULL,
  `permanentMailing_exists` TINYINT NULL DEFAULT NULL,
  `permanentMailing_contactInformationId` INT NULL DEFAULT NULL,
  `birth_date` VARCHAR(10) NULL DEFAULT NULL,
  `birth_city` VARCHAR(30) NULL DEFAULT NULL,
  `birth_state` VARCHAR(30) NULL DEFAULT NULL,
  `birth_country` CHAR(3) NULL DEFAULT NULL,
  `us_isCitizen` TINYINT NULL DEFAULT NULL,
  `us_state` CHAR(2) NULL DEFAULT NULL,
  `residencyStatus` VARCHAR(30) NULL DEFAULT NULL,
  `greenCardLink` VARCHAR(128) NULL DEFAULT NULL,
  `countryOfCitizenship` CHAR(3) NULL DEFAULT NULL,
  `socialSecurityNumber` MEDIUMTEXT NULL DEFAULT NULL,
  `ethnicity_amind` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_asian` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_black` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_hispa` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_pacif` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_white` VARCHAR(10) NULL DEFAULT NULL,
  `ethnicity_unspec` VARCHAR(10) NULL DEFAULT NULL,
  `englishYears_school` VARCHAR(15) NULL DEFAULT NULL,
  `englishYears_univ` VARCHAR(15) NULL DEFAULT NULL,
  `englishYears_private` VARCHAR(15) NULL DEFAULT NULL,
  `presentOccupation` VARCHAR(55) NULL DEFAULT NULL,
  `undergradGPA` DECIMAL(3,2) NULL DEFAULT NULL,
  `postbaccGPA` DECIMAL(3,2) NULL DEFAULT NULL,
  `extracurricularActivities` MEDIUMTEXT NULL DEFAULT NULL,
  `academicHonors` MEDIUMTEXT NULL DEFAULT NULL,
  `employmentHistory` MEDIUMTEXT NULL DEFAULT NULL,
  `gmat_hasTaken` TINYINT NULL DEFAULT NULL,
  `gmat_hasReported` TINYINT NULL DEFAULT NULL,
  `gmat_date` VARCHAR(7) NULL DEFAULT NULL,
  `gmat_quantitative` INT(2) NULL DEFAULT NULL,
  `gmat_verbal` INT(2) NULL DEFAULT NULL,
  `gmat_analytical` DECIMAL(2,1) NULL DEFAULT NULL,
  `gmat_score` INT(3) NULL DEFAULT NULL,
  `mat_hasTaken` TINYINT NULL DEFAULT NULL,
  `mat_hasReported` TINYINT NULL DEFAULT NULL,
  `mat_date` VARCHAR(7) NULL DEFAULT NULL,
  `mat_score` VARCHAR(3) NULL DEFAULT NULL,
  `prevUMGradApp_exists` TINYINT NULL DEFAULT NULL,
  `prevUMGradApp_date` VARCHAR(7) NULL DEFAULT NULL,
  `prevUMGradApp_dept` VARCHAR(30) NULL DEFAULT NULL,
  `prevUMGradApp_degree` VARCHAR(30) NULL DEFAULT NULL,
  `prevUMGradApp_degreeDate` VARCHAR(7) NULL DEFAULT NULL,
  `prevUMGradWithdraw_exists` TINYINT NULL DEFAULT NULL,
  `prevUMGradWithdraw_Date` VARCHAR(7) NULL DEFAULT NULL,
  PRIMARY KEY (`applicationId`)
);

-- ---
-- Table 'APPLICATION_Transaction'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_Transaction`;
    
CREATE TABLE `APPLICATION_Transaction` (
  `transactionId` VARCHAR(128) NULL DEFAULT NULL,
  `status` CHAR(1) NULL DEFAULT NULL,
  `type` VARCHAR(6) NULL DEFAULT NULL,
  `completedDate` DATE NULL DEFAULT NULL,
  `amount` DECIMAL(5,2) NULL DEFAULT NULL,
  `paymentMethod` TINYINT NULL DEFAULT NULL,
  `isCompleted` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`transactionId`)
);

-- ---
-- Table 'APPLICATION_degree'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_degree`;
    
CREATE TABLE `APPLICATION_degree` (
  `applicationId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `academic_program` VARCHAR(10) NULL DEFAULT NULL,
  `academic_plan` VARCHAR(10) NULL DEFAULT NULL,
  `academic_major` VARCHAR(30) NULL DEFAULT NULL,
  `academic_minor` VARCHAR(30) NULL DEFAULT NULL,
  `academic_load` CHAR(1) NULL DEFAULT NULL,
  `studentType` VARCHAR(5) NULL DEFAULT NULL,
  `isSeekingFinancialAid` TINYINT NULL DEFAULT NULL,
  `isSeekingAssistantship` TINYINT NULL DEFAULT NULL,
  `desiredAssistantshipDepartment` VARCHAR(30) NULL DEFAULT NULL,
  `isApplyingNebhe` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`applicationId`)
);

-- ---
-- Table 'APPLICATION_preenrollCourses'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_preenrollCourses`;
    
CREATE TABLE `APPLICATION_preenrollCourses` (
  `applicationId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `courseName` VARCHAR(155) NULL DEFAULT NULL,
  PRIMARY KEY (`applicationId`)
);

-- ---
-- Table 'Document'
-- 
-- ---

DROP TABLE IF EXISTS `Document`;
    
CREATE TABLE `Document` (
  `supportingDocumentId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `filename` VARCHAR(70) NULL DEFAULT NULL,
  `type` VARCHAR(128) NULL DEFAULT NULL,
  PRIMARY KEY (`supportingDocumentId`)
);

-- ---
-- Table 'APPLICATION_ContactInformation'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_ContactInformation`;
    
CREATE TABLE `APPLICATION_ContactInformation` (
  `contactInformationId` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `streetAddress1` VARCHAR(55) NULL DEFAULT NULL,
  `streetAddress2` VARCHAR(55) NULL DEFAULT NULL,
  `city` VARCHAR(30) NULL DEFAULT NULL,
  `state` VARCHAR(30) NULL DEFAULT NULL,
  `postal` VARCHAR(15) NULL DEFAULT NULL,
  `primaryPhone` VARCHAR(30) NULL DEFAULT NULL,
  `secondaryPhone` VARCHAR(30) NULL DEFAULT NULL,
  `country` VARCHAR(3) NULL DEFAULT NULL,
  PRIMARY KEY (`contactInformationId`)
);

-- ---
-- Table 'APPLICATION_SupportingDocuments'
-- 
-- ---

DROP TABLE IF EXISTS `APPLICATION_SupportingDocuments`;
    
CREATE TABLE `APPLICATION_SupportingDocuments` (
  `applicationId` TINYINT NULL AUTO_INCREMENT DEFAULT NULL,
  `resumeId` INT NULL DEFAULT NULL,
  `essayId` INT NULL DEFAULT NULL,
  PRIMARY KEY (`applicationId`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `Application` ADD FOREIGN KEY (applicantId) REFERENCES `Applicant` (`applicantId`);
ALTER TABLE `Application` ADD FOREIGN KEY (applicationTypeId) REFERENCES `APPLICATION_type` (`applicationTypeId`);
ALTER TABLE `Application` ADD FOREIGN KEY (transactionId) REFERENCES `APPLICATION_Transaction` (`transactionId`);
ALTER TABLE `APPLICATION_GRE` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_International` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_International` ADD FOREIGN KEY (usEmergencyContact_contactInformationId) REFERENCES `APPLICATION_ContactInformation` (`contactInformationId`);
ALTER TABLE `APPLICATION_International` ADD FOREIGN KEY (homeEmergencyContact_contactInformationId) REFERENCES `APPLICATION_ContactInformation` (`contactInformationId`);
ALTER TABLE `APPLICATION_CivilViolation` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_DisciplinaryViolation` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_Structure` ADD FOREIGN KEY (applicationTypeId) REFERENCES `APPLICATION_type` (`applicationTypeId`);
ALTER TABLE `APPLICATION_Progress` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_Progress` ADD FOREIGN KEY (structureId) REFERENCES `APPLICATION_Structure` (`structureId`);
ALTER TABLE `APPLICATION_PreviousSchool` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_Language` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_References` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_References` ADD FOREIGN KEY (contactInformationId) REFERENCES `APPLICATION_ContactInformation` (`contactInformationId`);
ALTER TABLE `APPLICATION_Cost` ADD FOREIGN KEY (applicationTypeId) REFERENCES `APPLICATION_type` (`applicationTypeId`);
ALTER TABLE `APPLICATION_Primary` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_Primary` ADD FOREIGN KEY (mailing_contactInformationId) REFERENCES `APPLICATION_ContactInformation` (`contactInformationId`);
ALTER TABLE `APPLICATION_Primary` ADD FOREIGN KEY (permanentMailing_contactInformationId) REFERENCES `APPLICATION_ContactInformation` (`contactInformationId`);
ALTER TABLE `APPLICATION_degree` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_preenrollCourses` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_SupportingDocuments` ADD FOREIGN KEY (applicationId) REFERENCES `Application` (`applicationId`);
ALTER TABLE `APPLICATION_SupportingDocuments` ADD FOREIGN KEY (resumeId) REFERENCES `Document` (`supportingDocumentId`);
ALTER TABLE `APPLICATION_SupportingDocuments` ADD FOREIGN KEY (essayId) REFERENCES `Document` (`supportingDocumentId`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `Applicant` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `Admin_accounts` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `Application` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_GRE` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_International` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_CivilViolation` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_DisciplinaryViolation` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `Academic_program` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_Structure` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_type` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_Progress` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_PreviousSchool` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_Language` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_References` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_Cost` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_Primary` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_Transaction` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_degree` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_preenrollCourses` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `Document` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_ContactInformation` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `APPLICATION_SupportingDocuments` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `Applicant` (`applicantId`,`loginEmail`,`password`,`isEmailConfirmed`,`loginEmailCode`,`forgotPasswordCode`,`givenName`,`lastName`) VALUES
-- ('','','','','','','','');
-- INSERT INTO `Admin_accounts` (`adminId`,`username`,`password`) VALUES
-- ('','','');
-- INSERT INTO `Application` (`applicationId`,`applicantId`,`applicationTypeId`,`createdDate`,`lastModified`,`startYear`,`startSemester`,`desiredHousing`,`waiveReferenceViewingRights`,`hasUmaineCorrespondent`,`umaineCorrespondentDetails`,`hasAcceptedTermsOfAgreement`,`transactionId`,`hasBeenSubmitted`,`submittedDate`,`hasBeenPushed`,`pushedDate`) VALUES
-- ('','','','','','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_GRE` (`GREId`,`applicationId`,`d`,`verbal`,`quantitative`,`analytical`,`subject`,`hasBeenReported`,`score`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `APPLICATION_International` (`internationalId`,`applicationId`,`toeflDate`,`toeflScore`,`hasUSCareer`,`usCareerDetails`,`hasFurtherStudies`,`furtherStudiesDetails`,`hasHomeCareer`,`homeCareerDetails`,`financeDetails`,`usEmergencyContact_name`,`usEmergencyContact_relationship`,`usEmergencyContact_contactInformationId`,`homeEmergencyContact_name`,`homeEmergencyContact_relationship`,`homeEmergencyContact_contactInformationId`,`isToeflTaken`,`isToeflReported`) VALUES
-- ('','','','','','','','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_CivilViolation` (`civilViolationsId`,`applicationId`,`type`,`date`,`details`) VALUES
-- ('','','','','');
-- INSERT INTO `APPLICATION_DisciplinaryViolation` (`disciplinaryViolationId`,`applicationId`,`type`,`date`,`details`) VALUES
-- ('','','','','');
-- INSERT INTO `Academic_program` (`academicProgramId`,`isActive`,`programCode`,`planCode`,`department_name`,`department_code`,`department_heading`,`degree_code`,`degree_name`,`degree_description`,`nebhe_ct`,`nebhe_ma`,`nebhe_nh`,`nebhe_ri`,`nebhe_vt`) VALUES
-- ('','','','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_Structure` (`structureId`,`applicationTypeId`,`name`,`path`,`isIncluded`,`order`) VALUES
-- ('','','','','','');
-- INSERT INTO `APPLICATION_type` (`applicationTypeId`,`name`) VALUES
-- ('','');
-- INSERT INTO `APPLICATION_Progress` (`progressId`,`applicationId`,`structureId`,`status`,`notes`) VALUES
-- ('','','','','');
-- INSERT INTO `APPLICATION_PreviousSchool` (`previousSchoolId`,`applicationId`,`name`,`city`,`state`,`country`,`code`,`startDate`,`endDate`,`major`,`degreeEarned_name`,`degreeEarned_date`) VALUES
-- ('','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_Language` (`languageId`,`applicationId`,`language`,`proficiency_writing`,`proficiency_reading`,`proficiency_speaking`) VALUES
-- ('','','','','','');
-- INSERT INTO `APPLICATION_References` (`referenceId`,`applicationId`,`firstName`,`lastName`,`email`,`relationship`,`contactInformationId`,`isSubmittingOnline`,`requestHasBeenSent`,`submittedDate`,`filename`) VALUES
-- ('','','','','','','','','','','');
-- INSERT INTO `APPLICATION_Cost` (`costId`,`applicationTypeId`,`cost`) VALUES
-- ('','','');
-- INSERT INTO `APPLICATION_Primary` (`applicationId`,`givenName`,`middleName`,`familyName`,`suffix`,`alternateName`,`gender`,`email`,`phonePrimary`,`phoneSecondary`,`mailing_contactInformationId`,`permanentMailing_exists`,`permanentMailing_contactInformationId`,`birth_date`,`birth_city`,`birth_state`,`birth_country`,`us_isCitizen`,`us_state`,`residencyStatus`,`greenCardLink`,`countryOfCitizenship`,`socialSecurityNumber`,`ethnicity_amind`,`ethnicity_asian`,`ethnicity_black`,`ethnicity_hispa`,`ethnicity_pacif`,`ethnicity_white`,`ethnicity_unspec`,`englishYears_school`,`englishYears_univ`,`englishYears_private`,`presentOccupation`,`undergradGPA`,`postbaccGPA`,`extracurricularActivities`,`academicHonors`,`employmentHistory`,`gmat_hasTaken`,`gmat_hasReported`,`gmat_date`,`gmat_quantitative`,`gmat_verbal`,`gmat_analytical`,`gmat_score`,`mat_hasTaken`,`mat_hasReported`,`mat_date`,`mat_score`,`prevUMGradApp_exists`,`prevUMGradApp_date`,`prevUMGradApp_dept`,`prevUMGradApp_degree`,`prevUMGradApp_degreeDate`,`prevUMGradWithdraw_exists`,`prevUMGradWithdraw_Date`) VALUES
-- ('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
-- INSERT INTO `APPLICATION_Transaction` (`transactionId`,`status`,`type`,`completedDate`,`amount`,`paymentMethod`,`isCompleted`) VALUES
-- ('','','','','','','');
-- INSERT INTO `APPLICATION_degree` (`applicationId`,`academic_program`,`academic_plan`,`academic_major`,`academic_minor`,`academic_load`,`studentType`,`isSeekingFinancialAid`,`isSeekingAssistantship`,`desiredAssistantshipDepartment`,`isApplyingNebhe`) VALUES
-- ('','','','','','','','','','','');
-- INSERT INTO `APPLICATION_preenrollCourses` (`applicationId`,`courseName`) VALUES
-- ('','');
-- INSERT INTO `Document` (`supportingDocumentId`,`filename`,`type`) VALUES
-- ('','','');
-- INSERT INTO `APPLICATION_ContactInformation` (`contactInformationId`,`streetAddress1`,`streetAddress2`,`city`,`state`,`postal`,`primaryPhone`,`secondaryPhone`,`country`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `APPLICATION_SupportingDocuments` (`applicationId`,`resumeId`,`essayId`) VALUES
-- ('','','');

