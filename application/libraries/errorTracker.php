<?php

/**
 * Manages and renders a collection of error messages
 */
class ErrorTracker {
	
	private $errors; //errors declared 


	/**
	 * Create a new error Tracker
	 */
	public function ErrorTracker() {
		$this->errors = array();
	}


	/**
	 * Add an error message
	 * 
	 * @param    string    Message to add
	 * @param    string    (optional) Type of error message
	 * 
	 * @return void
	 **/
	public function add($message, $type='warning')
	{
		$this->errors[] = array('message' => $message, 'type' => $type);
	}


	/**
	 * Render errors
	 * 
	 * @return    html    Error messages
	 **/
	public function render()
	{
		$output = '';
		foreach($this->errors as $error)
		{
			$message = $error['message'];
			$type = $error['type'];
			$output .= "<p class='$type'>$message</p>";
		}
		return $output;
	}


	/**
	 * Check whether errors exist
	 * 
	 * @return    bool    True if errors exist, otherwise false
	 **/
	public function hasErrors()
	{
		return count($this->errors) > 0;
	}
	
}