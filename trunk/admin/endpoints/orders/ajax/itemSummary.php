<?php

/**
*  Administrative AJAX Endpoint. Used to return a summary of a product with the order screen's "More..." dialog
*
*  This file is part of Flumpshop.
*
*  Flumpshop is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  Flumpshop is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with Flumpshop.  If not, see <http://www.gnu.org/licenses/>.
*
*
*  @Name        : itemSummary.php
*  @Version     : 1.0
*  @author		: Lloyd Wallis <lloyd@theflump.com>
*  @copyright	: Copyright (c) 2009-2010, Lloyd Wallis
*/

require_once "../../../../preload.php";

if (!acpusr_validate('can_create_orders')) die($config->getNode('messages','adminDenied'));

$item = new Item(intval($_GET['id']));
?><form id="dialogItemData">
<button onclick="if ($('#dialogItemData').valid()) {$('#dialog').dialog('close');$('#item'+window.tempFindItemId+'ID').val('<?php echo $item->getID()?>');idKeyPress('item'+window.tempFindItemId+'ID',true);}" class='ui-state-default'>Save and Close</button><br /><br /><?php

echo "<strong>".$item->getName()."</strong>";
echo "<p>".$item->getDesc()."</p>";
echo "<p>Stock: ".$item->getStock()."</p>";
//Price Field
echo "<p>Price: &pound;<input type='text' name='morePrice' id='morePrice' value='".$item->getPrice()."' class='ui-state-default positive number required' style='width:75px' />";
//Price Units Field
echo " for <input type='text' name='morePriceUnits' id='morePriceUnits' value='1' class='ui-state-default positiveInt required' style='width:50px' /> unit(s)</p>";
//Delivery Field
echo "<p>Delivery: &pound;<input type='text' name='moreDelivery' id='moreDelivery' value='".$item->getDeliveryCost()."' class='ui-state-default required positive number' style='width:75px' />";
//Delivery Units Field
echo " for <input type='text' name='moreDeliveryUnits' id='moreDeliveryUnits' value='1' class='ui-state-default positiveInt required' style='width:50px' /> unit(s)</p>";
echo $item->getDetails("FEATURES");
?></form>