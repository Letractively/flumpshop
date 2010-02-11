<?php
require_once dirname(__FILE__)."/../preload.php";
echo '<td id="leftside"><table id="leftside_nav">';
//Echo Subcats AFTER table (Compliancy things)
$subcatstr = "";

$categories = $dbConn->query("SELECT id FROM `category` WHERE parent='0' AND enabled='1'");
while ($category = $dbConn->fetch($categories)) {
	$subcatNoJS = ""; //Displayed after each main category
	$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$category['id']."' AND enabled='1' ORDER BY `name` ASC");
    $cat = new Category($category['id']);
    if ($dbConn->rows($result) != 0) echo "<tr><td class='ui-corner-all' onclick='loadCat(\"".$category['id']."\");' id='cat".$category['id']."'><center><a class='navigation ui-widget' href='javascript:void(0);'>".ucwords(strtolower($cat->getName()))."</a></center></td></tr>";
	
	else echo "<tr><td class='ui-corner-all' id='cat".$category['id']."' onclick='window.location = \"".$cat->getURL()."\";'><center><a class='navigation ui-widget' href='".$cat->getURL()."'>".ucwords(strtolower($cat->getName()))."</a></center></td></tr>"; //No subcats
	//Subcat Menu
	$subcatstr .= "<table class='subcat ui-corner-right' id='subcat".$category['id']."'>";
	
	if ($dbConn->rows($result) == 0) {
		$subcatstr .= "<tr><td>There are no subcategories in this section.</td></tr>";
	}
	
	while ($subcat = $dbConn->fetch($result)) {
		$subCat = new Category($subcat['id']);
		$subcatstr .= "<tr><td class='ui-corner-right'><a class='subcat' href='".$subCat->getURL()."'>".ucwords(strtolower($subCat->getName()))."</a></td></tr>";
		$subcatNoJS .= "<a href='".$subCat->getURL()."'>".ucwords(strtolower($subCat->getName()))."</a><br />";
	}
	echo "<noscript><tr><td class='noscriptNav'>".$subcatNoJS."</td></tr></noscript>";
	$subcatstr .= "</table>";
}
echo "<tr><td style='background: none; cursor: normal; padding-top: 50px;'>".$config->getNode('messages','navAdvert')."</td></tr>";
?></table><?php echo $subcatstr; ?></td>