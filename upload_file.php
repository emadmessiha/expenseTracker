<?php
/*
This file processes the upload of a transactions file and imports it to the database.
*/
require_once("include_files.php");

$Category_POSPurchase = "POS Purchase";
$Description_SccpInsurancePremium = "SCCP INSURANCE PREMIUM";

$date_format = "Y-m-d";
$date_year = "Y";
$date_month = "m";
$date_day = "d";

$transactionType = $_POST["transactionsType"];
$filename=$_FILES["file"]["tmp_name"];
$delimiter=",";

Debug("File type is '" . $transactionType . "'");

if ($_FILES["file"]["error"] > 0) {
	echo "Error: " . $_FILES["file"]["error"] . "<br>";
} else {
	Debug("Upload: " . $_FILES["file"]["name"]);
	Debug("Type: " . $_FILES["file"]["type"]);
	Debug("Size: " . ($_FILES["file"]["size"] / 1024) . " kB");
	Debug("Stored in: " . $filename);
	try{
		$insertQuery = new MySqlInsert();
		$insertQuery->INTO(tbl_transactions::tableName());
		$insertQuery->COLUMNS(
			array(
				tbl_transactions::$tdate,
				tbl_transactions::$in_amount,
				tbl_transactions::$out_amount,
				tbl_transactions::$category,
				tbl_transactions::$description,
				tbl_transactions::$ttype,
				tbl_transactions::$tmonth,
				tbl_transactions::$tyear,
				tbl_transactions::$tday
			));
			
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
			{
				$record_date = "";
				$record_in_amount = "";
				$record_out_amount = "";
				$record_category = "";
				$record_description = "";
				$record_type = "";
				$record_month = "";
				$record_year = "";
				$record_day = "";
				
				if($transactionType == db_enum_ttype::c_visa){
					//("date", "description", "value")
					$record_date = date($date_format, strtotime(getRowColumnValue($row,0)));
					if(getRowColumnValue($row,2) > 0){
						$record_in_amount = getRowColumnValue($row,2);
					}else{
						$record_out_amount = getRowColumnValue($row,2) * -1;
					}
					$record_category = (getRowColumnValue($row,1) == $Description_SccpInsurancePremium ? getRowColumnValue($row,1) : $Category_POSPurchase);
					$record_description = getRowColumnValue($row,1);
					$record_type = db_enum_ttype::c_visa;
					$record_month = date($date_month, strtotime(getRowColumnValue($row,0)));
					$record_year = date($date_year, strtotime(getRowColumnValue($row,0)));
					$record_day = date($date_day, strtotime(getRowColumnValue($row,0)));
					
				}else if($transactionType == db_enum_ttype::c_checking){
					//("date", "value","n/a", "category","description")
					$record_date = date($date_format, strtotime(getRowColumnValue($row,0)));
					if(getRowColumnValue($row,1) > 0){
						$record_in_amount = getRowColumnValue($row,1);
					}else{
						$record_out_amount = getRowColumnValue($row,1) * -1;
					}
					$record_category = getRowColumnValue($row,3);
					$record_description = getRowColumnValue($row,4);
					$record_type = db_enum_ttype::c_checking;
					$record_month = date($date_month, strtotime(getRowColumnValue($row,0)));
					$record_year = date($date_year, strtotime(getRowColumnValue($row,0)));
					$record_day = date($date_day, strtotime(getRowColumnValue($row,0)));
				}
				$insertQuery->ADD_ROW(array(
					$record_date,
					$record_in_amount,
					$record_out_amount,
					$record_category,
					$record_description,
					$record_type,
					$record_month,
					$record_year,
					$record_day
				));
			}
			fclose($handle);
		}
		
		// Query
		Debug($insertQuery->toString());
		db::MySqlSubmitQuery($insertQuery->toString());
		
		// Success
		echo("<img width='70' style='float:left' src='https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcQfhIUgJquf8L02tVeh0B_Vfe_kOhbLofy5kpGs_CB4PEfakg8vGzk6lp75' /><h3>File uploaded successfully.</h3><a href='index.html'>Done</a>");
		echo("<br><br><a href='upload.php'>upload another...</a>");
		echo("<br><a href='tag_records.php?execute-tagging=1'><img src='http://www.iconsdb.com/icons/preview/orange/tag-xxl.png' style='width:20px' /> RUN TAGGING</a>");
		
	} catch (Exception $e) {
	    echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}

function getRowColumnValue($r,$columnIndex){
	return addslashes(trim(trim($r[$columnIndex]), '"'));
}
?>