<?php require_once("../preload.php");
$item = new Item($_GET['id']);
$page_title = $item->getName();
define("PAGE_TYPE","item");
require_once dirname(__FILE__)."/../header.php";

    $category = new Category($item->getCategory());
    echo "<a href='".$config->getNode('paths','root')."'>Home</a> -> ".$category->getBreadcrumb()." -> ".$item->getName();
	echo '<div id="notice"></div>';
	echo $item->getDetails("FULL");
	
?><script type="text/javascript">
document.id = <?php echo intval($_GET['id']); ?>;
function reduceItem() {
	$("#dialog").html("<form action='<?php echo $config->getNode('paths','root');?>/item/createReduction.php' method='post' name='reducePriceForm' id='reducePriceForm'><table><tr><td><label for='reducedPrice'>Reduced Price:</label></td><td><input type='text' name='reducedPrice' id='reducedPrice' style='width: 110px;' value='"+$('#itemPrice').html()+"' /></td></tr><tr><td><label for='validDate'>Valid From:</label></td><td><input type='text' name='validDate' id='validDate' style='width: 110px;' /></td></tr><tr><td><label for='expiresDate'>Expires:</label></td><td><input type='text' name='expiresDate' id='expiresDate' style='width: 110px;' /></td></tr></table><input type='hidden' name='itemID' id='itemID' value='<?php echo $item->getID();?>' /></form>");
	$("#validDate").datepicker({minDate: +0, dateFormat: "dd/mm/yy", showButtonPanel: true});
	$("#expiresDate").datepicker({minDate: +0, dateFormat: "dd/mm/yy", showButtonPanel: true});
	$("#ui-datepicker-div").css("z-index",2006); 
	document.getElementById("dialog").title = "Create Offer";
	$("#dialog").dialog({buttons: {
							"Create Offer": function() {$('#reducePriceForm').submit();},
							Cancel: function() {$(this).dialog('destroy');}
							}
						});
}
//Full size image dialog
function openImageViewer(imageID) {
	$("#dialog").html("<img src='<?php echo $config->getNode('paths','root');?>/item/imageProvider.php?id="+document.id+"&amp;image="+imageID+"&amp;size=full' style='max-width: 800px; max-height: 500px;' />")
	document.getElementById("dialog").title = "View Image";
	$("#dialog").dialog({height: "auto", width: "auto", resizable: true, position: "left", buttons: {"Close": function() {$(this).dialog("destroy");}}});

}
</script><?php require_once dirname(__FILE__)."/../footer.php"; ?>