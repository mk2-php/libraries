<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * Routes
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

class Routes{

    private $_data = [];
    private $_main = null;
    private $_mode = "pages";
    private $_type = "release";
    private $_scope = false;

    public const TYPE_PAGES = "pages";
    public const TYPE_SHELL = "shell";

    public function __construct($mode = null){
        $this->_main = $mode;
    }

    /**
     * set
     * @param $method
     * @param $url
     * @param $params 
     */
    public function set($method,$url,$params){
        return $this->_set($method,$url,$params);
    }

    /**
     * route
     * @param $url
     * @param $params 
     */
    public function route($url,$params){
        return $this->_set(null,$url,$params);
    }

    /**
     * get
     * @param $url
     * @param $params 
     */
    public function get($url,$params){
        return $this->_set("GET",$url,$params);
    }

    /**
     * post
     * @param $url
     * @param $params 
     */
    public function post($url,$params){
        return $this->_set("POST",$url,$params);
    }

    /**
     * put
     * @param $url
     * @param $params 
     */
    public function put($url,$params){
        return $this->_set("PUT",$url,$params);
    }

    /**
     * delete
     * @param $url
     * @param $params 
     */
    public function delete($url,$params){
        return $this->_set("DELETE",$url,$params);
    }

    /**
     * option
     * @param $url
     * @param $params 
     */
    public function option($url,$params){
        return $this->_set("OPTION",$url,$params);
    }

    /**
     * _set
     * @param $method
     * @param $url
     * @param $params 
     */
    private function _set($method,$url,$params){	

        if($method){
            if($this->_scope){
                if($this->_main){
                    $this->_data[$this->_type][$this->_scope][$url][$method] = $params;
                }
                else{
                    $this->_data[$this->_mode][$this->_type][$this->_scope][$url][$method] = $params;
                }
            }
            else{
                if($this->_main){
                    $this->_data[$this->_type][$url][$method] = $params;
                }
                else{
                    $this->_data[$this->_mode][$this->_type][$url][$method] = $params;
                }
            }	
        }
        else{
            if($this->_scope){
                if($this->_main){
                    $this->_data[$this->_type][$this->_scope][$url] = $params;
                }
                else{
                    $this->_data[$this->_mode][$this->_type][$this->_scope][$url] = $params;
                }
            }
            else{
                if($this->_main){
                    $this->_data[$this->_type][$url] = $params;
                }
                else{
                    $this->_data[$this->_mode][$this->_type][$url] = $params;
                }
            }	
        }
        return $this;
    }

    /**
     * out
     */
    public function out(){
        return $this->_data;
    }

    /**
     * pages
     */
    public function pages(){
        $this->_mode="pages";
        $this->_scope=null;
        return $this;
    }

    /**
     * shell
     */
    public function shell(){
        $this->_mode="shell";
        $this->_scope=null;
        return $this;
    }

    /**
     * release
     */
    public function release(){
        $this->_type="release";
        $this->_scope=null;
        return $this;
    }

    /**
     * error
     */
    public function error(){
        $this->_type="error";
        $this->_scope=null;
        return $this;
    }

    /**
     * scope
     * @param $name = null
     */
    public function scope($name = null){
        if($name){
            if($this->_type=="release"){
                if($this->_main){
                    $this->_data["scope"] = true;
                }
                else{
                    $this->_data[$this->_mode]["scope"] = true;
                }
            }
            else if($this->_type=="error"){
                $this->_data[$this->_mode]["errorScope"] = true;
            }
        }
        $this->_scope=$name;
        return $this;
    }

}