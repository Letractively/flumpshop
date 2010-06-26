<?php
require_once "../../../../preload.php";
acpusr_validate() or die($config->getNode('messages','adminDenied'));

header("Cache-control: max-age=3600, must-revalidate, public");
header("Expires: ".date("D, d M Y H:i:s T",time()+(3600*24)));

$item = new Item(intval($_GET['id']));

if ($item->itemActive == 0) {
	echo json_encode(array("Invalid Item. [No longer available]",0,0,0));
} elseif ($item->getID() == -1) { //-1 means the item doesn't exist
	echo json_encode(array("Invalid Item. [Doesn't Exist]",0,0,0));
} elseif ($_GET['dialog'] == "false") {
	echo json_encode(array(
							html_entity_decode($item->getName()),
							$item->getPrice(),
							$item->getStock(),
							$item->getDeliveryCost()
							));
} else {
	//Called from dialog - less data needed
	echo json_encode(array(
							html_entity_decode($item->getName()),
							$item->getStock()
							));
}
?>