<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * Hash
 * 
 * Object class for initial operation.
 * 
 * URL : https://www.mk2-php.com/
 * 
 * Copylight : Nakajima-Satoru 2021.
 *           : Sakaguchiya Co. Ltd. (https://www.teastalk.jp/)
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class Hash{

	/**
	 * get
	 * @param array $target
	 * @param string $name
	 */
	public static function get($target,$name){

		$names=explode(".",$name);

		foreach($names as $n_){

			$target=(array)$target;
			
			if(empty($target[$n_])){
				return null;
			}

			$target=$target[$n_];
		}

		return $target;

	}

	/**
	 * exists
	 * @param array $target
	 * @param string $name
	 */
	public static function exists($target,$name){

		$check=self::get($target,$name);
		if($check){
			return true;
		}

		return false;
	}

}