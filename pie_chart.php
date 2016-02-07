<?php
/*
This file fetches transaction data from the database based on a start and end Month/Year and returns the data back as a JSON object that can be used to render a pie chart.
*/
require_once("include_files.php");
header('Content-Type: application/json');

$monthFrom = intval($_GET["monthFrom"]);
$monthTo = intval ($_GET["monthTo"]);
$yearFrom = intval ($_GET["yearFrom"]);
$yearTo = intval ($_GET["yearTo"]);

$groupBy = $_GET["groupBy"];
Debug($groupBy);

$fromDate = $yearFrom . str_pad($monthFrom, 2, "0", STR_PAD_LEFT) . "01";
$toDate = $yearTo . str_pad($monthTo, 2, "0", STR_PAD_LEFT) . getLastDayOfMonth($monthTo,$yearTo);

$sumExpenseColumnName = "expense";

$select = new MySqlSelect();
$select->COLUMNS(tbl_tags::$tag_name . ", SUM(".tbl_transactions::$out_amount.") as ".$sumExpenseColumnName);
$select->FROM(tbl_transactions::tableName());
$select->JOIN(tbl_tags::tableName());
$select->ADD_CONDITION(tbl_transactions::$in_amount."='0'");
$select->ADD_CONDITION("tdate BETWEEN ".$fromDate." AND ".$toDate);
$select->ADD_CONDITION(tbl_transactions::$istransfer."='0'");
$select->ADD_CONDITION(tbl_transactions::tableName().".".tbl_transactions::$tag_id."=".tbl_tags::tableName().".".tbl_tags::getPrimaryKeyField());
$select->GROUPBY($groupBy);
$select->ADD_SORT($sumExpenseColumnName,"DESC");

Debug($select->toString());
$results = db::MySqlSubmitQuery($select->toString());

$data = array();

if($results){
	while ($row = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$object = new stdClass();
   		$object->label = $row[tbl_tags::$tag_name];
		$object->value = $row[$sumExpenseColumnName];
		$object->link = "JavaScript: getTransactionsBy('".$groupBy."','".$row[$groupBy]."');";
		array_push($data,$object);
	}
}

echo '{
    "chart": {
        "caption": "Expenditure - percentage by ' . $groupBy .'",
        "bgcolor": "FFFFFF",
        "showvalues": "1",
        "showpercentvalues": "1",
        "showborder": "0",
        "showplotborder": "0",
        "showlegend": "1",
        "legendborder": "0",
        "legendposition": "bottom",
        "enablesmartlabels": "1",
        "use3dlighting": "0",
        "showshadow": "0",
        "legendbgcolor": "#CCCCCC",
        "legendbgalpha": "20",
        "legendborderalpha": "0",
        "legendshadow": "0",
        "legendnumcolumns": "3",
        "showBorder": "0",
        "palettecolors": "#f8bd19,#e44a00,#008ee4,#33bdda,#6baa01,#583e78,#CCFF33,#47C2FF"
    },
    "data": '.json_encode($data).' }';
?>