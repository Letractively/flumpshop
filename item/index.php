<?php require_once("../preload.php");
$item = new Item($_GET['id']);
$page_title = $item->getName();
require_once dirname(__FILE__)."/../header.php";
?>
  <?php
    $category = new Category($item->getCategory());
    echo "<a href='".$config->getNode('paths','root')."'>Home</a> -> ".$category->getBreadcrumb()." -> ".$item->getName();
	?>
  	<div id="notice"></div>
    <?php if (isset($_GET['reductionHappened'])) {?><div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>The price reduction has been scheduled.</div><?php }
	if (isset($_GET['imageHappened'])) {?><div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>The image has been added.</div><?php }?>
    <div id="itemData" class="ui-widget-content ui-state-default ui-corner-all">
		<?php
        echo $item->getDetails("FULL", isset($_GET['modify']) && $_GET['modify'] == "true");
        ?>
    </div>
<script type="text/javascript">
document.saving = "<div class='ui-state-highlight ui-corner-all'><span class='ui-icon ui-icon-refresh'></span>Saving Data...</div>";
document.modify = <?php echo intval(isset($_GET['modify']) && $_GET['modify'] == "true"); ?>;
document.id = <?php echo intval($_GET['id']); ?>;
document.adminAuth = <?php if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) echo "false"; else echo "true";?>;
document.updateURL = "<?php echo $config->getNode('paths','root')."/item/update.php?pid=".$_GET['id'];?>";
if (document.modify) {
	if (document.adminAuth == true) {
	   $('#itemTitle').editable(document.updateURL,
																	 {style: "display: inline;",
																	 cssclass: "ui-widget",
																	 submit: "Save",
																	 cancel: "Cancel",
																	 tooltip: "Click to edit Item Name",
																	 width: '75%',
																	 indicator: document.saving});
	   $('#itemPrice').editable(document.updateURL,
																	 {style: "display: inline;",
																	 cssclass: "ui-widget",
																	 submit: "Save",
																	 cancel: "Cancel",
																	 tooltip: "Click to edit Item Price",
																	 indicator: document.saving});
	   $('#itemDesc').editable(document.updateURL,
																	{type: "textarea",
																	cols: 70,
																	cssclass: "ui-widget",
																	submit: "Save",
																	cancel: "Cancel",
																	tooltip: "Click to edit Item Description",
																	indicator: document.saving});
	   $('#itemStock').editable(document.updateURL,
																	 {style: "display: inline;",
																	 cssclass: "ui-widget",
																	 submit: "Save",
																	 cancel: "Cancel",
																	 tooltip: "Click to edit Item Stock availibility",
																	 indicator: document.saving});
	   $('#notice').html("<div class='ui-state-highlight ui-corner-all'><span class='ui-icon ui-icon-pencil'></span>Edit mode enabled. Click a value to edit it.</div>");
	} else {
		$('#notice').html("<div class='ui-state-highlight ui-corner-all'><span class='ui-icon ui-icon-pencil'></span>Failed to authenticate session. Edit mode disabled.</div>");
	}
}

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
	$("#dialog").html("<img src='<?php echo $config->getNode('paths','root');?>/item/imageProvider.php?id="+document.id+"&image="+imageID+"&size=full' style='max-width: 800px; max-height: 500px;' />")
	document.getElementById("dialog").title = "View Image";
	$("#dialog").dialog({height: 650, width: 800, draggable: true, resizable: true, position: "top", buttons: {"Close": function() {$(this).dialog("destroy");}}});

}
</script>
<?php require_once dirname(__FILE__)."/../footer.php"; ?>