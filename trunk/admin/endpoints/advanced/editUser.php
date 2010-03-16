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
		<td><input type="checkbox" name="can_add_products" id="can_add_products"<?php if($row['can_add_products']) echo ' selected="selected"';?> /></td>
	</tr>
</table>
<br /><input type="submit" value="Save" />
</form>
</body></html>