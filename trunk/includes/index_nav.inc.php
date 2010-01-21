<?php
require_once dirname(__FILE__)."/../preload.php";
echo '<td id="leftside"><div id="leftside_nav">';

$categories = $dbConn->query("SELECT id FROM `category` WHERE parent='0' AND enabled='1'");
while ($category = $dbConn->fetch($categories)) {
    $cat = new Category($category['id']);
    echo "<a class='navigation' id='cat".$category['id']."' onclick='loadCat(\"cat".$category['id']."\",\"".$cat->getAjaxURL()."\");'>".ucwords(strtolower($cat->getName()))."</a><div class='subcat ui-corner-right'><center><img src='".$config->getNode('paths','root')."/images/loading.gif' alt='Loading Image' /><br />Loading Content...</center></div>";
}
?></div></td>