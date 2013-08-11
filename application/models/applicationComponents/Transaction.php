<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";


class Transaction extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Transaction';
	protected static $primaryKeys = array('transactionId');


	protected static $availableProperties = array('pretty_isPayingOnline', 'pretty_paymentMethod');

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
				return ($this->isPayingOnline == 1) ? 'Paying Online' : 'Paying Offline';
		 	break;
		 	case 'pretty_paymentMethod':
		 		return ($this->paymentMethod == 'CC') ? 'Credit Card' : $this->paymentMethod;
		 	break;
		 }

		return parent::__get($name);
	}
}
