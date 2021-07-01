<?php

/**
 * ===================================================
 * 
 * PHP Framework "Mk2"
 * 
 * Mk2shellMakeTable
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

class Mk2shellMakeTable extends Command{

    /**
     * __construct
     * @param $argv
     */
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
                $buff=$this->input("\t- Enter the name of the Table to create.");
                if($buff){
                    break;
                }
                $this->red("\t  [ERROR] The Table name has not been entered.");
            }
            $input["name"]=$buff;
        }

        $input["extends"]=$this->input("\t- If there is an inheritance source Table name, enter it.");

        $juge=strtolower($this->input("\t- Do you want to set options?[Y/n]"));
        if($juge!="y"){ $juge="n"; }

        $buff=[];

        if($juge=="y"){

            $buff["dbName"]=$this->input("\t\t- If you want to change the database connection destination name, enter the change s meeting.");

            $buff["table"]=$this->input("\t\t- Enter if you want to set the table name manually.");
            
            $juge=$this->input("\t\t- Do you want to set a time stamp?[y/n]");
            if($juge!="y"){ $juge="n"; }
            
            if($juge=="y"){

                $buff["timeStamp"]=[];

                for(;;){
                    $juge=$this->input("\t\t\t- Enter the column name of the record creation date.");

                    if($juge){
                        $buff["timeStamp"]["created"]=$juge;
                        break;
                    }
                    $this->red("\t\t\t- The column name of the record creation date has not been entered.");
                }

                for(;;){
                    $juge=$this->input("\t\t\t- Enter the column name of the record update date.");

                    if($juge){
                        $buff["timeStamp"]["updated"]=$juge;
                        break;
                    }
                    $this->red("\t\t\t- The column name of the record update date has not been entered.");
                }

            }

            $juge=$this->input("\t\t- Do you want to set up a logical delete?[y/n]");
            if($juge!="y"){ $juge="n"; }

            if($juge=="y"){

                for(;;){
                    $juge=$this->input("\t\t\t- Enter the column name for ethical deletion.");

                    if($juge){
                        $buff["logicalDelete"]=$juge;
                        break;
                    }
                    $this->red("\t\t\t- The column name for deleting ethics has not been entered.");
                }

            }


            $buff["comment"]=$this->input("\t\t- Enter any comment text.");

        }

        $input["option"]=$buff;

        $this->text("\t===========================================================================");

        $this->text("");
        $juge=strtolower($this->input("\t- Create a Table file based on the entered information. Is it OK?[Y/n]"));
        
        if($juge=="n"){
            $this->text("");
            $this->text("");
            $this->text("Table creation has been canceled,");
            return;
        }

        $juge=$this->_make($input);

        if(!$juge){
            $this->text("");
            $this->text("");
            $this->text("Table creation has been canceled,");
            return;
        }

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
        if(!empty($data["option"]["comment"])){
            $str.=" * ".$data["option"]["comment"]."\n";
        }
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
        
        if($data["option"]){

            $str.="\n";
            $opt=$data["option"];


            if($opt["dbName"]){

                $str.="\tpublic \$dbName = '".$opt["dbName"]."';\n";
                $str.="\n";
            }

            if($opt["table"]){

                $str.="\tpublic \$table = '".$opt["table"]."';\n";
                $str.="\n";
            }


            if(!empty($opt["timeStamp"])){

                $str.="\t/**\n";
                $str.="\t * timeStamp\n";
                $str.="\t*/\n";
                $str.="\tpublic \$timeStamp = [\n";
                $str.="\t\t'created' => [\n";
                $str.="\t\t\t'field' => '".$opt["timeStamp"]["created"]."',\n";
                $str.="\t\t],\n";
                $str.="\t\t'updated' => [\n";
                $str.="\t\t\t'field' => '".$opt["timeStamp"]["updated"]."',\n";
                $str.="\t\t],\n";
                $str.="\t];\n";
                $str.="\n";

            }

            if(!empty($opt["logicalDelete"])){
                $str.="\t/**\n";
                $str.="\t * logicalDelete\n";
                $str.="\t*/\n";
                $str.="\tpublic \$logicalDelete = [\n";
                $str.="\t\t'field' => '".$opt["logicalDelete"]."',\n";
                $str.="\t];\n";
                $str.="\n";

            }


        }

        $str.="\n";
        $str.="}";

        $fileName=MK2_ROOT."/".MK2_DEFNS_TABLE."/".ucfirst($data["name"])."Table.php";
        $fileName=str_replace("\\","/",$fileName);

        if(file_exists($fileName)){
            $juge=strtolower($this->input("\tThe same Table already exists, do you want to overwrite it as it is?[y/n]"));
            if($juge!="y"){ $juge="n"; }

            if($juge=="n"){
                return false;
            }
        }

        file_put_contents($fileName,$str);

        return true;
    }
}