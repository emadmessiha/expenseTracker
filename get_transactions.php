<?php
/*
This files fetchesThis file fetches transaction data from the database based on a start and end Month/Year and returns the data back as a JSON object that can be used to render a grid view.
*/
require_once("include_files.php");
header('Content-Type: application/json');

//$_GET["month"]
//$_GET["year"]
$monthFrom = intval($_GET["monthFrom"]);
$monthTo = intval ($_GET["monthTo"]);
$yearFrom = intval ($_GET["yearFrom"]);
$yearTo = intval ($_GET["yearTo"]);


$fromDate = $yearFrom . str_pad($monthFrom, 2, "0", STR_PAD_LEFT) . "01";
$toDate = $yearTo . str_pad($monthTo, 2, "0", STR_PAD_LEFT) . getLastDayOfMonth($monthTo,$yearTo);

$rows = array();
$total = 0;
$inTotal = 0;
$outTotal = 0;
$pageNumber = (is_null($_GET["page"]) ? 1 : $_GET["page"]);
$page = $pageNumber - 1;
$recordsPerPage = (is_null($_GET["rp"]) ? 10 : $_GET["rp"]);
$offset = $page * $recordsPerPage;

$sortName = (is_null($_GET["sortname"]) ? "tdate" : $_GET["sortname"]);
$sortOrder = (is_null($_GET["sortorder"]) ? "desc" : $_GET["sortorder"]);

$searchQuery = (is_null($_GET["query"]) ? null : trim($_GET["query"]));
$searchQueryType = (is_null($_GET["qtype"]) ? null : $_GET["qtype"]);
$searchCondition = "";
if(!(is_null($searchQuery) || empty($searchQuery)) && !(is_null($searchQueryType) || empty($searchQueryType))){
	$searchCondition = $searchQueryType." LIKE '%".$searchQuery."%'";
}

$valueCondition = ($_GET["type"] == $transaction_type->income ? "in_amount>0 AND out_amount=0" : ($_GET["type"] == $transaction_type->expenses ? "in_amount=0 AND out_amount>0" : ""));

$q = new MySqlSelect();
$q->COLUMNS("COUNT(*) as total, SUM(".tbl_transactions::$in_amount.") as inTotal, SUM(".tbl_transactions::$out_amount.") as outTotal");
$q->FROM(tbl_transactions::tableName());
$q->JOIN(tbl_stores::tableName());
$q->JOIN(tbl_tags::tableName());
if(!empty($valueCondition)){ $q->ADD_CONDITION($valueCondition); }
$q->ADD_CONDITION("tdate BETWEEN ".$fromDate." AND ".$toDate);
if(!empty($searchCondition)){$q->ADD_CONDITION($searchCondition);}
$q->ADD_CONDITION(tbl_transactions::$istransfer."='0'");
$q->ADD_CONDITION(tbl_transactions::tableName().".".tbl_transactions::$store_id."=".tbl_stores::tableName().".".tbl_stores::getPrimaryKeyField());
$q->ADD_CONDITION(tbl_transactions::tableName().".".tbl_transactions::$tag_id."=".tbl_tags::tableName().".".tbl_tags::getPrimaryKeyField());

//Debug($q->toString());
$results = db::MySqlSubmitQuery($q->toString());


if($results){
	$resultRow = mysql_fetch_assoc($results);
	$total = $resultRow["total"];
	$inTotal = $resultRow["inTotal"];
	$outTotal = $resultRow["outTotal"];
	
	//reset columns and add paging and sorting
	$q->COLUMNS(tbl_transactions::tableName().".".tbl_transactions::getPrimaryKeyField().",".
	            tbl_transactions::tableName().".".tbl_transactions::$tdate.",".
	            tbl_transactions::tableName().".".tbl_transactions::$in_amount.",".
	            tbl_transactions::tableName().".".tbl_transactions::$out_amount.",".
	            tbl_transactions::tableName().".".tbl_transactions::$category.",".
	            tbl_transactions::tableName().".".tbl_transactions::$description.",".
	            tbl_transactions::tableName().".".tbl_transactions::$ttype.",".
	            tbl_transactions::tableName().".".tbl_transactions::$tmonth.",".
	            tbl_transactions::tableName().".".tbl_transactions::$tyear.",".
	            tbl_transactions::tableName().".".tbl_transactions::$tday.",".
	            tbl_transactions::tableName().".".tbl_transactions::$istransfer.",".
	            tbl_transactions::tableName().".".tbl_transactions::$tag_id.",".
	            tbl_transactions::tableName().".".tbl_transactions::$store_id.",".
	            tbl_stores::tableName().".".tbl_stores::$store_name .",".
	            tbl_tags::tableName().".".tbl_tags::$tag_name .""
	);
	$q->ADD_SORT($sortName,$sortOrder);
	$q->LIMIT($offset,$recordsPerPage);
	Debug($q->toString());
	$pagedResults = db::MySqlSubmitQuery($q->toString());
	
	if($pagedResults){
		while ($row = mysql_fetch_array($pagedResults, MYSQL_ASSOC)) {
			$cell = array($row[tbl_transactions::$tdate],$row[tbl_transactions::$in_amount],
			$row[tbl_transactions::$out_amount],$row[tbl_transactions::$ttype],$row[tbl_transactions::$category],
			$row[tbl_transactions::$description],$row[tbl_tags::$tag_name],$row[tbl_transactions::$tag_id],$row[tbl_stores::$store_name],$row[tbl_transactions::$store_id]);
			array_push($rows,array("id" => $row["id"], "cell" => $cell));
		}
	}
}

echo json_encode(array(inTotal => $inTotal, outTotal => $outTotal, page => $pageNumber, total => $total.'', rows => $rows));
?>