<?php
/*
This is the home page of the application.
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

Debug($storeList);
?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript" src="fusioncharts-suite/assets/scripts/jquery.min.js"></script>
		<script type="text/javascript" src="fusioncharts-suite/js/fusioncharts.js"></script>
		<script type="text/javascript" src="fusioncharts-suite/js/themes/fusioncharts.theme.fint.js"></script>
		<script type="text/javascript" src="Flexigrid-master/js/flexigrid.pack.js"></script>
		<script type="text/javascript" src="jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
		<!--<link rel="stylesheet" type="text/css" href="fusioncharts-suite/assets/css/mainstyle.css">-->
		<link rel="stylesheet" type="text/css" href="jquery-ui-1.11.0.custom/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="Flexigrid-master/css/flexigrid.pack.css">
		
		<script>
		var months = {1:"Jan",2:"Feb",3:"Mar",4:"Apr",5:"May",6:"Jun",7:"Jul",8:"Aug",9:"Sep",10:"Oct",11:"Nov",12:"Dec"};
		var original_in='original_in';
		var original_out='original_out';
		var tdate='tdate';
		var ttype = 'ttype';
		var in_amount='in_amount';
		var out_amount='out_amount';
		var category='category';
		var description='description';
		var tagId='tag_id';
		var storeId='store_id';
		var inVal;
		var outVal;
		
		
		$(function(){
			if ( typeof String.prototype.startsWith != 'function' ) {
			  String.prototype.startsWith = function( str ) {
			    return this.substring( 0, str.length ) === str;
			  }
			};
			
			if ( typeof String.prototype.endsWith != 'function' ) {
			  String.prototype.endsWith = function( str ) {
			    return this.substring( this.length - str.length, this.length ) === str;
			  }
			};
			
			//initialize dates
		    var dStart = new Date();
		    dStart.setMonth(dStart.getMonth()-4);
			var dEnd = new Date();
			dEnd.setMonth(dEnd.getMonth()-1);
		    
		    $("#fromyear").val(dStart.getFullYear());
			$("#toyear").val(dEnd.getFullYear());
			$("#frommonth").val(dStart.getMonth()+1);
			$("#tomonth").val(dEnd.getMonth()+1);
			
			$("#go").click(function(){
				bind();
			});
			
			bind();
			
			$("#dialog-form").hide();
			$("#split-form").hide();
		});
		
		function bind(){
			//initialize chart
			getTagPieChart($("#fromyear").val().trim(),$("#toyear").val().trim(),$("#frommonth").val().trim(),$("#tomonth").val().trim());
			getChart();
			
			//initialize grid
			bindGrid("all",$("#frommonth").val().trim(),$("#fromyear").val().trim(),$("#tomonth").val().trim(),$("#toyear").val().trim());
		}
		
		function getChart(){
			
			$.getJSON( "fetch_data.php", {
											yearFrom: $("#fromyear").val().trim(),
											yearTo:$("#toyear").val().trim(),
											monthFrom:$("#frommonth").val().trim(),
											monthTo:$("#tomonth").val().trim()
										},
			function(data) {
				FusionCharts.ready(function () {
					var revenueChart = new FusionCharts({
						type: 'mscolumn2d',
						renderAt: 'chart-container',
						width: '500',
						height: '300',
						dataFormat: 'json',
						dataSource: data
					});
					revenueChart.render();
				});
			});
		}
		
		function getTagPieChart(yearFromVal,yearToVal,monthFromVal,monthToVal){
			$.getJSON( "pie_chart.php", {
											yearFrom: yearFromVal,
											yearTo: yearToVal,
											monthFrom: monthFromVal,
											monthTo: monthToVal,
											groupBy:tagId
										},
			function(data) {
				FusionCharts.ready(function () {
					var tagChart = new FusionCharts({
						type: 'pie2d',
						renderAt: 'pie-chart-tag',
						width: '700',
						height: '500',
						dataFormat: 'json',
						dataSource: data
					});
					tagChart.render();
				});
			});
		}
		
		function editSubmit(){
			var r = confirm("Are you sure you want to save?");
			if (r == true) {
			    $.ajax({
				  type: "POST",
				  url: $("#myedit").attr("action"),
				  data: $("#myedit").serialize(),
				  success: function(result){
				  		//$("#result").text(result);
				  		alert(result);
				  	},
				  dataType: "text"
				});
			}
		}
		
		function splitTransaction(){
			$("#split").val('1');
			$("#cancel-split").click(function(){
				$("#split").val('0');
				$("#split-form").hide();
			});
			$("#split-form").show();
		}
		
		function getColumnValueFromRowCell(cell,colName){
			return $($(cell).parent().parent().children("td[abbr="+colName+"]")[0]).text();
		}
		
		function editRow( celDiv, id ) {
		    $( celDiv ).dblclick( function() {
		    	$("#split").val('0');
		    	$("#record_id").val(id);
		    	$("#result").text("");
		    	
		    	var tdateVal = getColumnValueFromRowCell(this,tdate);
		    	$("#"+tdate).val(tdateVal);
		    	
		    	$("#dte").text($.datepicker.formatDate('MM dd, yy', $.datepicker.parseDate('yy-mm-dd',tdateVal)));
		    	
		    	var ttypeVal = getColumnValueFromRowCell(this,ttype);
		    	$("#"+ttype).val(ttypeVal);
		    	
		    	var inVal = getColumnValueFromRowCell(this,in_amount);
		    	$("#"+in_amount).val(inVal);
		    	$("#"+original_in).val(inVal);
		    	$("#"+in_amount).prop('disabled', false);
		    	$("#"+in_amount+"2").prop('disabled', false);
		    	if(inVal == '0'){
		    		$("#"+in_amount).prop('disabled', true);
		    		$("#"+in_amount+"2").val('0');
		    		$("#"+in_amount+"2").prop('disabled', true);
		    	}
		    	
		    	var outVal = getColumnValueFromRowCell(this,out_amount);
		    	$("#"+out_amount).val(outVal);
		    	$("#"+original_out).val(outVal);
		    	$("#"+out_amount).prop('disabled', false);
		    	$("#"+out_amount+"2").prop('disabled', false);
		    	if(outVal == '0'){
		    		$("#"+out_amount).prop('disabled', true);
		    		$("#"+out_amount+"2").val('0');
		    		$("#"+out_amount+"2").prop('disabled', true);
		    	}
		    	
		    	var categoryVal = getColumnValueFromRowCell(this,category);
		    	$("#"+category).val(categoryVal);
		    	$("#"+category+"2").val(categoryVal);
		    	
		    	var descriptionVal = getColumnValueFromRowCell(this,description);
		    	$("#"+description).val(descriptionVal);
		    	$("#"+description+"2").val(descriptionVal);
		    	
		    	var tagVal = getColumnValueFromRowCell(this,tagId);
		    	$("#"+tagId).val(tagVal);
		    	$("#"+tagId+"2").val(tagVal);
		    	
		    	var storeVal = getColumnValueFromRowCell(this,storeId);
		    	$("#"+storeId).val(storeVal);
		    	$("#"+storeId+"2").val(storeVal);
		    	
		        dialog = $( "#dialog-form" ).dialog({
			      autoOpen: false,
			      height: 450,
			      width: 370,
			      modal: true,
			      buttons: {
			      	"Save": editSubmit,
			      	"Split": splitTransaction,
			        "Close": function() {
			          dialog.dialog( "close" );
			        }
			      },
			      close: function() {dialog.dialog( "close" );}
			    });
			    $( "#dialog-form" ).dialog("open");
		    });
		}
		
		function getTransactions(type,month,year){
			bindGrid(type,month,year,month,year);
			getTagPieChart(year,year,month,month);
		}
		
		function getTransactionsBy(filterKey,filterValue){
			/*var item = "'"+filterValue+"'";
			var selectedValues = $("#selected-items-"+filterKey).val();
			if(selectedValues == '' || selectedValues == undefined){
				$("#selected-items-"+filterKey).val(item);
			}else if(!(selectedValues.indexOf(item) > -1)){
				$("#selected-items-"+filterKey).val(selectedValues+","+item);
			}else if(selectedValues.startsWith(item)){
				$("#selected-items-"+filterKey).val(selectedValues.replace(item,''));
				if($("#selected-items-"+filterKey).val().startsWith(",")){$("#selected-items-"+filterKey).val(selectedValues.substring(1));}
			}else{
				$("#selected-items-"+filterKey).val(selectedValues.replace(","+item,''));
			}
			
			alert($("#selected-items-"+filterKey).val());*/
		}
		
		function numberWithCommas(x) {
		    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
		
		function displayGridTotals(){
			if(inVal != null && inVal != undefined){
				var inamountDiv = $("th[abbr='in_amount'] > div");
				inamountDiv.html("<b style='color:green;'>IN</b> <b>(" + numberWithCommas(inVal) + ")</b>");
			}
			
			if(outVal != null && outVal != undefined){
				var outamountDiv = $("th[abbr='out_amount'] > div");
				outamountDiv.html("<b style='color:red;'>OUT</b> <b>(" + numberWithCommas(outVal) + ")</b>");
			}
		}
		
		function bindGrid(type,monthfrom,yearfrom,monthto,yearto){
			$(".flexigrid").remove();
			$( "#chart-container" ).after( "<div id=\"grid-container\" style=\"float: left;\"></div>" );
			$("#grid-container").flexigrid({
				url: 'get_transactions.php?type='+type+'&yearFrom='+yearfrom
											+'&yearTo='+yearto
											+'&monthFrom='+monthfrom
											+'&monthTo='+monthto,
				dataType: 'json',
				method: 'GET',
				colModel : [
					{display: 'Date', name : 'tdate', width : 60, sortable : true, align: 'center', process: editRow },
					{display: 'IN', name : 'in_amount', width : 70, sortable : true, align: 'left', process: editRow},
					{display: 'OUT', name : 'out_amount', width : 70, sortable : true, align: 'left', process: editRow},
					{display: 'Type', name : 'ttype', width : 50, sortable : true, align: 'left', process: editRow},
					{display: 'Category', name : 'category', width : 130, sortable : true, align: 'right', process: editRow},
					{display: 'Description', name : 'description', width : 150, sortable : true, align: 'right', process: editRow},
					{display: 'Tag', name : 'tag_name', width : 100, sortable : true, align: 'right', process: editRow},
					{display: 'TagId', name : 'tag_id', width : 100, sortable : true, align: 'right', process: editRow, hide: true},
					{display: 'Store', name : 'store_name', width : 100, sortable : true, align: 'right', process: editRow},
					{display: 'StoreId', name : 'store_id', width : 100, sortable : true, align: 'right', process: editRow, hide: true}
					],
				searchitems : [
					{display: 'Store', name : 'store_name'},
					{display: 'Tag', name : 'tag_name'},
					{display: 'Category', name : 'category'},
					{display: 'Description', name : 'description', isdefault: true}
					],
				sortname: "tdate",
				sortorder: "desc",
				usepager: true,
				title: 'Transactions: '+type+' ('+months[monthfrom]+' '+yearfrom+' till '+months[monthto]+' '+yearto+')',
				useRp: true,
				rp: 20,
				showTableToggleBtn: false,
				singleSelect: true,
				width: 850,
				height: 500,
				preProcess: function (data) {
					//read total numbers
                        inVal = data["inTotal"];
                        outVal = data["outTotal"];
                        
                        return data;
                    },
				onSuccess:displayGridTotals
			});
		}
		</script>
	</head>
	<body>
		<table>
			<tr>
				<td>
					<b>From:</b>
					<br>
					<label for="fromyear" style="float: left">Year:</label>
					<input name="fromyear" id="fromyear" type="text" value="" style="width: 70" />
					<br>
					<label for="frommonth" style="float: left">Month:</label>
					<input name="frommonth" id="frommonth" type="text" value="" style="width: 70" />
				</td>
				<td>
					<b>To:</b>
					<br>
					<label for="toyear" style="float: left">Year:</label>
					<input name="toyear" id="toyear" type="text" value="" style="width: 70" />
					<br>
					<label for="tomonth" style="float: left">Month:</label>
					<input name="tomonth" id="tomonth" type="text" value="" style="width: 70"/>
				</td>
				<td>
					<br><br>
					<input type="button" name="go" id="go" value="GO" />
				</td>
				<td>
					<br><br>
					<a target="_blank" href="stores.php">Manage Stores</a>
				</td>
				<td>
					<br><br>
					<a target="_blank" href="upload.php">Upload data</a>
				</td>
			</tr>
		</table>
		<div id="dialog-form" title="Edit Transaction">
		  <p class="validateTips">All form fields are required.</p>
		 
		  <form action="edit_transaction.php" method="post" enctype="multipart/form-data" name="myedit" id="myedit">
		    Date:<small id="dte"></small>
		    <fieldset>
		      <input type="hidden" name="record_id" id="record_id" value="" />
		      <input type="hidden" name="tdate" id="tdate" value="" />
		      <input type="hidden" name="ttype" id="ttype" value="" />
		      <input type="hidden" name="original_in" id="original_in" value="" />
		      <input type="hidden" name="original_out" id="original_out" value="" />
		      <label for="in_amount">IN</label>
		      <input type="text" name="in_amount" id="in_amount" value="" class="text ui-widget-content ui-corner-all" style="width: 60px">
		      <label for="out_amount">OUT</label>
		      <input type="text" name="out_amount" id="out_amount" value="" class="text ui-widget-content ui-corner-all" style="width: 60px">
		      <hr>
		      <label for="category">Category</label>
		      <input type="text" name="category" id="category" value="" class="text ui-widget-content ui-corner-all">
		      <br>
		      <label for="description">Description</label>
		      <input type="text" name="description" id="description" value="" class="text ui-widget-content ui-corner-all">
		      <br>
		      <label for="tag_id">Tag</label>
		      <select name="tag_id" id="tag_id" value="" class="text ui-widget-content ui-corner-all">
		      	<?php
		      	foreach($tagList as $key => $value){
		      		echo "<option value='".$value["id"]."'>".$value["name"]."</option>";
		      	}
		      	?>
		      </select>
		      <br>
		      <label for="store_id">Store</label>
		      <select name="store_id" id="store_id" value="" class="text ui-widget-content ui-corner-all">
		      	<?php
		      	foreach($storeList as $key => $value){
		      		echo "<option value='".$value["id"]."'>".$value["name"]."</option>";
		      	}
		      	?>
		      </select>
		      <!-- Allow form submission with keyboard without duplicating the dialog button -->
		      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		    </fieldset>
		    <fieldset id="split-form">
			      <legend>Transaction #2 <button type="button" id="cancel-split" name="cancel-split" >cancel split</button></legend>
			      <input type="hidden" name="split" id="split" value="0" />
			      <label for="in_amount2">IN</label>
			      <input type="text" name="in_amount2" id="in_amount2" value="" class="text ui-widget-content ui-corner-all" style="width: 60px">
			      <label for="out_amount2">OUT</label>
			      <input type="text" name="out_amount2" id="out_amount2" value="" class="text ui-widget-content ui-corner-all" style="width: 60px">
			      <hr>
			      <label for="category2">Category</label>
			      <input type="text" name="category2" id="category2" value="" class="text ui-widget-content ui-corner-all">
			      <br>
			      <label for="description2">Description</label>
			      <input type="text" name="description2" id="description2" value="" class="text ui-widget-content ui-corner-all">
			      <br>
			      <label for="tag_id">Tag</label>
			      <select name="tag_id2" id="tag_id2" value="" class="text ui-widget-content ui-corner-all">
			      	<?php
			      	foreach($tagList as $key => $value){
			      		echo "<option value='".$value["id"]."'>".$value["name"]."</option>";
			      	}
			      	?>
			      </select>
			      <br>
			      <label for="store_id2">Store</label>
			      <select name="store_id2" id="store_id2" value="" class="text ui-widget-content ui-corner-all">
			      	<?php
			      	foreach($storeList as $key => $value){
			      		echo "<option value='".$value["id"]."'>".$value["name"]."</option>";
			      	}
			      	?>
			      </select>
		    </fieldset>
		    <p id="result" name="result"></p>
		  </form>
		</div>
		<div id="pie-chart-tag" style="float: left;">Fusion Pie-Chart will render here</div>
		<div id="chart-container" style="float: left;">FusionCharts will render here</div>
	</body>
</html>
<?php
unset($tagList);
unset($storeList);
?>