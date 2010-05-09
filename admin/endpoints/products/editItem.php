<?php
$USR_REQUIREMENT = "can_edit_products";
require_once dirname(__FILE__)."/../header.php";

$item = new Item(intval($_GET['id']));
?>
<script type="text/javascript" src="../../tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('#description').tinymce({
			// Location of TinyMCE script
			script_url : '../../tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,advlink,iespell,inlinepopups,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,forecolor",
			theme_advanced_buttons2 : "search,|,bullist,numlist,|,blockquote,|,link,unlink,code,|,preview,|",
			theme_advanced_buttons3 : "hr,removeformat,|,sub,sup,|,charmap,iespell,media,cleanup",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "../../../style/cssProvider.php?theme=<?php echo $config->getNode("site","theme");?>&sub=main",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			media_external_list_url : "lists/media_list.js"
		});
	});
</script>

<img src='../../../item/imageProvider.php?id=<?php echo $item->getID();?>' style='float:right;width:100px' /><h1>Edit Item</h1>
<form action="../products/processEditForm.php" method="post" class="ui-widget-content" enctype="multipart/form-data" onsubmit="if ($(this).valid()) loader('Updating Product<br />Please be patient if you are adding an image','Saving Data');">
<input type="hidden" name="id" id="id" value="<?php echo intval($_GET['id']);?>" /><?php
if (isset($_GET['return']) and $_GET['return'] == "report") {
	echo "<input type='hidden' name='return' id='return' value='".$_SERVER['HTTP_REFERER']."' />";
}
?><a href="deleteItem.php?id=<?php echo $item->getID();?>">Delete Product</a><br /><table>
<tr>
	<td>Item Number: </td>
	<td><?php echo $item->getID();?></td>
	<td>This is the unique number that I have assigned to this product, and cannot be changed. You can use this to quickly access this product.</td>
</tr>
<tr>
	<td><label for="sku">SKU: </label></td>
    <td><input type="text" maxlength="25" name="sku" id="sku" class="ui-widget-content ui-state-default" value="<?php echo $item->getSKU();?>" /></td>
	<td>I don't actually use this, but it is helpful to keep a record of a code that is used by you or your supplier to identify the product.</td>
</tr>
<tr>
	<td><label for="name">Name: </label></td>
    <td><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" value="<?php echo $item->getName();?>" /></td>
	<td>This is the name of the product. Try to keep it unique but short.</td>
</tr>
<tr>
	<td><label for="description">Description: </label></td>
    <td><textarea rows="10" cols="45" name="description" id="description" class="ui-widget-content ui-state-default required"><?php echo str_replace(array("<br>","<br />"),"",$item->getDesc());?></textarea></td>
	<td>Flaunt the product here, doing everything you can to sell it to visitors.</td>
</tr>
<tr>
	<td><label for="price">Price &pound;</label></td>
    <td><input type="text" maxlength="11" name="price" id="price" class="ui-widget-content ui-state-default required" value="<?php echo $item->getPrice();?>" /></td>
	<td>The price of the product, in GBP.</td>
</tr>
<tr>
	<td><label for="cost">Cost to Produce &pound;</label></td>
    <td><input type="text" maxlength="11" name="cost" id="cost" class="ui-widget-content ui-state-default required" value="<?php echo $item->getCost();?>" /></td>
	<td>The amount it costs you to produce or obtain the product, which I can use for statistical purposes.</td>
</tr>
<tr>
	<td><label for="stock">Stock: </label></td>
    <td><input type="text" maxlength="10" name="stock" id="stock" class="ui-widget-content ui-state-default required number" value="<?php echo $item->getStock();?>" /></td>
	<td>This is the number of the product you currently have available to you. This product cannot be purchased by visitors if the stock is 0.</td>
</tr>
<tr>
	<td><label for="weight">Weight (Kg): </label></td>
    <td><input type="text" maxlength="8" name="weight" id="weight" class="ui-widget-content ui-state-default required number" value="<?php echo $item->getWeight();?>" /></td>
	<td>The weight of the product is used to calculate the delivery costs.</td>
</tr><?php
$i = 0;
	foreach ($item->getCategories() as $catID) {
	?><tr>
		<td><label for="category_<?php echo $i;?>">Choose a Category: </label></td>
		<td><select name="category_<?php echo $i;?>" id="category_<?php echo $i;?>" class="ui-widget-content ui-state-default" onchange="updateFeatures();"><option value="">[Remove Category]</option><?php
		$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
		while ($row = $dbConn->fetch($result)) {
			$selected = "";
			if ($row['id'] == $catID) {$selected=" selected='selected'";}
			$category = new Category($row['id']);
			echo "<option value='".$category->getID()."'$selected>".html_entity_decode(str_replace(">","&lt;",$category->getFullName()),ENT_QUOTES)."</option>\n";
		}
		?></select></td>
	</tr><?php
	$i++;
	}
?><tr>
	<td>&nbsp;</td>
	<td><button onclick='addCategory(this); return false;'>Add another category...</button></td>
	<td>It is possible to add an infinite number of categories to a product.</td>
</tr>
<tr>
	<td colspan="2" id="category_feature_fields"><!--Placeholder element for features--></td>
	<td>Please note that changing any categories will cause the Features section to update, losing any changes you have made.</td>
</tr>
<tr>
    <td><label for="image">Image: </label></td>
    <td><input type="file" name="image0" id="image" class="ui-widget-content ui-state-default" /></td>
	<td>Adding an image here won't replace the image that's already there, but will be added to the images available.</td>
</tr>
</table>
<input type="submit" value="Save" name="submit" id="submit" style="font-size: 13px; padding: .2em .4em;" />
</form>
<script type="text/javascript">
$('form').validate();
document.next_num=<?php echo $i;?>;
function updateFeatures() {
	str = "";
	for (i=0;i<document.next_num;i++) str = str+$('#category_'+i).val()+",";
	$('#category_feature_fields').html("<img src='../../../images/loading.gif' />Retrieving feature list...");
	$('#category_feature_fields').load('featureFields.php?itemid=<?php echo $item->getID();?>&id='+str);
}
updateFeatures();

function addCategory(caller) {
	$(caller).parent().parent().before("<tr id='newField_"+document.next_num+"'><td><img src='../../../images/loading.gif' />Loading content...</td></tr>");
	$('#newField_'+document.next_num).load("../products/categorySelect.php?id="+document.next_num);
	document.next_num++;
}
</script>
</body></html>