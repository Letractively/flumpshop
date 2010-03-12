<?php
require_once "../header.php";
?><h1>Import Customer Data</h1>
<p>Currently in heavy development, this feature will enable you to import customer data from various sources. At the current time, only manual entry of information is supported.</p>
<form action="../process/addCustomers.php?method=manual" method="post" id="manform">
<div id="cust_container">
<fieldset>
	<legend>Customer 1</legend>
	<table>
	<tr><td><label for="customer1_name">Name: </label></td>
	<td><input type="text" name="customer1_name" id="customer1_name" class="ui-state-default text" /></td></tr>
	<tr><td><label for="customer1_address1">Address 1: </label></td>
	<td><input type="text" name="customer1_address1" id="customer1_address1" class="ui-state-default text" /></td></tr>
	<tr><td><label for="customer1_address2">Address 2: </label></td>
	<td><input type="text" name="customer1_address2" id="customer1_address2" class="ui-state-default text" /></td></tr>
	<tr><td><label for="customer1_address3">Address 3: </label></td>
	<td><input type="text" name="customer1_address3" id="customer1_address3" class="ui-state-default text" /></td></tr>
	<tr><td><label for="customer1_postcode">Postcode: </label></td>
	<td><input type="text" name="customer1_postcode" id="customer1_postcode" class="ui-state-default" /></td></tr>
	<tr><td><label for="customer1_country">Country: </label></td>
	<td><select name="customer1_country" id="customer1_country" class="ui-widget-content" style="width: 250px;"><option value=""></option><?php
	$result = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");
	
	$countries = "";
	while ($row = $dbConn->fetch($result)) {
		$countries .= "<option value='".$row['iso']."'>".$row['name']."</option>";
	}
	echo $countries;
	?></select></td></tr>
	<tr><td><label for="customer1_email">Email Address: </label></td>
	<td><input type="text" name="customer1_email" id="customer1_email" class="ui-state-default email" /></td></tr>
	</table>
</fieldset>
</div>
<input type="submit" value="Submit" style="font-size: 13px; padding: .2em .4em; width: 65px;" />
<button onclick="addCustomer(); return false;">Add another...</button>
</form>
<script type="text/javascript">
$('#manform').validate();
document.nextCustomerIdentifier = 2;
function addCustomer() {
	$('#cust_container').append('<fieldset><legend>Customer '+document.nextCustomerIdentifier+'</legend><table><tr><td><label for="customer'+document.nextCustomerIdentifier+'_name">Name: </label></td><td><input type="text" name="customer'+document.nextCustomerIdentifier+'_name" id="customer'+document.nextCustomerIdentifier+'_name" class="ui-state-default text" /></td></tr><tr><td><label for="customer'+document.nextCustomerIdentifier+'_address1">Address 1: </label></td><td><input type="text" name="customer'+document.nextCustomerIdentifier+'_address1" id="custome'+document.nextCustomerIdentifier+'_address1" class="ui-state-default text" /></td></tr><tr><td><label for="customer'+document.nextCustomerIdentifier+'_address2">Address 2: </label></td><td><input type="text" name="customer'+document.nextCustomerIdentifier+'_address2" id="customer'+document.nextCustomerIdentifier+'_address2" class="ui-state-default text" /></td></tr><tr><td><label for="customer'+document.nextCustomerIdentifier+'_address3">Address 3: </label></td><td><input type="text" name="customer'+document.nextCustomerIdentifier+'_address3" id="customer'+document.nextCustomerIdentifier+'_address3" class="ui-state-default text" /></td></tr><tr><td><label for="customer'+document.nextCustomerIdentifier+'_postcode">Postcode: </label></td><td><input type="text" name="customer'+document.nextCustomerIdentifier+'_postcode" id="customer'+document.nextCustomerIdentifier+'_postcode" class="ui-state-default" /></td></tr><tr><td><label for="customer'+document.nextCustomerIdentifier+'_country">Country: </label></td><td><select name="customer'+document.nextCustomerIdentifier+'_country" id="customer'+document.nextCustomerIdentifier+'_country" class="ui-widget-content" style="width: 250px;"><option value=""></option><?php echo str_replace("'","\\'",$countries);?></select></td></tr><tr><td><label for="customer'+document.nextCustomerIdentifier+'_email">Email Address: </label></td><td><input type="text" name="customer'+document.nextCustomerIdentifier+'_email" id="customer'+document.nextCustomerIdentifier+'_email" class="ui-state-default email" /></td></tr></table></fieldset>');
	document.nextCustomerIdentifier++;
}
</script>
</body></html>