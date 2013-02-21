<?php

// Sanitize data in database before passing
// 
class InputSanitation
{

// Available filters and associated error messages
private static $filterErrorMessages = array(
	'filter_phone' 		=> 'Invalid phone number',
	'filter_email' 		=> 'Invalid email address',
	'filter_zipcode' 		=> 'Invalid postal code',
	'filter_long_date' 		=> 'Invalid dater',
	'filter_ssn' 			=> 'Invalid social security number',
	'filter_suffix' 		=> 'Invalid option',
	'filter_state' 		=> 'Invalid option',
	'filter_country' 		=> 'Invalid option',
	'filter_gender' 		=> 'Invalid option',
	'filter_residency'		=> 'Invalid option',
	'filter_boolean'		=> 'Invalid option',
	'filter_proficiency' 	=> 'Invalid option',
	'filter_short_date'		=> 'Invalid date',
	'filter_toefl_score'	=> 'Invalid value',
	'filter_generic'		=> 'Invalid option'
	);


// Clean characters out
// Solution and replacement values found at http://php.tonnikala.org/manual/en/function.utf8-decode.php
public static function cleanInput($text) {
     $badWordChars=array(
     	"\xe2\x80\x98", // left single quote
     	"\xe2\x80\x99", // right single quote
     	"\xe2\x80\x9c", // left double quote
     	"\xe2\x80\x9d", // right double quote
     	"\xe2\x80\x94", // em dash
     	"\xe2\x80\xa6" // elipses
     );
     $fixedWordChars=array(
     	"&#8216;",
     	"&#8217;",
     	'&#8220;',
     	'&#8221;',
     	'&mdash;',
     	'&#8230;'
     );
	$text=str_replace($badWordChars, $fixedWordChars, $text);

	return $text;
}

//Clean characters somewhat

// if($user && $_POST) {
// 	if ($validData = isValid($_POST['field'], $_POST['value'], &$errorMessage)) {
// 		if(!$_POST['table']) {	
// 			if(strtoupper($_POST['field']) == "SOCIAL_SECURITY_NUMBER") {
// 				$key = $GLOBALS['key'];
// 				$db->iquery("UPDATE applicants SET social_security_number=AES_ENCRYPT('%s', '%s') WHERE applicant_id=%d", $_POST['value'], $key, $user);
// 			} else {
// 				$db->iquery("UPDATE `applicants` SET %s='%s' WHERE applicants.applicant_id=%d", $_POST['field'], $_POST['value'], $user);
// 			}
// 		} else {
// 			if(!$db->getFirst("SELECT %s_id FROM `%s` WHERE applicant_id=%d AND %s_id=%d", $_POST['table'], $_POST['table'], $user, $_POST['table'], $_POST['index'])) {
// 				$db->iquery("INSERT INTO `%s` (applicant_id, %s_id) VALUES (%d, %d)", $_POST['table'], $_POST['table'], $user, $_POST['index']);
// 				$count = $db->getFirst("SELECT %s_repeatable FROM `applicants` WHERE applicant_id=%d", $_POST['table'], $user);
// 				$db->iquery("UPDATE `applicants` SET %s_repeatable=%d WHERE applicants.applicant_id=%d", $_POST['table'], $count+1, $user);
// 			}
// 			$db->iquery("UPDATE `%s` SET %s='%s' WHERE %s.applicant_id=%d AND %s_id=%d", $_POST['table'], $_POST['field'], $_POST['value'], $_POST['table'], $user, $_POST['table'], $_POST['index']);
// 		}
// 	}
// }


// @pragma databaseFieldTypes
private static $databaseFieldTypes;

public static function initialize($fieldInfo) {
	self::$databaseFieldTypes = $fieldInfo;
}

private static function filterResult($value, $option, $message='') {
	$valid = false;
	switch($option) {
		case 'filter_boolean':
			$valid = null !== filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		break;
		default:
			$valid = filter_var($value, FILTER_CALLBACK, array('options' => "InputSanitation::".$option));
		break;
	}

	if( !$valid ) 
	{
		if($message != '') { return $message; }
		// check if filter is valid

		if( isset( self::$filterErrorMessages[$option] ) ) {
			return self::$filterErrorMessages[$option];
		} else {
			// Filter error message did not exist -> freak out!
			throw new Exception("Internal Error Saving Data");
		}
	}
}




public static function isValid($name, $value, &$errorMessage) {
	$errorMessage = "";
	if(!$value) return TRUE;
	$valid = FALSE;
	$tested = FALSE;

	$option = self::$databaseFieldTypes[$name];
	// Ensure option is valid
	if( $option != null && array_key_exists($option, self::$filterErrorMessages) ) {
		$errorMessage = self::filterResult($value, self::$databaseFieldTypes[$name]);
	} else {
		// throw new Exception("key not found");
		$errorMessage = 'internal error on field ' . $name; // @pragma TEMPONLY_REMOVEME
	}




	
	//Future Plans

	//United States Emergency Contact

	//Home Country Emergency Contact


	###Educational History###
	//Previous Application to University of Maine


	//Previously Attended Institutions


	//Grade Information


	//Disciplinary Violations


	//Criminal Information


	//GRE


	//GMAT


	//MAT


	###Educational Objectives###
	//Academic Programs
	//degree

	//Rest


	###Letters of Recommendation###
	//Waiver of Viewing Rights


	//Additional References

	###Submission Manager###


	###Default###


	return $errorMessage == ''; // return if error message exists
}



// @pragma Filter Functions

public static function filter_generic($value) {
	$invalid_chars = array();//str_split("\"#$%&'*+\\/=?^_`{|}~;><");
	foreach($invalid_chars as $char)
		if (strpos($value, $char) !== false)
			return false;
	return $value;
}

//format mm/yyyy-mm/yyyy
public static function filter_date_range($value) {
	//if ( preg_match("/(**)/", $value) ) {
	if ( strpos($value, '-') != false ) {//Check for -
		$dates = explode('-', $value);
		$result = filter_short_date($dates[0]) && filter_short_date($dates[1]);

		return $result;
	} else {
		return false;
	}
	//filter_long_date;
}

//format mm/dd/yyyy
public static function filter_long_date($value) {
	if(preg_match("((0[1-9]|[10-12])/(0[1-9]|[12][0-9]|3[01])/(19|20)\d\d)", $value)) {
		$v = split("/", $value);
		$v[0] = (int) $v[0];
		$v[1] = (int) $v[1];
		$v[1] = (int) $v[1];

		//test for leap year
		if($v[0] == 2 && ($v[2] % 4) == 0) {
			if($v[1] <= 29)
				return $value;
			else
				return false;	
		}

		$month_length = array(31,28,31,30,31,30,31,31,30,31,30,31);

		
		//check against month lengths
		if($v[1] <= $month_length[$v[0]-1]) {
			return $value;
		} else {
			return false;
		}

	}
	else
		return false;
}

//format mm/yyyy
public static function filter_short_date($value) {
	if(preg_match("((0[1-9]|[10-12])/(19|20)\d\d)", $value))
		return $value;
	else
		return false;
}


public static function filter_name($value)	{
	if(preg_match("/^[\p{L} \.\-]+$/", $value))
		return $value;
	else
		return false;
}

public static function filter_zipcode($value) {
//	if(preg_match("/^([0-9]{5})(-[0-9]{4})?$/i",$value) || preg_match("([A-Za-z][0-9][A-Z] [0-9][A-Z][0-9])",$value))
	return filter_generic($value);
//	else
//		return false;
}

public static function filter_phone($value) {
	//Might not validate for international phone numbers.
//	$regex = '/^(?:\d*(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/';
	$regex = '/^[x0-9-\/]*$/';
	if(preg_match($regex, $value))
		return $value;
	else
		return false;
}

public static function filter_ssn($value) {
	if(strlen($value) == 11
		&& $value[3] == "-"
		&& $value[6] == "-"
		&& filter_var((int)substr($value, 0, 3), FILTER_VALIDATE_INT)
		&& (int)substr($value, 0, 3)!=0
		&& !((int)substr($value, 0, 3)>899)
		&& !((int)substr($value, 0, 3)==666)
		&& filter_var((int)substr($value, 4, 2), FILTER_VALIDATE_INT)
		&& (int)substr($value, 4, 2)!=0
		&& filter_var((int)substr($value, 7, 4), FILTER_VALIDATE_INT)
		&& (int)substr($value, 7, 4)!=0)
		return $value;
	else
		return false;
}

public static function filter_suffix($value) {
	$value = trim($value);
	if(preg_match("(ESQ\.|II|III|IV|V|SR|JR)", $value))
		return $value;
	else
		return false;
}

public static function filter_gender($value) {
	$value = trim($value);
	if(preg_match("(M|F|O)", $value))
		return $value;
	else
		return false;
}

public static function filter_residency($value) {
	$value = trim($value);
	if(preg_match("(resident|non-resident alien)", $value))
		return $value;
	else
		return false;
}

public static function filter_proficiency($value) {
	$value = trim($value);
	if(preg_match("(Good|Fair|Poor)", $value))
		return $value;
	else
		return false;
}

public static function filter_toefl_score($value) {
	return filter_var($value, FILTER_VALIDATE_INT);
}

//0-4
public static function filter_gpa($value) {
	$value = trim($value);
	if(preg_match("/([0-3](\.[0-9][0-9]?)?)|(4(\.00?)?)/",$value))
		return $value;
	else
		return false;
}

//400-800
public static function filter_gre_verbal($value) {
	$value = trim($value);
	if(preg_match("/(1[3-6][0-9])|(170)|([2-7][0-9][0-9])|(800)/",$value))
		return $value;
	else
		return false;
}

//200-800
public static function filter_gre_quantitative($value) {
	$value = trim($value);
	if(preg_match("/(1[3-6][0-9])|(170)|([2-7][0-9][0-9])|(800)/",$value))
		return $value;
	else
		return false;
}

//0-6
public static function filter_gre_analytical($value) {
	$value = trim($value);
	if(preg_match("/^[0-5](\.[0-9])?/",$value) || $value == "6.0")
		return $value;
	else
		return false;
}

//200-990 in 10's
public static function filter_gre_score($value) {
	$value = trim($value);
	if(preg_match("/[2-9][0-9]0/",$value))
		return $value;
	else
		return false;
}

public static function filter_gre_subject($value) {
	$value = trim($value);
	if(preg_match("(BCMB|BIO|CHEM|COS|LIT|MATH|PHYS|PSY)", $value))
		return $value;
	else
		return false;
}

//0-60
public static function filter_gmat_verbal($value) {
	$value = trim($value);
	if(preg_match("/([0-9])|([0-5][0-9])|(60)/",$value))
		return $value;
	else
		return false;
}

//0-60
public static function filter_gmat_quantitative($value) {
	$value = trim($value);
	if(preg_match("/([0-9])|([0-5][0-9])|(60)/",$value))
		return $value;
	else
		return false;
}

//0-6
public static function filter_gmat_analytical($value) {
	$value = trim($value);
	if(preg_match("/([0-5](\.[0-9])?)|(6(\.0)?)/",$value))
		return $value;
	else
		return false;
}

//200-800
public static function filter_gmat_score($value) {
	$value = trim($value);
	if(preg_match("/([2-7][0-9][0-9])|(800)/",$value))
		return $value;
	else
		return false;
}

//200-600
public static function filter_mat_score($value) {
	$value = trim($value);
	if(preg_match("/([2-5][0-9][0-9])|(600)/",$value))
		return $value;
	else
		return false;
}

public static function filter_student_type($value) {
	$value = trim($value);
	if(preg_match("(IS|OS|CAN|INTNL|NEBHE)", $value))
		return $value;
	else
		return false;
}

public static function filter_semester($value) {
	$value = trim($value);
	if(preg_match("(FALL|SPRING|SUMMER)", $value))
		return $value;
	else
		return false;
}

public static function filter_attendance_load($value) {
	$value = trim($value);
	if(preg_match("(F|P)", $value))
		return $value;
	else
		return false;
}

public static function filter_relationship($value) {
	$value = trim($value);
	if(preg_match("(Work|School|Family|Friend)", $value))
		return $value;
	else
		return false;
}


public static function filter_state($value) {
	$value = trim($value);
	if(preg_match("(IT|AL|AK|AZ|AR|CA|CO|CT|DC|DE|FL|GA|HI|ID|IL|IN|IA|KS|KY|LA|ME|MD|MA|MI|MN|MS|MO|MT|NE|NV|NH|NJ|NM|NY|NC|ND|OH|OK|OR|PA|RI|SC|SD|TN|TX|UT|VT|VA|WA|WV|WI|WY|AB|BC|MB|NB|NL|NS|ON|PE|QC|SK|NT|NU|YT)", $value))
		return $value;
	else
		return false;
}

public static function filter_country($value) {
	$value = trim($value);
	if(preg_match("(USA|AFG|ALA|ALB|DZA|ASM|AND|AGO|AIA|ATA|ATG|ARG|ARM|ABW|AUS|AUT|AZE|BHS|BHR|BGD|BRB|BLR|BEL|BLZ|BEN|BMU|BTN|BOL|BIH|BWA|BVT|BRA|IOT|BRN|BGR|BFA|BDI|KHM|CMR|CAN|CPV|CYM|CAF|TCD|CHL|CHN|CXR|CCK|COL|COM|COD|COK|CRI|CIV|HRV|CUB|CYP|CZE|DNK|DJI|DMA|DOM|TLS|ECU|EGY|SLV|GNQ|ERI|EST|ETH|FLK|FRO|FJI|FIN|MKD|FRA|GUF|PYF|ATF|GAB|GMB|GEO|DEU|GHA|GIB|GRC|GRL|GRD|GLP|GUM|GTM|GGY|GNB|GIN|GUY|HTI|HMD|VAT|HND|HKG|HUN|ISL|IND|IDN|IRN|IRQ|IRL|IMN|ISR|ITA|JAM|JPN|JEY|JOR|KAZ|KEN|KIR|LAO|KOR|KOS|KWT|KGZ|PRK|LVA|LBN|LSO|LBR|LBY|LIE|LTU|LUX|MAC|MDG|MWI|MYS|MDV|MLI|MLT|MHL|MTQ|MRT|MUS|MYT|MEX|FSM|MDA|MCO|MNG|MSR|MAR|MOZ|MMR|NAM|NRU|NPL|ANT|NLD|NCL|NZL|NIC|NER|NGA|NIU|NFK|MNP|NOR|OMN|PAK|PLW|PSE|PAN|PNG|PRY|PER|PHL|PCN|POL|PRT|PRI|QAT|MNE|SRB|REU|ROU|RUS|RWA|BLM|SHN|KNA|LCA|MAF|SPM|WSM|SMR|STP|SAU|SEN|SMX|SYC|SLE|SGP|SVK|SVN|SLB|SOM|ZAF|ESP|LKA|VCT|SGS|SDN|SUR|SJM|SWZ|SWE|CHE|SYR|TWN|TJK|TZA|THA|TGO|TKL|TON|TTO|TUN|TUR|TKM|TCA|TUV|UGA|UKR|ARE|GBR|USA|URY|UMI|UZB|VUT|VEN|VNM|VGB|VIR|WLF|ESH|YEM|YUG|ZMB|ZWE)", $value))
		return $value;
	else
		return false;

	return filter_generic($value);
}


//Validate an email address.
//Provide email address (raw input)
//Returns true if the email address has the email 
//address format and the domain exists.
//copied from : http://www.linuxjournal.com/article/9585?page=0,3
public static function filter_email($email) {
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }

   if($isValid)
	return $email;
   else
	return false;
}

} // End InputSanitation Class

