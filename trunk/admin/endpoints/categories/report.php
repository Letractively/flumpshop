<?php
$USR_REQUIREMENT = "can_view_reports";
require_once "../header.php";

//Get customisations
if (isset($_GET['custom'])) {
	$id = isset($_GET['id']);
	$name = isset($_GET['name']);
	$productCount = isset($_GET['productCount']);
	$parent = isset($_GET['parent']);
	$features = isset($_GET['features']);
	$sort1 = htmlentities($_GET['sort1'],ENT_QUOTES);
	$sort2 = htmlentities($_GET['sort2'],ENT_QUOTES);
} else {
	$id=true;
	$name=true;
	$productCount=true;
	$parent=false;
	$features=false;
	$sort1="id";
	$sort2="";
}
?><div id='navbar'>
	<a href="javascript:" onclick="$('#navbar').hide();$('#options_container').hide();parent.main.focus();parent.main.print()"><span class='ui-icon ui-icon-print'></span>Print Report</a> | 
	<a href="../categories/csv_report.php?<?php echo $_SERVER['QUERY_STRING'];?>" style="display:inline-block"><span class="ui-icon ui-icon-disk"></span>Generate CSV</a>
</div>
<h1>Category Report</h1>
<div id="options_container">
<div class="ui-widget-header" onclick="$('#options').toggle('blind');">Customise...</div>
<form action="../categories/report.php" method="GET" id="options" style="display:none;" class="ui-widget-content">
<table>
	<tr>
		<td><label for="id">ID</label></td>
		<td><input type="checkbox" name="id" id="id"<?php if ($id) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="name">Name</label></td>
		<td><input type="checkbox" name="name" id="name"<?php if ($name) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="productCount">Product Count</label></td>
		<td><input type="checkbox" name="productCount" id="productCount"<?php if ($productCount) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="parent">Parent</label></td>
		<td><input type="checkbox" name="parent" id="parent"<?php if ($parent) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="features">Features</label></td>
		<td><input type="checkbox" name="features" id="features"<?php if ($features) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td>Sort By: </td>
		<td><select name="sort1" id="sort1">
		<option value="id">ID</option>
		<option value="name"<?php if ($sort1=="name") echo " selected='selected'";?>>Name</option>
		<option value="productCount"<?php if ($sort1=="productCount") echo " selected='selected'";?>>Product Count (Not Implemented)</option>
		</select></td>
	</tr>
	<tr>
		<td>Then By: </td>
		<td><select name="sort2" id="sort2">
		<option value="id">ID</option>
		<option value="name"<?php if ($sort2=="name") echo " selected='selected'";?>>Name</option>
		</select></td>
	</tr>
</table>
<input type="hidden" name="custom" value="true" />
<input type="submit" value="Update" />
</form><br /><br /></div><?php

if ($sort1 != "productCount") {
	//Create the sort string
	$sortString = $sort1;
	if ($sort2 != "") $sortString .= ",$sort2";
	$sortString .= " ASC";
	
	$result = $dbConn->query("SELECT id FROM `category` WHERE enabled=1 ORDER BY ".$sortString);
} else {
	if ($sort2 != "") $sortString= ",category.$sort2";
	//Product count are a little more complicated... (and aren't implemented yet)
	$result = $dbConn->query("SELECT id FROM category ORDER BY id ASC");
}

echo "<table width='100%'><tr class='ui-widget-header' style='text-align:left;'>";
if ($id) echo "<th>ID</th>";
if ($name) echo "<th>Description</th>";
if ($productCount) echo "<th>Number of Products</th>";
if ($parent) echo "<th>Parent</th>";
if ($features) echo "<th>Features</th>";
echo "</tr>";

//If authorised, make clicking go to the item's edit page
if ($dbConn->rows($dbConn->query("SELECT id FROM `acp_login` WHERE uname='".$acp_uname."' AND can_edit_categories=1 LIMIT 1")) == 1) {
	$editAuth = 1;
} else {
	$editAuth = 0;
}

$class = "ui-widget-content";
while ($row = $dbConn->fetch($result)) {
	$category = new Category($row['id']);
	//Build onClick string
	if ($editAuth) {
		$onclick = " onclick=\"window.location.href = '../categories/editCategory.php?id=".$row['id']."&return=report';\"";
	} else {
		$onclick = "";
	}
	
	echo "<tr class='$class'$onclick style='cursor:pointer'>";
	if ($id) echo "<td>".$category->getID()."</td>";
	if ($name) echo "<td>".$category->getName()."</td>";
	if ($productCount) echo "<td>".$dbConn->rows($dbConn->query("SELECT id FROM products WHERE active=1 AND id IN (SELECT itemid as id FROM item_category WHERE catid=".$category->getID().")"))."</td>";
	if ($parent) {
		$parent = new Category($category->getParent(),"noparent");
		echo "<td>".$parent->getName()."</td>";
		unset($parent);
	}
	if ($features) echo "<td>Functionality not implemented.</td>";
	echo "</tr>";
	
	//Toggle row styles
	if ($class == "ui-widget-content") $class = "ui-state-hover"; else $class="ui-widget-content";
}
echo "</table>";

echo "<p class='ui-widget-content'>Category Report for ".$config->getNode('messages','name')." generated on ".date("d/m/Y")." by ".$acp_uname.".</p>";
echo "<p><sub>Flumpshop: Make it happen</sub></p>";
?></body></html>