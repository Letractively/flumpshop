<?php
$requires_tier2 = true;
require_once "../header.php";
?><h1>User Manager</h1>
<p>Fill out the form below to add a new ACP user account. Please note that an ACP login is different than a login for the site frontend.</p>
<form action="../process/createUser.php" method="post">
<h2>Details</h2>
<table>
	<tr>
		<td><label for="uname">Username:</label></td>
		<td><input type="text" name="uname" id="uname" /></td>
	</tr>
	<tr>
		<td><label for="pass">Password:</label></td>
		<td><input type="password" name="pass" id="pass" /></td>
	</tr>
	<tr>
		<td><label for="pass_confirm">Confirm Password:</label></td>
		<td><input type="password" name="pass_confirm" id="pass_confirm" /></td>
	</tr>
</table>
<h2>Permissions</h2>
<table>
	<tr>
		<th>Action</th>
		<th>Allowed</th>
	</tr>
	<tr>
		<td><label for="can_add_products">Add New Products</label></td>
		<td><input type="checkbox" name="can_add_products" id="can_add_products" /></td>
	</tr>
	<tr>
		<td><label for="can_edit_products">Edit Existing Products</label></td>
		<td><input type="checkbox" name="can_edit_products" id="can_edit_products" /></td>
	</tr>
	<tr>
		<td><label for="can_delete_products">Remove Products</label></td>
		<td><input type="checkbox" name="can_delete_products" id="can_delete_products" /></td>
	</tr>
	<tr>
		<td><label for="can_add_categories">Add New Categories</label></td>
		<td><input type="checkbox" name="can_add_categories" id="can_add_categories" /></td>
	</tr>
	<tr>
		<td><label for="can_edit_categories">Edit Existing Categories</label></td>
		<td><input type="checkbox" name="can_edit_categories" id="can_edit_categories" /></td>
	</tr>
	<tr>
		<td><label for="can_delete_categories">Remove Categories</label></td>
		<td><input type="checkbox" name="can_delete_categories" id="can_delete_categories" /></td>
	</tr>
	<tr>
		<td><label for="can_edit_pages">Edit Pages</label></td>
		<td><input type="checkbox" name="can_edit_pages" id="can_edit_pages" /></td>
	</tr>
	<tr>
		<td><label for="can_edit_delivery_rates">Edit Delivery Rates</label></td>
		<td><input type="checkbox" name="can_edit_delivery_rates" id="can_edit_delivery_rates" /></td>
	</tr>
	<tr>
		<td><label for="can_post_news">Post News</label></td>
		<td><input type="checkbox" name="can_post_news" id="can_post_news" /></td>
	</tr>
	<tr>
		<td><label for="can_contact_customers">Contact Customers</label></td>
		<td><input type="checkbox" name="can_contact_customers" id="can_contact_customers" /></td>
	</tr>
	<tr>
		<td><label for="can_view_customers">View Customer Data</label></td>
		<td><input type="checkbox" name="can_view_customers" id="can_view_customers" /></td>
	</tr>
	<tr>
		<td><label for="can_view_orders">View Orders</label></td>
		<td><input type="checkbox" name="can_view_orders" id="can_view_orders" /></td>
	</tr>
	<tr>
		<td><label for="can_edit_orders">Update Orders</label></td>
		<td><input type="checkbox" name="can_edit_orders" id="can_edit_orders" /></td>
	</tr>
</table>
<br /><input type="submit" value="Create User" />
</form>
</body></html>