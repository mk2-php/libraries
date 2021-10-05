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

class CoreBlock{

	/**
	 * __consturct
	 * @param $option = null
	 */
	public function __construct($option = null){

		if($option){
			foreach($option as $field=>$value){
				$this->{$field}=$value;
			}
		}

		$useClass=Config::get("config.useClass");
		if($useClass){
			foreach($useClass as $c_){
				$this->{$c_}=new ExpandClass($c_,$this);
			}	
		}
		
		if(Config::exists("config.coreBlock.useRequest")){
			// Load Reuqest Class
			$this->Request=new Request();
		}
		if(Config::exists("config.coreBlock.useResponse")){
			// Load Response Class
			$this->Response=new Response($this);
		}
		if(php_sapi_name()=="cli"){

			if(Config::exists("config.coreBlock.useCommand")){
				// Set Command Class (CLI Mode Only)
				require_once "Command.php";
				$this->Command=new Command($this);
			}
		}

		if(\method_exists($this,"handleBefore")){
			if(!empty($this->errorException)){
				$this->handleBefore($this->errorException);
			}
			else{
				$this->handleBefore();
			}
		}
	}

	/**
	 * require
	 * @param $path
	 * @param $requestData = null
	 * @return string $path
	 */
	public function require($path,$requestData = null){

		$getData= ResponseData::get();

		if($requestData){
			foreach($requestData as $field=>$value){
				$$field=$value;
			}
		}
		foreach($getData as $field=>$value){
			$$field=$value;
		}

		require $path;
	}
}