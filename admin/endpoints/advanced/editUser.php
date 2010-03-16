<?php
$requires_tier2 = true;
require_once "../header.php";

//Get User ID
$userid = intval($_GET['id']);
$result = $dbConn->query("SELECT * FROM `acp_login` WHERE id=$userid LIMIT 1");
$row = $dbConn->fetch($result);
?><h1>User Manager</h1>
<p>Below are the permissions for the user <?php echo $row['uname'];?>.</p>
<form action="../process/saveUser.php" method="post">
<input type="hidden" name="id" id="id" value="<?php echo $userid;?>" />
<p>Please note that this feature has not been implemented yet.</p>
<table>
	<tr>
		<th>Action</th>
		<th>Allowed</th>
	</tr>
	<tr>
		<td><label for="can_add_products">Add New Products</label></td>
		<td><input type="checkbox" name="can_add_products" id="can_add_products"<?php if($row['can_add_products']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_edit_products">Edit Existing Products</label></td>
		<td><input type="checkbox" name="can_edit_products" id="can_edit_products"<?php if($row['can_edit_products']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_delete_products">Remove Products</label></td>
		<td><input type="checkbox" name="can_delete_products" id="can_delete_products"<?php if($row['can_delete_products']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_add_categories">Add New Categories</label></td>
		<td><input type="checkbox" name="can_add_categories" id="can_add_categories"<?php if($row['can_add_categories']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_edit_categories">Edit Existing Categories</label></td>
		<td><input type="checkbox" name="can_edit_categories" id="can_edit_categories"<?php if($row['can_edit_categories']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_delete_categories">Remove Categories</label></td>
		<td><input type="checkbox" name="can_delete_categories" id="can_delete_categories"<?php if($row['can_delete_categories']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_edit_pages">Edit Pages</label></td>
		<td><input type="checkbox" name="can_edit_pages" id="can_edit_pages"<?php if($row['can_edit_pages']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_edit_delivery_rates">Edit Delivery Rates</label></td>
		<td><input type="checkbox" name="can_edit_delivery_rates" id="can_edit_delivery_rates"<?php if($row['can_edit_delivery_rates']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_post_news">Post News</label></td>
		<td><input type="checkbox" name="can_post_news" id="can_post_news"<?php if($row['can_post_news']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_add_customers">Add Customers</label></td>
		<td><input type="checkbox" name="can_add_customers" id="can_add_customers"<?php if($row['can_add_customers']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_contact_customers">Contact Customers</label></td>
		<td><input type="checkbox" name="can_contact_customers" id="can_contact_customers"<?php if($row['can_contact_customers']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_view_customers">View Customer Data</label></td>
		<td><input type="checkbox" name="can_view_customers" id="can_view_customers"<?php if($row['can_add_products']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_view_orders">View Orders</label></td>
		<td><input type="checkbox" name="can_view_orders" id="can_view_orders"<?php if($row['can_add_products']) echo ' checked="checked"';?> /></td>
	</tr>
	<tr>
		<td><label for="can_edit_orders">Update Orders</label></td>
		<td><input type="checkbox" name="can_edit_orders" id="can_edit_orders"<?php if($row['can_add_products']) echo ' checked="checked"';?> /></td>
	</tr>
</table>
<br /><input type="submit" value="Save" />
</form>
</body></html>