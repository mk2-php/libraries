<?php

namespace Mk2\Libraries;

class Config{

	private static $_data=null;

	public static function set($name=null,$value){

		if($name){
			self::$_data[$name]=$value;
		}
		else{
			self::$_data=$value;
		}

	}

	public static function get($name=null){

		if(!$name){
			return self::$_data;
		}

		$names=explode(".",$name);
		$getData=self::$_data;
		foreach($names as $ind=>$n_){

			if(!empty($getData[$n_])){
				$getData=$getData[$n_];
			}
			else if(in_array($n_,$getData)){
				return true;
			}
			else{
				return null;
			}
		}
		
		return $getData;
	}
	
	public static function exists($name){
		if(self::get($name)){
			return true;
		}
		else{
			return false;
		}
	}

	public static function require($pathName){
		return require MK2_PATH_CONFIG."/".$pathName;
	}
}