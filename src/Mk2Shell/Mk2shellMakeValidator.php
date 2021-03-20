<?php

/**
 * ===================================================
 * 
 * [Mark2] - Mk2shellMakeValidator
 * 
 * Object class for initial operation.
 * 
 * URL : https://www/mk2-php.com/
 * Copylight : Nakajima-Satoru 2021.
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

class Mk2shellMakeValidator extends Command{

    /**
     * __construct
     * @param $argv
     */
    public function __construct($argv){

        $input=[];

        $this->green("Create a new Validator file.");
        $this->text("");

        if(!empty($argv[0])){
            $input["name"]=$argv[0];
        }
        else{
            $buff="";
            for(;;){
                $buff=$this->input("\t- Enter the name of the Validator to create.");
                if($buff){
                    break;
                }
                $this->red("\t  [ERROR] The Validator name has not been entered.");
            }
            $input["name"]=$buff;
        }

        $input["extends"]=$this->input("\t- If there is an inheritance source Validator name, enter it.");

        $juge=strtolower($this->input("\t- Do you want to set a default validation rule?[Y/n]"));
        if($juge!="y"){ $juge="n"; }

        if($juge=="y"){

            $input["rules"]=[];

            for(;;){

                $buff=[];

                for(;;){
                    $field=$this->input("\t\t- Enter the field name.");
                    if($field){
                        $buff["field"]=$field;
                        break;
                    }
                    $this->red("\t\t  [ERROR] No field name entered.");
                }
    
                for(;;){
                    $rules=$this->input("\t\t- Enter the rules you want to apply in the fields above.\n\t\t  (\",\"Specify the delimiter setting value)");
                    if($field){
                        $buff["rule"]=$rules;
                        break;
                    }
                    $this->red("\t\t  [ERROR] No applicable rule entered.");
                }

                $buff["message"]=$this->input("\t\t- Enter any error messages for the rule.");
                
                $juge=strtolower($this->input("\t\t- Do you want to continue entering validation rules?[y/n]"));
                if($juge!="y"){ $juge="n"; }

                $input["rules"][]=$buff;
                $this->text("");

                if($juge=="n"){
                    break;
                }
            }
        }


        $juge=strtolower($this->input("\t- Do you want to set options?[Y/n]"));
        if($juge!="y"){ $juge="n"; }

        $buff=[];

        if($juge=="y"){

            $buff["comment"]=$this->input("\t\t- Enter any comment text.");

            $input["option"]=$buff;
        }

        $this->text("\t===========================================================================");

        $this->text("");
        $juge=strtolower($this->input("\t- Create a Validator file based on the entered information. Is it OK?[Y/n]"));
        
        if($juge=="n"){
            $this->text("");
            $this->text("");
            $this->text("Validator creation has been canceled,");
            return;
        }

        $juge=$this->_make($input);

        if(!$juge){
            $this->text("");
            $this->text("");
            $this->text("Validator creation has been canceled,");
            return;
        }

        $this->text("");
        $this->text("");
        $this->green("Validator creation completed.");
        
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
        $str.=" * ".ucfirst($data["name"]). "Validator \n";
        $str.=" * \n";
        if(!empty($data["option"]["comment"])){
            $str.=" * ".$data["option"]["comment"]."\n";
        }
        $str.=" * created : ".date("Y/m/d")."\n";
        $str.=" * \n";
        $str.=" * ============================================\n";
        $str.=" */ \n";
        $str.="namespace App\Validator;\n";
        $str.="\n";
        if(!$data["extends"]){
            $str.="use Mk2\Libraries\Validator;\n";
            $data["extends"]="";
            $str.="\n";
        }
        $str.="class ".ucfirst($data["name"])."Validator extends ".ucfirst($data["extends"])."Validator\n";
        $str.="{\n";

        if(!empty($data["rules"])){

            $rules=[];
            foreach($data["rules"] as $r_){
                if(empty($rules[$r_["field"]])){
                    $rules[$r_["field"]]=[];
                }
                
                $rules[$r_["field"]][]=[
                    "rule"=>$r_["rule"],
                    "message"=>$r_["message"],
                ];
            }

            $str.="\n";
            $str.="\tpublic \$rule = [\n";

            foreach($rules as $field=>$r_){

                $str.="\t\t\"".$field."\" => [\n";

                foreach($r_ as $rr_){

                    $str.="\t\t\t[\n";

                    $rule2=explode(",",$rr_["rule"]);
                    $ruleStr="";
                    foreach($rule2 as $ind=>$rrr_){
                        if($ind){
                            $ruleStr.=",";
                        }
                        if(is_int($rrr_)){
                            $ruleStr.=$rrr_;
                        }
                        else{
                            $ruleStr.="\"".$rrr_."\"";
                        }
                    }

                    $str.="\t\t\t\t\"rule\" => [".$ruleStr."],\n";
                    $str.="\t\t\t\t\"message\" => \"".$rr_["message"]."\",\n";
                    $str.="\t\t\t],\n";
                }

                $str.="\t\t],\n";
            }

            $str.="\t];\n";
        }

        $str.="\n";
        $str.="}";

        $fileName=MK2_ROOT."/".MK2_DEFNS_VALIDATOR."/".ucfirst($data["name"])."Validator.php";
        $fileName=str_replace("\\","/",$fileName);

        if(file_exists($fileName)){
            $juge=strtolower($this->input("\tThe same Validator already exists, do you want to overwrite it as it is?[y/n]"));
            if($juge!="y"){ $juge="n"; }

            if($juge=="n"){
                return false;
            }
        }

        file_put_contents($fileName,$str);

        return true;

        print_r($data);




    }

}