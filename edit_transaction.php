<?php
/*
This file handles the saving of an existing transaction.
*/
require_once("include_files.php");

try{

	$id = $_POST["record_id"];
	$originalIn = $_POST["original_in"];
	$originalOut = $_POST["original_out"];
	$date = $_POST[tbl_transactions::$tdate];
	
	$type = $_POST[tbl_transactions::$ttype];
	
	$in = $_POST[tbl_transactions::$in_amount];
	$out = $_POST[tbl_transactions::$out_amount];
	$cat = $_POST[tbl_transactions::$category];
	$desc = $_POST[tbl_transactions::$description];
	$tag = $_POST[tbl_transactions::$tag_id];
	$store = $_POST[tbl_transactions::$store_id];
	
	$split = $_POST["split"];
	if($split == '1'){
		
		$d = DateTime::createFromFormat("Y-m-d", $date);
		$year = $d->format("Y");
		$month = $d->format("m");
		$day = $d->format("d");
		
		$in2 = $_POST[tbl_transactions::$in_amount . "2"];
		$out2 = $_POST[tbl_transactions::$out_amount . "2"];
		$cat2 = $_POST[tbl_transactions::$category . "2"];
		$desc2 = $_POST[tbl_transactions::$description . "2"];
		$tag2 = $_POST[tbl_transactions::$tag_id . "2"];
		$store2 = $_POST[tbl_transactions::$store_id . "2"];
		
		if(($in + $in2) != $originalIn){
			throw new Exception("new amounts are not equal to original total amount of '".$originalIn."'");
		}
		
		if(($out + $out2) != $originalOut){
			throw new Exception("new amounts are not equal to original total amount of '".$originalOut."'");
		}
		
		$insert = new MySqlInsert();
		$insert->INTO(tbl_transactions::tableName());
		$insert->COLUMNS(array(
			tbl_transactions::$tdate,
			tbl_transactions::$in_amount,
			tbl_transactions::$out_amount,
			tbl_transactions::$category,
			tbl_transactions::$description,
			tbl_transactions::$ttype,
			tbl_transactions::$tyear,
			tbl_transactions::$tmonth,
			tbl_transactions::$tday,
			tbl_transactions::$tag_id,
			tbl_transactions::$store_id));
		$insert->ADD_ROW(array(
			$date,
			$in2,
			$out2,
			$cat2,
			$desc2,
			$type,
			$year,
			$month,
			$day,
			$tag2,
			$store2));
		
		Debug($insert->toString());
	}
	
	$update = new MySqlUpdate();
	$update->TABLE(tbl_transactions::tableName());
	$update->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$in_amount,$in);
	$update->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$out_amount,$out);
	$update->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$category,$cat);
	$update->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$description,$desc);
	$update->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$tag_id,$tag);
	$update->ADD_COLUMN_VALUE_PAIR(tbl_transactions::$store_id,$store);
	$update->ADD_CONDITION(tbl_transactions::getPrimaryKeyField()."=".$id);
	
	Debug($update->toString());
	
	if($insert){
		db::MySqlSubmitTransaction(array($update->toString(),$insert->toString()));
	}else{
		db::MySqlSubmitQuery($update->toString());
	}
	
	echo "Success.";
}catch(Exception $e){
	echo $e->getMessage();
}
?>