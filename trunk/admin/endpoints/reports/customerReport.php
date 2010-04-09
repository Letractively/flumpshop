<?php
$USR_REQUIREMENT = "can_view_reports";
require_once "../header.php";
//Customer auth needed too
if ($dbConn->rows($dbConn->query("SELECT id FROM `acp_login` WHERE uname='".$acp_uname."' AND can_view_customers=1 LIMIT 1")) == 0) {
	die("You do not have permission to perform that action.");
}

//Get customisations
if (isset($_GET['custom'])) {
	$id = isset($_GET['id']);
	$name = isset($_GET['name']);
	$address1 = isset($_GET['address1']);
	$address2 = isset($_GET['address2']);
	$address3 = isset($_GET['address3']);
	$postcode = isset($_GET['postcode']);
	$country = isset($_GET['country']);
	$email = isset($_GET['email']);
	$can_contact = isset($_GET['can_contact']);
	$sort1 = htmlentities($_GET['sort1'],ENT_QUOTES);
	$sort2 = htmlentities($_GET['sort2'],ENT_QUOTES);
} else {
	$id = true;
	$name = true;
	$address1 = false;
	$address2 = false;
	$address3 = false;
	$postcode = true;
	$country = true;
	$email = true;
	$can_contact = isset($_GET['can_contact']);
	$sort1="id";
	$sort2="";
}
?><div id='navbar'>
	<a href="javascript:" onclick="$('#navbar').hide();$('#options_container').hide();parent.main.focus();parent.main.print()"><span class='ui-icon ui-icon-print'></span>Print Report</a> | 
	<a href="customerCSV.php?<?php echo $_SERVER['QUERY_STRING'];?>" style="display:inline-block"><span class="ui-icon ui-icon-disk"></span>Generate CSV</a>
</div>
<h1>Customer Report</h1>
<div id="options_container">
<div class="ui-widget-header" onclick="$('#options').toggle('blind');">Customise...</div>
<form action="customerReport.php" method="GET" id="options" style="display:none;" class="ui-widget-content">
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
		<td><label for="address1">Address 1</label></td>
		<td><input type="checkbox" name="address1" id="address1"<?php if ($address1) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="address2">Address 2</label></td>
		<td><input type="checkbox" name="address2" id="address2"<?php if ($address2) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="address3">Address 3</label></td>
		<td><input type="checkbox" name="address3" id="address3"<?php if ($address3) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="postcode">Postcode</label></td>
		<td><input type="checkbox" name="postcode" id="postcode"<?php if ($postcode) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="country">Country</label></td>
		<td><input type="checkbox" name="country" id="country"<?php if ($country) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="email">Email</label></td>
		<td><input type="checkbox" name="email" id="email"<?php if ($country) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td><label for="can_contact">Can Contact</label></td>
		<td><input type="checkbox" name="can_contact" id="can_contact"<?php if ($country) echo " checked='checked'";?>  /></td>
	</tr>
	<tr>
		<td>Sort By: </td>
		<td><select name="sort1" id="sort1">
		<option value="id">ID</option>
		<option value="name"<?php if ($sort1=="name") echo " selected='selected'";?>>Name</option>
		<option value="address1"<?php if ($sort1=="address1") echo " selected='selected'";?>>Address 1</option>
		<option value="address2"<?php if ($sort1=="address2") echo " selected='selected'";?>>Address 2</option>
		<option value="address3"<?php if ($sort1=="address3") echo " selected='selected'";?>>Address 3</option>
		<option value="postcode"<?php if ($sort1=="postcode") echo " selected='selected'";?>>Postcode</option>
		<option value="country"<?php if ($sort1=="country") echo " selected='selected'";?>>Country</option>
		<option value="email"<?php if ($sort1=="email") echo " selected='selected'";?>>Email</option>
		<option value="can_contact"<?php if ($sort1=="can_contact") echo " selected='selected'";?>>Can Contact</option>
		</select></td>
	</tr>
	<tr>
		<td>Then By: </td>
		<td><select name="sort2" id="sort2">
		<option value="id">ID</option>
		<option value="name"<?php if ($sort2=="name") echo " selected='selected'";?>>Name</option>
		<option value="address1"<?php if ($sort2=="address1") echo " selected='selected'";?>>Address 1</option>
		<option value="address2"<?php if ($sort2=="address2") echo " selected='selected'";?>>Address 2</option>
		<option value="address3"<?php if ($sort2=="address3") echo " selected='selected'";?>>Address 3</option>
		<option value="postcode"<?php if ($sort2=="postcode") echo " selected='selected'";?>>Postcode</option>
		<option value="country"<?php if ($sort2=="country") echo " selected='selected'";?>>Country</option>
		<option value="email"<?php if ($sort2=="email") echo " selected='selected'";?>>Email</option>
		<option value="can_contact"<?php if ($sort2=="can_contact") echo " selected='selected'";?>>Can Contact</option>
		</select></td>
	</tr>
</table>
<input type="hidden" name="custom" value="true" />
<input type="submit" value="Update" />
</form><br /><br /></div><?php

//Create the sort string
$sortString = $sort1;
if ($sort2 != "") $sortString .= ",$sort2";
$sortString .= " ASC";

$result = $dbConn->query("SELECT id FROM `customers` WHERE archive=0 ORDER BY ".$sortString);

echo "<table width='100%'><tr class='ui-widget-header' style='text-align:left;'>";
if ($id) echo "<th>ID</th>";
if ($name) echo "<th>Name</th>";
if ($address1) echo "<th>Address 1</th>";
if ($address2) echo "<th>Address 2</th>";
if ($address3) echo "<th>Address 3</th>";
if ($postcode) echo "<th>Postcode</th>";
if ($country) echo "<th>Country</th>";
if ($email) echo "<th>Email</th>";
if ($can_contact) echo "<th>Can Contact</th>";
echo "</tr>";

$class = "ui-widget-content";
while ($row = $dbConn->fetch($result)) {
	$customer = new Customer($row['id']);
	//Build onClick string
	$onclick = " onclick=\"window.location.href = '../orders/customer.php?id=".$row['id']."';\"";
		
	echo "<tr class='$class'$onclick style='cursor:pointer'>";
	if ($id) echo "<td>".$customer->getID()."</td>";
	if ($name) echo "<td>".$customer->getName()."</td>";
	if ($address1) echo "<td>".$customer->getAddress1()."</td>";
	if ($address2) echo "<td>".$customer->getAddress2()."</td>";
	if ($address3) echo "<td>".$customer->getAddress3()."</td>";
	if ($postcode) echo "<td>".$customer->getPostcode()."</td>";
	if ($country) echo "<td>".$customer->getCountryName()."</td>";
	if ($email) echo "<td>".$customer->getEmail()."</td>";
	if ($can_contact) echo "<td>".intval($customer->can_contact)."</td>";
	echo "</tr>";
	
	//Toggle row styles
	if ($class == "ui-widget-content") $class = "ui-state-hover"; else $class="ui-widget-content";
}
echo "</table>";

echo "<p class='ui-widget-content'>Customer Report for ".$config->getNode('messages','name')." generated on ".date("d/m/Y")." by ".$acp_uname.".</p>";
echo "<p><sub>Flumpshop: Make it happen</sub></p>";
?></body></html>