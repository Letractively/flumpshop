<?php
define("PAGE_TYPE", "category");
require_once dirname(__FILE__)."/../header.php";
if (isset($_GET['id'])) $cid = $_GET['id']; else $cid = 1; //Get Category ID
if (isset($_GET['sort'])) $sort = strtolower(htmlentities($_GET['sort'],ENT_QUOTES)); else $sort = "name";
//Get sort field (name, id, popularity)
if (isset($_GET['sortType'])) $sortType = strtoupper(htmlentities($_GET['sortType'])); else $sortType = "PRODUCTS";
//Where the field is (is it in the products table, or is it a feature)
if (isset($_GET['order'])) $order = strtoupper(htmlentities($_GET['order'],ENT_QUOTES)); else $order = "ASC";
//Get sort order (ascending/descending)
$category = new Category($cid);
$page_title = $category->getName();
echo "<a href='".$config->getNode('paths','root')."'>Home</a> -> ".$category->getBreadcrumb();
?><div id="page_text"><h3 id="page_title"><?php echo $category->getName();?></h3>
    <p><?php echo $category->getDescription();?></p><?php
	
	//Sort order
	echo "<div id='sorter'>";
	echo "<h4 id='sort-header' class='ui-widget-header'>Features<sup>labs</sup></h4>";
	echo "<div id='sort-content' class='ui-widget-content'>";
	
	//Determine sort text
	if ($sortType == "PRODUCTS") {
		$sortText = ucwords($sort);
		if ($order == "DESC") {
			$sortText  .=  " (Descending)";
		} else {
			$sortText .= " (Ascending)";
		}
	} elseif ($sortType == "FEATURES") {
		$feature = new Feature(intval($sort));
		$sortText = $feature->getName();
		if ($order == "DESC") {
			$sortText  .=  " (Descending)";
		} else {
			$sortText .= " (Ascending)";
		}
	}
	
	echo "<strong>Listing items by: ".$sortText."</strong><br />";
	
    ?><a href='javascript:' onclick='$("#sort-content-hidden").toggle("blind");'>Click to change...</a>
	<div id='sort-content-hidden' style="display:none;">
		<!--Standard Sort Modes-->
		<ul id='sort-types-list'>
			<li><a href='<?php echo "?id=".$cid;?>&amp;sort=name'>Name</a>&nbsp;&middot;&nbsp;</li>
			<li><a href='<?php echo "?id=".$cid;?>&amp;sort=id'>Date Added</a>&nbsp;&middot;&nbsp;</li><?php
			//Calculated Feature Sort Modes
			$result = $dbConn->query("SELECT feature_id FROM `category_feature` WHERE category_id = ".$category->getID());
			while ($row = $dbConn->fetch($result)) {
				$temp = new Feature($row['feature_id']);
				echo "<li><a href='?id=".$cid."&amp;sortType=features&amp;sort=".$temp->getID()."'>".$temp->getName()."</a>&nbsp;&middot;&nbsp;</li>";
				unset($temp);
			}
		?></ul>
		<!--Search Form-->
		<form action="<?php echo $config->getNode('paths','root');?>/search.php" method="get" id="category_search_form">
			<input type="text" name="q" id="q" value="Search <?php echo $category->getName();?>" onfocus="if (this.value == 'Search <?php echo $category->getName();?>') this.value = '';" onblur="if (this.value == '') this.value = 'Search <?php echo $category->getName();?>'" />
			<input type="hidden" name="cat" id="cat" value="<?php echo $category->getID(); ?>" />
			<input type="submit" value="Go!" />
		</form>
	</div><?php //End sort-content-hidden
	$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$category->getID()."' ORDER BY `name` ASC");
	if ($dbConn->rows($result) != 0) {
		?><h4><?php echo $config->getNode("messages","subcatHeader");?></h4><ul class='list_subcat'><?php
		while ($row = $dbConn->fetch($result)) {
			$subCat = new Category($row['id'],"noparent"); //Noparent addition reduces unnecessary queries
			echo "<li><a href='".$subCat->getURL()."'>".$subCat->getName()."</a></li>";
		}
		echo "</ul>";
	}
	
	echo "</div><!-- End Page Text -->";
	
	//Get Page number/items per page
	$perPage = $config->getNode("pagination","categoryPerPage");
	if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
	$start = ($page-1)*$perPage;
	
	//Special query. Even I don't understand it. Leave it alone.
	$criteria = "";
	foreach ($category->getChildren() as $child) {
		$criteria .= " OR catid='$child'";
	}
	$totalItems = $dbConn->rows($dbConn->query("SELECT products.id FROM `products` WHERE id IN (SELECT itemid FROM `item_category` WHERE (catid='".$category->getID()."'".$criteria.")) AND active=1"));
	
	if ($sortType == "PRODUCTS") {
		//This is simple - Just order by the field
		$sql = "SELECT products.id FROM `products` WHERE id IN (SELECT itemid FROM `item_category` WHERE (catid='".$category->getID()."'".$criteria.")) AND active=1 ORDER BY $sort $order LIMIT $start,$perPage";
	} elseif ($sortType == "FEATURES") {
		//This isn't so easy - it needs table joins and shiz
		//Get the data type (different table for each)
		$result = $dbConn->query("SELECT data_type FROM `compare_features` WHERE id=".intval($sort)." LIMIT 1");
		$row = $dbConn->fetch($result);
		$dataType = $row['data_type'];
		//Seriously, WTF does this do. Meh, it works. NO TOUCHY.
		$sql = "SELECT * FROM (
							   SELECT products.id, t1.value
								FROM products LEFT JOIN (SELECT * FROM `item_feature_$dataType` WHERE feature_id = ".intval($sort).") AS t1
								ON products.id = t1.item_id
								) AS t2
				ORDER BY value $order
				LIMIT $start,$perPage";
	}
	
	$itemsResult = $dbConn->query($sql);
	
	$num = $dbConn->rows($itemsResult);
	
	if ($num == 0) {
		echo "<div id='cat_item_container'>There are no products in this category.</div>";
	} else {
		echo "<div id='cat_item_container'>";
		//Only one item - Redirect
		if ($num == 1 && $page == 1) {
			$item = $dbConn->fetch($itemsResult);
			$item = new Item($item['products.id']);
			header("Location: ".$item->getURL());
			echo $item->getDetails('CATEGORY');
		}
		//Print all items
		while ($item = $dbConn->fetch($itemsResult)) {
			$item = new Item($item['products.id']);
			echo $item->getDetails('CATEGORY');
		}
		echo "</div>"; //End item container
		
		$paginator = new Paginator();
		
		if ($config->getNode('server','rewrite')) {
			echo $paginator->paginate($page,$perPage,$totalItems,"",NULL,true);
		} else {
			echo $paginator->paginate($page,$perPage,$totalItems,"?id=".$category->getID());
		}
	}
require_once dirname(__FILE__)."/../footer.php";
?>