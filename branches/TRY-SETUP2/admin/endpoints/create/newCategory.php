<?php
require_once dirname(__FILE__)."/../header.php";
?>
<div class="ui-widget-header">Add Category</div>
<form action="../process/insertCategory.php" method="post" class="ui-widget-content" onsubmit="if ($(this).valid()) {$(body).html(loadMsg('Saving Content...')); return true;} else return false;">
<label for="name">Name: </label><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" /><br />
<label for="description">Description: </label><br /><textarea rows="4" cols="45" name="description" id="description" class="ui-widget-content ui-state-default"></textarea><br />
<label for="parent">Parent: </label>
<select name="parent" id="parent" class="ui-widget-content">
	<option value="0" selected="selected">No Parent</option>
    <?php
	$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
	while ($row = $dbConn->fetch($result)) {
		$category = new Category($row['id']);
		echo "<option value='".$category->getID()."'>".$category->getFullName()."</option>";
	}
	?>
</select>
<br /><input type="submit" value="Save" name="submit" id="submit" style="font-size: 13px; padding: .2em .4em;" />
</form></body></html>