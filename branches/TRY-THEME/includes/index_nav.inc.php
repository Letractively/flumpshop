<?php
require_once dirname(__FILE__)."/../preload.php";
echo "<ul id='categories'>";

$categories = $dbConn->query("SELECT id FROM `category` WHERE parent='0' AND enabled='1'");
while ($category = $dbConn->fetch($categories)) {
	$subcatNoJS = ""; //Displayed after each main category
	$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$category['id']."' AND enabled='1' ORDER BY `name` ASC");
    $cat = new Category($category['id']);
    echo "<li onclick='window.location = \"".$cat->getURL()."\";'><a class='category' href='".$cat->getURL()."'>".ucwords(strtolower($cat->getName()))."</a>";
	
	//Subcat Menu
	if ($dbConn->rows($result) != 0) {
		echo "<ul class='subcategory_container'>";
	
		while ($subcat = $dbConn->fetch($result)) {
			$subCat = new Category($subcat['id']);
			echo "<li onclick='window.location = \"".$subCat->getURL()."\";'><a class='subcategory_link' href='".$subCat->getURL()."'>".ucwords(strtolower($subCat->getName()))."</a></li>";
		}
		echo "</ul></li>";
	}
}
echo "</ul>";
echo "<div id='category_advert'>".$config->getNode('messages','navAdvert')."</div>";
?>