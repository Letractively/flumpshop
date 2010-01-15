<?php
require_once dirname(__FILE__)."/../../../preload.php";

$result = $dbConn->query("SELECT * FROM `country` ORDER BY name ASC");

if (isset($_GET['id'])) $id = $_GET['id']; else $id = 0;
echo "<label for='country$id'>Country $id</label>&nbsp;<select name='country$id' id='country$id' class='ui-widget-content required' style='width: 150px;'><option value=''></option>";
while ($row = $dbConn->fetch($result)) {
	echo "<option value='".$row['iso']."'>".$row['name']."</option>";
}
echo "</select>";
?>