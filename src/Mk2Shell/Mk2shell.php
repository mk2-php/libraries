<?php

namespace Mk2\Libraries;

class Mk2shell{

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



        }

    }

}