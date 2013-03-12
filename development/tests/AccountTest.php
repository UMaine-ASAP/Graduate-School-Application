<?php
session_cache_limiter(false);
session_start();

$app_directory = __DIR__ . "/../../../application";

require_once "PHPUnit/Autoload.php";

//require_once $app_directory . "/routes.php";
require_once $app_directory . "/libraries/Slim/Slim.php";
require_once $app_directory . "/models/Applicant.php";
require_once $app_directory . "/controllers/ApplicantController.php";

require_once __DIR__ . '/curl.php';

require_once $app_directory . "/models/databaseConfig.php";


class AccountTest extends PHPUnit_Framework_TestCase
{

    public function query($relativePath)
    {
    	$curl = new Curl();
    	return $curl->setUrl($GLOBALS['WEBROOT'] . "$relativePath");
    }

    protected function setUp() {
    		// login to system
 	     $curl = $this->query('/login')->setCookieOptions()
 	         ->setData(array('form_name' => 'signIn',
 	         				'email' => 'timbone945@gmail.com',
 	         				'password' => 'test123'))
 	         ->setType('POST');
 	     $curl->send();  

 	     // set active application
 	     $curl = $this->query('/edit-application/1')
 	         ->setType('GET');
 	     $curl->send();  
    }

    public function testIndexRedirects() {
 	     $curl = $this->query('/')
// 	         ->setData('&q=testing+curl')
 	         ->setType('GET');
 	     $curl->send();
        $this->assertEquals('302', $curl->getStatusCode());    	
    }

    public function testLoginPageExists() {
 	     $curl = $this->query('/login')->setType('GET');
 	     $curl->send();
        $this->assertRegExp('|login|', $curl->getBody());    	
    }

    public function testRegistration() {
    	//$this->assertEquals(0,1);
    }

    public function testLogin() {
    	//$this->assertEquals(0,1);
    }

    public function testSavingAllDatabaseValues() {

    	$testValues = array(	
    		'filter_generic'      => 'text',
		'filter_short_date'   => '01/2013',
		'filter_long_date'    => '01/01/2013',
		'filter_date_range'   => '01/2013-02/2013',	
		'filter_boolean'      => '1',

		'filter_phone'        => '207-111-1111',
		'filter_email'        => 'test@yahoo.com',
		'filter_zipcode'      => '04468',
		'filter_ssn'          => '',
		'filter_suffix'       => 'mr',
		'filter_state'        => 'ME',
		'filter_country'      => 'USA',
		'filter_gender'       => 'M',
		'filter_gpa'          => '4.0',
		'filter_residency'    => 'resident',

		'filter_name'		  => 'John',

		'filter_proficiency'  => '300',
		'filter_toefl_score'  => '300',
		'filter_relationship' => '300',

		// GRE
		'filter_gre_verbal'       => '300',
		'filter_gre_quantitative' => '300',
		'filter_gre_analytical'   => '6.0',
		'filter_gre_score'        => '300',
		'filter_gre_subject'      => '300',

		// GMAT
		'filter_gmat_score'        => '300',
		'filter_gmat_quantitative' => '300',
		'filter_gmat_verbal'       => '300',
		'filter_gmat_analytical'   => '300',
		'filter_gmat_score'        => '300',

		// MAT
		'filter_mat_score' => '300');

    	foreach ($GLOBALS['databaseFields'] as $fieldName => $filter) {
    		// don't test social security number
    		if($fieldName == 'personal-socialSecurityNumber') continue;

    		// don't test repeatables
    		if(strpos($fieldName, 'language') !== false) continue;
    		if(strpos($fieldName, 'mailing') !== false) continue;
    		if(strpos($fieldName, 'Contact') !== false) continue;
    		if(strpos($fieldName, 'contact') !== false) continue;
    		if(strpos($fieldName, 'gre-') !== false) continue;
    		if(strpos($fieldName, 'previousSchool') !== false) continue;
    		if(strpos($fieldName, 'Violation') !== false) continue;
    		if(strpos($fieldName, 'reference') !== false) continue;

    		$this->assertEquals("\n", $this->mockSave($fieldName, $testValues[$filter]));
    	}
    }

    public function mockSave($fieldName, $value)
    {

 	     $curl = $this->query('/application/saveField')
 	         ->setData(array('field' => $fieldName,
 	         				'value' => $value))
 	         ->setCookie()
 	         ->setType('POST');
 	     $curl->send();
 	     return $curl->getBody();
    }



    protected function tearDown() {
    }

}