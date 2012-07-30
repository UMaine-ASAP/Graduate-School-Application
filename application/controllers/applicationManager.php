<?php

// Libraries
require_once __DIR__ . "/../libs/database.php";
require_once __DIR__ . "/../libs/corefuncs.php";

// Controllers
require_once __DIR__ . "/application.php";


class ApplicationManager
{

	private $application_id;
	private $db;

	function ApplicationManager()
	{
       $this->db = Database::getInstance();
       $this->application_id = Application::getActiveApplication();
	}

   	function __destruct() {
   		$this->db->close();
   	}


}
