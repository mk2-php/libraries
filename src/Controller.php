<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * Controller
 * 
 * Basic Controller class.
 * 
 * URL : https://www.mk2-php.com/
 * 
 * Copylight : Nakajima-Satoru 2021.
 *           : Sakaguchiya Co. Ltd. (https://www.teastalk.jp/)
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class Controller extends CoreBlock{

	public $Template=null;
	public $autoRender=false;

	/**
	 * _rendering
	 */
	public function _rendering(){

		if(empty($this->view)){
			$this->view=RequestRouting::$_params["action"];
		}

		$useClass=Config::get("config.useClass");

		if(in_array("Render",$useClass)){

			$renderName="Render";
			$renderClassName='Mk2\Libraries\\'.$renderName;
	
			if(!empty($this->RenderName)){
				$renderName=$this->RenderName."Render";
				$renderClassName=MK2_DEFNS_RENDER."\\".$renderName;

				if(!class_exists($renderClassName)){
					$_r0 = $renderClassName;
					$renderClassName = $renderName;
					if(!class_exists($renderClassName)){
						throw new \Exception("Render Class Not existed. \"".$renderClassName."\" or \"".$_r0."\"");
					}
				}

			}
	
			$render=new $renderClassName();

			if(method_exists($render,"handleBefore")){
				$render->handleBefore();
			}

			if(!empty($this->UI)){
				$render->UI=$this->UI;
			}

			$render->render($this);

			if(method_exists($render,"handleAfter")){
				$render->handleAfter();
			}

		}
	}

	/**
	 * handleBefore
	 */
	public function handleBefore(){}

	/**
	 * handleAfter
	 * @param $output
	 */	
	public function handleAfter($output){}
	
}