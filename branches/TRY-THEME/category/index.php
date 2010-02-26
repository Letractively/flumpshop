<?php
require_once dirname(__FILE__)."/../header.php";
if (isset($_GET['id'])) $cid = $_GET['id']; else $cid = 1;
$category = new Category($cid);
$page_title = $category->getName();
echo "<a href='".$config->getNode('paths','root')."'>Home</a> -> ".$category->getBreadcrumb();
?><div id="page_text"><h3 id="page_title"><?php echo $category->getName();?></h3>
    <p><?php echo $category->getDescription();?></p>
    <form action="<?php echo $config->getNode('paths','root');?>/search.php" method="get" id="category_search_form">
        <input type="text" name="q" id="q" value="Search <?php echo $category->getName();?>" onfocus="if (this.value == 'Search <?php echo $category->getName();?>') this.value = '';" onblur="if (this.value == '') this.value = 'Search <?php echo $category->getName();?>'" />
        <input type="hidden" name="cat" id="cat" value="<?php echo $category->getID(); ?>" />
        <input type="submit" value="Go!" />
    </form><?php
	if ($dbConn->rows($dbConn->query("SELECT id FROM `category` WHERE parent='".$category->getID()."'")) != 0) {
		?><h4><?php echo $config->getNode("messages","subcatHeader");?></h4><ul class='list_subcat'><?php
		$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$category->getID()."' ORDER BY `name` ASC");
		while ($row = $dbConn->fetch($result)) {
			$subCat = new Category($row['id']);
			echo "<li><a href='".$subCat->getURL()."'>".$subCat->getName()."</a></li>";
		}
		echo "</ul>";
	}
	echo "</div><!-- End Page Text -->";
	$criteria = "";
	foreach ($category->getChildren() as $child) {
		$criteria .= " OR category='$child'";
	}
	$items = $dbConn->query("SELECT id FROM `products` WHERE (category='".$category->getID()."'".$criteria.") AND active=1");
	$num = $dbConn->rows($items);
	
	$perPage = $config->getNode("pagination","categoryPerPage");
	if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
	
	$items = $dbConn->query("SELECT id FROM `products` WHERE (category='".$category->getID()."'$criteria) AND active=1 ORDER BY name ASC LIMIT ".($page-1)*$perPage.",".$perPage);
	
	if ($dbConn->rows($items) == 0) {
		echo "<div id='item_container'>There are no products in this category.</div>";
	} else {
		echo "<div id='item_container'>";
		//Only one item - Redirect
		if ($dbConn->rows($items) == 1 && $page == 1) {
			$item = $dbConn->fetch($items);
			$item = new Item($item['id']);
			header("Location: ".$item->getURL());
			echo $item->getDetails('CATEGORY');
		}
		//Print all items
		while ($item = $dbConn->fetch($items)) {
			$item = new Item($item['id']);
			echo $item->getDetails('CATEGORY');
		}
		
		$paginator = new Paginator();
		
		if ($config->getNode('server','rewrite')) {
			echo $paginator->paginate($page,$perPage,$num,"",NULL,true);
		} else {
			echo $paginator->paginate($page,$perPage,$num,"?id=".$category->getID());
		}
		echo "</div>";
	}
require_once dirname(__FILE__)."/../footer.php";
?>