<?php

require_once __DIR__ . "/../config.php";

/**
 * Email
 * 
 * Processes emails from template
 */
class Email
{
	private static $email_template_directory = '/../templates/emails/';

	private $to;
	private $subject;
	private $headers;
	private $message;

	public function EmailSystem()
	{
		$this->to = '';
		$this->subject = '';
		$this->headers = '';
		$this->message = '';

		EmailSystem::$email_template_directory = $GLOBALS['email_templates'];
	}

	public function loadFromTemplate($email_template, $replacement = array())
	{
		$email_template_path = __DIR__ . Email::$email_template_directory . $email_template;

		if( !file_exists($email_template_path) ) {
			throw new Exception("email template $email_template_path not found");
		}

		// Load email variables (to, subject, headers, and message)
		include $email_template_path;

		$search_data = array_keys($replacement);
		$replace_data = array_values($replacement);

		$this->to 	= str_replace($search_data, $replace_data, $to);
		$this->subject = str_replace($search_data, $replace_data, $subject);
		$this->headers = str_replace($search_data, $replace_data, $headers);
		$this->message = str_replace($search_data, $replace_data, $message);
	}

	public function setDestinationEmail($toEmail)
	{
		$this->to = $toEmail;
	}

	public function sendEmail()
	{
		mail($this->to, $this->subject, $this->message, $this->headers);
	}

}
