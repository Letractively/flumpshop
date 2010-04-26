<?php
function dataFilter($data) {
	$data = htmlspecialchars(utf8_encode($data));
	return $data;
}

$requires_tier2 = true;
require_once dirname(__FILE__)."/../../../preload.php";

if (!isset($storeExport)) {
	header("Content-Type:text/xml");
	header("Content-Disposition:attachment;filename=flumpshop_export.xml");
} else {
	$fp = fopen($storeExport,'w');
}

function append($str) {
	if (isset($GLOBALS['storeExport'])) {
		fputs($GLOBALS['fp'],$str);
	} else {
		echo $str;
	}
}

function exportResultSet($result,$mainKey,$keyName) {
	global $dbConn;
	append("\t<$mainKey>\n");
	while ($row = $dbConn->fetch($result)) {
		append("\t\t<$keyName>\n");
		foreach ($row as $key => $value) {
			if (!is_int($key)) {
				$value = dataFilter($value);
				append("\t\t\t<$key>".$value."</$key>\n");
			}
		}
		append("\t\t</$keyName>\n");
	}
	append("\t</$mainKey>\n");
}

append('<?xml version="1.0"?>');
append("<fsexport version='1'>\n");

//Export database tables
append("<database>");
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

exportResultSet($dbConn->query("SELECT * FROM `sessions` ORDER BY session_id"),"sessions","session");

exportResultSet($dbConn->query("SELECT * FROM `stats` ORDER BY `key`"),"stats","stat");

exportResultSet($dbConn->query("SELECT * FROM `techhelp` ORDER BY id"),"techhelp","entry");

append("</database>\n");

//Export Configuration
append("<config>\n");

foreach ($config->getTrees() as $tree) {
	append("\t<$tree>\n");
	foreach ($config->getNodes($tree) as $node) {
		$node = preg_replace("/^[0-9]*$/","num__$0",$node);
		append("\t\t<$node>".htmlspecialchars($config->getNode($tree,$node),ENT_QUOTES)."</$node>\n");
	}
	append("\t</$tree>\n");
}

append("</config>\n");

append("</fsexport>");

if (isset($fp)) fclose($fp);
?>