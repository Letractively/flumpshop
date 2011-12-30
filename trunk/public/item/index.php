<?php
define("PAGE_TYPE", "item");
require_once("../preload.php");
$item = new Item($_GET['id']);
$page_title = $item->getName();

//Meta
$_KEYWORDS = $item->getKeywords();
$_DESCRIPTION = preg_split('/(\.)|(\\n)|<\/p>/', $item->getDesc(), 2);
$_DESCRIPTION = str_replace('\'', '', strip_tags($_DESCRIPTION[0]));
require_once dirname(__FILE__) . "/../header.php";

ob_start(); //Template Buffer
if ($config->isNode('cache', 'item' . intval($item->getID()))) { //intval removes zerofill
    echo $config->getNode('cache', 'item' . intval($item->getID()));
} else {
    $category = new Category($item->getCategory());
    echo "<a href='" . $config->getNode('paths', 'root') . "'>Home</a> -> " . $category->getBreadcrumb() . " -> " . $item->getName();
    echo '<div id="notice"></div>';
    echo $item->getDetails("FULL");
    ?><script type="text/javascript">
        document.id = <?php echo intval($_GET['id']); ?>;
        //Full size image dialog
        function openImageViewer(imageID) {
    	$("#dialog").html("<img src='<?php echo $config->getNode('paths', 'root'); ?>/item/imageProvider.php?id="+document.id+"&amp;image="+imageID+"&amp;size=full' style='max-width: 800px; max-height: 500px;' />")
    	document.getElementById("dialog").title = "View Image";
    	$("#dialog").dialog({height: "auto", width: "auto", resizable: true, position: "left", buttons: {"Close": function() {$(this).dialog("destroy");}}});
        }
    </script><?php
    //Cache the data now
    $config->setNode("cache", "item" . intval($item->getID()), ob_get_contents());
}

templateContent($_GET['id']);

require_once dirname(__FILE__) . "/../footer.php";
?>