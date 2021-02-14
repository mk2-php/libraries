<?php

namespace Mk2\Libraries;

class Mk2shellMakeModel extends Command{

    public function __construct($argv){

        $input=[];

        $this->green("Create a new Model file.");
        $this->text("");

        if(!empty($argv[0])){
            $input["name"]=$argv[0];
        }
        else{
            $buff="";
            for(;;){
                $buff=$this->input("[Q] Enter the name of the Model to create.");
                if($buff){
                    break;
                }
                $this->text("[ERROR] The Model name has not been entered.");
            }
            $input["name"]=$buff;
        }
        
        $input["extends"]=$this->input("[Q] If there is an inheritance source Model name, enter it.");

        
        $juge=strtolower($this->input("[Q] Do you want to add an public method?[Y/n]"));
        if($juge!="y"){ $juge="n"; }

        if($juge=="y"){
            $input["methods"]=[];

            $looped=false;
            for(;;){
    
                $buff=[];

                for(;;){
                    $name=$this->input(" L [Q] Please enter the method name.");
                    if($name){
                        $buff["name"]=$name;
                        break;
                    }
                    $this->text("[ERROR] The method name has not been entered.");
                }

                $buff["aregment"]=$name=$this->input(' L [Q] If there is an argument name, enter it with.("," Separation)');
                
                $juge=strtolower($this->input(" L [Q] Do you want to continue adding methods?[Y/n]"));
                if($juge!="y"){ $juge="n"; }
    
                $input["methods"][]=$buff;

                if($juge=="n"){
                    break;
                }
            
            }
        }

        $this->text("===========================================================================");

        $this->text("");
        $juge=strtolower($this->input("[Create a Model file based on the entered information. Is it OK?[Y/n]"));
        
        if($juge=="n"){
            $this->text("");
            $this->text("");
            $this->text("Model creation has been canceled,");
            return;
        }

        $this->_make($input);

        $this->text("");
        $this->text("");
        $this->green("Model creation completed.");

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
        $str.=" * ".ucfirst($data["name"]). "Model \n";
        $str.=" * \n";
        $str.=" * created : ".date("Y/m/d")."\n";
        $str.=" * \n";
        $str.=" * ============================================\n";
        $str.=" */ \n";
        $str.="namespace App\Model;\n";
        $str.="\n";
        if(!$data["extends"]){
            $str.="use Mk2\Libraries\Model;\n";
            $data["extends"]="Model";
            $str.="\n";
        }
        $str.="class ".ucfirst($data["name"])."Model extends ".ucfirst($data["extends"])."Model\n";
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

        $fileName=MK2_ROOT."/".MK2_DEFNS_MODEL."/".ucfirst($data["name"])."Model.php";
        $fileName=str_replace("\\","/",$fileName);
        file_put_contents($fileName,$str);

    }
}