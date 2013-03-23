<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";


class Transaction extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Transaction';
	protected static $primaryKeys = array('transactionId');


	protected static $availableProperties = array('pretty_isPayingOnline');

	/**
	 * Magic Getter
	 * 
	 * Gets the data for special properties
	 * 
	 * @return any
	 */
	public function __get($name)
	{
		// Data
		 switch($name)
		 {
		 	case 'pretty_isPayingOnline':
				return ($personal_data['application_payment_method'] == 1) ? 'Paying Online' : 'Paying Offline';
		 	break;
		 }

		return parent::__get($name);
	}
}
