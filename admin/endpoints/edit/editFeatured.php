<?php
require_once dirname(__FILE__)."/../../../preload.php";
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
?><form action="./endpoints/process/saveFeatured.php" method="get" onsubmit="$('#adminContent').html(loadMsg('Saving Content...'));$(this).ajaxSubmit({target: '#adminContent'}); return false;">
<label for="featuredItem1">Featured Item ID: </label><input type="text" name="featuredItem1" id="featuredItem1" class="ui-state-default" value="<?php echo $id;?>" /><br />
<label for="featuredItem2">Featured Item ID: </label><input type="text" name="featuredItem2" id="featuredItem2" class="ui-state-default" value="<?php echo $id2;?>" /><br />
<input type="submit" value="Save" />
</form>