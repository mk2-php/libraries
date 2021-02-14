<?php

namespace Mk2\Libraries;

class Mk2shellMakeController extends Command{

    public function __construct($argv){

        $input=[];

        $this->green("Create a new Controller file.");
        $this->text("");

        if(!empty($argv[0])){
            $input["name"]=$argv[0];
        }
        else{
            $buff="";
            for(;;){
                $buff=$this->input("[Q] Enter the name of the controller to create.");
                if($buff){
                    break;
                }
                $this->text("[ERROR] The Controller name has not been entered.");
            }
            $input["name"]=$buff;
        }

        $input["extends"]=$this->input("[Q] If there is an inheritance source Controller name, enter it.");

        $juge=strtolower($this->input("[Q] Do you want to add an action?[Y/n]"));
        if($juge!="y"){ $juge="n"; }

        if($juge=="y"){
            $input["actions"]=[];

            $looped=false;
            for(;;){
    
                $buff=[];

                for(;;){
                    $name=$this->input(" L [Q] Please enter the action name.");
                    if($name){
                        $buff["name"]=$name;
                        break;
                    }
                    $this->text("[ERROR] The action name has not been entered.");
                }

                $buff["aregment"]=$name=$this->input(' L [Q] If there is an argument name, enter it with.("," Separation)');
                
                $juge=strtolower($this->input(" L [Q] Do you want to continue adding actions?[Y/n]"));
                if($juge!="y"){ $juge="n"; }
    
                $input["actions"][]=$buff;

                if($juge=="n"){
                    break;
                }
            
            }
        }

        $this->text("===========================================================================");

        $this->text("");
        $juge=strtolower($this->input("[Create a Controller file based on the entered information. Is it OK?[Y/n]"));
        
        if($juge=="n"){
            $this->text("");
            $this->text("");
            $this->text("Controller creation has been canceled,");
            return;
        }

        $this->_make($input);

        $this->text("");
        $this->text("");
        $this->green("Controller creation completed.");
        
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
        $str.=" * ".ucfirst($data["name"]). "Controller \n";
        $str.=" * \n";
        $str.=" * created : ".date("Y/m/d")."\n";
        $str.=" * \n";
        $str.=" * ============================================\n";
        $str.=" */ \n";
        $str.="namespace App\Controller;\n";
        $str.="\n";
        if(!$data["extends"]){
            $str.="use Mk2\Libraries\Controller;";
            $data["extends"]="Controller";
            $str.="\n";
        }
        $str.="class ".ucfirst($data["name"])."Controller extends ".ucfirst($data["extends"])."Controller\n";
        $str.="{\n";
        

        if($data["actions"]){
            foreach($data["actions"] as $a_){
                
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

        $fileName=MK2_ROOT."/".MK2_DEFNS_CONTROLLER."/".ucfirst($data["name"])."Controller.php";
        $fileName=str_replace("\\","/",$fileName);
        file_put_contents($fileName,$str);

    }
}