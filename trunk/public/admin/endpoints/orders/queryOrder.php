<?php
$USR_REQUIREMENT = "can_view_orders";
require_once dirname(__FILE__)."/../header.php";

?><div class="ui-widget-header">Query Order</div>
<form action="./order.php" method="get" onsubmit="if ($(this).valid()) {loader(loadMsg('Running Query...')); return true;} return false;" class="ui-widget-content">
	<p>Enter the ID number of a order below to display it.</p>
	<label for="id">Order ID #</label><input type="text" name="id" id="id" class="ui-state-default required number" />
	<input type="submit" class="ui-state-default" style="font-size: 12px; padding: .2em .4em;" />
</form>
</body></html>