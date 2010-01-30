<?php
require_once dirname(__FILE__)."/../header.php";

//Set all countries as disabled
$dbConn->query("UPDATE `country` SET supported=0");

$query = "UPDATE `country` SET `supported`=1 WHERE false";
foreach (array_keys($_POST) as $country) {
	if ($_POST[$country] == "On") {
		$query .= " OR `iso`='$country'";
	}
}

if ($dbConn->query($query)) {
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Supported Countries Saved Succesfully</div>";
} else {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to save supported countries</div>";
}
include dirname(__FILE__)."/../delivery/countries.php";
?>