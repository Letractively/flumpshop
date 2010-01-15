<?php
require_once dirname(__FILE__)."/../../../preload.php";
?>
<a onclick="$('#newRateForm').toggle('fold');">New Rate</a>

<form action="../process/insertDeliveryRate.php" method="post" onsubmit="if ($(this).valid()) {$(this).ajaxSubmit({target: '#adminContent'});} return false;" name="newRateForm" id="newRateForm" class="ui-widget-content ui-helper-hidden">
	<input type="hidden" id="counter" name="counter" value="1">
    Weight From <input type="text" name="lowerBound" id="lowerBound" class="ui-widget-content required number" value="0.00" />kg To <input type="text" name="upperBound" id="upperBound" class="ui-widget-content required number" value="0.00" />kg<br />
    Costs &pound;<input type="text" name="price" id="price" class="ui-widget-content required number" value="0.00" /> Exc. VAT
    <h3 class="content">Applies to: </h3>
    <div id="countrySelectors"><?php require dirname(__FILE__)."/countrySelect.php"; ?><br /></div>
    <a href="javascript:void(0);" onclick="addCountrySelector();">Add Country</a>
    <br /><input type="submit" value="Add Rate" class="ui-widget-content" />
    <div id="formErrors"></div>
    <!--Country Selection JS Functions are in admin/index.php-->
</form>
<h3 class="content">Existing Rates</h3>
<?php

//Existing Rates TODO: Improve layout
$result = $dbConn->query("SELECT * FROM `delivery` ORDER BY lowerbound ASC, upperbound ASC");

$prevLower = -1.00;
$prevUpper = -1.00;
$prevPrice = -1.00;
$countryList = "";
while ($row = $dbConn->fetch($result)) {
	if (!($prevLower == $row['lowerbound'] and $prevUpper == $row['upperbound'])) {
		/***Boundaries have changed - new header***/
		//Commit Previous
		if ($prevLower != -1.00) { //Not if first cycle
			echo "&pound;".$prevPrice." -> ".$countryList."<br />";
			$countryList = "";
		}
		if ($row['lowerbound'] == $row['upperbound']) $header = $row['lowerbound']; else $header = $row['lowerbound']." - ".$row['upperbound'];
		echo "<h2 class='content'>$header Kg</h2>";
		$prevPrice = $row['price'];
		$prevLower = $row['lowerbound'];
		$prevUpper = $row['upperbound'];
	}
	if ($row['price'] == $prevPrice) {
		$countryList .= $row['country'].", ";
	} else {
		//New Price - Commit Current List and Start New One
		if ($prevLower != -1.00) {
			echo "&pound;".$prevPrice." -> ".$countryList."<br />";
			$countryList = $row['country'].", ";
			$prevPrice = $row['price'];
			$prevLower = $row['lowerbound'];
			$prevUpper = $row['upperbound'];
		}
	}
}
echo "&pound;".$prevPrice." -> ".$countryList."<br />";
?>