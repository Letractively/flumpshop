<?php
$USR_REQUIREMENT = "can_edit_products";
require_once dirname(__FILE__)."/../header.php";

if (isset($_GET['filter'])) $criteria = " AND name LIKE '%".$_GET['filter']."%'"; else $criteria = "";

$count = $dbConn->rows($dbConn->query("SELECT id FROM `products` WHERE id>0".$criteria));
$perPage = $config->getNode("pagination","editItemsPerPage");
if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;

$result = $dbConn->query("SELECT id FROM `products` WHERE id>0".$criteria." ORDER BY name ASC LIMIT ".(($page-1)*$perPage).",$perPage");
?><div class='ui-widget-header'>Edit Item</div>
<div class='ui-widget-content'><p>Choose an item below to edit. You will then be taken to the item page, where you can click any value to change it.</p>
<form action='editItems.php' method="GET">
<input type="text" name="filter" id="filter" class="ui-state-default" value="<?php if (isset($_GET['filter'])) echo $_GET['filter'];?>" />
<input type="submit" value="Search" style="width: 100px; height: 30px; font-size: 13px;" /><br />
</form><?php
while ($row = $dbConn->fetch($result)) {
	$item = new Item($row['id']);
	echo "<a href='".$item->getModifyURL()."'>".$item->getName()."</a><br />";
}

if (isset($_GET['filter'])) $prefix = "editItems.php?filter=".$_GET['filter']; else $prefix = "editItems.php";

$paginator = new Paginator();
echo $paginator->paginate($page,$perPage,$count,$prefix);
?></div></div>
<script>$('#filter').autocomplete({source: 'searchItems.php'});</script>
</body></html>