<?php
/*
This is the core class for database interactions/queries.
*/
require_once("debug.php");
class db
{
	//These variable values come from your hosting account.
	public static $hostname = "#####";
	public static $username = "#####";
	
	//These variable values need to be changed by you before deploying
	public static $dbname = "#####";
	public static $password = "#####";
	
	public static function MySqlSubmitQuery($q){
		$results;
		// Connect
		$link = mysql_connect(self::$hostname, self::$username, self::$password)
		    OR die(mysql_error());
		mysql_select_db(self::$dbname,$link);
		
		$results = mysql_query($q, $link);
		
		if(!$results){
			echo mysql_error();
		}
		
		mysql_close($link);
		return $results;
	}
	
	public static function MySqlSubmitTransaction($queries){
		
		if(!(is_null($queries) || empty($queries))) {
			if(!is_array($queries))
			{
				throw new Exception("MySqlSubmitTransaction: parameter 'queries' must be an array of string queries.");
			}else{
				$r = self::MySqlSubmitQuery("START TRANSACTION;" . join(";",$queries) . "; COMMIT;");
				return $r;
			}
		}
	}
}

class MySqlQuery{}

class MySqlSelect extends MySqlQuery
{
	private $cols;
	private $table;
	private $jointables = array();
	private $conditions = array();
	private $groupBy;
	private $orderBys = array();
	private $limit;
	
	function __construct() {}
	
	function COLUMNS($columns){$this->cols = $columns;}
	function FROM($tableName){$this->table = $tableName;}
	function JOIN($tableName){array_push($this->jointables, $tableName);}
	function ADD_CONDITION($stringCondition){array_push($this->conditions,$stringCondition);}
	function ADD_SORT($columnName,$direction){array_push($this->orderBys,$columnName . " " .$direction);}
	function LIMIT($startIndex,$numberOfRecords){$this->limit = $startIndex . "," .$numberOfRecords;}
	function GROUPBY($groupByField){$this->groupBy = $groupByField;}
	function toString(){
		return "SELECT ".$this->cols." FROM " . $this->table . (empty($this->jointables) ? "" : " JOIN ". join(",",$this->jointables)) . (empty($this->conditions) ? "" : " WHERE " . join(" AND ",$this->conditions)) . (empty($this->groupBy) ? "" : " GROUP BY ".$this->groupBy) . (empty($this->orderBys) ? "" : " ORDER BY ". join(" ",$this->orderBys)) . (empty($this->limit) ? "" : " LIMIT ".$this->limit);
	}
}

class MySqlDelete extends MySqlQuery
{
	private $table;
	private $conditions = array();
	private $limit;
	
	function __construct() {}
	
	function FROM($tableName){$this->table = $tableName;}
	function ADD_CONDITION($stringCondition){array_push($this->conditions,$stringCondition);}
	function LIMIT($startIndex,$numberOfRecords){$this->limit = $startIndex . "," .$numberOfRecords;}
	function toString(){
		return "DELETE FROM " . $this->table . (empty($this->conditions) ? "" : " WHERE " . join(" AND ",$this->conditions)) . (empty($this->limit) ? "" : " LIMIT ".$this->limit);
	}
}

class MySqlInsert extends MySqlQuery
{
	private $cols;
	private $rows = array();
	private $table;
	private $on_duplicate_key;
	
	function __construct() {}
	
	function COLUMNS($columns){
		if(!(is_null($columns) || empty($columns))){
			if(!is_array($columns))
			{
				throw new Exception("MySqlInset: columns must be an array of string column names.");
			}else{
				$this->cols = join(",",$columns);
			}
		}
	}
	function ADD_ROW($rowValues){
		if(!(is_null($rowValues) || empty($rowValues))) {
			if(!is_array($rowValues))
			{
				throw new Exception("MySqlInset: row values must be an array of string values.");
			}else{
				array_push($this->rows,"('" . join("','",$rowValues) . "')");
			}
		}
	}
	function ON_DUPLICATE_KEY_UPDATE($duplicate_update){
		$this->on_duplicate_key = $duplicate_update;
	}
	function INTO($tableName){$this->table = $tableName;}
	function toString(){
		return "INSERT INTO " . $this->table . " (".$this->cols.") VALUES ".join(",",$this->rows) . "" . ($this->on_duplicate_key ? " ON DUPLICATE KEY UPDATE ".$this->on_duplicate_key : "");
	}
}

class MySqlUpdate extends MySqlQuery
{
	private $columnValues = array();
	private $table;
	private $conditions = array();
	private $limit;
	
	function __construct() {}
	
	function ADD_COLUMN_VALUE_PAIR($colName,$val){ if(!(is_null($colName) || empty($colName)) && !(is_null($val) || empty($val))) { $this->columnValues[$colName] = $val; }  }
	function TABLE($tableName){$this->table = $tableName;}
	function ADD_CONDITION($stringCondition){array_push($this->conditions,$stringCondition);}
	function LIMIT($startIndex,$numberOfRecords){$this->limit = $startIndex . "," .$numberOfRecords;}
	function toString(){
		$updates = array();
		foreach ($this->columnValues as $key => $value){
			array_push($updates,$key . "='" . $value . "'");
		}
		
		return "UPDATE " . $this->table . " SET ". join(" , " , $updates) . (empty($this->conditions) ? "" : " WHERE " . join(" AND ",$this->conditions)) . (empty($this->limit) ? "" : " LIMIT ".$this->limit);;
	}
}

class MySqlOperand
{
	public static $AND='AND';
	public static $OR='OR';
}
?>