<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";
?>
<fieldset class="ui-widget">
<legend>Add Product</legend>
<form action="./endpoints/process/insertItem.php" method="post" class="ui-widget-content" onsubmit="if ($(this).valid()) {document.getElementById('submit').disabled = 'disabled'; $(this).ajaxSubmit({target: '#adminContent'});} else {$('#submit').removeClass('ui-state-disabled').val('Save');} return false;">
<label for="name">Name: </label><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" /><br />
<label for="description">Description: </label><br /><textarea rows="10" cols="45" name="description" id="description" class="ui-widget-content ui-state-default required" /><br />
<label for="price">&pound;</label><input type="text" maxlength="11" value="0.00" name="price" id="price" class="ui-widget-content ui-state-default required" />
<label for="stock">Stock: </label><input type="text" maxlength="10" value="0" name="stock" id="stock" class="ui-widget-content ui-state-default required number" /><br />

<label for="weight">Weight (Kg): </label><input type="text" maxlength="8" value="0" name="weight" id="weight" class="ui-widget-content ui-state-default required number" />

<br /><label for="category">Category: </label>
<select name="category" id="category" class="ui-widget-content">
	<option value="0" selected="selected">Uncategorised</option>
    <?php
	$result = $dbConn->query("SELECT id FROM `category` WHERE `parent`>0 ORDER BY `parent` ASC");
	while ($row = $dbConn->fetch($result)) {
		$category = new Category($row['id']);
		echo "<option value='".$category->getID()."'>".$category->getFullName()."</option>";
	}
	?>
</select>

<br /><input type="submit" value="Save" onclick="$(this).addClass('ui-state-disabled').val('Saving...');" name="submit" id="submit" class="ui-widget-content ui-state-default" /><input type="button" value="Cancel" onclick="$('#adminContent').html('');" class="ui-widget-content ui-state-default" /><br />
</form>