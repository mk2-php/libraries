<?php

/**
 * ===================================================
 * 
 * [Mark2] - Render
 * 
 * Object class for initial operation.
 * 
 * URL : https://www/mk2-php.com/
 * Copylight : Nakajima-Satoru 2021.
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class Render extends CoreBlock{

	/**
	 * render
	 * @param &$context
	 */
	public function render(&$context){

		$this->view=$context->view;
		$this->Template=$context->Template;
		
		if($context->Template){
			if(Config::exists("config.coreBlock.useResponse")){
				$this->Response->loadTemplate($context->Template);
			}
		}
		else{
			if(Config::exists("config.coreBlock.useResponse")){
				if($this->Request->params("action")!=$context->view){
					$this->Response->loadView($context->view);
				}
				else{
					$this->Response->loadView();
				}
			}
		}

	}
}