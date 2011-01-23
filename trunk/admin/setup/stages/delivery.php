<?php
require_once dirname(__FILE__)."/../header.inc.php";
?><h1>Delivery</h1>
<p>Flumpshop has multiple ways of handling Delivery Charges. By default, is uses a sliding-scale system based on weight. However, it can be configured in other ways too.</p>
<p>The option you configure here decides the layout of the Delivery Management section of the Admin CP later.</p>
<form action="../process/doDelivery.php" method="post"><table>
<tr>
	<td><label for="deliveryType">Delivery Cost Type</label></td>
    <td><select name="deliveryType" id="deliveryType">
			<option value="custom">Sliding Scale</option>
			<option value="perItem">Charge-per-item</option>
			<option value="single">Charge-per-order</option>
		</select></td>
    <td><span class='iconbutton' onclick='$("#deliveryHelp").dialog("open");'></span></td>
</tr>
</table>
<input type="submit" value="Continue" />
</form>
<div class="ui-helper-hidden helpDialog" id="delivery" title="Delivery Cost Type">Can be one of the following:
	<ul>
		<li>Sliding Scale: Configure a custom set of costs depending on the weight of each item</li>
		<li>Charge-per-item: Charge a fixed amount per item</li>
		<li>Chager-per-order: Charge a fixed amount per order (with multiple tiers, e.g. standard, express)</li>
	</ul>
</div>
<script>
document.logDirFocus = true;
</script><?php
require_once dirname(__FILE__)."/../footer.inc.php";?>