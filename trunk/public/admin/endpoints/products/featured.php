<?php
$USR_REQUIREMENT = "can_edit_pages";
require_once dirname(__FILE__)."/../header.php";
//First
$result = $dbConn->query("SELECT value FROM `stats` WHERE `key`='featuredItem1' LIMIT 1");
if ($dbConn->rows($result) == 0) {
	$id = "";
} else {
	$result = $dbConn->fetch($result);
	$id = $result['value'];
}
//Second
$result = $dbConn->query("SELECT value FROM `stats` WHERE `key`='featuredItem2' LIMIT 1");
if ($dbConn->rows($result) == 0) {
	$id2 = "";
} else {
	$result = $dbConn->fetch($result);
	$id2 = $result['value'];
}
?><div class="ui-widget-header">Featured Items</div>
<p>Flumpshop is capable of placing two images on the visitor home page, under the Featured Items heading. To set which Products are displayed, enter their Product Numbers below. If you don't know the product numbers, you can look them up using the edit item feature.</p>
<form action="processFeatured.php" method="get" class="ui-widget-content" onsubmit="loader('Updating...');">
<label for="featuredItem1">Featured Item ID 1: </label><input type="text" name="featuredItem1" id="featuredItem1" class="ui-state-default number" value="<?php echo $id;?>" /><br />
<label for="featuredItem2">Featured Item ID 2: </label><input type="text" name="featuredItem2" id="featuredItem2" class="ui-state-default number" value="<?php echo $id2;?>" /><br />
<input type="submit" class="ui-state-default" value="Save" />
</form></body></html>