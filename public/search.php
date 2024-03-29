<?php
define("PAGE_TYPE","search");
require_once "preload.php";
loadClass('Paginator');

$page_title = $config->getNode("messages","searchHeader");
require_once dirname(__FILE__)."/header.php";

ob_start(); //Template Buffer

echo '<h2 id="page_title">'.$config->getNode("messages","searchHeader").'</h2>';

//Get search query
if (isset($_GET['q'])) $query = htmlentities($_GET['q']); elseif (isset($_GET['searchfield'])) $query = $_GET['searchfield']; else $query = "";
?><form action="search.php" method="get">
  <input type="text" name="searchfield" class="ui-state-default" value="<?php echo $_GET['q'];?>" onfocus="if (this.value == 'Search...') this.value = '';" onblur="if (this.value == '') this.value = 'Search...';" />
  <input type="submit" value="Go!" class="ui-state-default" />
</form>
<div id="searchResults"><?php
	//Prevent sql injection
	$query = htmlentities($query,ENT_QUOTES);
	//Filter out magic characters
	$query = str_replace(array(".","%"),"",$query);
	
	//Filter out invalid queries
	if ($query == "" || $query == "Search") {
		echo "Please enter a search query.";
	} else {
		echo "<h3>Search Results for '$query':</h3>";
		//Remove whitespace and escape apostrophes
		$trimmed = trim(str_replace("'","''",$query));
		
		//Get current page
		if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
		
		//Track whether any results have been found
		$resultFound = false;
		
		//Direct ID Search
		if (preg_match("/[0-9]/",$query)) { //Check just number
			$result = $dbConn->query("SELECT id FROM `products` WHERE id='$query' AND active=1 LIMIT 1");
			if ($dbConn->rows($result) == 1) {
				//Found an item with that ID
				$item = $dbConn->fetch($result);
				$item = new Item($item['id']);
				$resultFound = true;
				?><div class="ui-widget">
					<div class="ui-widget-header">Product #<?php echo $query;?></div>
					<div class="ui-widget-content"><?php echo "<a href='".$item->getURL()."'>".$item->getName()."</a>";?><br /><?php
					echo $item->getDesc();
					?></div>
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
				//Recursion
				$cat = new Category($cat);
				foreach ($cat->getChildren(true) as $newCat) {
					$cats[] = $newCat;
				}
			}
			$additions .= ")";
			echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>Showing results in ".$category->getName()." only.</div><table>";
		}
		
		$perpage = $config->getNode('pagination','searchPerPage');
		
		if ($config->getNode('database','type') !== 'mysql') {
			echo 'Sorry! Flumpshop does not support searching systems using an SQLite backend.';
			include './footer.php';
			exit;
		} else {
			$fullQuery = 'SELECT id FROM `products` WHERE MATCH(name,description) AGAINST ("'.$trimmed.'" IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) AND active=1'.$additions;
		}
		$count = 'SELECT COUNT(*) FROM `products` WHERE MATCH(name,description) AGAINST ("'.$trimmed.'" IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) AND active=1'.$additions;
		$count = $dbConn->query($count);
		$count = $dbConn->fetch($count);
		$results = $count[0];
		$result = $dbConn->query($fullQuery." LIMIT ".$perpage*($page-1).",$perpage");
		if ($dbConn->rows($result) != 0) {
			$highlight = explode(" ",$query);
			$resultFound = true;
			//Keep track of what result number this is
			$i = ($perpage*($page-1)+1);
			
			echo "Found <b>".$results." Items</b><br /><table>";
			while ($row = $dbConn->fetch($result)) {
				$item = new Item($row['id']);
				?>
				<tr><td><?php echo $i;?></td><td class="result_container"><div class="result"><?php echo $item->getDetails("SEARCH",$highlight);?></div></td></tr>
				<?php
				//Increment the result counter
				$i++;
			}
			echo "</table>";
			$baseurl = "search.php?q=".$query;
			if (isset($_GET['cat'])) $baseurl.="&cat=".$_GET['cat'];
			$paginate = new Paginator();
			echo $paginate->paginate($page,$perpage,$results,$baseurl);
		}
		
		if (!$resultFound) {
			echo "No Results found.";
		}
		echo "</div>";
	}
	
templateContent();
	
require_once dirname(__FILE__)."/footer.php";?>