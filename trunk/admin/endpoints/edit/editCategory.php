<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";

if (!isset($_GET['id'])) {
	$count = $dbConn->rows($dbConn->query("SELECT id FROM `category`"));
	$perPage = $config->getNode("pagination","editItemsPerPage");
	if (isset($_GET['page'])) $page = $_GET['page']; else $page = 1;
	
	$result = $dbConn->query("SELECT id FROM `category` ORDER BY name ASC LIMIT ".(($page-1)*$perPage).",$perPage");
	
	while ($row = $dbConn->fetch($result)) {
		$category = new Category($row['id']);
		echo "<a href='javascript:void(0);' onclick='$(\"#adminContent\").html(loadingString).load(\"./endpoints/edit/editCategory.php?id=".$category->getID()."\");'>".$category->getFullName()."</a><br />";
	}
	
	$paginator = new Paginator();
	echo $paginator->paginate($page,$perPage,$count,"./endpoints/edit/editCategory.php","adminContent");
} else {
	$category = new Category(intval($_GET['id']));
	?>
    <fieldset class="ui-widget">
    <legend>Edit Category</legend>
    <a href="javascript:void(0);" onclick="$('#adminContent').html(loadMsg('Hiding Category...')).load('./endpoints/process/disableCategory.php?id=<?php echo $category->getID();?>');">Hide Category</a>
    <form action="./endpoints/process/updateCategory.php" method="post" class="ui-widget-content" onsubmit="if ($(this).valid()) {$(this).ajaxSubmit({target: '#adminContent'});} else {$('#submit').removeClass('ui-state-disabled').val('Save');} return false;">
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
    <br /><input type="submit" value="Save" onclick="$(this).addClass('ui-state-disabled').val('Saving...');" name="submit" id="submit" class="ui-widget-content ui-state-default" /><input type="button" value="Cancel" onclick="$('#adminContent').html('');" class="ui-widget-content ui-state-default" />
    <input type="hidden" name="catid" id="catid" value="<?php echo $category->getID(); ?>" />
    </form>
    <?php
}
?>