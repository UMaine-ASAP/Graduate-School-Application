<?php
class DescribeDatabase
{


function itShouldIncrementInstanceCounterForNewConnections {

	$applicant = Applicant::getApplicant(2);
	print $applicant->getFullName();
	print "<br><br>";

	$applicant2 = Applicant::getApplicant(1);
	print $applicant2->getFullName();
	print "<br><br>";

	unset($applicant);

	$applicant3 = Applicant::getApplicant(3);
	print $applicant3->getFullName();
	print "<br><br>";

	unset($applicant2);

	echo "<br>end<br>";	
}

function itShouldDecrementInstanceCounterForNewConnections {
}

function itShouldHaveZeroCounterAfterARandomNumberOfInstantiations() {
	
}


}