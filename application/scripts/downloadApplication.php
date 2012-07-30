<?php

// Libraries
include_once "../libs/corefuncs.php";

// Controllers
include_once "../controllers/application.php";

// Redirect if user is not logged in
redirect_Unauthorized_User("../application/pages/login.php");

// Data
$application = Application::getActiveApplication();


$application->generateClientPDF();


