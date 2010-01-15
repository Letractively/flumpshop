<?php
$ajaxProvider = true;
require_once dirname(__FILE__)."/../../../preload.php";

$result = $dbConn->query("SELECT * FROM `bugs` WHERE resolved = 0");

if ($dbConn->rows($result) == 0) {
	die("There are no open bug reports.");
}

echo "<table>";

while ($bug = $dbConn->fetch($result)) {
	echo "<tr><td><strong>".$bug['header']."</strong><span style='color: red;'>&nbsp; Assigned to ".$bug['assignedTo']."</span><p>".nl2br($bug['body'])."</p><a href='javascript:void(0);' onclick=\"$('#adminContent').html(loadingString);$('#empty').html(null);$('#adminContent').load('./endpoints/process/bugresolved.php?id=".$bug['id']."');\">Resolved?</a><hr /></td></tr>";
}
echo "</table>";
?>