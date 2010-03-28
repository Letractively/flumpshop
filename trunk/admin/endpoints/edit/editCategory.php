<?php
$USR_REQUIREMENT = "can_edit_categories";
require_once dirname(__FILE__)."/../header.php";

if (!isset($_GET['id'])) {
	//No category selected yet, show list
	
	if (isset($_GET['filter'])) $criteria = " AND name LIKE '%".$_GET['filter']."%'"; else $criteria = "";
	
	$count = $dbConn->rows($dbConn->query("SELECT id FROM `category` WHERE id>0".$criteria." ORDER BY name"));
	$perPage = $config->getNode("pagination","editItemsPerPage");
	if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
	
	$result = $dbConn->query("SELECT id FROM `category` WHERE id>0".$criteria." ORDER BY name ASC LIMIT ".(($page-1)*$perPage).",$perPage");
	
	$pageStart = (($page-1)*$perPage)+1;
	$pageEnd = $page*$perPage; if ($pageEnd > $count) $pageEnd = $count;
	
	echo "<div class='ui-widget-header'>Edit Category (Showing ".$pageStart."-".$pageEnd." of $count)</div>";
	?><div class='ui-widget-content'>
<form action='editCategory.php' method="GET">
<input type="text" name="filter" id="filter" class="ui-state-default" value="<?php if (isset($_GET['filter'])) echo $_GET['filter'];?>" />
<input type="submit" value="Search" style="width: 100px; height: 30px; font-size: 13px;" /><br />
</form><?php
	while ($row = $dbConn->fetch($result)) {
		$category = new Category($row['id']);
		if ($category->enabled == false) $disabled = " <strong>(Hidden)</a><span class='iconbutton' onclick='disabledDialog();'></span></strong>"; else $disabled = "</a>";
		echo "<a href='javascript:void(0);' onclick='loader(loadMsg(\"Loading Content...\"));window.location = \"editCategory.php?id=".$category->getID()."\";'>".$category->getFullName().$disabled."<br />";
	}
	
	if (isset($_GET['filter'])) $prefix = "editCategory.php?filter=".$_GET['filter']; else $prefix = "editCategory.php";

	$paginator = new Paginator();
	echo $paginator->paginate($page,$perPage,$count,$prefix);

	echo "</div>";
} else {
	//Category selected, load edit form
	$category = new Category(intval($_GET['id']));
	?><div class="ui-widget-header">Edit Category</div><div class="ui-widget-content">
    <a href="../process/disableCategory.php?cid=<?php echo $category->getID();?>" onclick="$(body).html(loadMsg('Hiding Category...'));">Hide Category</a>
    <form action="../process/updateCategory.php?id=<?php echo $category->getID();?>" method="post" class="ui-widget-content" onsubmit="if ($(this).valid()) {loader(loadMsg('Saving Content...')); return true;} else return false;">
    <label for="name">Name: </label><input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" value="<?php echo $category->getName();?>" /><br />
    <label for="description">Description: </label><br /><textarea rows="4" cols="45" name="description" id="description" class="ui-widget-content ui-state-default"><?php echo $category->getDescription();?></textarea><br />
    <label for="parent">Parent: </label>
    <select name="parent" id="parent" class="ui-widget-content">
        <option value="0">No Parent</option>
        <?php
        $result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
        while ($row = $dbConn->fetch($result)) {
            $parentCategory = new Category($row['id']);
			$selected = "";
			if ($category->getParent() == $row['id']) $selected = " selected='selected'";
            echo "<option value='".$parentCategory->getID()."'$selected>".$parentCategory->getFullName()."</option>";
        }
        ?>
    </select>
	<h3>Features<sup>labs</sup></h3>
	<p>Features allows users to compare different products depending on different attributes that you define. Below are the attributes that are currently applicable to this catgeory.</p>
	<h4>Current Features</h4><?php
	//List current features applicable to this category
	$result = $dbConn->query("SELECT feature_id FROM `category_feature` WHERE category_id=".$category->getID());
	if ($dbConn->rows($result) == 0) {
		echo "<p>This category currently has no features applied to it.</p>";
	} else {
		echo "<ul>";
		while ($row = $dbConn->fetch($result)) {
			$feature = new Feature($row['feature_id']);
			echo "<li>".$feature->getName()." (".$feature->getDataType().")</li>";
		}
		echo "</ul>";
	}
	//Assign a new feature to this category
	?><h4>Add Feature</h4>
	<p>Select an attribute from the menu below to add it to the category specification.</p>
	<select name="new_feature" class="ui-state-default">
	<option selected="selected"></option><?php
	
	//Retrieve a list of features
	$result = $dbConn->query("SELECT id,feature_name FROM `compare_features`");
	if ($dbConn->rows($result) == 0) {
		//There are no features
		echo "<option disabled='disabled'>No features found.</option>";
	} else {
		//Create an option for each feature
		while ($row = $dbConn->fetch($result)) {
			echo "<option value='".$row['id']."'>".$row['feature_name']."</option>";
		}
	}
    ?></select><br /><input type="submit" value="Save" name="submit" id="submit" class="ui-state-default ui-corner-all" style="font-size: 13px; padding: .2em .4em;" />
    <input type="hidden" name="catid" id="catid" value="<?php echo $category->getID(); ?>" />
    </form></div><?php
}
?><div class="ui-helper-hidden" id="hiddenDialog" title="Category Hidden">This category is hidden and won't appear in any public lists.</div>
<script type="text/javascript">
$('#hiddenDialog').dialog({autoOpen: false});
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px');
function disabledDialog() {$('#hiddenDialog').dialog('open');}
$('#filter').autocomplete({source: 'searchCategory.php'});
</script>
</body></html>