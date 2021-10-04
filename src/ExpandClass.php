<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * ExpandClass
 * 
 * Element class Management object class,
 * 
 * URL : https://www.mk2-php.com/
 * 
 * Copylight : Nakajima-Satoru 2021.
 *           : Sakaguchiya Co. Ltd. (https://www.teastalk.jp/)
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class ExpandClass{

	private const CLASSTYPE_BACKPACK="Backpack";
	private const CLASSTYPE_UI="UI";
	private const CLASSTYPE_MIDDLEWARE="Middleware";

    private $_classType;
	private $_context;
	private $extendNamespace;
    
	/**
	 * __construct
	 * @param $classType
	 * @param &$context
	 */
    public function __construct($classType,&$context){
        $this->_classType=$classType;
		$this->_context=$context;
		
		if($classType==self::CLASSTYPE_BACKPACK){
			$this->extendNamespace="mk2\backpack_{className}\\";
		}
		else if($classType==self::CLASSTYPE_UI){
			$this->extendNamespace="mk2\ui_{className}\\";
		}
    	else if($classType==self::CLASSTYPE_MIDDLEWARE){
			$this->extendNamespace="mk2\middleware_{className}\\";
		}
    }

    /**
     * load
     * @param $list
     */
    public function load($list){

		if(!is_array($list)){
			$list=[$list];
        }
        
		foreach($list as $name=>$option){

			$className=$name;
			if(is_int($name)){
				$name=$option;
				$className=$option;
				$option=null;
			}

			if(!empty($option["className"])){
				$className=$option["className"];
			}

			if(defined("MK2_DEFNS_".strtoupper($this->_classType))){

				if(!empty($this->_context->Request->params("module"))){
					$moduleName=$this->_context->Request->params("module");
					$namespace="Modules\\".$moduleName."\App\\".$this->_classType;	
				}
				else{
					$namespace=constant("MK2_DEFNS_".strtoupper($this->_classType));
				}

				if(!class_exists("\\".$namespace."\\".$className.$this->_classType)){
					$namespace = MK2_DEFNS."\\".$this->_classType;
				}
			}
			else{
				$namespace=MK2_DEFNS."\\".$this->_classType;
			}
			$className2="\\".$namespace."\\".$className.$this->_classType;


			if(!class_exists($className2)){
				if($this->extendNamespace){
					$className2=str_replace("{className}",strtolower($className),$this->extendNamespace).$className.$this->_classType;

					if(!class_exists($className2)){
						throw new \Exception("class '".$className2."' or '".$namespace."\\".$className.$this->_classType."' not found in");
					}		
				}
				else{
					throw new \Exception("class '".$className2."' or '".$namespace."\\".$className.$this->_classType."' not found in");
				}
			}

			$classObject=new $className2();

			$classObject->__parent=$this->_context;

			if($option){
				foreach($option as $field=>$value){
					$classObject->{$field}=$value;
				}
			}

			$this->{$name}=$classObject;

		}

		return $this;
        
    }

	/**
	 * getFullClassName
	 * @param $className
	 */
	public function getFullClassName($className){


		if(defined("MK2_DEFNS_".strtoupper($this->_classType))){

			if(!empty($this->_context->Request->params("module"))){
				$moduleName=$this->_context->Request->params("module");
				$namespace="Modules\\".$moduleName."\App\\".$this->_classType;	
			}
			else{
				$namespace=constant("MK2_DEFNS_".strtoupper($this->_classType));
			}
			
			if(!class_exists("\\".$namespace."\\".$className.$this->_classType)){
				$namespace = MK2_DEFNS."\\".$this->_classType;
			}
		}
		else{
			$namespace=MK2_DEFNS."\\".$this->_classType;
		}
		$className2="\\".$namespace."\\".$className.$this->_classType;

		if(class_exists($className2)){
			return $className2;
		}
		
		$className2=str_replace("{className}",strtolower($className),$this->extendNamespace).$className.$this->_classType;
		
		if(class_exists($className2)){
			return $className2;
		}

	}

	/**
	 * exists
	 * @params $className 
	 */
	public function exists($className){

		$exists=$this->getFullClassName($className);
		if($exists){
			return true;
		}

		return false;
	}

	/**
	 * clsssName
	 * @param $className
	 */
	public function get($className,$option=null){

		$fullClassName=$this->getFullClassName($className);

		if(!$fullClassName){
			return;
		}

		$classObject=new $fullClassName();

		$classObject->__parent=$this->_context;

		if($option){
			foreach($option as $field=>$value){
				$classObject->{$field}=$value;
			}
		}

		return $classObject;
	}

	/**
	 * my
	 */
	public function my(){

		if(class_exists("Mk2\Libraries\\".$this->_classType)){
			$namespace="Mk2\Libraries";
		}
		else{
			$namespace="\\".MK2_DEFNS."\\".$this->_classType;
		}

		$className=$namespace."\\".$this->_classType;

		$classObject=new $className();

		$classObject->__parent=$this->_context;

		return $classObject;
	}
}