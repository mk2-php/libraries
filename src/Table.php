<?php

/**
 * 
 *  Mk2 Table Class
 * 
 *  Copylight : Nakajima Satoru.
 * 
 */

namespace Mk2\Libraries;

use Mk2\Orm\Orm;
use \Illuminate\Database\Capsule\Manager as Capsule;

class Table extends CoreBlock{

	private const ORMTYPE_ELOQUENT="eloquent";
	private const ORMTYPE_MK2ORM="mk2orm";

	public $ormType=self::ORMTYPE_MK2ORM;

	public $dbName="default";
	public $table=null;
	public $prefix=null;

	public $eloquent=null;
	public $mk2Orm=null;

	public $surrogateKey=[
		"enable"=>true,
		"field"=>"id",
		"incremented"=>true,
	];

	public $timeStamp=[
		"create"=>[
			"field"=>"created",
			"dateFormt"=>"Y/m/d H:i:s",
		],
		"update"=>[
			"field"=>"updated",
			"dateFormt"=>"Y/m/d H:i:s",
		],
	];

	public $logicalDelete=[
		"field"=>"del_flg",
		"stampType"=>"1",
	];
/*
	public $timeStampCreateKey="created";
	public $timeStampUpdateKey="updated";

	public $logicalDeleteKey="del_flg";
	public $logicalDeleteType="date";
*/

	/**
	 * handleBefore
	 */
	public function handleBefore(){

		if(empty($this->table)){
			$className=get_class($this);
			$className=explode("\\",$className);
			$className=end($className);
			$tableName=lcfirst($className);
			$tableName=str_replace("Table","",$tableName);
			$this->table=$tableName;
		}
		
		$dbConnection=Config::get("config.database.".$this->dbName);

		if(empty($dbConnection["orm"])){
			$dbConnection["orm"]="eloquent";
		}

		$this->ormType=$dbConnection["orm"];

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			$this->setEloquent($this->dbName,$dbConnection);
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->setMk2Orm($dbConnection);
		}

	}
	
	/**
	 * setEloquent
	 * @param string $dbName
	 * @param array $dbConnection
	 */
	private function setEloquent($dbName,$dbConnection){
		$capsule=new Capsule();
		$capsule->addConnection($dbConnection,$dbName);
		$capsule->setAsGlobal();
		$capsule->bootEloquent();
	}
	
	/**
	 * setMk2Orm
	 * @param array $dbConnection
	 */
	private function setMk2Orm($dbConnection){
		$this->mk2Orm=new Orm();
		$this->mk2Orm->setContext($this);
		$this->mk2Orm->setConnection($dbConnection);
	}

	/**
	 * query
	 * @param string $sql
	 */
	public function query($sql){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			if(strpos(strtolower($sql),"select")===0){
				return Capsule::connection($this->dbName)->select($sql);
			}
			else if(strpos(strtolower($sql),"insert")===0){
				return Capsule::connection($this->dbName)->insert($sql);
			}
			else if(strpos(strtolower($sql),"update")===0){
				return Capsule::connection($this->dbName)->update($sql);
			}
			else if(strpos(strtolower($sql),"delete")===0){
				return Capsule::connection($this->dbName)->delete($sql);
			}
			else{
				return Capsule::connection($this->dbName)->statement($sql);
			}
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			$orm=new OrmBase($this);
			return $orm->query($orm);
		}
	}

	/**
	 * connectCheck
	 */
	public function connectCheck(){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			return $this->mk2Orm->connectCheck();
		}
		
	}

	/**
	 * tableExists
	 */
	public function tableExists(){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			return $this->mk2Orm->tableExists();
		}
		
	}
	
	/**
	 * _ormDataSet
	 */
	private function _ormDataSet(){
		$this->mk2Orm->table=$this->table;
		$this->mk2Orm->prefix=$this->prefix;
		$this->mk2Orm->surrogateKey=$this->surrogateKey;
		$this->mk2Orm->timeStamp=$this->timeStamp;
		$this->mk2Orm->logicalDelete=$this->logicalDelete;
		if(!empty($this->fields)){
			$this->mk2Orm->fields=$this->fields;
		}
		if(!empty($this->where)){
			$this->mk2Orm->where=$this->where;
		}
		if(!empty($this->orderBy)){
			$this->mk2Orm->orderBy=$this->orderBy;
		}
		if(!empty($this->deleteFlgOnly)){
			$this->mk2Orm->deleteFlgOnly=$this->deleteFlgOnly;
		}
		if(!empty($this->deleteFlgAlso)){
			$this->mk2Orm->deleteFlgAlso=$this->deleteFlgAlso;
		}		
	}

	/**
	 * select
	 * @param array $params
	 */
	public function select($params=null){	

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			return $this->mk2Orm->select($params);
		}

	}

	/**
	 * show
	 * @param array $params
	 */
	public function show($params=null){	

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			return $this->mk2Orm->show($params);
		}

	}

	/**
	 * save
	 * @param array $params
	 */
	public function save($params=null){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			return $this->mk2Orm->save($params);
		}

	}

	/**
	 * insert
	 * @param array $params
	 */
	public function insert($params=null){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			return $this->mk2Orm->insert($params);
		}

	}

	/**
	 * update
	 * @param array $params
	 */
	public function update($params=null){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			return $this->mk2Orm->update($params);
		}

	}

	/**
	 * delete
	 * @param array $params
	 */
	public function delete($params=null){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			return $this->mk2Orm->delete($params);
		}

	}

	/**
	 * migration
	 * @param array $params
	 */
	public function migration($params=null){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			return $this->mk2Orm->migration($params);
		}

	}

	/**
	 * transaction
	 * @param array $params
	 */
	public function transaction($params=null){

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){
			$this->_ormDataSet();
			return $this->mk2Orm->transaction($params);
		}

	}

	/**
	 * hasMany
	 * @param Array $params
	 */
	public function hasMany($params){
		return $this->_associated("hasMany",$params);
	}

	/**
	 * hasOne
	 * @param Array $params
	 */
	public function hasOne($params){
		return $this->_associated("hasOne",$params);
	}

	/**
	 * belongsTo
	 * @param Array $params
	 */
	public function belongsTo($params){
		return $this->_associated("belongsTo",$params);
	}

	/**
	 * _associated
	 * @param string $type
	 * @param Array $params
	 */
	private function _associated($type, $params){

		$getSubClass=$this->Table->Load($params,true);

		if($this->ormType==self::ORMTYPE_ELOQUENT){
			
		}
		else if($this->ormType==self::ORMTYPE_MK2ORM){

			$this->_ormDataSet();

			foreach($getSubClass as $className=>$object){
				$object->_ormDataSet();
				if($type=="hasMany"){
					$this->mk2Orm->hasMany($className,$object->mk2Orm);				
				}
				else if($type=="hasOne"){
					$this->mk2Orm->hasOne($className,$object->mk2Orm);
				}
				else if($type=="belongsTo"){
					$this->mk2Orm->belongsTo($className,$object->mk2Orm);					
				}
			}
		}

		return $this;
	}

}