<?php

namespace Mk2\Libraries;

class ExpandClass{

    private $_classType;
    private $_context;
    
    public function __construct($classType,&$context){
        $this->_classType=$classType;
        $this->_context=$context;
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

			$className="\\".$namespace."\\".$className.$this->_classType;

			$classObject=new $className();

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

}