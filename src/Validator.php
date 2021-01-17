<?php

namespace Mk2\Libraries;

class Validator extends CoreBlock{

    protected $_validator;

    public function __construct($option=null){
        $this->_validator=new \Mk2\Validator\Validator($this);
    }

    public function verify($post,$validateName=null){
        return $this->_validator->verify($post,$validateName);
    }

    public function addRule(...$argv){
        return $this->_validator->addRule(...$argv);
    }

    public function deleteRule($field,$name=null){
        return $this->_validator->deleteRule($field,$name);
    }
    
    public function getValue($field){
        return $this->_validator->getValue($field);
    }

}