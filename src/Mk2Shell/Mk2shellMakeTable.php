<?php

namespace Mk2\Libraries;

class Mk2shellMakeTable extends Command{

    public function __construct($argv){

        $input=[];

        $this->green("Create a new Table file.");
        $this->text("");

        if(!empty($argv[0])){
            $input["name"]=$argv[0];
        }
        else{
            $buff="";
            for(;;){
                $buff=$this->input("[Q] Enter the name of the Table to create.");
                if($buff){
                    break;
                }
                $this->text("[ERROR] The Table name has not been entered.");
            }
            $input["name"]=$buff;
        }
        
        $input["extends"]=$this->input("[Q] If there is an inheritance source Table name, enter it.");

        $juge=strtolower($this->input("[Q] Do you want to set options??[Y/n]"));
        if($juge!="y"){ $juge="n"; }

        $input["option"]=[];

        if($juge=="y"){

        }

        $this->text("===========================================================================");

        $this->text("");
        $juge=strtolower($this->input("[Create a Table file based on the entered information. Is it OK?[Y/n]"));
        
        if($juge=="n"){
            $this->text("");
            $this->text("");
            $this->text("Table creation has been canceled,");
            return;
        }

        $this->_make($input);

        $this->text("");
        $this->text("");
        $this->green("Table creation completed.");
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
        $str.=" * ".ucfirst($data["name"]). "Table \n";
        $str.=" * \n";
        $str.=" * created : ".date("Y/m/d")."\n";
        $str.=" * \n";
        $str.=" * ============================================\n";
        $str.=" */ \n";
        $str.="namespace App\Table;\n";
        $str.="\n";
        if(!$data["extends"]){
            $str.="use Mk2\Libraries\Table;\n";
            $data["extends"]="";
            $str.="\n";
        }
        $str.="class ".ucfirst($data["name"])."Table extends ".ucfirst($data["extends"])."Table\n";
        $str.="{\n";
        
        /*
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
                $str.="\t *\n";
                $str.="\tpublic function ".$a_["name"]."(".$argStr.")\n";
                $str.="\t{\n";
                $str.="\t\n";
                $str.="\t}\n";
            }
        }
        */

        $str.="\n";
        $str.="}";

        $fileName=MK2_ROOT."/".MK2_DEFNS_TABLE."/".ucfirst($data["name"])."Rable.php";
        $fileName=str_replace("\\","/",$fileName);
        file_put_contents($fileName,$str);
    }
}