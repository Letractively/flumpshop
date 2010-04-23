<?php
//This file uses the event-based PHP Expat Parser to complicatedly read the uploaded XML file and deal with it accordingly.
//I do it this way as the system doesn't have to read the entire file into memory in order to parse it
require_once "../header.php";
ob_flush();flush(); //Don't cache output
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
			$dataCache[$currentNode] = str_replace("'","''",$data); //MySQL escape
	}
}

function commitCache() {
	global $dbConn,$config,$currentSection,$currentElement,$currentSubElement,$dataCache;
	//Actually store the data
	switch ($currentSection) {
		case "DATABASE":
			switch ($currentSubElement) {
				case "ACPUSER":
					//Import an ACP User Account
					//Delete any existing one
					$dbConn->query("DELETE FROM `acp_login` WHERE id='".$dataCache['ID']."' LIMIT 1");
					//Set Default if necessary
					if (!isset($dataCache['LAST_LOGIN']) or $dataCache['LAST_LOGIN'] == '') $dataCache['LAST_LOGIN'] = "0000-00-00 00:00:00";
					if (!isset($dataCache['LAST_TIER2_LOGIN']) or $dataCache['LAST_TIER2_LOGIN'] == '') $dataCache['LAST_TIER2_LOGIN'] = "0000-00-00 00:00:00";
					//Insert new one
					if (!$dbConn->query("INSERT INTO `acp_login` (id,uname,pass,last_login,last_tier2_login,can_add_products,can_edit_products,can_delete_products,can_add_categories,can_edit_categories,can_delete_categories,can_edit_pages,can_edit_delivery_rates,can_post_news,can_add_customers,can_contact_customers,can_view_customers,can_view_orders,can_edit_orders,can_view_reports,pass_expires) VALUES ('".$dataCache['ID']."','".$dataCache['UNAME']."','".$dataCache['PASS']."','".$dataCache['LAST_LOGIN']."','".$dataCache['LAST_TIER2_LOGIN']."','".$dataCache['CAN_ADD_PRODUCTS']."','".$dataCache['CAN_EDIT_PRODUCTS']."','".$dataCache['CAN_DELETE_PRODUCTS']."','".$dataCache['CAN_ADD_CATEGORIES']."','".$dataCache['CAN_EDIT_CATEGORIES']."','".$dataCache['CAN_DELETE_CATEGORIES']."','".$dataCache['CAN_EDIT_PAGES']."','".$dataCache['CAN_EDIT_DELIVERY_RATES']."','".$dataCache['CAN_POST_NEWS']."','".$dataCache['CAN_ADD_CUSTOMERS']."','".$dataCache['CAN_CONTACT_CUSTOMERS']."','".$dataCache['CAN_VIEW_CUSTOMERS']."','".$dataCache['CAN_VIEW_ORDERS']."','".$dataCache['CAN_EDIT_ORDERS']."','".$dataCache['CAN_VIEW_REPORTS']."','".$dataCache['PASS_EXPIRES']."')")) {
						 //Failed
						 echo "<div class='ui-state-error'>An error occurred creating ACP User #".$dataCache['ID'].".</div>";
					 } else {
						 //Success
						 debug_message("Created ACP User #".$dataCache['ID'].".");
					 }
					break;
				case "BASKET":
					//Easy, since this is an actual object
					$cart = unserialize(base64_decode($dataCache['OBJ']));
					if ($cart->import()) debug_message("Imported Cart #".$dataCache['ID']."."); else {
						echo "<div class='ui-state-error'>An error occurred importing Cart #".$dataCache['ID'].".</div>";
					}
					unset($cart);
					break;
				case "BUG":
					//Import feedback
					//Delete old
					$dbConn->query("DELETE FROM `bugs` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `bugs` (id,header,body,resolved,assignedTo) VALUES ('".$dataCache['ID']."', '".$dataCache['HEADER']."', '".$dataCache['BODY']."', '".$dataCache['RESOLVED']."', '".$dataCache['ASSIGNEDTO']."')")) {
						//Success
						debug_message("Imported bug #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Bug #".$dataCache['ID'].".</div>";
					}
					break;
				case "CATEGORY":
					//Import Category
					//Delete old
					$dbConn->query("DELETE FROM `category` WHERE id='".$dataCache['ID']."' LIMIT 1");
					//Defaults for empty values
					if (!isset($dataCache['DESCRIPTION'])) $dataCache['DESCRIPTION'] = "";
					//Do
					if ($dbConn->query("INSERT INTO `category` (id,name,description,parent,enabled) VALUES ('".$dataCache['ID']."','".$dataCache['NAME']."','".$dataCache['DESCRIPTION']."','".$dataCache['PARENT']."','".$dataCache['ENABLED']."')")) {
						//Success
						debug_message("Imported category #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Category #".$dataCache['ID'].".</div>";
					}
					break;
				case "CATEGORY_FEATURE":
					//Import Category/Feature Reference
					//Delete old
					$dbConn->query("DELETE FROM `category_feature` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `category_feature` (id,category_id,feature_id) VALUES ('".$dataCache['ID']."','".$dataCache['CATEGORY_ID']."','".$dataCache['FEATURE_ID']."')")) {
						//Success
						debug_message("Imported Category/Feature Reference #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Category/Feature Reference #".$dataCache['ID'].".</div>";
					}
					break;
				case "FEATURE":
					//Import Feature
					//Set Default
					if (!isset($dataCache['DEFAULT_VALUE'])) $dataCache['DEFAULT_VALUE'] = "";
					//Delete old
					$dbConn->query("DELETE FROM `compare_features` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `compare_features` (id,feature_name,data_type,default_value) VALUES ('".$dataCache['ID']."','".$dataCache['FEATURE_NAME']."','".$dataCache['DATA_TYPE']."','".$dataCache['DEFAULT_VALUE']."')")) {
						//Success
						debug_message("Imported Feature #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Feature #".$dataCache['ID'].".</div>";
					}
					break;
				case "COUNTRY":
					//Import Country
					//Delete old
					$dbConn->query("DELETE FROM `country` WHERE iso='".$dataCache['ISO']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `COUNTRY` (iso,name,supported,currency) VALUES ('".$dataCache['ISO']."','".$dataCache['NAME']."','".$dataCache['SUPPORTED']."','".$dataCache['CURRENCY']."')")) {
						//Success
						debug_message("Imported Country ".$dataCache['ISO'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Country ".$dataCache['ISO'].".</div>";
					}
					break;
				case "CUSTOMER":
					//Import Customer
					//Delete old
					$dbConn->query("DELETE FROM `customers` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `customers` (id,name,address1,address2,address3,postcode,country,email,paypalid,archive,can_contact) VALUES ('".$dataCache['ID']."','".$dataCache['NAME']."','".$dataCache['ADDRESS1']."','".$dataCache['ADDRESS2']."','".$dataCache['ADDRESS3']."','".$dataCache['POSTCODE']."','".$dataCache['COUNTRY']."','".$dataCache['EMAIL']."','".$dataCache['PAYPALID']."','".$dataCache['ARCHIVE']."','".$dataCache['CAN_CONTACT']."')")) {
						//Success
						debug_message("Imported Customer #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Customer #".$dataCache['ID'].".</div>";
					}
					break;
				case "DELIVERY":
					//Import Delivery Rate
					//Delete old
					$dbConn->query("DELETE FROM `delivery` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `delivery` (id,country,lowerbound,upperbound,price) VALUES ('".$dataCache['ID']."','".$dataCache['COUNTRY']."','".$dataCache['LOWERBOUND']."','".$dataCache['UPPERBOUND']."','".$dataCache['PRICE']."')")) {
						//Success
						debug_message("Imported Delivery Rate #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Delivery Rate #".$dataCache['ID'].".</div>";
					}
					break;
				case "FEATURE_UNIT":
					//Import Feature Unit
					//Delete old
					$dbConn->query("DELETE FROM `feature_units` WHERE feature_id='".$dataCache['FEATURE_ID']."' AND multiple='".$dataCache['MULTIPLE']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `feature_units` (feature_id,multiple,unit) VALUES ('".$dataCache['FEATURE_ID']."','".$dataCache['MULTIPLE']."','".$dataCache['UNIT']."')")) {
						//Success
						debug_message("Imported Feature Unit #".$dataCache['FEATURE_ID']."-".$dataCache['MULTIPLE'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Customer #".$dataCache['FEATURE_ID']."-".$dataCache['MULTIPLE'].".</div>";
					}
					break;
				case "ITEM_CATEGORY":
					//Import Item/Category Reference
					//Delete old
					$dbConn->query("DELETE FROM `item_category` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `item_category` (id,itemid,catid) VALUES ('".$dataCache['ID']."','".$dataCache['ITEMID']."','".$dataCache['CATID']."')")) {
						//Success
						debug_message("Imported Item/Category Reference #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Item/Category Reference #".$dataCache['ID'].".</div>";
					}
					break;
				case "ITEM_FEATURE_DATE":
					//Import Item Feature Date Value
					//Delete old
					$dbConn->query("DELETE FROM `item_feature_date` WHERE item_id='".$dataCache['ITEM_ID']."' AND feature_id='".$dataCache['FEATURE_ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `item_feature_date` (item_id,feature_id,value) VALUES ('".$dataCache['ITEM_ID']."','".$dataCache['FEATURE_ID']."','".$dataCache['VALUE']."')")) {
						//Success
						debug_message("Imported Item Feature (Date) Value #".$dataCache['ITEM_ID']."-".$dataCache['FEATURE_ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Item Feature (Date) Value #".$dataCache['ITEM_ID']."-".$dataCache['FEATURE_ID'].".</div>";
					}
					break;
				case "ITEM_FEATURE_NUMBER":
					//Import Item Feature Numeric Value
					//Delete old
					$dbConn->query("DELETE FROM `item_feature_number` WHERE item_id='".$dataCache['ITEM_ID']."' AND feature_id='".$dataCache['FEATURE_ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `item_feature_number` (item_id,feature_id,value) VALUES ('".$dataCache['ITEM_ID']."','".$dataCache['FEATURE_ID']."','".$dataCache['VALUE']."')")) {
						//Success
						debug_message("Imported Item Feature (Numeric) Value #".$dataCache['ITEM_ID']."-".$dataCache['FEATURE_ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Item Feature (Numeric) Value #".$dataCache['ITEM_ID']."-".$dataCache['FEATURE_ID'].".</div>";
					}
					break;
				case "ITEM_FEATURE_STRING":
					//Import Item Feature String Value
					//Delete old
					$dbConn->query("DELETE FROM `item_feature_string` WHERE item_id='".$dataCache['ITEM_ID']."' AND feature_id='".$dataCache['FEATURE_ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `item_feature_string` (item_id,feature_id,value) VALUES ('".$dataCache['ITEM_ID']."','".$dataCache['FEATURE_ID']."','".$dataCache['VALUE']."')")) {
						//Success
						debug_message("Imported Item Feature (String) Value #".$dataCache['ITEM_ID']."-".$dataCache['FEATURE_ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Item Feature (String) Value #".$dataCache['ITEM_ID']."-".$dataCache['FEATURE_ID'].".</div>";
					}
					break;
				case "KEY":
					//Import Key
					//Delete old
					$dbConn->query("DELETE FROM `keys` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `keys` (id,action,`key`,expiry,expiryAction,uid) VALUES ('".$dataCache['ID']."','".$dataCache['ACTION']."','".$dataCache['KEY']."','".$dataCache['EXPIRY']."','".$dataCache['EXPIRYACTION']."','".$dataCache['UID']."')")) {
						//Success
						debug_message("Imported Key #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Key #".$dataCache['ID'].".</div>";
					}
					break;
				case "USER":
					//Import User
					//Delete old
					$dbConn->query("DELETE FROM `login` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `login` (id,uname,password,customer,active,can_contact) VALUES ('".$dataCache['ID']."','".$dataCache['UNAME']."','".$dataCache['PASSWORD']."','".$dataCache['CUSTOMER']."','".$dataCache['ACTIVE']."','".$dataCache['CAN_CONTACT']."')")) {
						//Success
						debug_message("Imported User #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing User #".$dataCache['ID'].".</div>";
					}
					break;
				case "ENTRY":
					//Both news and techhelp here. $currentElement = table name
					//Import News Entry
					//Defaults
					if (!isset($dataCache['TIMESTAMP']) or $dataCache['TIMESTAMP'] == '' or $dataCache['TIMESTAMP'] == "CURRENT_TIMESTAMP") $dataCache['TIMESTAMP'] = $dbConn->time();
					//Delete old
					$dbConn->query("DELETE FROM `".$currentElement."` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `".$currentElement."` (id,title,body,timestamp) VALUES ('".$dataCache['ID']."','".$dataCache['TITLE']."','".$dataCache['BODY']."','".$dataCache['TIMESTAMP']."')")) {
						//Success
						debug_message("Imported Entry #".$currentElement."-".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Entry #".$currentElement."-".$dataCache['ID'].".</div>";
					}
					break;
				case "NEWSLETTER":
					//Import Newsletter
					//Delete old
					$dbConn->query("DELETE FROM `newsletters` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `newsletters` (id,title,body) VALUES ('".$dataCache['ID']."','".$dataCache['TITLE']."','".$dataCache['BODY']."')")) {
						//Success
						debug_message("Imported Newsletter #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Newsletter #".$dataCache['ID'].".</div>";
					}
					break;
				case "ORDER":
					//Import Order
					//Delete old
					$dbConn->query("DELETE FROM `orders` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `orders` (id,basket,status,token,customer) VALUES ('".$dataCache['ID']."','".$dataCache['BASKET']."','".$dataCache['STATUS']."','".$dataCache['TOKEN']."','".$dataCache['CUSTOMER']."')")) {
						//Success
						debug_message("Imported Order #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Order #".$dataCache['ID'].".</div>";
					}
					break;
				case "PRODUCT":
					$item = new Item(-1);
					if (isset($dataCache['SKU'])) $item->itemSKU = $dataCache['SKU']; else $item->itemSKU = "N/A";
					$item->itemID = $dataCache['ID'];
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
						debug_message("Imported Item #".$dataCache['ID'].".");
					}
					break;
				case "RESERVE":
					//Import Item Reserve
					//Delete old
					$dbConn->query("DELETE FROM `reserve` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `reserve` (id,item,quantity,expire) VALUES ('".$dataCache['ID']."','".$dataCache['ITEM']."','".$dataCache['QUANTITY']."','".$dataCache['EXPIRE']."')")) {
						//Success
						debug_message("Imported Item Reserve #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Item Reserve #".$dataCache['ID'].".</div>";
					}
					break;
				case "SESSION":
					//Import Session
					//Defaults
					if (!isset($dataCache['ACTIVE']) or $dataCache['ACTIVE'] == '' or $dataCache['ACTIVE'] == "CURRENT_TIMESTAMP") $dataCache['ACTIVE'] = $dbConn->time();
					//Delete old
					$dbConn->query("DELETE FROM `sessions` WHERE session_id='".$dataCache['SESSION_ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `sessions` (session_id,basket,active,ip_addr) VALUES ('".$dataCache['SESSION_ID']."','".$dataCache['BASKET']."','".$dataCache['ACTIVE']."','".$dataCache['IP_ADDR']."')")) {
						//Success
						debug_message("Imported Session #".$dataCache['SESSION_ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Session #".$dataCache['SESSION_ID'].".</div>";
					}
					break;
				case "STAT":
					//Import Stat
					//Delete old
					$dbConn->query("DELETE FROM `stats` WHERE id='".$dataCache['ID']."' LIMIT 1");
					if ($dbConn->query("INSERT INTO `stats` (id,`key`,value) VALUES ('".$dataCache['ID']."','".$dataCache['KEY']."','".$dataCache['VALUE']."')")) {
						//Success
						debug_message("Imported Stat #".$dataCache['ID'].".");
					} else {
						//Fail
						echo "<div class='ui-state-error'>An error occurred importing Stat #".$dataCache['ID'].".</div>";
					}
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