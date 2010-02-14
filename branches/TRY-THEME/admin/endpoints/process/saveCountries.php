<?php
require_once dirname(__FILE__)."/../header.php";

//Set all countries as disabled (unchecked boxes don't get submitted)
$dbConn->query("UPDATE `country` SET supported=0");

$query = "UPDATE `country` SET `supported`=1 WHERE supported!=0";
foreach (array_keys($_POST) as $country) {
        $query .= " OR `iso`='$country'";
}

if ($dbConn->query($query)) {
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>Supported Countries Saved Succesfully</div>";
} else {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>Failed to save supported countries</div>";
}
include dirname(__FILE__)."/../delivery/countries.php";
?>