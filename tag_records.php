<?php
/*
This is one of the core files in the application. It goes through transaction records in the database and tags (grocery, gas, etc. or even stores) them based on what it determines from parsing the transaction details
*/
require_once('include_files.php');

if(!is_null($_GET["execute-tagging"])){
	//tag all transfers
	$tagTransfers = new MySqlUpdate();
	$tagTransfers->TABLE(tbl_transactions::tableName());
	$tagTransfers->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$istransfer,"1");
	$tagTransfers->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$tag_id,db_cache_tag::$_N_A["id"]);
	//$tagTransfers->ADD_CONDITION(tbl_transactions::$store_id . "='" . db_cache_store::$_N_A["id"]."'");
	$tagTransfers->ADD_CONDITION(tbl_transactions::$category . " LIKE 'Customer Transfer%' OR " . tbl_transactions::$description . " LIKE 'PC - PAYMENT%'");
	db::MySqlSubmitQuery($tagTransfers->toString());
	
	//tag records
	$t = new db_cache_store();
	$r = new ReflectionObject($t);
	$storeList = $r->getStaticProperties();
	
	foreach($storeList as $key => $value) {
		if($value != db_cache_store::$_other){
			$updateStore = new MySqlUpdate();
			$updateStore->TABLE(tbl_transactions::tableName());
			$updateStore->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$store_id, $value["id"]);
			$updateStore->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$tag_id, $value["default_tag_id"]);
			$updateStore->ADD_CONDITION(alphaNumericLowerMySql(tbl_transactions::$description) . " LIKE '%".alphaNumericLower($value["name"])."%'");
			//$updateStore->ADD_CONDITION(tbl_transactions::$in_amount . "=0");
			$updateStore->ADD_CONDITION(tbl_transactions::$istransfer . "=0");
			$updateStore->ADD_CONDITION(tbl_transactions::$store_id . "='" . db_cache_store::$_none["id"]."'");
			
			db::MySqlSubmitQuery($updateStore->toString());
		}
	}
	
	echo 'tagging complete. ';
	
}else if(!is_null($_GET["reset-tagging"])){
	//reset all tags to other
	$resetTag = new MySqlUpdate();
	$resetTag->TABLE(tbl_transactions::tableName());
	$resetTag->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$tag,db_enum_tag::c_none);
	$resetTag->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$store_id,db_cache_store::$_none["id"]);
	$resetTag->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$istransfer,"0");
	db::MySqlSubmitQuery($resetTag->toString());
	echo 'tag reset complete.';
}
	
function alphaNumericLower($text){
	return strtolower(str_ireplace('\'','',str_ireplace('#','',str_ireplace('(','',str_ireplace(')','',str_ireplace('-','',str_ireplace(' ','',trim($text))))))));
}
function alphaNumericLowerMySql($fieldName){
	return 'LOWER(REPLACE(REPLACE(REPLACE(REPLACE('.$fieldName.', " ", ""),"-",""),"#",""),"\'",""))';
}
?>