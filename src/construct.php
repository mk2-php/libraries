<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * construct
 * 
 * For constant initialization..
 * 
 * URL : https://www.mk2-php.com/
 * 
 * Copylight : Nakajima-Satoru 2021.
 *           : Sakaguchiya Co. Ltd. (https://www.teastalk.jp/)
 * 
 * ===================================================
 */

defineCheck('MK2_PATH_CONFIG',MK2_ROOT.'/config');

defineCheck('MK2_PATH_APP',MK2_ROOT.'/app');

defineCheck('MK2_PATH_RENDERING',MK2_ROOT.'/rendering');

defineCheck('MK2_PATH_RENDERING_RENDER',MK2_PATH_RENDERING."/Render");

defineCheck('MK2_PATH_RENDERING_VIEW',MK2_PATH_RENDERING."/View");

defineCheck('MK2_PATH_RENDERING_TEMPLATE',MK2_PATH_RENDERING."/Template");

defineCheck('MK2_PATH_RENDERING_VIEWPART',MK2_PATH_RENDERING."/ViewPart");

defineCheck('MK2_DEFNS','app');

defineCheck('MK2_DEFNS_CONTROLLER','app\Controller');

defineCheck('MK2_DEFNS_BACKPACK','app\Backpack');

defineCheck('MK2_DEFNS_UI','app\UI');

defineCheck('MK2_DEFNS_MODEL','app\Model');

defineCheck('MK2_DEFNS_TABLE','app\Table');

defineCheck('MK2_DEFNS_VALIDATOR','app\Validator');

defineCheck('MK2_DEFNS_RENDER','app\Render');

defineCheck('MK2_DEFNS_SHELL','app\Shell');

defineCheck('MK2_DEFNS_MIDDLEWARE','app\Middleware');

defineCheck('MK2_DEFNS_ELCLASS','app\ElClass');

defineCheck("MK2_DEFNS_UPDATER","app/Updater");

defineCheck('MK2_PATH_TEMPORARY',MK2_ROOT.'/temporaries');

defineCheck('MK2_PATH_LOG',MK2_ROOT.'/logs');

defineCheck('MK2_PATH_PUBLIC',MK2_ROOT.'/public');

defineCheck('MK2_VIEW_EXTENSION',".view");

defineCheck('MK2_PATH_LEVEL',0);

function defineCheck($name,$value){

	if(!defined($name)){
		define($name,$value);
	}

}