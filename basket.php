<?php
define("PAGE_TYPE","basket");
require_once dirname(__FILE__)."/preload.php";
$page_title = $config->getNode('messages','basketHeader');
require_once dirname(__FILE__)."/header.php";
ob_start(); //Template Buffer
?><script type='text/javascript'>
function removeItem(id) {
	document.RemoveItemTempid = id;
	$("#dialog").html("<?php echo $config->getNode('messages','basketRemItemConf');?>");
	document.getElementById("dialog").title = "Remove Item";
	$("#dialog").dialog({buttons: {
							"Remove item": function() {window.location.href = "removeItem.php?id="+document.RemoveItemTempid;},
							Cancel: function() {$(this).dialog('destroy');}
							}
						});
}

function emptyBasket() {
	$("#dialog").html("<?php echo $config->getNode('messages','basketEmptyConf');?>");
	document.getElementById("dialog").title = "Empty Basket";
	$("#dialog").dialog({buttons: {
							"Empty": function() {window.location.href = "emptyBasket.php";},
							Cancel: function() {$(this).dialog('destroy');}
							}
						});
}

function editQuantity(id,quantity,maxQuantity) {
	$("#dialog").html("Enter Quantity (max. "+maxQuantity+"): <form id='editQuantFrm' action='editQuantity.php?id="+id+"' method='post'><input type='text' name='itemQuantity' id='itemQuantity' value='"+quantity+"' /></form>");
	document.getElementById("dialog").title = "Edit Quantity";
	$("#dialog").dialog({buttons: {
							"Update": function() {$('#editQuantFrm').submit();},
							Cancel: function() {$(this).dialog('destroy');}
							}
						});
}
</script>
<?php
if ($config->getNode('temp','crawler') == true) {echo $config->getNode('messages','crawler');} else {?><noscript><?php
echo $config->getNode('messages','noScript');
?></noscript><?php
	if (isset($_GET['item'])) {
		$pid = $_GET['item'];
		$basket->addItem($pid);
		echo "<script type='text/javascript'>window.location = 'basket.php';</script>";
	}
	//Notices
	if (isset($_GET['insufficientStock'])) {
		//Insufficient stock to increase quantity
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Sorry, there is insufficient stock to order that many items.</div>";
	}
	if (isset($_GET['stockUpdated'])) {
		//Quantity Updated
		echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-info'></span>The quantity has been updated.</div>";
	}
	if (isset($_GET['invalidParameter'])) {
		//Invalid input
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Invalid data.</div>";
	}
	debug_message(print_r($basket,true));
?><a href="javascript:history.go(-1);"><?php echo $config->getNode("messages","basketGoBackLink");?></a>
<div id="page_text">
<h3 id="page_title"><?php echo $config->getNode('messages','basketHeader');?></h3><?php
	if ($basket->getItems() != 0) {
		echo $basket->listItems();
		echo "<div class='ui-widget-content' onclick='$(this).addClass(\"ui-state-disabled\").html(\"".$config->getNode('messages','loading')."\"); window.location.href =\"checkout.php\";'><a href='checkout.php'>".$config->getNode('messages','basketLinkToCheckout')."<span class='ui-icon ui-icon-circle-arrow-e ui-icon-right'></span></a></div>";
	} else {
		echo "<div class='ui-corner-all ui-state-highlight'><span class='ui-icon ui-icon-info'></span>".$config->getNode("messages", "basketEmpty")."</div>";
	}
}
echo "</div>"; //End Page Content

templateContent();

require_once dirname(__FILE__)."/footer.php";
?>