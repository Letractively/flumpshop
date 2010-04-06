<?php
$USR_REQUIREMENT = "can_view_reports";
require_once "../header.php";

//Get customisations
if (isset($_GET['custom'])) {
	$id = isset($_GET['id']);
	$sku = isset($_GET['sku']);
	$name = isset($_GET['name']);
	$cost = isset($_GET['cost']);
	$delivery = isset($_GET['delivery']);
	$price = isset($_GET['price']);
	$category = isset($_GET['category']);
	$sort1 = htmlentities($_GET['sort1'],ENT_QUOTES);
	$sort2 = htmlentities($_GET['sort2'],ENT_QUOTES);
} else {
	$id=true;
	$sku=true;
	$name=true;
	$cost=true;
	$delivery=true;
	$price=true;
	$category=false;
	$sort1="id";
	$sort2="";
}
?><div id='navbar'>
	<a href="javascript:" onclick="$('#navbar').hide();$('#options_container').hide();parent.main.focus();parent.main.print()"><span class='ui-icon ui-icon-print'></span>Print Report</a> | 
	<a href="itemCSV.php?<?php echo $_SERVER['QUERY_STRING'];?>" style="display:inline-block"><span class="ui-icon ui-icon-disk"></span>Generate CSV</a>
</div>
<h1>Product Report</h1>
<div id="options_container">
<div class="ui-widget-header" onclick="$('#options').toggle('blind');">Customise...</div>
<form action="itemReport.php" method="GET" id="options" style="display:none;" class="ui-widget-content">
<table>
	<tr>
		<td><label for="id">ID</label></td>
		<td><input type="checkbox" name="id" id="id"<?php if ($id) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="sku">SKU</label></td>
		<td><input type="checkbox" name="sku" id="sku"<?php if ($sku) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="name">Name</label></td>
		<td><input type="checkbox" name="name" id="name"<?php if ($name) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="cost">Cost</label></td>
		<td><input type="checkbox" name="cost" id="cost"<?php if ($cost) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="delivery">Delivery Cost</label></td>
		<td><input type="checkbox" name="delivery" id="delivery"<?php if ($delivery) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="price">Price</label></td>
		<td><input type="checkbox" name="price" id="price"<?php if ($price) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="category">Category</label></td>
		<td><input type="checkbox" name="category" id="category"<?php if ($category) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td>Sort By: </td>
		<td><select name="sort1" id="sort1">
		<option value="id">ID</option>
		<option value="sku"<?php if ($sort1=="sku") echo " selected='selected'";?>>SKU</option>
		<option value="category"<?php if ($sort1=="category") echo " selected='selected'";?>>Category</option>
		<option value="name"<?php if ($sort1=="name") echo " selected='selected'";?>>Name</option>
		<option value="cost"<?php if ($sort1=="cost") echo " selected='selected'";?>>Cost</option>
		<option value="delivery"<?php if ($sort1=="delivery") echo " selected='selected'";?>>Delivery</option>
		<option value="price"<?php if ($sort1=="price") echo " selected='selected'";?>>Price</option>
		</select></td>
	</tr>
	<tr>
		<td>Then By: </td>
		<td><select name="sort2" id="sort2">
		<option value="id">ID</option>
		<option value="sku"<?php if ($sort2=="sku") echo " selected='selected'";?>>SKU</option>
		<option value="name"<?php if ($sort2=="name") echo " selected='selected'";?>>Name</option>
		<option value="cost"<?php if ($sort2=="cost") echo " selected='selected'";?>>Cost</option>
		<option value="delivery"<?php if ($sort2=="delivery") echo " selected='selected'";?>>Delivery</option>
		<option value="price"<?php if ($sort2=="price") echo " selected='selected'";?>>Price</option>
		</select></td>
	</tr>
</table>
<input type="hidden" name="custom" value="true" />
<input type="submit" value="Update" />
</form><br /><br /></div><?php

if ($sort1 != "category") {
	//Create the sort string
	$sortString = $sort1;
	if ($sort2 != "") $sortString .= ",$sort2";
	$sortString .= " ASC";
	
	$result = $dbConn->query("SELECT id FROM `products` WHERE active=1 ORDER BY ".$sortString);
} else {
	if ($sort2 != "") $sortString= ",products.$sort2";
	//Categories are a little more complicated...
	$result = $dbConn->query("SELECT id FROM products
							 LEFT JOIN (SELECT item_category.itemid AS pid,category.name AS cname FROM item_category
							 			LEFT JOIN category
							 			ON item_category.catid = category.id) AS t1
							 ON products.id = t1.pid
							 ORDER BY t1.cname".$sortString);
}

echo "<table width='100%'><tr class='ui-widget-header' style='text-align:left;'>";
if ($id) echo "<th>ID</th>";
if ($sku) echo "<th>SKU</th>";
if ($category) echo "<th>Category</th>";
if ($name) echo "<th>Description</th>";
if ($cost) echo "<th>Cost to Produce</th>";
if ($delivery) echo "<th>Delivery Cost</th>";
if ($price) echo "<th>Selling Price</th>";
echo "</tr>";

//If authorised, make clicking go to the item's edit page
if ($dbConn->rows($dbConn->query("SELECT id FROM `acp_login` WHERE uname='".$acp_uname."' AND can_edit_products=1 LIMIT 1")) == 1) {
	$editAuth = 1;
} else {
	$editAuth = 0;
}

$class = "ui-widget-content";
while ($row = $dbConn->fetch($result)) {
	$item = new Item($row['id']);
	//Build onClick string
	if ($editAuth) {
		$onclick = " onclick=\"window.location.href = '../edit/editItem.php?id=".$row['id']."&return=report';\"";
	} else {
		$onclick = "";
	}
	
	echo "<tr class='$class'$onclick style='cursor:pointer'>";
	if ($id) echo "<td>".$item->getID()."</td>";
	if ($sku) echo "<td>".$item->getSKU()."</td>";
	if ($category) {
		$cat = new Category($item->getCategory(0),"noparent");
		echo "<td>".$cat->getName()."</td>";
	}
	if ($name) echo "<td>".$item->getName()."</td>";
	if ($cost) echo "<td>".$item->getFriendlyCost()."</td>";
	if ($delivery) echo "<td>".$item->getFriendlyDeliveryCost()."</td>";
	if ($price) echo "<td>".$item->getFriendlyPrice()."</td>";
	echo "</tr>";
	
	//Toggle row styles
	if ($class == "ui-widget-content") $class = "ui-state-hover"; else $class="ui-widget-content";
}
echo "</table>";

echo "<p class='ui-widget-content'>Product Report for ".$config->getNode('messages','name')." generated on ".date("d/m/Y")." by ".$acp_uname.".</p>";
echo "<p><sub>Flumpshop: Make it happen</sub></p>";
?></body></html>