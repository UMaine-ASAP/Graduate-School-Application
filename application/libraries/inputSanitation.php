<?php

// Sanitize data in database before passing
// 
class InputSanitation
{

	// accepted database field types
	private static $databaseFieldTypes;

	// Available filters and associated error messages
	private static $filterErrorMessages = array(
		// General filters
		'filter_generic'      => 'Invalid option',
		'filter_short_date'   => 'Invalid date',
		'filter_long_date'    => 'Invalid date',
		'filter_date_range'   => 'Invalid date range',	
		'filter_boolean'      => 'Invalid option',
	
		'filter_phone'        => 'Invalid phone number',
		'filter_email'        => 'Invalid email address',
		'filter_zipcode'      => 'Invalid postal code',
		'filter_ssn'          => 'Invalid social security number',
		'filter_suffix'       => 'Invalid option',
		'filter_state'        => 'Invalid option',
		'filter_country'      => 'Invalid option',
		'filter_gender'       => 'Invalid option',
		'filter_gpa'          => 'GPA invalid',
		'filter_residency'    => 'Invalid option',
	
		'filter_name'		  => 'Invalid name',
	
		'filter_proficiency'  => 'Invalid option',
		'filter_toefl_score'  => 'Invalid value',
		'filter_relationship' => 'Invalid value',
	
		// GRE
		'filter_gre_verbal'       => 'Invalid score',
		'filter_gre_quantitative' => 'Invalid score',
		'filter_gre_analytical'   => 'Invalid score',
		'filter_gre_score'        => 'Invalid score',
		'filter_gre_subject'      => 'Invalid subject',
	
		// GMAT
		'filter_gmat_score'        => 'Invalid score',
		'filter_gmat_quantitative' => 'Invalid score',
		'filter_gmat_verbal'       => 'Invalid score',
		'filter_gmat_analytical'   => 'Invalid score',
		'filter_gmat_score'        => 'Invalid score',
	
		// MAT
		'filter_mat_score' => 'Invalid score'
	
	);


	/**
	 * Initialize input Sanitation
	 * 
	 * @param    array    Array of valid field values. See databaseConfiguration.php for an example
	 * 
	 * @return    void
	 */
	public static function initialize($fieldInfo) {
		self::$databaseFieldTypes = $fieldInfo;
	}


	/**
	 * Clean characters by replacing smart quotes with regular quotes
	 *
	 * Solution and replacement values found at http://php.tonnikala.org/manual/en/function.utf8-decode.php
	 * 
	 * @param    string    Value to clean
	 * 
	 * @return    string    Cleeaned value
	 */
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
	

	/**
	 * Sanitize value
	 * 
	 * @param    string    Value to sanitize
	 * 
	 * @return    string    Sanitized value
	 */
	public static function sanitizeString( $var ) {
		return( filter_var($var, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW) );
	}


	/**
	 * Checks whether a value is valid to put in a field
	 * 
	 * @param    string    Field name
	 * @param    string    Value to check
	 * @param    string    Reference to variable to store error message
	 * 
	 * @return    bool    True if value for field name passes filter test, otherwise false
	 */
	public static function isValid($name, $value, &$errorMessage) {
		$errorMessage = "";
		if(!$value) return TRUE;
		$valid = FALSE;
		$tested = FALSE;
	
		// Find option
		$option = null;
		foreach (self::$databaseFieldTypes as $fields) {
			foreach ($fields as $fieldName => $fieldData) {
				if ( $name == $fieldName ) {
					$option = $fieldData['filter'];
					break;
				}
			}
		}
		if ($option == null) {
			return "Error Saving";
		}
	
		// Ensure option is valid
		if( $option != null && array_key_exists($option, self::$filterErrorMessages) ) {
			$errorMessage = self::filterResult($value, $option);
		} else {
			error_log("Input Sanitation Error: Field $name not saved. Either the filter option isn't set or the specified option doesn't exist. See databaseConfig.php for field settings.");
			$errorMessage = 'Error saving'; // @pragma TEMPONLY_REMOVEME
		}
	
		return $errorMessage == ''; // return if error message exists
	}


	/**
	 * Filter a value by filter function
	 * 
	 * @param    string    Value to filter
	 * @param    string    Filter function to use
	 * @param    string    (optional) Error message to display. Defaults to message in filterErrorMessages
	 * 
	 * @return    string    Error message if value does not pass filter, otherwise empty
	 */
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
			if($message != '') 
			{
				return $message;
			}
			// check if filter is valid
	
			if( isset( self::$filterErrorMessages[$option] ) )
			{
				return self::$filterErrorMessages[$option];
			} else {
				// Filter error message did not exist -> freak out!
				throw new Exception("Internal Error Saving Data");
			}
		}
	}


	/**
	 * Replaces non-digit characters with empty string
	 * 
	 * @param    string    String for filtering
	 * 
	 * @return     string    String with non-digits characters stripped out
	 */
	public static function replaceNonDigits($value)
	{
		return preg_replace('/[^\d]', '', $value);
	}


	/**
	 * Replaces non-alphanumeric characters with empty string
	 * 
	 * @param    string    String for filtering
	 * 
	 * @return     string    String with non-alphanumeric characters stripped out
	 */
	public static function replaceNonAlphanumeric($value)
	{
		return preg_replace('/[^\da-z]/i', '', $value);
	}


	/* ============================================ */
	/* = Filters
	/* ============================================ */

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
			$result = self::filter_short_date($dates[0]) && self::filter_short_date($dates[1]);
	
			return $result;
		} else {
			return false;
		}
		//filter_long_date;
	}

	//format mm/dd/yyyy
	public static function filter_long_date($value) {
		if(preg_match("((0[1-9]|[10-12])/(0[1-9]|[12][0-9]|3[01])/(19|20)\d\d)", $value)) {
			$v = explode("/", $value);
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
		return self::filter_generic($value);
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
