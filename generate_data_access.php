<?php
/*
This files generates the data access "da.php" file from the database.
*/
require_once("db_connect.php");
require_once("debug.php");
require_once("constants.php");

$getAllTablesQuery = "SHOW FULL TABLES IN " . db::$dbname;
$getAllColumnsInTable = "SHOW FULL COLUMNS IN %s";

Debug("getAllTablesQuery = " . $getAllTablesQuery);
$tables = db::MySqlSubmitQuery($getAllTablesQuery);

$da = "<?php";
$enums = "";

if($tables){
	while ($row = mysql_fetch_array($tables, MYSQL_ASSOC)) {
		$table = $row["Tables_in_".db::$dbname];
		
		$tblClassName = "tbl_".$table;
		
		$da .= sprintf($newLine . "class %s{",$tblClassName);
		$da .= $newLine . " public static function tableName(){ return '".$table."';}";
		$da .= $newLine . " public static function getColumnsArray(){return array_keys(get_class_vars(".$tblClassName."));}";
		
		$qq = sprintf($getAllColumnsInTable,db::$dbname . "." . $table);
		Debug("<br>");
		Debug("getAllColumnsInTable = " . $qq);
		Debug("<br>");
		$columns = db::MySqlSubmitQuery($qq);
		
		if($columns){
			while ($columnRow = mysql_fetch_array($columns, MYSQL_ASSOC)) {
				$isPrimaryKey = $columnRow["Key"] == "PRI";
				$colName = $columnRow["Field"];
				$colType = $columnRow["Type"];
				
				if(startsWith($colType,"enum")){
					$enums .= $newLine . getEnumClass($colType,$colName);
				}
				
				if($isPrimaryKey){
					$da .= $newLine . " public static function getPrimaryKeyField(){ return '".$colName."';}";
				}else{
					$da .= $newLine . " public static $".$colName." = '".$colName."';";
				}
			}
		}
		
		$da .= $newLine . "}";
	}
}
$da .= $enums . $newLine . "?>";

file_put_contents("da.php", $da);

echo 'complete.';

function getEnumClass($enumDefinition,$columnName){
	$eClass = "class db_enum_".$columnName." { function __construct() {}";
	$enumVals = explode(",",str_ireplace("'","",str_ireplace(")","",str_ireplace("(","",str_ireplace("enum","",$enumDefinition)))));
	foreach ($enumVals as &$value) {
	    $eClass .= " const c_" . cleanName($value) . "='" . $value . "';";
	}
	$eClass .= " }";
	return $eClass;
}

?>