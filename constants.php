<?php
/*
A generic helpers and constants file.
*/

$transaction_type->income = "income";
$transaction_type->expenses = "expenses";
$transaction_type->all = "all";
$newLine = "\r\n";

function getLastDayOfMonth($month,$year){
	$a_date = $year . "-" . $month . "-01";
	return date("t", strtotime($a_date));
}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function cleanName($name){
	return str_ireplace("'", "",str_ireplace(".", "",str_ireplace("-", "_",str_ireplace("&","_",str_ireplace("/","_",str_ireplace(" ","_",$name))))));
}
?>