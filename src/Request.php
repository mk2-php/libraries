<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * Request
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

class Request{

	/**
	 * data
	 */
	public function data(){

		if($_SERVER['REQUEST_METHOD']==RequestCollectionStatic::METHOD_QUERY){
			return $this->query();
		}
		else if($_SERVER['REQUEST_METHOD']==RequestCollectionStatic::METHOD_POST){
			return $this->post();
		}
		else if($_SERVER['REQUEST_METHOD']==RequestCollectionStatic::METHOD_PUT){
			return $this->put();
		}
		else if($_SERVER['REQUEST_METHOD']==RequestCollectionStatic::METHOD_DELETE){
			return $this->delete();
		}

	}

	/**
	 * params
	 * @param string $name = null
	 */
	public function params($name=null){

		if($name){
			if(!empty(Routings::$_data[$name])){
				return Routings::$_data[$name];
			}
		}
		else{
			return Routings::$_data;
		}

	}

	/**
	 * query
	 */
	public function query(){
		return new RequestCollection(RequestCollectionStatic::METHOD_QUERY);
	}

	/**
	 * post
	 */
	public function post(){
		return new RequestCollection(RequestCollectionStatic::METHOD_POST);
	}

	/**
	 * put
	 */
	public function put(){
		return new RequestCollection(RequestCollectionStatic::METHOD_PUT);
	}

	/**
	 * delete
	 */
	public function delete(){
		return new RequestCollection(RequestCollectionStatic::METHOD_DELETE);
	}

}

class RequestCollection{
	
	private $type;

	public function __construct($type){
		$this->type=$type;
	}

	/**
	 * get
	 * @param $name = null
	 */
	public function get($name=null){
		return RequestCollectionStatic::get($this->type,$name);
	}

	/**
	 * set
	 * @param $values
	 */
	public function set($values){
		return RequestCollectionStatic::set($this->type,$values);
	}

	/**
	 * exists
	 * @param $name = null
	 */
	public function exists($name=null){
		return RequestCollectionStatic::exists($this->type,$name);
	}

	/**
	 * delete
	 * @param $name = null
	 */
	public function delete($values=null){
		return RequestCollectionStatic::delete($this->type,$values);
	}
}

class RequestCollectionStatic{

	private static $_request=[];
	
	public const METHOD_QUERY="GET";
	public const METHOD_POST="POST";
	public const METHOD_PUT="PUT";
	public const METHOD_DELETE="DELETE";

	/**
	 * get
	 * @param $type
	 * @param $name
	 */
	public static function get($type,$name=null){

		if(!isset(self::$_request[$type])){
			$mediaType=null;
			if(!empty($_SERVER['CONTENT_TYPE'])){
				$content_type=explode(';',trim(strtolower($_SERVER['CONTENT_TYPE'])));
				$mediaType=$content_type[0];	
			}
	
			if ($_SERVER['REQUEST_METHOD']==$type && $mediaType=='application/json') {
				if($type==self::METHOD_QUERY){
					$request=$_GET;
				}
				else{
					// Correspondence in case of json format.
					$request = json_decode(file_get_contents('php://input'), true);
				}
			}
			else{
				if($type==self::METHOD_QUERY){
					$request=$_GET;
				}
				else if($type==self::METHOD_POST){
					$request=$_POST;
				}
				else{
					$request=file_get_contents('php://input');
				}
			}
	
			self::$_request[$type]=$request;	
		}

		if($name){

			$names=explode(".",$name);

			$buff=self::$_request[$type];

			foreach($names as $n_){
				if(isset($buff[$n_])){
					$buff=$buff[$n_];
				}
				else{
					$buff=null;
				}
			}

			return $buff;
		}

		return self::$_request[$type];	
	}

	/**
	 * exists
	 * @param $type
	 * @param $name = null
	 */
	public static function exists($type,$name=null){
		if(self::get($type,$name)){
			return true;
		}
		return false;
	}

	/**
	 * set
	 * @param $type
	 * @param $values
	 */
	public static function set($type,$values){

		if(!isset(self::$_request[$type])){
			self::get($type);
		}

		foreach($values as $key=>$val){
			self::$_request[$type][$key]=$val;
		}

	}

	/**
	 * delete
	 * @param $type
	 * @param $values
	 */
	public static function delete($type,$values){
		
		if(!isset(self::$_request[$type])){
			self::get($type);
		}

		foreach($values as $colum){
			unset(self::$_request[$type][$colum]);
		}

	}
}