<?php
require_once dirname(__FILE__)."/../../../preload.php";
$result = $dbConn->query("SELECT value FROM `stats` WHERE `key`='featuredItem' LIMIT 1");
if ($dbConn->rows($result) == 0) {
	$id = "";
} else {
	$result = $dbConn->fetch($result);
	$id = $result['value'];
}
?><form action="./endpoints/process/saveFeatured.php" method="get" onsubmit="$('#adminContent').html(loadMsg('Saving Content...'));$(this).ajaxSubmit({target: '#adminContent'}); return false;">
<label for="featuredItem">Featured Item ID: </label><input type="text" name="featuredItem" id="featuredItem" class="ui-state-default" value="<?php echo $id;?>" /><br />
<input type="submit" value="Save" />
</form>