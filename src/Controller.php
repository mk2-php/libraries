<?php

namespace Mk2\Libraries;

class Controller extends CoreBlock{

	public $Template=null;
	public $autoRender=false;

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
}