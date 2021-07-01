<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * CoreBlock
 * 
 * Element class base object class.
 * 
 * URL : https://www.mk2-php.com/
 * 
 * Copylight : Nakajima-Satoru 2021.
 *           : Sakaguchiya Co. Ltd. (https://www.teastalk.jp/)
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class Debug{

	/**
	 * out
	 * @param $value
	 */
	public static function out($value){
		$trace=debug_backtrace();
		$firstTrace=$trace[0];

		echo "<pre>";
		echo "<strong>Debug:".$firstTrace["file"]."(".$firstTrace["line"].")</strong><br>";
		print_r($value);
		echo "</pre>";

	}

}