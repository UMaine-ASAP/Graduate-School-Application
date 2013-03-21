<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";


class Personal extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Primary';
	protected static $primaryKeys = array('applicationId');


	protected static $availableProperties = array('mailing', 'permanentMailing', 'fullName', 'pretty_hasDisciplinaryViolation', 'pretty_hasCivilViolation', 'pretty_prevUMGradApp_appExists', 'pretty_prevUMGradWithdraw_exists', 'pretty_gmat_hasTaken', 'pretty_mat_hasTaken', 'pretty_hasTakenGRE', 'nonHispanicEthnicities');

	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'mailing':
		 		return ContactInformation::getWithId($this->mailing_contactInformationId);
		 	break;		 	
		 	case 'permanentMailing':
		 		return ContactInformation::getWithId($this->permanentMailing_contactInformationId);
		 	break;
		 	case 'fullName':
				return $this->givenName . " " . $this->middleName . " " . $this->familyName;
			break;
		 	case 'pretty_hasDisciplinaryViolation':
		 		return ($this->hasDisciplinaryViolation == 1) ? "Yes" : "No";
		 	break;
		 	case 'pretty_hasCivilViolation':
		 		return ($this->hasCivilViolation == 1) ? "Yes" : "No";
		 	break;
		 	case 'pretty_prevUMGradApp_appExists':
		 		return ($this->hasPrevUMGradApp_appExists == 1) ? "Yes" : "No";
		 	break;
		 	case 'pretty_prevUMGradWithdraw_exists':
		 		return ($this->hasPrevUMGradWithdraw_exists == 1) ? "Yes" : "No";
		 	break;
		 	case 'pretty_gmat_hasTaken':
		 		return ($this->gmat_hasTaken == 1) ? "Yes" : "No";
		 	break;
		 	case 'pretty_mat_hasTaken':
		 		return ($this->mat_hasTaken == 1) ? "Yes" : "No";
		 	break;
		 	case 'pretty_hasTakenGRE':
		 		return ($this->hasTakenGRE == 1) ? "Yes" : "No";
		 	break;
		 	case 'nonHispanicEthnicities':
		 		$output = "";
				$output .= ( $this->ethnicity_amind == 1 ) ? "American Indian/Alaska Native ":"";
				$output .= ( $this->ethnicity_asian == 1 ) ? "Asian ":"";
				$output .= ( $this->ethnicity_black == 1 ) ? "Black ":"";
				$output .= ( $this->ethnicity_pacif == 1 ) ? "Native Hawaiian/Pacific Islander ":"";
				$output .= ( $this->ethnicity_white == 1 ) ? "White ":"";
				$output .= ( $this->ethnicity_unspec == 1 ) ? "unspecified":"";
				return $output;
		 	break;

		 	case 'socialSecurityNumber':
				$ssnResult = DATABASE::getFirst("SELECT AES_DECRYPT(social_security_number, '%s') AS ssn FROM APPLICATION_Primary WHERE applicationId=%d LIMIT 1", $GLOBALS['key'], $this->id);
				return $ssnResult[0];
		 	break;		 		
		 }

		 return parent::__get($name);
	}

}
