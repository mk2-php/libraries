<?php

namespace Mk2\Libraries;

class Debug{

	public static function out($value){
		$trace=debug_backtrace();
		$firstTrace=$trace[0];

		echo "<pre>";
		echo "<strong>Debug:".$firstTrace["file"]."(".$firstTrace["line"].")</strong><br>";
		print_r($value);
		echo "</pre>";

	}

	public static function logWrite($name,$value){


	}
	
	public static function logRead($name){


	}

}