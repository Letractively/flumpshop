<?php
require_once dirname(__FILE__)."/../header.php";
?><div class="ui-widget-header">Add Item</div>
<form action="../process/insertItem.php" method="post" class="ui-widget-content" onsubmit="if ($(this).valid()) {loader(loadMsg('Saving Content...')); return true;} else return false;">
<label for="name">Name: </label><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" /><br />
<label for="description">Description: </label><br /><textarea rows="10" cols="45" name="description" id="description" class="ui-widget-content ui-state-default required"></textarea><br />
<label for="price">&pound;</label><input type="text" maxlength="11" value="0.00" name="price" id="price" class="ui-widget-content ui-state-default required" />
<label for="stock">Stock: </label><input type="text" maxlength="10" value="0" name="stock" id="stock" class="ui-widget-content ui-state-default required number" /><br />

<label for="weight">Weight (Kg): </label><input type="text" maxlength="8" value="0" name="weight" id="weight" class="ui-widget-content ui-state-default required number" />

<br /><label for="category">Category: </label>
<select name="category" id="category" class="ui-widget-content">
<option value="0" selected="selected">Uncategorised</option><?php
	$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
	while ($row = $dbConn->fetch($result)) {
		$category = new Category($row['id']);
		echo "<option value='".$category->getID()."'>".str_replace(">","&lt;",$category->getFullName())."</option>\n";
	}
?></select>

<label for="number">Number to Create: </label><input type="text" value="1" name="number" id="number" class="ui-widget-content ui-state-default required number" /><br />

<br /><input type="submit" value="Save" name="submit" id="submit" style="font-size: 13px; padding: .2em .4em;" />
</form></body></html>