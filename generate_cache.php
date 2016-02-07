<?php
/*
This file generates the "cache_php" file from the database.
*/
require_once("db_connect.php");
require_once("debug.php");
require_once("constants.php");

$cache = "<?php";

//special handling for stores table (creating stores cache)
$selectStores = new MySqlSelect();
$selectStores->COLUMNS("*");
$selectStores->FROM("stores");
$selectStores->ADD_SORT("store_name","desc");
$storesResult = db::MySqlSubmitQuery($selectStores->toString());

if($storesResult){
	$cache .= $newLine . "class db_cache_store{";
	while ($storesResultRow = mysql_fetch_array($storesResult, MYSQL_ASSOC)) {
		$cache .= $newLine . " public static $"."_". cleanName($storesResultRow["store_name"])." = array(id => ". $storesResultRow["id"] . " , name => '". $storesResultRow["store_name"]."', default_tag_id => '".$storesResultRow["default_tag_id"]."');";
	}
	
	$cache .= $newLine . "}";
}


//special handling for tags table (creating stores cache)
$selectTags = new MySqlSelect();
$selectTags->COLUMNS("*");
$selectTags->FROM("tags");
$selectTags->ADD_SORT("tag_name","desc");
$tagsResult = db::MySqlSubmitQuery($selectTags->toString());

if($tagsResult){
	$cache .= $newLine . "class db_cache_tag{";
	while ($tagsResultRow = mysql_fetch_array($tagsResult, MYSQL_ASSOC)) {
		$cache .= $newLine . " public static $"."_". cleanName($tagsResultRow["tag_name"])." = array(id => ". $tagsResultRow["id"] . " , name => '". $tagsResultRow["tag_name"]."');";
	}
	
	$cache .= $newLine . "}";
}

$cache .= $newLine . "?>";

file_put_contents("cache.php", $cache);

echo 'complete.';
?>