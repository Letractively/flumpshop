<?php
header("Cache-control: max-age:3600, must-revalidate, public");
require_once "../../../preload.php";
if (!acpusr_validate()) {
	header("HTTP/1.1 403 Forbidden");
	die($config->getNode("messages","adminDenied"));
}

$i = intval($_GET['id']);
?><td><label for="feature_<?php echo $i;?>">Choose a feature:</label></td>
<td><select name="feature_<?php echo $i;?>" id="feature_<?php echo $i;?>" class="ui-widget-content ui-state-default featureRef" onchange="updateFeatures();" unique="featureRef"><option value="">None (Cancel)</option><?php
	$result = $dbConn->query("SELECT id FROM `compare_features` ORDER BY `feature_name` ASC");
	while ($row = $dbConn->fetch($result)) {
		$feature = new Feature($row['id']);
		echo "<option value='".$feature->getID()."'>".$feature->getName()."</option>\n";
	}
	echo "</select></td>";
?>