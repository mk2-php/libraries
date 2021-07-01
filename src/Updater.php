<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * Updater
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

use Mk2\Libraries\Config;

class Updater{

    private const UPD_CONFIG = MK2_ROOT."/.version";

    /**
     * update
     */
    public static function update(){

        $newVersion = Config::get("config.version");

        $updateFileList = glob(MK2_ROOT."/".MK2_DEFNS_UPDATER."/*");

        $nowVersion=null;
        if(file_exists(self::UPD_CONFIG)){
            $nowVersion=file_get_contents(self::UPD_CONFIG); 
        }

        $setVersion=null;
        foreach($updateFileList as $u_){
            $version=basename($u_);
            $version=str_replace("Update","",$version);
            $version=str_replace(".php","",$version);

            if(version_compare($newVersion,$version)==-1){
                break;
            }

            if($nowVersion){
                if(version_compare($version,$nowVersion)>0){
                    require($u_);
                    self::_loadClass($version);
                }
            }
            else{
                require($u_);
                self::_loadClass($version);
            }

            $setVersion=$version;
        }

        file_put_contents(self::UPD_CONFIG,$setVersion);

        return true;
    }

    /**
     * _loadClass
     * @param $version
     */
    private static function _loadClass($version){
        if(class_exists("\App\Updater\Update".$version)){
            $updateClassName="\App\Updater\Update".$version;

            $upd = new $updateClassName();

            if(method_exists($upd,"index")){
                $upd->index();
            }
        }
    }
}