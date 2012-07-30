<?php

// Libraries
require_once __DIR__ . "/../libs/database.php";
require_once __DIR__ . "/../libs/corefuncs.php";

// Scripts
require_once __DIR__ . "/../scripts/exportApplication/application_generate_pdf.php";


class Application
{
	private $application_id;
	private $db;

	function Application()
	{
       $this->db = Database::getInstance();
	}

   function __destruct()
   {
   		$this->db->close();
   }

	/** Get a new application object from current session data **/
	public static function getActiveApplication()
	{
		$id = check_ses_vars();
		if($id == 0) { 
			return NULL;
		} else {
			return Application::getApplication($id);
		}
	}

	/** Get a new application object by passed in id **/
	public static function getApplication($id)
	{
		$instance = new self();
       if( is_integer($id) ) {
	       $instance->application_id = $id;
	       return $instance;
       } else {
       		throw new Exception("Passed in application identifier is not an integer.", 1);
       }		
	}

	public function hasBeenSubmitted()
	{
		$result = $this->db->query('SELECT has_been_submitted FROM applicants WHERE applicant_id=%d', $this->application_id);
		if( !is_array($result) ) {
			return TRUE;
		}
		$has_been_submitted = ($result[0]['has_been_submitted']) ? TRUE : FALSE;
		return $has_been_submitted;
	}

	public function submitWithPayment($paymentIsHappeningNow)
	{
		// Set payment method
		$payment_method = ($paymentIsHappeningNow) ? "PAYNOW" : "PAYLATER";
		$db->iquery("UPDATE applicants SET application_payment_method='%s' WHERE applicant_id=%d", $payment_method, $this->application_id );

		// Update database to show that application has been submitted
		$db->iquery("UPDATE `applicants` SET `has_been_submitted` = '1' WHERE `applicant_id` = %d LIMIT 1", $this->application_id);
	
		// Set application submit date
		$date = date("Y-m-d");
		$db->iquery("UPDATE `applicants` SET `application_submit_date` = '%s' WHERE `applicant_id` = %d LIMIT 1", $date, $this->application_id);		


		// Submitting with payment
		if( $paymentIsHappeningNow ) {
			//Generate transaction ID
			$trans_id = 'UMGRAD' . '*' . $this->applicant_id . '*' . time();
			
			//update database transaction_id
			$db->iquery("UPDATE applicants SET application_fee_transaction_number='%s' WHERE applicant_id=%d", $trans_id, $this->applicant_id);
			
			//Fetch application cost
			$app_cost_query = $db->query("SELECT application_fee_transaction_amount FROM applicants WHERE applicant_id=%d", $this->applicant_id);
			$app_cost = $app_cost_query[0][0];
			
			//Build request
			$data ='UPAY_SITE_ID=' . $GLOBALS["touchnet_site_id"].'&';
			$data.='UMS_APP_ID='   . $GLOBALS["touchnet_app_id"].'&';
			$data.='EXT_TRANS_ID=' . $trans_id.'&';
			$data.='AMT=' . $app_cost;
			
			$header = array("MIME-Version: 1.0","Content-type: application/x-www-form-urlencoded","Contenttransfer-encoding: text");
			
			//Execute request
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_URL, $GLOBALS["touchnet_proxy_url"]);
			curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
			curl_setopt($ch, CURLPROTO_HTTPS, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10); //Max time to connect
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //Put result in variable
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
			
			if ( ! $result = curl_exec($ch) ) {
				trigger_error(curl_error($ch));
			}
			curl_close($ch);
			
			/** print curl results **/
			
			//Insert base url into result after head tag so that redirects are correct
			$baseTag = "<base href='" . $GLOBALS['touchnet_proxy_url'] . "' >";
			$pos = strpos($result, '<head>') + strlen('<head>');
			$result = substr_replace($result, $baseTag, $pos, 0);
			
			print $result;						
		}
	}

	public function generateClientPDF() {
		generate_application_pdf("USER");
	}

	public function generateServerPDF() {
		generate_application_pdf("SERVER");
	}

}

