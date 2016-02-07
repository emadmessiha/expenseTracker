<?php
/*
This file fetches transaction data from the database based on a start and end Month/Year and returns the data back as a JSON object that can be used to render a bar chart.
*/
require_once("include_files.php");
header('Content-Type: application/json');

$monthFrom = intval($_GET["monthFrom"]);
$monthTo = intval ($_GET["monthTo"]);
$yearFrom = intval ($_GET["yearFrom"]);
$yearTo = intval ($_GET["yearTo"]);


$months = array(
	1 => "Jan",
	2 => "Feb",
	3 => "Mar",
	4 => "Apr",
	5 => "May",
	6 => "Jun",
	7 => "Jul",
	8 => "Aug",
	9 => "Sep",
	10 => "Oct",
	11 => "Nov",
	12 => "Dec");

$fromDate = $yearFrom . str_pad($monthFrom, 2, "0", STR_PAD_LEFT) . "01";
$toDate = $yearTo . str_pad($monthTo, 2, "0", STR_PAD_LEFT) . getLastDayOfMonth($monthTo,$yearTo);

// Query
$incomeData = array();
$expenseData = array();
$labels = array();

$q = new MySqlSelect();
$q->COLUMNS("SUM( in_amount ) AS income, SUM( out_amount ) AS expenses, tmonth , tyear");
$q->FROM(tbl_transactions::tableName());
$q->ADD_CONDITION(tbl_transactions::$istransfer."='0'");
$q->ADD_CONDITION("tdate BETWEEN ".$fromDate." AND ".$toDate);
$q->GROUPBY("tmonth");
$q->ADD_SORT("tyear,tmonth","ASC");

Debug($q->toString());

$results = db::MySqlSubmitQuery($q->toString(), $link);
Debug($results);
if($results){
	while ($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
		array_push($labels, array("label" => $months[$row["tmonth"]] . "-" . $row["tyear"]));
	    array_push($incomeData, array("value" => $row["income"],"link" => "JavaScript: getTransactions('".$transaction_type->income."','".$row["tmonth"]."','".$row["tyear"]."');"));
	    array_push($expenseData, array("value" => $row["expenses"],"link" => "JavaScript: getTransactions('".$transaction_type->expenses."','".$row["tmonth"]."','".$row["tyear"]."');"));
	}
}

echo '{"chart": {
"caption": "Monthly income/expenses",
"subCaption": "",
"xAxisName": "Month",
"yAxisName": "Amount ($)",
"numberPrefix": "$",
"theme": "fint"
},
"categories": [{
"category": '.json_encode($labels).'
}],
"dataset": [
{
"seriesname": "Income",
"data": '.json_encode($incomeData).'
},
{
"seriesname": "Expenses",
"data": '.json_encode($expenseData).'
}
]
}';

?>