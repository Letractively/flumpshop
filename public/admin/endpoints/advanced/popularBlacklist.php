<?php
/**
 *  Allows Administrators to block certain products from appearing in the 'Popular Items' section of the home page
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
 *  @Name admin/endpoints/advanced/popularBlacklist.php
 *  @Version 1.00
 *  @author Lloyd Wallis <flump5281@gmail.com>
 *  @copyright Copyright (c) 2009-2011, Lloyd Wallis
 *  @package Flumpshop
 */
$requires_tier2 = true;

require_once dirname(__FILE__) . "/../header.php";
?><div class="ui-widget-header">Popular Item Blacklist</div>
<div class="ui-widget-content">
	<p>Sometimes, there's a product that for one reason or another you don't want to appear on the home page in dazzling 'Popular Item' lights. Here, you can just give me the Product's ID number, and I'll make sure it never shows up there again.</p>
	<form action="../process/blacklistProduct.php" method="post" onsubmit="loader('Updating Blacklist...');">
		<label for="item_id">Blacklist product #</label>
		<input type="text" class="ui-widget-content ui-state-default required number" name="item_id" id="item_id" />
		<input type="submit" value="Blacklist" style="font-size: 12px; padding: .2em .4em;" />
	</form>
</div>
<div class="ui-widget-header">Blacklisted Products</div>
<div class="ui-widget-content">
	<table>
		<thead>
			<tr>
				<th>Product ID</th>
				<th>Unblock</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$result = $dbConn->query('SELECT id FROM product_popular_blacklist ORDER BY id ASC');
		while ($row = $dbConn->fetch($result)) {
			echo '<tr><td><a href="../../../item/?id='.$row['id'].'" target="_blank">'.$row['id'].'</a></td>'.
				'<td><a href="../process/unblacklistProduct.php?id='.$row['id'].'" title="Click to remove this item from the popular blacklist"><span class="ui-icon ui-icon-trash"></span></a>';
		}
		?>
		</tbody>
	</table>
</div>
</body>
</html>