<?php
/*
This is a health check file for the application.
*/
require_once("include_files.php");
$dbOk = false;
$dbErrors = "";
$mysqli = new mysqli(db::$hostname, db::$username, db::$password, db::$dbname);

/* check connection */
if ($mysqli->connect_errno) {
    $dbErrors .= $mysqli->connect_error;
}else if ($mysqli->ping()) {/* check if server is alive */
    $dbOk = true;
} else {
    $dbErrors .= $mysqli->error;
}

/* close connection */
$mysqli->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Health Check</title>
	</head>
	<body>
		<h1>Code Version: 1.0</h1>
		<ul>
			<li>Database:
			<?php
			if($dbOk){
				?>
			<img src="http://co2nsult.co.uk/wp-content/uploads/2012/09/bill-validation-icon.png" style="width: 20px;" />
			<?php
			}else{
				?>
			<img src="http://png-4.findicons.com/files/icons/1014/ivista/256/error.png" style="width: 21px;" /><br>
			<?php
			echo $dbErrors;
			}
			?>
			</li>
		</ul>
		
	</body>
</html>