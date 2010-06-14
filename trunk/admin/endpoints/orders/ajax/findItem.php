<?php
$noPreValidate = true;
$USR_REQUIREMENT = "can_create_orders";
$backDisabled = true; //Hides back button
require_once "../../header.php";
?>
If you're trying to find a non-specific item, or you don't know the product number, use the form below to find it. You can enter a name, product code or SKU and I'll whip up a quick summary before you make a decision.
<form>
<input type="text" class="ui-state-default" name="findItemName" id="findItemName" style="width:100%" />
</form>
<div id="findItemDetails" class="ui-state-highlight">When you choose an item, details will appear here.</div>