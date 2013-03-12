<?php

require_once __DIR__ . "/../configuration.php";

/**
 * Email
 * 
 * Loads and sends emails based on templates
 * 
 * Email templates are specified in php setting the values for to, subject, headers, and message
 */
class Email
{
	// Template directory for all email messages
	private static $templateDirectory = '';

	// Generic email variables
	private $to;
	private $subject;
	private $headers;
	private $message;

	public function Email()
	{
		$this->to = '';
		$this->subject = '';
		$this->headers = '';
		$this->message = '';

		if ( isset($GLOBALS['email_templates']) )
		{
			Email::$templateDirectory = $GLOBALS['email_templates'];
		}

		// Set default value
		if ( Email::$templateDirectory == '' )
		{
			Email::$templateDirectory = __DIR__ . '/../views/templates/emails/';
		}
	}

	/**
	 * Load From Template
	 * 
	 * Prepares the specified email template with the provided data
	 * 
	 * @param    String    emailTemplate    The name of the email template
	 * @param    Array     replacement      Associative array of extra data to replace in the email
	 * 
	 * @return void
	 */
	public function loadFromTemplate($emailTemplate, $replacement = array())
	{
		$templateFullPath = Email::$templateDirectory . $emailTemplate;

		if( !file_exists($templateFullPath) ) {
			throw new Exception("email template $templateFullPath not found");
		}

		// Load email variables (to, subject, headers, and message)
		include $templateFullPath;

		// Perform replacements 
		if( $replacement != array() ) 
		{
			$searchData = array_keys($replacement);
			$replaceData = array_values($replacement);

			$this->to 	= str_replace($searchData, $replaceData, $to);
			$this->subject = str_replace($searchData, $replaceData, $subject);
			$this->headers = str_replace($searchData, $replaceData, $headers);
			$this->message = str_replace($searchData, $replaceData, $message);
		}
	}

	/**
	 * Set Destination Email
	 * 
	 * Specify the destination email
	 * 
	 * @param    String    toEmail    Destination email
	 * 
	 * @return void
	 */
	public function setDestinationEmail($toEmail)
	{
		$this->to = $toEmail;
	}

	/**
	 * Send Email
	 *
	 * Submit the email using the php mail function
	 * 
	 * @return void
	 */
	public function sendEmail()
	{
		mail($this->to, $this->subject, $this->message, $this->headers);
	}

}
