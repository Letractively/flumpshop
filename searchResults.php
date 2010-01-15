<?php
require_once dirname(__FILE__)."/preload.php";

$query = $_GET['q'];
if ($query == "" || $query == "Search..." || $query == "%") {
	die("Please enter a search query.");
}
echo "<h3>Search Results for '$query':</h3>";
$query = str_replace("'","''",$query);

if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;

$resultFound = false;

//Direct ID Search
if (!preg_match("/[a-z]/",$query)) {
	$result = $dbConn->query("SELECT id FROM `products` WHERE id='$query' LIMIT 1");
	if ($dbConn->rows($result) == 1) {
		$item = $dbConn->fetch($result);
		$item = new Item($item['id']);
		$resultFound = true;
		?>
		<div class="ui-widget">
			<div class="ui-widget-header">Product #<?php echo $query;?></div>
			<div class="ui-widget-content"><?php echo "<a href='".$item->getURL()."'>".$item->getName()."</a>";?><br />
			<?php echo $item->getDesc();?>
			</div>
		</div>
		<?php
	}
}

//General Matches
$spacedNameQuery = str_replace(" ","%' OR name LIKE '%",$query);
$spacedDescQuery = str_replace(" ","%' OR description LIKE '%",$query);
$additions = "";
if (isset($_GET['cat'])) {
	$category = new Category($_GET['cat']);
	$cats = $category->getChildren(true);
	$additions .= " AND (category = '".$category->getID()."'";
	foreach ($cats as $cat) {
		$additions .= " OR category = '$cat'";
	}
	$additions .=")";
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>Showing results in ".$category->getName()." only.</div>";
}

$perpage = $config->getNode('pagination','searchPerPage');

$fullQuery = "SELECT id FROM `products` WHERE (name LIKE '%$spacedNameQuery%' OR description LIKE '%$spacedDescQuery%')".$additions;
$results = $dbConn->rows($dbConn->query($fullQuery));
$result = $dbConn->query($fullQuery." LIMIT ".$perpage*($page-1).",$perpage");
debug_message($fullQuery);
if ($dbConn->rows($result) != 0) {
	$highlight = explode(" ",$query);
	$resultFound = true;
	echo "Found <b>".$results." Items</b><br /><ol start='".($perpage*($page-1)+1)."'>";
	while ($row = $dbConn->fetch($result)) {
		$item = new Item($row['id']);
		?>
        <li><div><?php echo $item->getDetails("SEARCH",$highlight);?></div></li>
        <?php
	}
	echo "</ol>";
	$baseurl = "search.php?q=".$query;
	if (isset($_GET['cat'])) $baseurl.="&cat=".$_GET['cat'];
	echo paginate($page,intval($results/$perpage),$baseurl);
}

if (!$resultFound) {
	echo "No Results found.";
}
?>