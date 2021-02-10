<?php

/**
 * ===================================================
 * 
 * [Mark2] - ExpandClass
 * 
 * Element class Management object class,
 * 
 * URL : https://www/mk2-php.com/
 * Copylight : Nakajima-Satoru 2021.
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
				$namespace=constant("MK2_DEFNS_".strtoupper($this->_classType));
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
			}

			$classObject=new $className2();

			$classObject->__parent=$this->_context;

			if($option){
				foreach($option as $field=>$value){
					$classObject->{$field}=$value;
				}
			}

			if(\method_exists($classObject,"handleBefore")){
				$classObject->handleBefore();
			}

			$this->{$name}=$classObject;

		}

		return $this;
        
    }

	/**
	 * exists
	 * @params $className 
	 */
	public function exists($className){

		if(defined("MK2_DEFNS_".strtoupper($this->_classType))){
			$namespace=constant("MK2_DEFNS_".strtoupper($this->_classType));
		}
		else{
			$namespace=MK2_DEFNS."\\".$this->_classType;
		}
		$className2="\\".$namespace."\\".$className.$this->_classType;

		if(class_exists($className2)){
			return true;
		}
		
		$className2=str_replace("{className}",strtolower($className),$this->extendNamespace).$className.$this->_classType;
		
		if(class_exists($className2)){
			return true;
		}

		return false;

	}
}