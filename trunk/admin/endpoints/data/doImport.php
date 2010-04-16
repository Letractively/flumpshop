<?php
//This file uses the event-based PHP Expat Parser to complicatedly read the uploaded XML file and deal with it accordingly.
require_once "../header.php";
ob_flush();flush();
$config->setNode("site","enabled",false);
echo "<h1>Import in Progress</h1>";
echo "<div class='ui-state-highlight'>The Flumpshop frontend has entered maintenance mode.</div>";
echo "<p>The Flumpnet Robot is now sifting through the database, and comparing and updating information based on the import file. Any existing information that conflicts with the import will be overwritten. Please wait for the page to finish loading.</p>";
$file = $_FILES['data']['tmp_name'];

$parser = xml_parser_create();
$currentElement = "";
$currentSubElement = "";
$currentNode = "";
$currentSection = "";
$dataCache = array();
$version = 1;

//Every element etc. Is in upper case. Don't know why, just is.
function startElement($parser, $element, $attributes) {
	global $version, $currentElement, $currentSubElement, $currentNode, $currentSection;
	switch ($element) {
		case "FSEXPORT":
		//This is just the container. Fetch the version
		$version = $attributes['VERSION'];
		break;
		default:
		//Section Container. Update section
		if ($currentSection == "") $currentSection = $element;
		//Must be a node. Update element or subelement depending on what's populated
		elseif ($currentElement != "") {
			if ($currentSubElement != "") $currentNode = $element; else $currentSubElement = $element;
		} else {
			$currentElement = $element;
			echo "Importing $element<br />";
		}
		break;
	}
}

function stopElement($parser, $element) {
	global $currentElement,$currentSubElement,$currentNode,$currentSection,$dataCache;
	
	if ($currentNode != "") $currentNode = "";
	elseif ($currentSubElement != "") {commitCache();$currentSubElement = "";}
	elseif ($currentElement != "") $currentElement = "";
	else $currentSection = "";
}

function getData($parser,$data) {
	global $config, $dbConn, $currentSection, $currentElement, $currentSubElement, $currentNode, $dataCache, $version;
	//This one's a mission. Find out what we're working with, then work with it
	switch ($currentSection) {
		case "DATABASE":
			//It's a database item we're working with
			//No need to check further, the data can just be cached until the commit query.
			$dataCache[$currentNode] = $data;
	}
}

