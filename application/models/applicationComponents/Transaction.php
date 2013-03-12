<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";


class Transaction extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Transaction';
	protected static $primaryKeys = array('transactionId');


	protected static $availableProperties = array('appliedProgram', 'pretty_isSeekingAssistantship', 'pretty_isApplyingNebhe', 'pretty_academic_load');

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
		 	case 'pretty-isPayingOnline':
				return ($personal_data['application_payment_method'] == 1) ? 'Paying Online' : 'Paying Offline';
		 	break;
		 }

		return parent::__get($name);
	}
}
