<?php

/**
 * ===================================================
 * 
 * [Mark2] - TableView
 * 
 * Object class for initial operation.
 * 
 * URL : https://www/mk2-php.com/
 * Copylight : Nakajima-Satoru 2021.
 * 
 * ===================================================
 */

namespace Mk2\Libraries;

use Mk2\Orm\Orm;
use \Illuminate\Database\Capsule\Manager as Capsule;

class TableView extends CoreBlock{

	private const ORMTYPE_ELOQUENT="eloquent";
	private const ORMTYPE_MK2ORM="mk2orm";

	public $ormType=self::ORMTYPE_MK2ORM;
	
	public $dbName="default";
	
	public $_selectObj=null;

	public function handleBefore(){
		
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

		$this->_selectObj=$this->mk2Orm->select();
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
		$this->mk2Orm->table="(".$this->sql.") AS _table";
	}

    /**
     * where
     * @param $field
     * @param $operand
     * @param $value
     * @param $conditions = null
     * @param $index = 0
     */
	public function where($field,$operand,$value,$conditions = null,$index = 0){
		$this->_selectObj->where($field,$operand,$value,$conditions,$index);
		return $this;
	}

    /**
     * whereAnd
     * @param $field
     * @param $operand
     * @param $value
     * @param $index = 0
     */
    public function whereAnd($field,$operand,$value,$index = 0){
    	$this->_selectObj->whereAnd($field,$operand,$value,$index);
        return $this;
    }

    /**
     * whereOr
     * @param $field
     * @param $operand
     * @param $value
     * @param $index = 0
     */
    public function whereOr($field,$operand,$value,$index = 0){
    	$this->_selectObj->whereOr($field,$operand,$value,$index);
        return $this;
    }

    /**
     * having
     * @param $field
     * @param $operand
     * @param $value
     * @param $conditions = null
     * @param $index = 0
     */
    public function having($field,$operand,$value,$conditions=null ,$index = 0){
    	$this->_selectObj->having($field,$operand,$value,$conditions,$index);
        return $this;
    }

    /**
     * havingAnd
     * @param $field
     * @param $operand
     * @param $value
     * @param $index = 0
     */
    public function havingAnd($field,$operand,$value, $index = 0){
		$this->_selectObj->havingAnd($field,$operand,$value,$index);
        return $this;
    }

    /**
     * havingOr
     * @param $field
     * @param $operand
     * @param $value
     * @param $index = 0
     */
    public function havingOr($field,$operand,$value, $index = 0){
		$this->_selectObj->havingOr($field,$operand,$value,$index);
        return $this;
    }

    /**
     * fields
     * @param $fields
     */
    public function fields($fields){
		$this->_selectObj->fields($fields);
        return $this;
    }


    /**
     * join
     * @param $argv
     */
    public function join(...$argv){
		$this->_selectObj->join(...$argv);
        return $this;
    }

    /**
     * innerjoin
     * @param $argv
     */
    public function innerjoin(...$argv){
		$this->_selectObj->innerjoin(...$argv);
        return $this;
    }

    /**
     * leftJoin
     * @param $argv
     */
    public function leftJoin(...$argv){
		$this->_selectObj->leftJoin(...$argv);
        return $this;
    }

    /**
     * outerLeftJoin
     * @param $argv
     */
    public function outerLeftJoin(...$argv){
		$this->_selectObj->outerLeftJoin(...$argv);
        return $this;
    }

    /**
     * rightJoin
     * @param $argv
     */
    public function rightJoin(...$argv){
		$this->_selectObj->rightJoin(...$argv);
        return $this;
    }

    /**
     * outerRightJoin
     * @param $argv
     */
    public function outerRightJoin(...$argv){
		$this->_selectObj->outerRightJoin(...$argv);
        return $this;        
    }

    /**
     * limit
     * @param $limit
     * @param $offset = 0
     */
    public function limit($limit,$offset=0){
		$this->_selectObj->limit($limit,$offset);
        return $this;
    }

    /**
     * paging
     * @param $limit
     * @param $page = 1
     */
    public function paging($limit,$page=1){
		$this->_selectObj->paging($limit,$page);
        return $this;
    }

    /**
     * orderBy
     * @param $fieldName
     * @param $sort
     */
    public function orderBy($fieldName,$sort){
		$this->_selectObj->orderBy($fieldName,$sort);
        return $this;
	}

	/**
     * get
     * @param string $type = "all"
     */
    public function get($type="all"){
		return $this->_selectObj->get($type);
	}

    /**
     * all
     */
    public function all(){
		return $this->_selectObj->get();
        return $this->get();
    }

    /**
     * first
     */
    public function first(){
		return $this->_selectObj->first();
    }

    /**
     * one
     * @param $fieldName
     */
    public function one($fieldName){
		return $this->_selectObj->one($fieldName);
    }

    /**
     * value
     * @param $fieldName
     */
    public function value($fieldName){
        return $this->_selectObj->value($fieldName);
    }

	
    /**
     * max
     * @param $fieldName
     */
    public function max($fieldName){
        return $this->_selectObj->max($fieldName);
    }

    /**
     * min
     * @param $fieldName
     */
    public function min($fieldName){
        return $this->_selectObj->min($fieldName);
    }

    /**
     * sum
     * @param $fieldName
     */
    public function sum($fieldName){
		return $this->_selectObj->sum($fieldName);
    }

    /**
     * avg
     * @param $fieldName
     */
    public function avg($fieldName){
		return $this->_selectObj->avg($fieldName);
    }
    
	/**
     * lists
     * @param $fieldName
     * @param $valueName = null
     */
    public function lists($fieldName,$valueName=null){
		return $this->_selectObj->lists($fieldName,$valueName);
	}

    /**
     * count
     */
    public function count(){
		return $this->_selectObj->count();
    }

    /**
     * sql
     */
    public function sql(){
		return $this->_selectObj->sql();
    }

	/**
     * paginate
     * @param $limit
     * @param $page
     */
    public function paginate($limit,$page){
		return $this->_selectObj->paginate($limit,$limit);
	}
}