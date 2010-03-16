<?php
$USR_REQUIREMENT = "can_add_products";
require_once dirname(__FILE__)."/../header.php";
?><div class="ui-widget-header">Add Item</div>
<form action="../process/insertItem.php" method="post" class="ui-widget-content" enctype="multipart/form-data" onsubmit="if ($(this).valid()) loader(loadMsg('Saving Content. If you are uploading an image, this may take a few moments...'));">
<table>
<tr>
	<td><label for="name">Name: </label></td>
    <td><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" /></td>
</tr>
<tr>
	<td><label for="description">Description: </label></td>
    <td><textarea rows="10" cols="45" name="description" id="description" class="ui-widget-content ui-state-default required"></textarea></td>
</tr>
<tr>
	<td><label for="price">Price &pound;</label></td>
    <td><input type="text" maxlength="11" value="0.00" name="price" id="price" class="ui-widget-content ui-state-default required" /></td>
</tr>
<tr>
	<td><label for="stock">Stock: </label></td>
    <td><input type="text" maxlength="10" value="0" name="stock" id="stock" class="ui-widget-content ui-state-default required number" /></td>
</tr>
<tr>
	<td><label for="weight">Weight (Kg): </label></td>
    <td><input type="text" maxlength="8" value="0" name="weight" id="weight" class="ui-widget-content ui-state-default required number" /></td>
</tr>
<tr>
	<td><label for="category">Category: </label></td>
   <td><select name="category" id="category" class="ui-widget-content ui-state-default required">
   		<option selected="selected" disabled="disabled"></option>
   		<option value="0">Uncategorised</option><?php
	$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
	while ($row = $dbConn->fetch($result)) {
		$category = new Category($row['id']);
		echo "<option value='".$category->getID()."'>".str_replace(">","&lt;",$category->getFullName())."</option>\n";
	}
		?></select></td>
</tr>
<tr>
	<td><label for="number">Repeat: </label></td>
    <td><input type="text" value="1" name="number" id="number" class="ui-widget-content ui-state-default required number" onchange="updateImageField();" /></td>
</tr>
<tr>
    <td><label for="image">Image: </label></td>
    <td><input type="file" name="image0" id="image" class="ui-widget-content ui-state-default" /></td>
</tr>
</table>
<input type="submit" value="Save" name="submit" id="submit" style="font-size: 13px; padding: .2em .4em;" />
</form>
<script>
$('form').validate();

//Recursive Image support
document.imageNo = 0;
function updateImageField() {
	while ($('#number').val() - 1 > document.imageNo) {
		document.imageNo++;
		$('table').append("<tr><td><label for='image"+document.imageNo+"'>Image "+(document.imageNo+1)+": </label></td><td><input type='file' name='image"+document.imageNo+"' id='image"+document.imageNo+"' class='ui-widget-content ui-state-default' /></td></tr>");
	}
}
</script>
</body></html>