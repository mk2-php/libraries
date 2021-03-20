<?php

/**
 * ===================================================
 * 
 * [Mark2] - Mk2shell
 * 
 * Object class for initial operation.
 * 
 * URL : https://www/mk2-php.com/
 * Copylight : Nakajima-Satoru 2021.
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class Mk2shell{

    /**
     * __construct
     * @param $argv
     */
    public function __construct($argv){

        $main=$argv[0];

        if($main=="top"){
            require_once "Mk2shellTop.php";
            new Mk2shellTop();
        }
        else if($main=="make"){

            array_shift($argv);
            $type=$argv[0];
            array_shift($argv);

            if($type=="controller"){
                require_once "Mk2shellMakeController.php";
                new Mk2shellMakeController($argv);
            }
            else if($type=="model"){
                require_once "Mk2shellMakeModel.php";
                new Mk2shellMakeModel($argv);
            }
            else if($type=="middleware"){
                require_once "Mk2shellMakeMiddleware.php";
                new Mk2shellMakeMiddleware($argv);
            }
            else if($type=="table"){
                require_once "Mk2shellMakeTable.php";
                new Mk2shellMakeTable($argv);
            }
            else if($type=="validator"){
                require_once "Mk2shellMakeValidator.php";
                new Mk2shellMakeValidator($argv);
            }
            else if($type=="backpack"){
                require_once "Mk2shellMakeBackpack.php";
                new Mk2shellMakeBackpack($argv);
            }
            else if($type=="ui"){
                require_once "Mk2shellMakeUI.php";
                new Mk2shellMakeUI($argv);
            }
            else if($type=="render"){
                require_once "Mk2shellMakeRender.php";
                new Mk2shellMakeRender($argv);
            }
            else if($type=="shell"){
                require_once "Mk2shellMakeShell.php";
                new Mk2shellMakeShell($argv);
            }
        }
        else if($main=="add"){

            array_shift($argv);
            $type=$argv[0];
            array_shift($argv);

            if($type=="routing"){
                require_once "Mk2shellAddRouting.php";
                new Mk2shellAddRouting($argv);
            }
            else if($type=="ddatabase"){
                require_once "Mk2shellAddDatabase.php";
                new Mk2shellAddDatabase($argv);
            }

        }
        else if($main=="config"){

            array_shift($argv);

            require_once "Mk2shellConfig.php";
            new Mk2shellConfig($argv);
        }
    }
}