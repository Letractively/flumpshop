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
*  @Name        : findAddress.php
*  @Version     : 1.0
*  @author		: Lloyd Wallis <lloyd@theflump.com>
*  @copyright	: Copyright (c) 2010, Lloyd Wallis
*/

require_once "../../../../preload.php";

if (!acpusr_validate('can_create_orders')) die($config->getNode('messages','adminDenied'));

$fieldPrefix = $_GET['pre'];

?><p>If you are taking an order for a repeat customer, you can type in their stored contact email, customer ID number, or any part of their address to find them.</p>
<form>
<input type="text" class="ui-state-default" name="findAddressTerm" id="findAddressTerm" style="width:100%" />
</form>
<div id="findAddressDetails" class="ui-state-highlight">When you choose an address, details will appear here.</div>