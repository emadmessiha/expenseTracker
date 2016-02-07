<?php
/*
This file provides methods to allow for printing values for debugging purposes.
*/
function Debug($message){
	if(!is_null($_GET["debug"])){
		var_dump($message);
	}
}
?>