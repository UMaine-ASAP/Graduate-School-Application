<?php
include_once "../libs/variables.php";
include_once "../templates/template.php";

// Start Template
$content = new Template();
$content->changeTemplate("../templates/page_payment_response.tpl");

// Set Content
$replace = array();

$replace['GRADHOMEPAGE'] = $GLOBALS['graduate_homepage'];
$replace['TITLE'] = "Transaction Canceled";
$replace['APPLICATION_RESULT_MESSAGE'] = "You have successfully submitted an online application to The University of Maine Graduate School, however your application fee payment transaction has been cancelled. Please contact the Graduate School office at 207-581-3291 to pay the application fee. Applications are not processed until an application fee has been received.";
$replace['ADDITIONAL_MESSAGE'] = "";

$$content->changeArray($replace);

// Output result
print $content->parse();

