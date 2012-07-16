<?php
include_once "../libs/variables.php";
include_once "../templates/template.php";

// Start Template
$content = new Template();
$content->changeTemplate("../templates/page_payment_response.tpl");

// Set Content
$replace = array();

$replace['GRADHOMEPAGE'] = $GLOBALS['graduate_homepage'];
$replace['TITLE'] = "Transaction Failed";
$replace['APPLICATION_RESULT_MESSAGE'] = "Your transaction has failed.";
$replace['ADDITIONAL_MESSAGE'] = "";

$$content->changeArray($replace);

// Output result
print $content->parse();
