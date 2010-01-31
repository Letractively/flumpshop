<?php
require_once dirname(__FILE__)."/../header.php";
?><div class="ui-widget-content">
<a href="javascript:" onclick="$('#newRateForm').toggle('fold');">New Rate</a>
<form action="../process/insertDeliveryRate.php" method="post" onsubmit="if ($(this).valid()) {$(body).html(loadMsg('Saving Content')); return true;} else return false;" name="newRateForm" id="newRateForm" class="ui-widget-content ui-helper-hidden">
	<input type="hidden" id="counter" name="counter" value="1">
    Weight From <input type="text" name="lowerBound" id="lowerBound" class="ui-widget-content required number" value="0.00" />kg To <input type="text" name="upperBound" id="upperBound" class="ui-widget-content required number" value="0.00" />kg<br />
    Costs &pound;<input type="text" name="price" id="price" class="ui-widget-content required number" value="0.00" /> Exc. VAT
    <h3 class="content">Applies to: </h3>
    <div id="countrySelectors"><?php require dirname(__FILE__)."/countrySelect.php"; ?><br /></div>
    <a href="javascript:void(0);" onclick="addCountrySelector();">Add Country</a>
    <br /><input type="submit" value="Add Rate" class="ui-widget-content" style="font-size: 12px;" />
    <div id="formErrors"></div>
    <!--Country Selection JS Functions are in admin/index.php-->
</form></div>
<script type="text/javascript">
function addCountrySelector() {
	var id = $('#counter').val();
	$.ajax({url: './countrySelect.php?id='+id, success: function(data, textStatus) {$("#countrySelectors").append(data+"<br />");}, cache: true});
	id++;
	$('#counter').val(id);
		}
</script>
<div class="ui-widget-header">Existing Rates</div><div class="ui-widget-content"><?php

//Existing Rates TODO: Improve layout
$result = $dbConn->query("SELECT * FROM `delivery` ORDER BY lowerbound ASC, upperbound ASC");

//4-D Array
/*0: Lower Boundary
1: Upper Boundary
2: Price
3: Array of Countries*/
$data = array();

while ($row = $dbConn->fetch($result)) {
	$data[$row['lowerbound']][$row['upperbound']][$row['price']][] = $row['country'];
}

echo "<table style='width: 100%;'><tr class='ui-state-active'><th>Lower Boundary</th><th>Upper Boundary</th><th>Price</th><th>Countries</th></tr>\n";
foreach ($data as $lowerBound => $array1) {
	//Lower Boundary
	$rows1 = 0;
	//Total must be recursive
	foreach (array_keys($array1) as $upperBound) {
		$rows1 += sizeof($array1[$upperBound]);
	}
	echo "<tr><td rowspan='$rows1'>$lowerBound kg</td>\n";
	foreach ($array1 as $upperBound => $array2) {
		//Upper Boundary
		$rows2 = sizeof($array2);
		echo "<td rowspan='$rows2'>$upperBound kg</td>\n";
		foreach ($array2 as $price => $array3) {
			//Price
			$rows3 = sizeof($array3);
			echo "<td rowspan='$rows3'>&pound;$price</td>\n";
			echo "<td>";
			foreach ($array3 as $country) {
				//Countries
				echo "$country, ";
			}
			echo "</td></tr>\n<tr>";
		}
	}
	echo "</tr>";
}

echo "</table>";
?><style>
td, th {
	border: 1px solid #000;
	line-height: 2;
}
</style>
<script type="text/javascript">
$('tr:odd').addClass('ui-state-focus').css("color","#c43131");
$('tr:even').addClass('ui-state-focus');
$('th').parent().addClass("ui-state-active").removeClass("ui-state-disabled");
</script>