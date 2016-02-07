<?php
/*
This is a page that displays all stores that the user has defined. It also provides the ability to add new stores or refresh the database tagging of transactions after a new store has been added.
*/
require_once("include_files.php");
$t = new db_cache_tag();
$rt = new ReflectionObject($t);
$tagList = $rt->getStaticProperties();
unset($t);
unset($rt);

$s = new db_cache_store();
$st = new ReflectionObject($s);
$storeList = $st->getStaticProperties();
unset($s);
unset($st);
?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript" src="fusioncharts-suite/assets/scripts/jquery.min.js"></script>
		<script type="text/javascript" src="jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
		<link rel="stylesheet" type="text/css" href="jquery-ui-1.11.0.custom/jquery-ui.min.css">
		<script>
			$(function(){
				$( "#dialog-form" ).hide();
				
				//initialize form
				resetForm();
			});
			
			function openAddStoreForm(){
				dialog = $( "#dialog-form" ).dialog({
			      autoOpen: false,
			      height: 300,
			      width: 370,
			      modal: true,
			      buttons: {
			      	"Save": submitAddStore,
			        "Close": function() {
			          dialog.dialog( "close" );
			        }
			      },
			      close: function() {dialog.dialog( "close" );}
			    });
			    $( "#dialog-form" ).dialog("open");
			}
			
			function submitAddStore(){
				var r = confirm("Are you sure you want to save?");
				if (r == true) {
				    $.ajax({
					  type: "POST",
					  url: $("#addstore").attr("action"),
					  data: $("#addstore").serialize(),
					  success: function(result){
					  		dialog.dialog( "close" );
					  		resetForm();
					  		alert(result);
					  	},
					  dataType: "text"
					});
				}
			}
			
			function resetForm(){
				$('#stag').val('1');
				$('#sname').val('');
			}
			
			function refreshTagging(){
				$.get( "generate_cache.php", function( data ) {
				  alert( "Data cache generation:" + data );
				});
				
				$.get( "tag_records.php?execute-tagging=1", function( data ) {
				  alert( "Record tagging:" + data );
				});
			}
		</script>
	</head>
	<body>
		<button id="addStoreButton" title="Add Store" onclick="openAddStoreForm()">Add Store</button>
		<button id="addStoreButton" title="Refresh tagging" onclick="refreshTagging()">Refresh tagging</button>
		<ul>
			<?php
			foreach($storeList as $key => $value){
	      		echo "<li>".$value["name"]."</li>";
	      	}
			?>
		</ul>
		<div id="dialog-form" title="Add Store">
		  <p class="validateTips">All form fields are required.</p>
		 
		  <form action="add_store.php" method="post" enctype="multipart/form-data" name="addstore" id="addstore">
		    <fieldset>
		      <label for="sname">Store Name</label>
		      <input type="text" name="sname" id="sname" value="" class="text ui-widget-content ui-corner-all">
		      <label for="stag">Default tag</label>
		      <select name="stag" id="stag" value="" class="text ui-widget-content ui-corner-all">
		      	<?php
		      	foreach($tagList as $key => $value){
		      		echo "<option value='".$value["id"]."'>".$value["name"]."</option>";
		      	}
		      	?>
		      </select>
		    </fieldset>
		  </form>
	</body>
</html>