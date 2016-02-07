<?php
/*
Code to save a new store to the database.
*/
require_once("include_files.php");

$storeName = $_POST["sname"];
$storeDefaultTag = $_POST["stag"];

if($storeName && $storeDefaultTag){
	
	$insertStore = new MySqlInsert();
	$insertStore->INTO(tbl_stores::tableName());
	$insertStore->COLUMNS(array(tbl_stores::$store_name,tbl_stores::$default_tag_id));
	$insertStore->ADD_ROW(array($storeName,$storeDefaultTag));
	db::MySqlSubmitQuery($insertStore->toString());
	
	echo "Store '".$storeName."' added successfully.";
	
}else{
	echo "Empty or Invalid Store name '".$storeName."' provided";
}
?>