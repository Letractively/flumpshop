<?php
$requires_tier2 = true;
require_once "../../../preload.php";

function exportResultSet($result,$mainKey,$keyName) {
	global $dbConn;
	echo "\t<$mainKey>\n";
	while ($row = $dbConn->fetch($result)) {
		echo "\t\t<$keyName>\n";
		foreach ($row as $key => $value) {
			if (!is_int($key)) {
				$value = filter_var($value,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_STRIP_LOW);
				echo "\t\t\t<$key>".$value."</$key>\n";
			}
		}
		echo "\t\t</$keyName>\n";
	}
	echo "\t</$mainKey>\n";
}

header("Content-Type:text/xml");
header("Content-Disposition:attachment;filename=flumpshop_export.xml");

echo '<?xml version="1.0"?>';
echo "<fsexport version='1'>\n";

//Export database tables
echo "<database>";
exportResultSet($dbConn->query("SELECT * FROM `acp_login` ORDER BY id"),"acpusers","acpuser");

exportResultSet($dbConn->query("SELECT * FROM `basket` ORDER BY id"),"baskets","basket");

exportResultSet($dbConn->query("SELECT * FROM `bugs` ORDER BY id"),"bugs","bug");

exportResultSet($dbConn->query("SELECT * FROM `category` ORDER BY id"),"categories","category");

exportResultSet($dbConn->query("SELECT * FROM `category_feature` ORDER BY id"),"category_features","category_feature");

exportResultSet($dbConn->query("SELECT * FROM `compare_features` ORDER BY id"),"features","feature");

exportResultSet($dbConn->query("SELECT * FROM `country`"),"countries","country");

exportResultSet($dbConn->query("SELECT * FROM `customers` ORDER BY id"),"customers","customer");

exportResultSet($dbConn->query("SELECT * FROM `delivery` ORDER BY id"),"deliveryrates","delivery");

exportResultSet($dbConn->query("SELECT * FROM `feature_units`"),"feature_units","feature_unit");

exportResultSet($dbConn->query("SELECT * FROM `item_category` ORDER BY id"),"item_categories","item_category");

exportResultSet($dbConn->query("SELECT * FROM `item_feature_date`"),"item_feature_dates","item_feature_date");

exportResultSet($dbConn->query("SELECT * FROM `item_feature_number`"),"item_feature_numbers","item_feature_number");

exportResultSet($dbConn->query("SELECT * FROM `item_feature_string`"),"item_feature_strings","item_feature_string");

exportResultSet($dbConn->query("SELECT * FROM `keys` ORDER BY id"),"keys","key");

exportResultSet($dbConn->query("SELECT * FROM `login` ORDER BY id"),"users","user");

exportResultSet($dbConn->query("SELECT * FROM `news` ORDER BY id"),"news","entry");

exportResultSet($dbConn->query("SELECT * FROM `newsletters` ORDER BY id"),"newsletters","newsletter");

exportResultSet($dbConn->query("SELECT * FROM `orders` ORDER BY id"),"orders","order");

exportResultSet($dbConn->query("SELECT * FROM `products` ORDER BY id"),"products","product");

exportResultSet($dbConn->query("SELECT * FROM `reserve` ORDER BY id"),"reserves","reserve");

exportResultSet($dbConn->query("SELECT * FROM `sessions` ORDER BY id"),"sessions","session");

exportResultSet($dbConn->query("SELECT * FROM `stats` ORDER BY id"),"stats","stat");

exportResultSet($dbConn->query("SELECT * FROM `techhelp` ORDER BY id"),"techhelp","entry");

echo "</database>\n";

//Export Configuration
echo "<config>\n";

foreach ($config->getTrees() as $tree) {
	echo "\t<$tree>\n";
	foreach ($config->getNodes($tree) as $node) {
		$node = preg_replace("/^[0-9]*$/","num__$0",$node);
		echo "\t\t<$node>".filter_var($config->getNode($tree,$node),FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_STRIP_LOW)."</$node>\n";
	}
	echo "\t</$tree>\n";
}

echo "</config>\n";

echo "</fsexport>";
?>