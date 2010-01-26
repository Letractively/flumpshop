<?php
require_once dirname(__FILE__)."/../preload.php";
echo '<td id="leftside"><div id="leftside_nav">';

$categories = $dbConn->query("SELECT id FROM `category` WHERE parent='0' AND enabled='1'");
while ($category = $dbConn->fetch($categories)) {
    $cat = new Category($category['id']);
    echo "<a class='navigation ui-widget ui-corner-all' id='cat".$category['id']."' onclick='loadCat(\"cat".$category['id']."\");'>".ucwords(strtolower($cat->getName()))."</a>";
	//Subcat Menu
	echo "<div class='subcat ui-corner-right'>";
	$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$category['id']."' AND enabled='1' ORDER BY `name` ASC");
	
	if ($dbConn->rows($result) == 0) {
		echo "There are no subcategories in this section.";
	}
	
	while ($subcat = $dbConn->fetch($result)) {
		$subCat = new Category($subcat['id']);
		echo "<a class='subcat ui-corner-right' href='".$subCat->getURL(),"'>".ucwords(strtolower($subCat->getName()))."</a>";
	}
	echo "</div>";
}
?></div></td>