<?php

/**
 * ===================================================
 * 
 * [Mark2] - Mk2shellMakeRender
 * 
 * Object class for initial operation.
 * 
 * URL : https://www/mk2-php.com/
 * Copylight : Nakajima-Satoru 2021.
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class Mk2shellMakeRender extends Command{

    /**
     * __construct
     * @param $argv
     */
    public function __construct($argv){

        $input=[];

        $this->green("Create a new Render file.");
        $this->text("");

        if(!empty($argv[0])){
            $input["name"]=$argv[0];
        }
        else{
            $buff="";
            for(;;){
                $buff=$this->input("\t- Enter the name of the Render to create.");
                if($buff){
                    break;
                }
                $this->red("\t  [ERROR] The Render name has not been entered.");
            }
            $input["name"]=$buff;
        }

        $input["extends"]=$this->input("\t- If there is an inheritance source Render name, enter it.");

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

        $input["comment"]=$this->input("\t\t- Enter any comment text.");


        $this->text("\t===========================================================================");

        $this->text("");
        $juge=strtolower($this->input("\t- Create a Render file based on the entered information. Is it OK?[Y/n]"));
        
        if($juge=="n"){
            $this->text("");
            $this->text("");
            $this->text("Render creation has been canceled,");
            return;
        }

        $juge=$this->_make($input);

        if(!$juge){
            $this->text("");
            $this->text("");
            $this->text("Render creation has been canceled,");
            return;
        }

        $this->text("");
        $this->text("");
        $this->green("Render creation completed.");

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
        $str.=" * ".ucfirst($data["name"]). "Render \n";
        $str.=" * \n";
        if(!empty($data["option"]["comment"])){
            $str.=" * ".$data["option"]["comment"]."\n";
        }
        $str.=" * created : ".date("Y/m/d")."\n";
        $str.=" * \n";
        $str.=" * ============================================\n";
        $str.=" */ \n";
        $str.="namespace App\Render;\n";
        $str.="\n";
        if(!$data["extends"]){
            $str.="use Mk2\Libraries\Render;\n";
            $data["extends"]="";
            $str.="\n";
        }
        $str.="class ".ucfirst($data["name"])."Render extends ".ucfirst($data["extends"])."Render\n";
        $str.="{\n";
        
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

        $fileName=MK2_ROOT."/".MK2_DEFNS_RENDER."/".ucfirst($data["name"])."Render.php";
        $fileName=str_replace("\\","/",$fileName);

        if(file_exists($fileName)){
            $juge=strtolower($this->input("\tThe same Render already exists, do you want to overwrite it as it is?[y/n]"));
            if($juge!="y"){ $juge="n"; }

            if($juge=="n"){
                return false;
            }
        }

        file_put_contents($fileName,$str);

        return true;

    }


}
