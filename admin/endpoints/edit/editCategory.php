<?php
require_once dirname(__FILE__)."/../header.php";

if (!isset($_GET['id'])) {
	$count = $dbConn->rows($dbConn->query("SELECT id FROM `category`"));
	$perPage = $config->getNode("pagination","editItemsPerPage");
	if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
	
	$result = $dbConn->query("SELECT id FROM `category` ORDER BY name ASC LIMIT ".(($page-1)*$perPage).",$perPage");
	
	$pageStart = (($page-1)*$perPage)+1;
	$pageEnd = $page*$perPage; if ($pageEnd > $count) $pageEnd = $count;
	
	echo "<div class='ui-widget-header'>Edit Category (Showing ".$pageStart."-".$pageEnd." of $count)</div>";
	echo "<div class='ui-widget-content'>";
	while ($row = $dbConn->fetch($result)) {
		$category = new Category($row['id']);
		if ($category->enabled == false) $disabled = " <strong>(Hidden)</a><span class='iconbutton' onclick='disabledDialog();'></span></strong>"; else $disabled = "</a>";
		echo "<a href='javascript:void(0);' onclick='$(body).html(loadMsg(\"Loading Content...\")).load(\"editCategory.php?id=".$category->getID()."\");'>".$category->getFullName().$disabled."<br />";
	}
	
	$paginator = new Paginator();
	echo $paginator->paginate($page,$perPage,$count,"editCategory.php");
	echo "</div>";
} else {
	$category = new Category(intval($_GET['id']));
	?><div class="ui-widget-header">Edit Category</div><div class="ui-widget-content">
    <a href="../process/disableCategory.php?cid=<?php echo $category->getID();?>" onclick="$(body).html(loadMsg('Hiding Category...'));">Hide Category</a>
    <form action="../process/updateCategory.php?id=<?php echo $category->getID();?>" method="post" class="ui-widget-content" onsubmit="if ($(this).valid()) {$(body).html(loadMsg('Saving Content...')); return true;} else return false;">
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
    <br /><input type="submit" value="Save" name="submit" id="submit" class="ui-state-default ui-corner-all" style="font-size: 13px; padding: .2em .4em;" />
    <input type="hidden" name="catid" id="catid" value="<?php echo $category->getID(); ?>" />
    </form></div><?php
}
?><div class="ui-helper-hidden" id="hiddenDialog" title="Category Hidden">This category is hidden and won't appear in any public lists.</div>
<script type="text/javascript">
$('#hiddenDialog').dialog({autoOpen: false});
$('.iconbutton').button({icons: {primary: 'ui-icon-help'}}).width('16px');
function disabledDialog() {$('#hiddenDialog').dialog('open');}
</script>
</body></html>