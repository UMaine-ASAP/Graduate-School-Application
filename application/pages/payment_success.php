<?php
include_once "../libs/variables.php";
include_once "../templates/template.php";

// Start Template
$content = new Template();
$content->changeTemplate("../templates/page_payment_response.tpl");

// Set Content
$replace = array();

$replace['GRADHOMEPAGE'] = $GLOBALS['graduate_homepage'];
$replace['TITLE'] = "Transaction Successful";
$replace['APPLICATION_RESULT_MESSAGE'] = "Your application was submitted successfully.";
$replace['ADDITIONAL_MESSAGE'] = "As soon as your payment has been received your application will be reviewed.";

$$content->changeArray($replace);

// Output result
print $content->parse();
