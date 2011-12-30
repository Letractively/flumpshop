<?php

/**
*  Administrative AJAX Endpoint. Used to search for an address in the order screen's "Load Data..." dialog
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
*  @Name        : addressSummary.php
*  @Version     : 1.0
*  @author		: Lloyd Wallis <flump@flump.me>
*  @copyright	: Copyright (c) 2010, Lloyd Wallis
*/

require_once '../../../../preload.php';

if (!acpusr_validate('can_create_orders')) die($config->getNode('messages','adminDenied'));

$customer = new Customer(intval($_GET['id']));

echo $customer->getName(true).'<br />';
echo $customer->getAddress(true).'<br />';
echo $customer->getCountry(true).'<br />';
echo $customer->getEmail(true).'<br />';
echo $customer->printDeliverySupported();
?><button onclick="applyAddress('<?php echo $_GET['pre'];?>');" class="ui-state-default">Use This Address</button>