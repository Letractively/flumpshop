<?php
require_once dirname(__FILE__)."/../preload.php";
if (isset($_GET['id'])) $cid = $_GET['id']; else $cid = 1;
$category = new Category($cid);
$page_title = $category->getName();
require_once dirname(__FILE__)."/../header.php";
echo "<a href='".$config->getNode('paths','root')."'>Home</a> -> ".$category->getBreadcrumb();
?><h1 class="content"><?php echo $category->getName();?></h1>
    <p><?php echo $category->getDescription();?></p>
    <form action="<?php echo $config->getNode('paths','root');?>/search.php" method="get">
        <input type="text" name="q" id="q" class="ui-state-default" value="Search <?php echo $category->getName();?>" onfocus="if (this.value == 'Search <?php echo $category->getName();?>') this.value = '';" onblur="if (this.value == '') this.value = 'Search <?php echo $category->getName();?>'" />
        <input type="hidden" name="cat" id="cat" value="<?php echo $category->getID(); ?>" />
        <input type="submit" value="Go!" class="ui-state-default" />
    </form><?php
	if ($dbConn->rows($dbConn->query("SELECT id FROM `category` WHERE parent='".$category->getID()."'")) != 0) {
		?><h3>This section has the following subcategories:</h3><?php
		$result = $dbConn->query("SELECT id FROM `category` WHERE parent='".$category->getID()."' ORDER BY `name` ASC");
		while ($row = $dbConn->fetch($result)) {
			$subCat = new Category($row['id']);
			echo "<a href='".$subCat->getURL()."'>".$subCat->getName()."</a><br />";
		}
	}
	foreach ($category->getChildren() as $child) {
		$criteria .= " OR category='$child'";
	}
	$items = $dbConn->query("SELECT id FROM `products` WHERE category='".$category->getID()."' AND active=1".$criteria);
	$num = $dbConn->rows($items);
	
	$perPage = $config->getNode("pagination","categoryPerPage");
	if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
	
	$items = $dbConn->query("SELECT id FROM `products` WHERE category='".$category->getID()."'$criteria ORDER BY name ASC LIMIT ".($page-1)*$perPage.",".$perPage);
	
	if ($dbConn->rows($items) == 0) {
		echo "There are no products in this category.";
	} else {
		echo "\n<table style='width: 100%'>\n\t<tr>";
		if ($dbConn->rows($items) == 1) {
			$item = $dbConn->fetch($items);
			$item = new Item($item['id']);
			header("Location: ".$item->getURL());
			echo "\n\t\t<td>".$item->getDetails('CATEGORY')."\n\t\t</td>";
		}
		$i = 1;
		while ($item = $dbConn->fetch($items)) {
			echo "\n\t\t<td>";
			$item = new Item($item['id']);
			echo $item->getDetails('CATEGORY');
			echo "\n\t\t</td>\n";
			if (is_int($i/$config->getNode('viewItem','catCols'))) echo "\n\t</tr>\n\t<tr>";
			$i++;
		}
		echo "</tr></table>";
		
		$paginator = new Paginator();
		
		if ($config->getNode('server','rewrite')) {
			echo $paginator->paginate($page,$perPage,$num,"");
		} else {
			echo $paginator->paginate($page,$perPage,$num,"?id=".$category->getID());
		}
	}
require_once dirname(__FILE__)."/../footer.php";
?>