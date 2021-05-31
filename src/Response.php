<?php

/**
 * ===================================================
 * 
 * [Mark2] - ResponseData
 * 
 * Object class for initial operation.
 * 
 * URL : https://www/mk2-php.com/
 * Copylight : Nakajima-Satoru 2021.
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

use Exception;

class ResponseData{

	private static $_data=[];

	/**
	 * get
	 * @param $name = null
	 */
	public static function get($name=null){

		if($name){
			if(!empty(self::$_data[$name])){
				return self::$data[$name];
			}
		}
		else{
			return self::$_data;
		}

	}

	/**
	 * set
	 * @param $name
	 * @param $value
	 */
	public static function set($name,$value){
		self::$_data[$name]=$value;
	}

}

class Response{

	private const TEMPLATEENGINE_SMARTY="smarty";
	private const TEMPLATEENGINE_TWIG="twig";

	/**
	 * __construct
	 * @param &$context
	 */
	public function __construct(&$context){
		$this->context=$context;
	}

	/**
	 * getCode
	 */
	public function getCode(){
		return http_response_code();
	}

	/**
	 * getCode
	 * @params int $code
	 */
	public function setCode($code){
		http_response_code($code);
		return $this;
	}

	/**
	 * url
	 * @param string $urls
	 */
	 public function url($urls=null){

		if(is_string($urls)){

			if($urls[0]=="/"){
				return $urls;
			}
			else if($urls[0]=="@"){
				if(!RequestRouting::$_params["phpSelf"]){
					return "/";
				}
				return RequestRouting::$_params["phpSelf"];
			}
			else{
				return RequestRouting::$_params["phpSelf"]."/".$urls;
			}

		}
		else{

			if(!$urls){
				return RequestRouting::$_params["path"];
			}

			$url="";
			if(!empty($urls["controller"])){
				$url.=$urls["controller"]."/";
			}
			else{
				$url.=RequestRouting::$_params["controller"]."/";
			}

			if(!empty($urls["action"])){
				if($urls["action"]!="index"){
					$url.=$urls["action"]."/";
				}
			}

			if(!empty($urls["pass"])){
				if(!is_array($urls["pass"])){
					$urls["pass"]=[$urls["pass"]];
				}
				foreach($urls["pass"] as $p_){
					$url.=$p_."/";
				}
			}

			if(!empty($urls["query"])){
				if(!is_array($urls["query"])){
					$urls["query"]=[$urls["query"]];
				}
				$query="?";
				$ind=0;
				foreach($urls["query"] as $field=>$value){
					if($ind){
						$query.="&";
					}
					$query.=$field."=".$value;
					$ind++;
				}

				$url.=$query;
			}

			return RequestRouting::$_params["path"].$url;

		}

	}

	/**
	 * homeUrl
	 */
	public function homeUrl(){
		return $this->url("@");
	}

	/**
	 * redirect
	 * @param string $urls = null
	 */
	public function redirect($urls=null){
		$url=$this->url($urls);
		header('location: '.$url);
		exit;
	}

	/**
	 * setData
	 * @param string $name
	 * @param string $value
	 */
	public function setData($name,$value){
		ResponseData::set($name,$value);
		return $this;
	}

	/**
	 * setDatas
	 * @param string $values
	 */
	public function setDatas($values){
		foreach($values as $colum=>$value){
			ResponseData::set($colum,$value);
		}
		return $this;
	}

	/**
	 * loadTemplate
	 * @param string $templateName
	 * @param boolean $outputBufferd
	 */
	public function loadTemplate($templateName=null,$outputBufferd=false){
		
		$TemplatePath=MK2_PATH_RENDERING_TEMPLATE."/".$templateName.MK2_VIEW_EXTENSION;
		if(!file_exists($TemplatePath)){
			throw new Exception("Template file not found. \n Path : '".$TemplatePath."'\n");
		}

		$templateEngine=Config::get("config.templateEngine");

		if($templateEngine===self::TEMPLATEENGINE_SMARTY){
			return $this->requireEngineSmarty($TemplatePath,$outputBufferd);
		}
		else if($templateEngine===self::TEMPLATEENGINE_TWIG){
			return $this->requireEngineTwig($TemplatePath,$outputBufferd);
		}

		return $this->require($TemplatePath,$outputBufferd);
	}

	/**
	 * loadView
	 * @param string $viewName
	 * @param boolean $outputBufferd
	 */
	public function loadView($viewName=null,$outputBufferd=false){

		if(!$viewName){
			$params=RequestRouting::$_params;
			if(!empty($this->context->view)){
				$viewName=ucfirst($params["controller"])."/".$this->context->view;

				// module connected viewname....
				if(!empty($params["module"])){
					$controllerName = str_replace("Modules\\".$params["module"]."\App\Controller\\","",$params["controller"]);
					$viewName = MK2_ROOT."/modules/".$params["module"]."/rendering/View".$controllerName."/".$params["action"];
					$direct =true;
				}
			}
			else{
				$viewName=ucfirst($params["controller"])."/".$params["action"];
			}
		}

		$viewName=str_replace("\\","/",$viewName);

		$viewPath=MK2_PATH_RENDERING_VIEW."/".$viewName.MK2_VIEW_EXTENSION;

		// module connected viewpath....
		if(!empty($direct)){
			$viewPath=$viewName.MK2_VIEW_EXTENSION;
		}

		if(!file_exists($viewPath)){
			echo "[ViewError] View file not found. \n Path : '".$viewPath."'\n";
			return;
		}

		$templateEngine=Config::get("config.templateEngine");

		if($templateEngine===self::TEMPLATEENGINE_SMARTY){
			return $this->requireEngineSmarty($viewPath,$outputBufferd);
		}
		else if($templateEngine===self::TEMPLATEENGINE_TWIG){
			return  $this->requireEngineTwig($viewPath,$outputBufferd);
		}

		return $this->require($viewPath,$outputBufferd);
	}

	/**
	 * loadViewPart
	 * @param string $viewPartName
	 * @param boolean $outputBufferd
	 */
	public function loadViewPart($viewPartName,$outputBufferd=false){

		$viewPartPath=MK2_PATH_RENDERING_VIEWPART."/".$viewPartName.MK2_VIEW_EXTENSION;
		
		$params=RequestRouting::$_params;

		// module connected viewPartPath....
		if(!empty($params["module"])){
			$pathOnMOdule = MK2_ROOT."/modules/".$params["module"]."/rendering/ViewPart/".$viewPartName.MK2_VIEW_EXTENSION;
			if(file_exists($pathOnMOdule)){
				$viewPartPath = $pathOnMOdule;
			}
		}

		$viewPartPath=str_replace("\\","/",$viewPartPath);

		if(!file_exists($viewPartPath)){
			echo "[ViewPartError] ViewPart file not found. \n Path : '".$viewPartPath."'\n";
			return;
		}
		
		$templateEngine=Config::get("config.templateEngine");

		if($templateEngine===self::TEMPLATEENGINE_SMARTY){
			return $this->requireEngineSmarty($viewPartPath,$outputBufferd);
		}
		else if($templateEngine===self::TEMPLATEENGINE_TWIG){
			return  $this->requireEngineTwig($viewPartPath,$outputBufferd);
		}

		return $this->require($viewPartPath,$outputBufferd);
	}

	/**
	 * require
	 * @param string $path
	 * @param boolean $outputBufferd
	 */
	private function require($path,$outputBufferd){

		if($outputBufferd){
			ob_start();
		}

		$this->context->require($path);

		if($outputBufferd){
			$contents=ob_get_contents();
			ob_end_clean();
	
			return $contents;	
		}

	}

}