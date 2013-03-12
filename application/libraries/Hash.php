<?php

/**
 * Generate an encoded value
 */
class Hash
{

	/**
	 * 
	 * Source: ????
	 */
	public static function create( $index )
	{
		$validCharacters = 'abcdefghijklmnopqerstuv0123456789';
		$mod = strlen($validCharacters);
		$hash = '';
		$tmp = $index;

		while( $tmp > $mod )
		{
			$hash .= substr($validCharacters, $tmp%$mod,1);
			$tmp = floor( $tmp / $mod );
		}
		return $hash;
	}
	
}