function commitCache() {
	global $dbConn,$config,$currentSection,$currentSubElement,$dataCache;
	//Actually store the data
	switch ($currentSection) {
		case "DATABASE":
			switch ($currentSubElement) {
				case "ACPUSER":
					//Import an ACP User Account
					//Check if it exists
					if ($dbConn->query("SELECT id FROM `acp_login` WHERE id=".$dataCache['ID']." LIMIT 1")) {
						//Yep, run an update query
						if (!$dbConn->query("UPDATE `acp_login` SET
									   uname = '".$dataCache['UNAME']."',
									   pass = '".$dataCache['PASS']."',
									   last_login = '".$dataCache['LAST_LOGIN']."',
									   last_tier2_login = '".$dataCache['LAST_TIER2_LOGIN']."',
									   can_add_products = '".$dataCache['CAN_ADD_PRODUCTS']."',
									   can_edit_products = '".$dataCache['CAN_EDIT_PRODUCTS']."',
									   can_delete_products = '".$dataCache['CAN_DELETE_PRODUCTS']."',
									   can_add_categories = '".$dataCache['CAN_ADD_CATEGORIES']."',
									   can_edit_categories = '".$dataCache['CAN_EDIT_CATEGORIES']."',
									   can_delete_categories = '".$dataCache['CAN_DELETE_CATEGORIES']."',
									   can_edit_pages = '".$dataCache['CAN_EDIT_PAGES']."',
									   can_edit_delivery_rates = '".$dataCache['CAN_EDIT_DELIVERY_RATES']."',
									   can_post_news = '".$dataCache['CAN_POST_NEWS']."',
									   can_add_customers = '".$dataCache['CAN_ADD_CUSTOMERS']."',
									   can_contact_customers = '".$dataCache['CAN_CONTACT_CUSTOMERS']."',
									   can_view_customers = '".$dataCache['CAN_VIEW_CUSTOMERS']."',
									   can_view_orders = '".$dataCache['CAN_VIEW_ORDERS']."',
									   can_edit_orders = '".$dataCache['CAN_EDIT_ORDERS']."',
									   can_view_reports = '".$dataCache['CAN_VIEW_REPORTS']."',
									   pass_expires = '".$dataCache['PASS_EXPIRES']."'
									   WHERE id=".$dataCache['ID']." LIMIT 1")) {
							//Failed
							echo "<div class='ui-state-error'>An error occurred updating ACP User #".$dataCache['ID'].".</div>";
						} else {
							//Success
							debug_message("Updated ACP User #".$dataCache['ID'].".");
						}
					} else {
						//Nope, run an insert
						 if (!$dbConn->query("INSERT INTO `acp_login` (id,uname,pass,last_login,last_tier2_login,can_add_products,can_edit_products,can_delete_products,can_add_categories,can_edit_categories,can_delete_categories,can_edit_pages,can_edit_delivery_rates,can_post_news,can_add_customers,can_contact_customers,can_view_customers,can_view_orders,can_edit_orders,can_view_reports,pass_expires) VALUES ('".$dataCache['ID']."','".$dataCache['UNAME']."','".$dataCache['PASS']."','".$dataCache['LAST_LOGIN']."','".$dataCache['LAST_TIER2_LOGIN']."','".$dataCache['CAN_ADD_PRODUCTS']."','".$dataCache['CAN_EDIT_PRODUCTS']."','".$dataCache['CAN_DELETE_PRODUCTS']."','".$dataCache['CAN_ADD_CATEGORIES']."','".$dataCache['CAN_EDIT_CATEGORIES']."','".$dataCache['CAN_DELETE_CATEGORIES']."','".$dataCache['CAN_EDIT_PAGES']."','".$dataCache['CAN_EDIT_DELIVERY_RATES']."','".$dataCache['CAN_POST_NEWS']."','".$dataCache['CAN_ADD_CUSTOMERS']."','".$dataCache['CAN_CONTACT_CUSTOMERS']."','".$dataCache['CAN_VIEW_CUSTOMERS']."','".$dataCache['CAN_VIEW_ORDERS']."','".$dataCache['CAN_EDIT_ORDERS']."','".$dataCache['CAN_VIEW_REPORTS']."','".$dataCache['PASS_EXPIRES']."')")) {
							 //Failed
							 echo "<div class='ui-state-error'>An error occurred creating ACP User #".$dataCache['ID'].".</div>";
						 } else {
							 //Success
							 debug_message("Created ACP User #".$dataCache['ID'].".");
						 }
					}
					break;
				case "BASKET":
					break;
				case "BUG":
					break;
				case "CATEGORY":
					break;
				case "CATEGORY_FEATURE":
					break;
				case "FEATURE":
					break;
				case "COUNTRY":
					break;
				case "CUSTOMER":
					break;
				case "DELIVERY":
					break;
				case "FEATURE_UNIT":
					break;
				case "ITEM_CATEGORY":
					break;
				case "ITEM_FEATURE_DATE":
					break;
				case "ITEM_FEATURE_NUMBER":
					break;
				case "ITEM_FEATURE_STRING":
					break;
				case "KEY":
					break;
				case "USER":
					break;
				case "ENTRY":
					//Both news and techhelp here.
					break;
				case "NEWSLETTER":
					break;
				case "ORDER":
					break;
				case "PRODUCT":
					$item = new Item($dataCache['ID']);
					if (isset($dataCache['SKU'])) $item->itemSKU = $dataCache['SKU']; else $item->itemSKU = "N/A";
					$item->itemName = $dataCache['NAME'];
					$item->itemPrice = $dataCache['PRICE'];
					$item->itemCost = $dataCache['COST'];
					$item->itemStock = $dataCache['STOCK'];
					$item->itemDesc = $dataCache['DESCRIPTION'];
					$item->itemReducedPrice = $dataCache['REDUCEDPRICE'];
					$item->itemReductionStart = $dataCache['REDUCEDVALIDFROM'];
					$item->itemReductionEnd = $dataCache['REDUCEDEXPIRY'];
					$item->itemWeight = $dataCache['WEIGHT'];
					if (!$item->import()) {
						//Failed
						echo "<div class='ui-state-error'>Failed to import Item #".$dataCache['ID'].".</div>";
					} else {
						//Success
						debug_message("Imported item #".$dataCache['ID'].".");
					}
					break;
				case "BASKET":
					break;
				case "RESERVE":
					break;
				case "SESSION":
					break;
				case "STAT":
					break;
			}
	}
	$dataCache = array();
}

//Specify element handler
xml_set_element_handler($parser,"startElement","stopElement");

//Specify data handler
xml_set_character_data_handler($parser,"getData");

//Open XML file
$fp=fopen($file,"r");

//Read data
while ($data=fread($fp,4096))
  {
  xml_parse($parser,$data,feof($fp)) or 
  die (sprintf("XML Error: %s at line %d", 
  xml_error_string(xml_get_error_code($parser)),
  xml_get_current_line_number($parser)));
  }

//Free the XML parser
xml_parser_free($parser);
$config->setNode("site","enabled",true);
echo "<div class='ui-state-highlight'>The Flumpshop frontend is now operating normally.</div>";
echo "<strong>Import complete. Please review any potential errors above before continuing.</strong>";
?>