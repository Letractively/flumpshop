<?php
require_once dirname(__FILE__)."/../preload.php";
echo '<td id="leftside"><table id="leftside_nav">';
//Echo Subcats AFTER table (Compliancy things)
$subcatstr = "";

$categories = $dbConn->query("SELECT id FROM `category` WHERE parent='0' AND enabled='1'");
while ($category = $dbConn->fetch($categories)) {
    $cat = new Category($category['id']);
    echo "<tr><td class='ui-corner-all' onclick='loadCat(".$category['id'].");' id='cat".$category['id']."'><a class='navigation ui-widget' href='javascript:void(0);'>".ucwords(strtolower($cat->getName()))."</a></td></tr>";
	//Subcat Menu
	$subcatstr .= "<table class='subcat ui-corner-right' id='subcat".$category['id']."'>";
	$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$category['id']."' AND enabled='1' ORDER BY `name` ASC");
	
	if ($dbConn->rows($result) == 0) {
		$subcatstr .= "<tr><td>There are no subcategories in this section.</td></tr>";
	}
	
	while ($subcat = $dbConn->fetch($result)) {
		$subCat = new Category($subcat['id']);
		$subcatstr .= "<tr><td class='ui-corner-right'><a class='subcat' href='".$subCat->getURL()."'>".ucwords(strtolower($subCat->getName()))."</a></td></tr>";
	}
	$subcatstr .= "</table>";
}
?></table><?php echo $subcatstr; ?></td>