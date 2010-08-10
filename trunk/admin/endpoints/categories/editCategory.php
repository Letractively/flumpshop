<?php
$USR_REQUIREMENT = "can_edit_categories";
require_once dirname(__FILE__)."/../header.php";

loadClass('Feature');

$category = new Category(intval($_GET['id']));
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

<h1>Edit Category</h1>
<form action="../categories/processEditForm.php" method="post" class="ui-widget-content" enctype="multipart/form-data" onsubmit="if ($(this).valid()) loader('Updating Category','Saving Data');">
<input type="hidden" name="id" id="id" value="<?php echo intval($_GET['id']);?>" /><?php
if (isset($_GET['return']) and $_GET['return'] == "report") {
	echo "<input type='hidden' name='return' id='return' value='".$_SERVER['HTTP_REFERER']."' />";
}
?><a href="../categories/deleteCategory.php?id=<?php echo $category->getID();?>">Delete Category</a><br /><table>
<tr>
	<td width="150">Category Number: </td>
	<td><?php echo $category->getID();?></td>
	<td>This is the unique number that I have assigned to this category, and cannot be changed. You can use this to quickly edit this category.</td>
</tr>
<tr>
	<td><label for="name">Name: </label></td>
    <td><input type="text" maxlength="75" name="name" id="name" class="ui-widget-content ui-state-default required" value="<?php echo $category->getName();?>" /></td>
	<td>This is the name of the category. Try to keep it unique but short.</td>
</tr>
<tr>
	<td><label for="description">Description: </label></td>
    <td><textarea rows="10" cols="45" name="description" id="description" class="ui-widget-content ui-state-default required"><?php echo $category->getDescription();?></textarea></td>
	<td>Describe the products that are in the category here.</td>
</tr>
<tr>
	<td><label for="parent">Parent: </label></td>
	<td><select name="parent" id="parent" class="ui-widget-content">
        <option value="0">No Parent (Top-level Category)</option>
        <?php
        $result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
        while ($row = $dbConn->fetch($result)) {
            $parentCategory = new Category($row['id']);
			$selected = "";
			if ($category->getParent() == $row['id']) $selected = " selected='selected'";
            echo "<option value='".$parentCategory->getID()."'$selected>".$parentCategory->getFullName()."</option>";
        }
        ?>
    </select>
	</td>
</tr>
<tr class="ui-widget-header">
	<td colspan="2">Features</td>
	<td style="text-align:right"><a href="javascript:" onclick="$('.featureRow').toggle()">Show/Hide</a></td>
</tr>
<tr class="featureRow">
	<td colspan="3">Features allow you to add custom fields to products, such as dimensions or capacity. No only does this help you with managing this information, it also allows visitors to the website to compare and sort based on these features. To use Features, you must first create some using the <a href="javascript:alert('TODO');">Manage Features</a> section.<br /><br /><strong>Features currently applied to this category:</strong></td>
</tr><?php
	$features = $category->getFeatures(false);
	if (empty($features)) {
		?><tr class="featureRow"><td colspan="2">There aren't any features applied to this category yet.</td></tr><?php
	} else {
		foreach ($category->getFeatures(false) as $featureID) {
			$feature = new Feature($featureID);
		?><tr class="featureRow">
			<td><?php echo $feature->getName();?></td>
			<td><input type="hidden" class="featureRef" value="<?php echo $featureID;?>" /><a href="javascript:" onclick="removeFeature(<?php echo $featureID;?>);">Remove This Feature</a></td>
		</tr><?php
		}
	}
?><tr class="featureRow">
	<td colspan="2"><button onclick='addFeature(this);return false;'>Add another feature...</button></td>
	<td>It is possible to add an infinite number of features to a category.</td>
</tr><?php
	if ($category->getParent() != 0) {
	?><tr class="featureRow">
		<td colspan="2"><strong>Inherited Features</strong></td>
		<td rowspan="2">These features are applied by the category's parent. They cannot be removed here.</td>
	</tr><?php
		$parent = new Category($category->getParent(),"noparent");
		$features = $parent->getFeatures(true);
		if (empty($features)) {
			?><tr class="featureRow"><td colspan="2">There are no inherited features for this category.</td></tr><?php
		} else {
			foreach ($features as $featureID) {
				$feature = new Feature($featureID);
			?><tr class="featureRow">
				<td><input type="hidden" class="featureRef" value="<?php echo $featureID;?>" /><?php echo $feature->getName();?></td>
			</tr><?php
				unset($feature);
			}
		}
		unset($parent);
	}
	?>
<tr class="featureRow"><td colspan="3"><div class="ui-state-highlight"><span class="ui-icon ui-icon-info"></span>Please note that changing the Parent Category above does not update the inherited features list until you save the changes.</div><hr /></td></tr>
</table>
<input type="submit" value="Save Category" name="submit" id="submit" style="font-size: 13px; padding: .2em .4em;" />
</form>
<script type="text/javascript">
document.next_num=0;

function addFeature(caller) {
	$(caller).parent().parent().before("<tr id='newFeature_"+document.next_num+"'><td><img src='../../../images/loading.gif' />Loading content...</td></tr>");
	$('#newFeature_'+document.next_num).load("../categories/featureSelect.php?id="+document.next_num);
	document.next_num++;
}
</script>
</body></html>