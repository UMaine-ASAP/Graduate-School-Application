
<?php

class ErrorTracker {
	
	private $errors;

	public function Error() {
		$this->errors = array();
	}

	public function add($message, $type='warning')
	{
		$this->errors[] = array('message' => $message, 'type' => $type);
	}

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

	public function hasErrors()
	{
		return count($this->errors) > 0;
	}
	
}