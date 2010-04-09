<?php
require_once "../../../preload.php";
if (!acpusr_validate()) {
	header("HTTP/1.1 403 Forbidden");
	die($config->getNode("messages","adminDenied"));
}

$i = $_GET['id'];
?><td><label for="category_<?php echo $i;?>">Category <?php echo $i;?>: </label></td>
	<td><select name="category_<?php echo $i;?>" id="category_<?php echo $i;?>" class="ui-widget-content ui-state-default" onchange="updateFeatures();"><option value="">[Remove Category]</option><?php
	$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
	while ($row = $dbConn->fetch($result)) {
		$selected = "";
		$category = new Category($row['id']);
		echo "<option value='".$category->getID()."'$selected>".html_entity_decode(str_replace(">","&lt;",$category->getFullName()),ENT_QUOTES)."</option>\n";
	}
	echo "</select>";
?>