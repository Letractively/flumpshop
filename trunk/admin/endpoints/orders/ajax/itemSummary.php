<?php
require_once "../../../../preload.php";

if (!acpusr_validate('can_create_orders')) die($config->getNode('messages','adminDenied'));

$item = new Item(intval($_GET['id']));

echo "<strong>".$item->getName()."</strong>";
echo "<p>".$item->getDesc()."</p>";
echo "<p>Stock: ".$item->getStock()."</p>";
echo "<p>Price: &pound;".$item->getPrice()."</p>";
echo $item->getDetails("FEATURES");
?>
<button onclick="$('#item'+window.tempFindItemId+'ID').val('<?php echo $item->getID()?>');idKeyPress('item'+window.tempFindItemId+'ID');$('#dialog').dialog('destroy');">Add this item</button>