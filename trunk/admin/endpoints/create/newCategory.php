<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";
?>
<fieldset class="ui-widget">
<legend>Add Category</legend>
<form action="./endpoints/process/insertCategory.php" class="ui-widget-content" onsubmit="if ($(this).valid()) {$(this).ajaxSubmit({target: '#adminContent'});} else {$('#submit').removeClass('ui-state-disabled').val('Save');} return false;">
<label for="name">Name: </label><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" /><br />
<label for="description">Description: </label><br /><textarea rows="4" cols="45" name="description" id="description" class="ui-widget-content ui-state-default" /><br />
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
<br /><input type="submit" value="Save" onclick="$(this).addClass('ui-state-disabled').val('Saving...');" name="submit" id="submit" class="ui-widget-content ui-state-default" /><input type="button" value="Cancel" onclick="$('#adminContent').html('');" class="ui-widget-content ui-state-default" />
</form>