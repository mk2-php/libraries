<?php

namespace Mk2\Libraries;

class Mk2shell{

    public function __construct($argv){

        $main=$argv[0];

        if($main=="top"){
            require_once "shells/Mk2shellTop.php";
            new Mk2shellTop();
        }
        else if($main=="make"){

            array_shift($argv);
            $type=$argv[0];
            array_shift($argv);

            if($type=="controller"){
                require_once "shells/Mk2shellMakeController.php";
                new Mk2shellMakeController($argv);
            }
            else if($type=="model"){
                require_once "shells/Mk2shellMakeModel.php";
                new Mk2shellMakeModel($argv);
            }
            else if($type=="middleware"){
                require_once "shells/Mk2shellMakeMiddleware.php";
                new Mk2shellMakeMiddleware($argv);
            }
            else if($type=="table"){
                require_once "shells/Mk2shellMakeTable.php";
                new Mk2shellMakeTable($argv);
            }
            else if($type=="validator"){
                require_once "shells/Mk2shellMakeValidator.php";
                new Mk2shellMakeValidator($argv);
            }
            else if($type=="backpack"){
                require_once "shells/Mk2shellMakeBackpack.php";
                new Mk2shellMakeBackpack($argv);
            }



        }

    }

}