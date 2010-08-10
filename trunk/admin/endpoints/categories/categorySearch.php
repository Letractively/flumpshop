<?php
$USR_REQUIREMENT = "can_edit_categories";
require_once dirname(__FILE__)."/../header.php";
loadClass('Paginator');

if (isset($_GET['filter'])) $criteria = " AND name LIKE '%".$_GET['filter']."%'"; else $criteria = "";

$criteria .= " AND enabled = 1";

$count = $dbConn->rows($dbConn->query("SELECT id FROM `category` WHERE id>0".$criteria));
$perPage = $config->getNode("pagination","editItemsPerPage");
if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;

if (isset($_GET['status'])) echo $_GET['status'];

$result = $dbConn->query("SELECT id FROM `category` WHERE id>0".$criteria." ORDER BY name ASC LIMIT ".(($page-1)*$perPage).",$perPage");
?><div class='ui-widget-header'>Edit Category</div>
<div class='ui-widget-content'><p>Choose an category below to edit.</p>
<form action='../categories/categorySearch.php' method="GET">
<input type="text" name="filter" id="filter" class="ui-state-default" value="<?php if (isset($_GET['filter'])) echo $_GET['filter'];?>" />
<input type="submit" value="Search" style="width: 100px; height: 30px; font-size: 13px;" /><br />
</form><?php
while ($row = $dbConn->fetch($result)) {
	$category = new Category($row['id']);
	echo "<a href='editCategory.php?id=".$row['id']."'>".$category->getName()."</a><br />";
}

if (isset($_GET['filter'])) $prefix = "categorySearch.php?filter=".$_GET['filter']; else $prefix = "categorySearch.php";

$paginator = new Paginator();
echo $paginator->paginate($page,$perPage,$count,$prefix);
?></div></div>
<script>$('#filter').autocomplete({source: 'ajax_categorySearch.php'});</script>
</body></html>