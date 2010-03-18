<?php
$requires_tier2 = true;
require_once dirname(__FILE__)."/../header.php";

$result = $dbConn->query("SELECT * FROM `bugs` WHERE resolved = 0");

if ($dbConn->rows($result) == 0) {
	die("<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>There are no open bug reports.</div>");
}

echo "<table class='ui-widget-content'>";

while ($bug = $dbConn->fetch($result)) {
	echo "<tr><td><strong>".$bug['header']."</strong><span style='color: red;'>&nbsp; Assigned to ".$bug['assignedTo']."</span><p>".nl2br($bug['body'])."</p><a href='../process/bugresolved.php?id=".$bug['id']."' onclick=\"$(body).html(loadMsg('Closing Bug...'));');\">Mark as Read</a><hr /></td></tr>";
}
echo "</table>";
?>