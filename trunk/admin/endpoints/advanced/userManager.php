<?php
$requires_tier2 = true;
require_once "../header.php";
?><h1>User Manager</h1>
<p>This feature allows you to control the various users that have access to Flumpshop, and what permissions they have. Please note that the following features cannot be accessed without the use of the second tier password you set up with the Flumpshop system:</p>
<ul>
	<li>Cron script force</li>
	<li>File Upload</li>
	<li>Log Viewer</li>
	<li>Feedback</li>
	<li>Duplicate Content</li>
	<li>Execute SQL</li>
	<li>Configuration Manager</li>
	<li>Export</li>
	<li>Import</li>
	<li>PHP Info</li>
	<li>Rebuild Images</li>
	<li>User Manager</li>
	<li>Plugin Manager (and all plugin settings)</li>
	<li>Run the Upgrade/Setup Wizards</li>
</ul>
<p>The following user accounts are currently configured on this Flumpshop instance:</p><ul><?php
//Get a list of Admin users
$result = $dbConn->query("SELECT id,uname FROM `acp_login` ORDER BY uname ASC");

while ($row = $dbConn->fetch($result)) {
	echo "<li><a href='editUser.php?id=".$row['id']."'>".$row['uname']."</a></li>";
}
?></ul>
<a href="addUser.php">Add new user</a>
</body></html>