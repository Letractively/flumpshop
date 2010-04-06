<?php
$USR_REQUIREMENT = "can_edit_products";
require_once dirname(__FILE__)."/../header.php";

$item = new Item(intval($_GET['id']));
?><div class="ui-widget-header">Edit Item</div>
<form action="../process/updateItem.php" method="post" class="ui-widget-content" enctype="multipart/form-data" onsubmit="if ($(this).valid()) loader(loadMsg('Saving Content. If you are uploading an image, this may take a few moments...'));">
<input type="hidden" name="id" id="id" value="<?php echo intval($_GET['id']);?>" /><?php
if (isset($_GET['return']) and $_GET['return'] == "report") {
	echo "<input type='hidden' name='return' id='return' value='".$_SERVER['HTTP_REFERER']."' />";
}
?><table>
<tr>
	<td><label for="sku">SKU: </label></td>
    <td><input type="text" maxlength="25" name="sku" id="sku" class="ui-widget-content ui-state-default required" value="<?php echo $item->getSKU();?>" /></td>
</tr>
<tr>
	<td><label for="name">Name: </label></td>
    <td><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" value="<?php echo $item->getName();?>" /></td>
</tr>
<tr>
	<td><label for="description">Description: </label></td>
    <td><textarea rows="10" cols="45" name="description" id="description" class="ui-widget-content ui-state-default required"><?php echo $item->getDesc();?></textarea></td>
</tr>
<tr>
	<td><label for="price">Price &pound;</label></td>
    <td><input type="text" maxlength="11" name="price" id="price" class="ui-widget-content ui-state-default required" value="<?php echo $item->getPrice();?>" /></td>
</tr>
<tr>
	<td><label for="cost">Cost to Produce &pound;</label></td>
    <td><input type="text" maxlength="11" name="cost" id="cost" class="ui-widget-content ui-state-default required" value="<?php echo $item->getCost();?>" /></td>
</tr>
<tr>
	<td><label for="stock">Stock: </label></td>
    <td><input type="text" maxlength="10" name="stock" id="stock" class="ui-widget-content ui-state-default required number" value="<?php echo $item->getStock();?>" /></td>
</tr>
<tr>
	<td><label for="weight">Weight (Kg): </label></td>
    <td><input type="text" maxlength="8" name="weight" id="weight" class="ui-widget-content ui-state-default required number" value="<?php echo $item->getWeight();?>" /></td>
</tr>
<tr>
	<td><label for="category">Category: </label></td>
   <td><select name="category" id="category" class="ui-widget-content ui-state-default required" onchange="updateFeatures();"><?php
	$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
	while ($row = $dbConn->fetch($result)) {
		$selected = "";
		if ($row['id'] == $item->getCategory(0)) {$selected=" selected='selected'";}
		$category = new Category($row['id']);
		echo "<option value='".$category->getID()."'$selected>".str_replace(">","&lt;",$category->getFullName())."</option>\n";
	}
		?></select></td>
</tr>
<tr>
	<td colspan="2" id="category_feature_fields"><!--Placeholder element for features--></td>
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
function updateFeatures() {
	$('#category_feature_fields').html("<img src='../../../images/loading.gif' />Retrieving feature list...");
	$('#category_feature_fields').load('featureFields.php?itemid=<?php echo $item->getID();?>&id='+$('#category').val());
}
updateFeatures();
</script>
</body></html>