<?php

/**
 * ===================================================
 * 
 * [Mark2] - Validator
 * 
 * Object class for initial operation.
 * 
 * URL : https://www/mk2-php.com/
 * Copylight : Nakajima-Satoru 2021.
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class Validator extends CoreBlock{

    protected $_validator;

    /**
     * __construct
     * @param $option = null
     */
    public function __construct($option=null){
        $this->_validator=new \Mk2\Validator\Validator($this);
    }

    /**
     * verify
     * @param $post
     * @param $validateName = null
     */
    public function verify($post, $validateName=null){
        return $this->_validator->verify($post,$validateName);
    }

    /**
     * addRule
     * @param ...$argv
     */
    public function addRule(...$argv){
        return $this->_validator->addRule(...$argv);
    }

    /**
     * deleteRule
     * @param $field
     * @param $name = null
     */
    public function deleteRule($field,$name=null){
        return $this->_validator->deleteRule($field,$name);
    }

    /**
     * getValue
     * @param $field
     */
    public function getValue($field){
        return $this->_validator->getValue($field);
    }

}