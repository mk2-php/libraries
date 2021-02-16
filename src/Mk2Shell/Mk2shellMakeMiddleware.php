<?php

namespace Mk2\Libraries;

class Mk2shellMakeMiddleware extends Command{

    public function __construct($argv){

        $input=[];

        $this->green("Create a new Middleware file.");
        $this->text("");

        if(!empty($argv[0])){
            $input["name"]=$argv[0];
        }
        else{
            $buff="";
            for(;;){
                $buff=$this->input("\t- Enter the name of the controller to create.");
                if($buff){
                    break;
                }
                $this->red("\t  [ERROR] The Middleware name has not been entered.");
            }
            $input["name"]=$buff;
        }

        $input["extends"]=$this->input("\t- If there is an inheritance source Middleware name, enter it.");

        $juge=$this->input("\t- Do you want to set up a handleBefore?[y/n]");
        if($juge!="y"){ $juge="n"; }
        $input["onHandleBefore"]=$juge;

        $juge=$this->input("\t- Do you want to set up a handleAfter?[y/n]");
        if($juge!="y"){ $juge="n"; }
        $input["onHandleAfter"]=$juge;

        $input["comment"]=$this->input("\t\t- Enter any comment text.");

        $this->text("\t===========================================================================");

        $this->text("");
        $juge=strtolower($this->input("\t- Create a Middleware file based on the entered information. Is it OK?[Y/n]"));
        
        if($juge=="n"){
            $this->text("");
            $this->text("");
            $this->text("Middleware creation has been canceled,");
            return;
        }

        $juge=$this->_make($input);

        if(!$juge){
            $this->text("");
            $this->text("");
            $this->text("Middleware creation has been canceled,");
            return;
        }

        $this->text("");
        $this->text("");
        $this->green("Middleware creation completed.");
        
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
        $str.=" * ".ucfirst($data["name"]). "Middleware \n";
        $str.=" * \n";
        if(!empty($data["comment"])){
            $str.=" * ".$data["comment"]."\n";
        }
        $str.=" * created : ".date("Y/m/d")."\n";
        $str.=" * \n";
        $str.=" * ============================================\n";
        $str.=" */ \n";
        $str.="namespace App\Middleware;\n";
        $str.="\n";
        if(!$data["extends"]){
            $str.="use Mk2\Libraries\Middleware;\n";
            $data["extends"]="";
            $str.="\n";
        }
        $str.="class ".ucfirst($data["name"])."Middleware extends ".ucfirst($data["extends"])."Middleware\n";
        $str.="{\n";
        $str.="\n";

        if($data["onHandleBefore"]=="y"){
            $str.="\t/**\n";
            $str.="\t * handleBefore\n";
            $str.="\t */\n";
            $str.="\tpublic function handleBefore()\n";
            $str.="\t{\n";
            $str.="\n";
            $str.="\t}\n\n";
        }

        if($data["onHandleAfter"]=="y"){
            $str.="\t/**\n";
            $str.="\t * handleAfter\n";
            $str.="\t * @param \$input \n";                
            $str.="\t */\n";
            $str.="\tpublic function handleAfter(\$input)\n";
            $str.="\t{\n";
            $str.="\n";
            $str.="\t}\n\n";
        }

        $str.="\n";
        $str.="}";

        $fileName=MK2_ROOT."/".MK2_DEFNS_MIDDLEWARE."/".ucfirst($data["name"])."Middleware.php";
        $fileName=str_replace("\\","/",$fileName);

        if(file_exists($fileName)){
            $juge=strtolower($this->input("\tThe same Middleware already exists, do you want to overwrite it as it is?[y/n]"));
            if($juge!="y"){ $juge="n"; }

            if($juge=="n"){
                return false;
            }
        }

        file_put_contents($fileName,$str);

        return true;
    }
}