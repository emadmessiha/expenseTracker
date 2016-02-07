<?php
/*
This is a page that enables the user to import more data into the application.
*/
require_once("include_files.php");
$t = new db_enum_ttype();
$r = new ReflectionObject($t);
$ttypeList = $r->getConstants();
?>
<html>
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function()
		{
			// do stuff when the submit button is clicked
			$('form[name="myform"]').submit(function(e)
			{
				if(confirm("Are you sure you want to proceed with upload?")){
					// disable the submit button
					$('input[type="submit"]').attr('disabled', true);
					// submit the form
					return true;
				}else{return false;}
			});
		});
		</script>
	</head>
	
	<body>
	
		<form action="upload_file.php" method="post" enctype="multipart/form-data" name="myform">
			<label for="transactionsType">Type:</label>
			<select name="transactionsType" id="transactionsType">
				<?php
				foreach($ttypeList as $key => $value) {
					echo "<option>".$value."</option>";
				}
				?>
			</select>
			<br>
			<label for="file">Filename:</label>
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Submit">
		</form>
	
	</body>
</html>