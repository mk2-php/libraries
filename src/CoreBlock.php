<?php

namespace Mk2\Libraries;

class CoreBlock{

	public function __construct(){

		$useClass=Config::get("config.useClass");
		foreach($useClass as $c_){
			$this->{$c_}=new ExpandClass($c_,$this);
		}
		
		if(Config::exists("config.coreBlock.useRequest")){
			$this->Request=new Request();
		}
		if(Config::exists("config.coreBlock.useResponse")){
			$this->Response=new Response($this);
		}
		if(php_sapi_name()=="cli"){

			if(Config::exists("config.coreBlock.useCommand")){
				// Set Command Class (CLI Mode Only)
				require_once "Command.php";
				$this->Command=new Command($this);
			}

		}

	}

	public function require($path){

		$getData= ResponseData::get();

		foreach($getData as $field=>$value){
			$$field=$value;
		}

		require $path;
	}
}