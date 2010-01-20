<?php
$_PRINTDATA = false;
$ajaxProvider = true;
require_once dirname(__FILE__)."/../preload.php";

$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$_GET['id']."' AND enabled='1' ORDER BY `name` ASC");

if ($dbConn->rows($result) == 0) {
	echo "There are no subcategories in this section.";
}

while ($subcat = $dbConn->fetch($result)) {
	$subCat = new Category($subcat['id']);
	echo "<a class='subcat ui-corner-right' href='".$subCat->getURL(),"'>".ucwords(strtolower($subCat->getName()))."</a>";
}
?>