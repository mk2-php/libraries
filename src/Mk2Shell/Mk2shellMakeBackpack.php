<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * Mk2shellMakeBackpack
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

class Mk2shellMakeBackpack extends Command{

    /**
     * __consturct
     * @param $argv
     */
    public function __construct($argv){

        $input=[];

        $this->green("Create a new Backpack file.");
        $this->text("");

        if(!empty($argv[0])){
            $input["name"]=$argv[0];
        }
        else{
            $buff="";
            for(;;){
                $buff=$this->input("\t- Enter the name of the Backpack to create.");
                if($buff){
                    break;
                }
                $this->red("\t  [ERROR] The Backpack name has not been entered.");
            }
            $input["name"]=$buff;
        }

        $input["extends"]=$this->input("\t- If there is an inheritance source Backpack name, enter it.");

        
        $juge=strtolower($this->input("\t- Do you want to add an public method?[Y/n]"));
        if($juge!="y"){ $juge="n"; }

        $input["methods"]=[];
        if($juge=="y"){

            $looped=false;
            for(;;){
    
                $buff=[];

                for(;;){
                    $name=$this->input("\t\t- Please enter the method name.");
                    if($name){
                        $buff["name"]=$name;
                        break;
                    }
                    $this->red("\t\t  [ERROR] The method name has not been entered.");
                }

                $buff["aregment"]=$name=$this->input("\t\t- If there is an argument name, enter it with.(\",\" Separation)");
                
                $juge=strtolower($this->input("\t\t- Do you want to continue adding methods?[Y/n]"));
                if($juge!="y"){ $juge="n"; }
    
                $input["methods"][]=$buff;

                if($juge=="n"){
                    break;
                }
            
            }
        }

        $juge=strtolower($this->input("\t- Do you want to set options?[Y/n]"));
        if($juge!="y"){ $juge="n"; }

        $buff=[];

        if($juge=="y"){
            
            $juge=$this->input("\t\t- Do you want to set up a handleBefore?[y/n]");
            if($juge!="y"){ $juge="n"; }
            $buff["onHandleBefore"]=$juge;

            $buff["loadModel"]=$this->input("\t\t- If there is a model to be used in common, please enter it.(\",\" Separation).");
            $buff["loadBackpack"]=$this->input("\t\t- If there is a Backpack to be used in common, please enter it.(\",\" Separation).");
            $buff["loadUI"]=$this->input("\t\t- If there is a UI to be used in common, please enter it.(\",\" Separation).");

            if(
                $buff["loadModel"] || 
                $buff["loadBackpack"] || 
                $buff["loadUI"]
            ){
                $this->yellow("\t\t: Since one of Model, Backpack, UI is specified, handleBefore is installed.");
                $buff["onHandleBefore"]="y";
            }

            $buff["comment"]=$this->input("\t\t- Enter any comment text.");

        }

        $input["option"]=$buff;

        $this->text("\t===========================================================================");


        $this->text("");
        $juge=strtolower($this->input("\t- Create a Backpack file based on the entered information. Is it OK?[Y/n]"));
        
        if($juge=="n"){
            $this->text("");
            $this->text("");
            $this->text("Backpack creation has been canceled,");
            return;
        }

        $juge=$this->_make($input);

        if(!$juge){
            $this->text("");
            $this->text("");
            $this->text("Backpack creation has been canceled,");
            return;
        }

        $this->text("");
        $this->text("");
        $this->green("Backpack creation completed.");
        
    }
    
    /**
     * _make
     * @param $data
     */
    private function _make($data){


        $str="";

        $str.="<?php \n";
        $str.="\n";
        $str.="/** \n";
        $str.=" * ============================================\n";
        $str.=" * \n";
        $str.=" * PHP Fraemwork - Mark2 \n";
        $str.=" * ".ucfirst($data["name"]). "Backpack \n";
        $str.=" * \n";
        if(!empty($data["option"]["comment"])){
            $str.=" * ".$data["option"]["comment"]."\n";
        }
        $str.=" * created : ".date("Y/m/d")."\n";
        $str.=" * \n";
        $str.=" * ============================================\n";
        $str.=" */ \n";
        $str.="namespace App\Backpack;\n";
        $str.="\n";
        if(!$data["extends"]){
            $str.="use Mk2\Libraries\Backpack;\n";
            $data["extends"]="";
            $str.="\n";
        }
        $str.="class ".ucfirst($data["name"])."Backpack extends ".ucfirst($data["extends"])."Backpack\n";
        $str.="{\n";
        
        if($data["option"]){

            $str.="\n";
            $opt=$data["option"];

            if($opt["onHandleBefore"]=="y"){
                $str.="\t/**\n";
                $str.="\t * handleBefore\n";
                $str.="\t */\n";
                $str.="\tpublic function handleBefore()\n";
                $str.="\t{\n";
                $str.="\n";

                if($opt["loadModel"]){
                    $models=explode(",",$opt["loadModel"]);

                    $str.="\t\t// load Model\n";
                    $str.="\t\t\$this->Model->load([\n";
                    foreach($models as $m_){
                        $str.="\t\t\t\"".ucfirst($m_)."\",\n";
                    }
                    $str.="\t\t]);\n\n";
                }

                if($opt["loadBackpack"]){
                    $backpacks=explode(",",$opt["loadBackpack"]);
                    $str.="\t\t// load Backpack\n";
                    $str.="\t\t\$this->Backpack->load([\n";
                    foreach($backpacks as $b_){
                        $str.="\t\t\t\"".ucfirst($b_)."\",\n";
                    }
                    $str.="\t\t]);\n\n";
                }

                if($opt["loadUI"]){
                    $uis=explode(",",$opt["loadUI"]);
                    $str.="\t\t// load UI\n";
                    $str.="\t\t\$this->UI->load([\n";
                    foreach($uis as $u_){
                        $str.="\t\t\t\"".ucfirst($u_)."\",\n";
                    }
                    $str.="\t\t]);\n\n";
                }

                $str.="\t}\n\n";
            }

        }

        if($data["methods"]){
            foreach($data["methods"] as $a_){
                
                $argStr="";
                $argComment="";
                if($a_["aregment"]){
                    $aregments=explode(",",$a_["aregment"]);
                    foreach($aregments as $ind=>$ag_){
                        if($ind>0){
                            $argStr.=", ";
                        }
                        $argStr.="$".$ag_;
                        $argComment.="\t * @param ".$ag_."\n";
                    }
                }
                

                $str.="\n";
                $str.="\t/**\n";
                $str.="\t * ".$a_["name"]."\n";
                $str.=$argComment;
                $str.="\t */\n";
                $str.="\tpublic function ".$a_["name"]."(".$argStr.")\n";
                $str.="\t{\n";
                $str.="\t\n";
                $str.="\t}\n";
            }
        }

        $str.="\n";
        $str.="}";

        $fileName=MK2_ROOT."/".MK2_DEFNS_BACKPACK."/".ucfirst($data["name"])."Backpack.php";
        $fileName=str_replace("\\","/",$fileName);

        if(file_exists($fileName)){
            $juge=strtolower($this->input("\tThe same Backpack already exists, do you want to overwrite it as it is?[y/n]"));
            if($juge!="y"){ $juge="n"; }

            if($juge=="n"){
                return false;
            }
        }

        file_put_contents($fileName,$str);

        return true;

    }
}