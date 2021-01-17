<?php

namespace Mk2\Libraries;

class Render extends CoreBlock{

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
				$this->Response->loadView($context->view);
			}
		}

	}
}