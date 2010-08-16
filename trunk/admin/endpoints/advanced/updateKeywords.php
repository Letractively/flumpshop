<?php
/**
Automatically generate any blank keywords
*/
$requires_tier2 = true;
//Record if any failed to generate keywords
$failer = false;
require_once dirname(__FILE__).'/../header.php';
//Load the autokeyword generator
loadclass('autokeyword');
echo '<h1>Automatic Keyword Generation Results</h1>';

//Check for products without keywords
$result = $dbConn->query('SELECT id FROM `products` WHERE keywords IS NULL OR keywords=""');

if ($dbConn->rows($result) == 0) {
	//All products are already configured
	echo("<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>All products already have keywords.</div>");
} else {
	echo "<table class='ui-widget-content' style='width:600px;'>";
	echo '<tr><th>Product Name</th><th>Generated Keywords</th></tr>';
	while ($item = $dbConn->fetch($result)) {
		$item = new Item($item['id']);
		$item->setKeywords(autokeyword::get_keywords($item->getName().' '.$item->getDesc()));
		echo '<tr><td>'.$item->getName().'</td>';
		if ($item->getKeywords() !== '') {
			echo '<td>'.$item->getKeywords().'</td>';
		} else {
			//Insufficient description, store failure and output notice
			$failer = true;
			echo '<td class="ui-state-error"><a href="#failerhelp"><span class="ui-icon ui-icon-alert"></span>Insufficient Data.</a></td>';
		}
		echo '</tr>';
	}
	echo "</table>";
}

//Check for categories without keywords
//Order by ID Desc so that child categories are more likely to be generated first (TODO: Improve this)
$result = $dbConn->query('SELECT id FROM `category` WHERE keywords IS NULL OR keywords="" ORDER BY id DESC');

if ($dbConn->rows($result) == 0) {
	//All categories are already configured
	echo("<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>All categories already have keywords.</div>");
} else {
	echo "<table class='ui-widget-content' style='width:600px;'>";
	echo '<tr><th>Category Name</th><th>Generated Keywords</th></tr>';
	while ($category = $dbConn->fetch($result)) {
		//Categories uses a more complex algorithm
		/**
		Use the category name and description, along with the keywords generated for child categories (generate these first)
		as well as the Name/Desc from products directly in this category (Not Inherited Ones)
		*/
		$category = new Category($category['id']);
		//Basic Data
		$contentString = $category->getName().' '.$category->getDescription();
		//Items
		foreach ($category->getProducts() as $item) {
			$item = new Item($item);
			$contentString .= ' '.$item->getDesc().' '.$item->getName();;
		}
		unset($item);
		//Subcategories
		foreach ($category->getChildren(false) as $child) {
			$child = new Category($child);
			$contentString .= ' '.$child->getKeywords();
		}
		unset($child);
		$category->setKeywords(autokeyword::get_keywords($contentString));
		echo '<tr><td>'.$category->getName().'</td>';
		if ($category->getKeywords() != '') {
			echo '<td>'.$category->getKeywords().'</td>';
		} else {
			//Insufficient description, store failure and output notice
			$failer = true;
			echo '<td class="ui-state-error"><a href="#failerhelp"><span class="ui-icon ui-icon-alert"></span>Insufficient Data.</a></td>';
		}
		echo '</tr>';
	}
	echo "</table>";
}

if ($failer) {
	echo '<br /><br /><a name="failerhelp"></a><div class="ui-state-highlight"><span class="ui-icon ui-icon-info"></span>One or more objects could not have keywords generated as the description is not detailed enough. Please improve the description for these, as it will both allow the system to generate keywords, and improve the quality of the content on your site.<br /><br />It is a good rule of thumb that if I can\'t generate keywords, then the description is not detailed enough.</div>';
}
?></body></html>