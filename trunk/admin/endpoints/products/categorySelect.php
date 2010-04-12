<?php
header("Cache-control: max-age:3600, must-revalidate, public");
require_once "../../../preload.php";
if (!acpusr_validate()) {
	header("HTTP/1.1 403 Forbidden");
	die($config->getNode("messages","adminDenied"));
}

$i = $_GET['id'];
?><label for="category_<?php echo $i;?>">Choose a category:
<select name="category_<?php echo $i;?>" id="category_<?php echo $i;?>" class="ui-widget-content ui-state-default" onchange="updateFeatures();"><option value="">Uncategorised</option><?php
	$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
	while ($row = $dbConn->fetch($result)) {
		$category = new Category($row['id']);
		echo "<option value='".$category->getID()."'>".html_entity_decode(str_replace(">","&lt;",$category->getFullName()),ENT_QUOTES)."</option>\n";
	}
	echo "</select></label>";
?>