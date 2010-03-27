<?php
class Item {
	//Define Variables
	var $itemResult;
	var $itemID;
	var $itemName;
	var $itemPrice;
	var $itemStock;
	var $itemDesc;
	var $itemURL;
	var $itemModifyURL;
	var $itemCategory = array();
	var $itemReducedPrice;
	var $itemReductionStart;
	var $itemReductionEnd;
	var $itemWeight;
	var $itemDeliveryCost;
	var $itemActive = 1;
	var $change = false;
	
	//Constructor
	function Item($id) {
		str_replace("'","''",$id);
		global $dbConn, $config;
		$this->itemQuery = $dbConn->query("SELECT * FROM `products` WHERE id='$id' LIMIT 1");
		if ($dbConn->rows($this->itemQuery) == 0) {
			trigger_error("Failed to locate item in Database (id: $id)");
			$this->setDefaults();
		} else {
			$this->itemResult = $dbConn->fetch($this->itemQuery);
			if ($this->itemResult['active'] == 0) {
				$this->setDefaults();
				$this->itemID = $this->itemResult['id'];
				$this->itemName = str_replace("\\","",$this->itemResult['name']);
			} else {
				$this->itemID = $id;
				$this->itemName = str_replace("\\","",$this->itemResult['name']);
				$this->itemPrice = $this->itemResult['price'];
				$this->itemStock = $this->itemResult['stock'];
				$this->itemDesc = str_replace("\\","",$this->itemResult['description']);
				//Categories
				$result = $dbConn->query("SELECT catid FROM `item_category` WHERE itemid='".$id."'");
				while ($row = $dbConn->fetch($result)) {
					$this->itemCategory[] = $row['catid'];
				}
				//Price Reduction
				$this->itemReducedPrice = $this->itemResult['reducedPrice'];
				$this->itemReductionStart = $this->itemResult['reducedValidFrom'];
				$this->itemReductionEnd = $this->itemResult['reducedExpiry'];
				$this->itemWeight = $this->itemResult['weight'];
				$this->itemActive = 1;
				//URLs
				if ($config->getNode('server','rewrite')) {
					$this->itemURL = $config->getNode('paths','root')."/item/".$this->itemID."/".str_replace(" ","_",$this->itemName)."/";
					$this->itemModifyURL = $config->getNode('paths','root')."/item/".$this->itemID."/".str_replace(" ","_",$this->itemName)."/true";
				} else {
					$this->itemURL = $config->getNode('paths','root')."/item/?id=".$this->itemID;
					$this->itemModifyURL = $config->getNode('paths','root')."/item/?id=".$this->itemID."&modify=true";
				}
				//Delivery Rate
				if ($config->isNode("temp","country")) $country = $config->getNode("temp","country"); else $country = $config->getNode("site","country");
				$result = $dbConn->query("SELECT price FROM `delivery` WHERE lowerbound>='$this->itemWeight' AND upperbound<='$this->itemWeight' AND `country`='$country' LIMIT 1");
				if ($dbConn->rows($result) == 0) {
					$this->itemDeliveryCost = -1;
				} else {
					$result = $dbConn->fetch($result);
					$this->itemDeliveryCost = $result['price'];
				}
			}
		}
	}
	
	function setDefaults() {
		global $config;
		if (!stristr($_SERVER['HTTP_REFERER'],"admin/doImport.php")) {//Stop reset on import
			$this->itemID = -1;
			$this->itemName = $config->getNode("messages", "itemDefaultName");
			$this->itemPrice = 0;
			$this->itemStock = 0;
			$this->itemDesc = $config->getNode("messages", "itemDefaultDesc");
			$this->itemURL = "javascript:void(0);";
			$this->itemCategory[0] = 0;
			$this->itemReducedPrice = 0;
			$this->itemReductionStart = strtotime(0);
			$this->itemReductionEnd = strtotime(0);
			$this->itemWeight = 0.00;
			$this->itemDeliveryCost = -1;
			$this->itemActive = 1;
		}
	}
	
	//Destructor
	function __destruct() {
		$this->save();
	}
	
	function save() {
		if ($this->change) {
			global $dbConn;
			debug_message("Commiting Changes to Item #".$this->getID());
			$name = str_replace("'","''",$this->getName());
			$price = str_replace("'","''",$this->getPrice());
			$stock = str_replace("'","''",$this->getStock());
			$desc = str_replace("'","''",$this->getDesc());
			$categories = $this->getCategories();
			$reducedPrice = $this->itemReducedPrice;
			$reduceStart = $this->itemReductionStart;
			$reduceEnd = $this->itemReductionEnd;
			$dbConn->query("UPDATE `products` SET name='$name', price='$price', stock='$stock', description='$desc', reducedPrice='$reducedPrice', reducedValidFrom='$reduceStart', reducedExpiry='$reduceEnd' WHERE id=".$this->getID()." LIMIT 1");
			$catstring = "";
			foreach ($categories as $category) {
				//Update each category reference
				if ($dbConn->rows($dbConn->query("SELECT id FROM `item_category` WHERE itemid=".$this->getID()." AND catid=".$category." LIMIT 1")) == 0) {
					//Category reference not yet created
					$dbConn->query("INSERT INTO `item_category` (itemid,catid) VALUES (".$this->getID().",$category)");
				}
				//Append category string for old category clearing
				$catstring .= " AND catid!=".$category;
			}
			//Check there are no old category references
			$dbConn->query("DELETE FROM `item_category` WHERE itemid=".$this->getID().$catstring);
		}
	}
	
	//Import
	function import() {
		global $dbConn;
		//Upgrade
		if (!isset($this->itemActive)) $this->itemActive = 1;
		//Do
		if ($dbConn->rows($dbConn->query("SELECT id FROM `products` WHERE id=".$this->getID()." LIMIT 1"))) {
			$query = "UPDATE `products` SET name='$this->itemName', price='$this->itemPrice', stock='$this->itemPrice', description='$this->itemDesc', reducedPrice='$this->itemReducedPrice', reducedValidFrom='$this->itemReductionStart', reducedExpiry='$this->itemReductionEnd', category='$this->itemCategory',weight='$this->itemWeight', active='$this->itemActive' WHERE id=".$this->getID()." LIMIT 1";
		} else {
			$query = "INSERT INTO `products` (id,name,price,stock,description,category,reducedPrice,reducedValidFrom,reducedExpiry,weight,active) VALUES ($this->itemID,'$this->itemName','$this->itemPrice','$this->itemStock','$this->itemDesc','$this->itemCategory','$this->itemReducedPrice','$this->itemReductionStart','$this->itemReductionEnd','$this->itemWeight','$this->itemActive')";
		}
		return $dbConn->query($query);
	}
	
	//Convert and store an uploaded image
	function saveImage($file, $ftype = "application/octet-stream") {
		global $config;
		
		$imgnum = 0;
		while (file_exists($config->getNode('paths','offlineDir')."/images/item_".$this->itemID."/full_$imgnum.png")) {
			$imgnum++;
		}
		
		if ($config->getNode("temp","fileinfo")) {
			$info = finfo_open(FILEINFO_MIME_TYPE);
			$type = finfo_file($info,$file);
		} elseif (function_exists("mime_content_type")) {
			$type = mime_content_type($file);
		} else {
			$type = $ftype; //Insecure to use $_FILES
		}
		switch ($type) {
			case "image/jpeg":
				debug_message("JPEG Image Detected");
				$image = imagecreatefromjpeg($file);
				break;
			case "image/gif":
				debug_message("GIF Image Detected");
				$image = imagecreatefromgif($file);
				break;
			case "image/png":
				debug_message("PNG Image Detected");
				$image = imagecreatefrompng($file);
				break;
			case "image/bmp":
			case "image/wbmp":
				debug_message("BMP Image Detected");
				$image = imagecreatefromwbmp($file);
				break;
			default:
				return false;
		}
		
		if (!file_exists($config->getNode('paths','offlineDir')."/images/item_".$this->itemID)) {
			mkdir($config->getNode('paths','offlineDir')."/images/item_".$this->itemID);
		}
		
		//800x650
		list($img_w,$img_h) = getimagesize($file);
		$f = min(800/$img_w, 650/$img_h, 1); 
		$w = round($f * $img_w); 
		$h = round($f * $img_h); 
		
		$fullimage = imagecreatetruecolor($w,$h);
		imagecopyresampled($fullimage,$image,0,0,0,0,$w,$h,$img_w,$img_h);
		
		imagepng($fullimage,$config->getNode('paths','offlineDir')."/images/item_".$this->itemID."/full_$imgnum.png");
		imagedestroy($fullimage);
		
		//150x150
		$scale = 150*$config->getNode('viewItem','imageScale');
		$f = min($scale/$img_w, $scale/$img_h, 1); 
		$w = round($f * $img_w); 
		$h = round($f * $img_h); 
		$smallimage = imagecreatetruecolor($w,$h);
		imagecopyresampled($smallimage,$image,0,0,0,0,$w,$h,$img_w,$img_h);
		
		imagepng($smallimage,$config->getNode('paths','offlineDir')."/images/item_".$this->itemID."/thumb_$imgnum.png");
		imagedestroy($smallimage);
		
		//45x45
		$f = min(45/$img_w, 45/$img_h, 1); 
		$w = round($f * $img_w); 
		$h = round($f * $img_h); 
		$miniimage = imagecreatetruecolor($w,$h);
		imagecopyresampled($miniimage,$image,0,0,0,0,$w,$h,$img_w,$img_h);
		
		imagepng($miniimage,$config->getNode('paths','offlineDir')."/images/item_".$this->itemID."/minithumb_$imgnum.png");
		imagedestroy($miniimage);
		return true;
	}
	
	//Schedule a price reduction
	function setReduction($price,$start = 0, $end = 0) {
		$this->change = true;
		$this->itemReducedPrice = $price;
		$this->itemReductionStart = $start;
		$this->itemReductionEnd = $end;
	}
	
	//Check for sufficient stock
	function checkStock($num = 1) {
		global $_PRINTDATA;
		debug_message("Checking for $num/$this->itemStock Stock");
		return $this->itemStock >= $num;
	}
	
	//Return Product Info
	function getDetails($type,$int = 0) {
		//Int Usage: Quantity for Basket View (Integer), Edit Mode for Full View (Boolean), $higlight for Search View (String/Array)
		global $config, $stats, $dbConn;
		$type = strtoupper($type); //Standardize for easy comparison
		if ($type == "INDEX") {
			//Image
			$reply = "<a href='".$this->getURL()."'><img src='".$config->getNode('paths','root')."/item/imageProvider.php?id=".$this->getID()."&image=0&size=thumb' alt='".$this->getName()."' /></a>";
			//Title
			$reply .= "<h5><a href='".$this->getURL()."'>".$this->getName()."</a></h5>";
			//Price (TODO)
			if ($config->getNode("site","shopEnabled")) {
				//$reply .= "<em>&pound;".$this->itemPrice."</em><span class='ui-state-disabled'>&nbsp;ex.VAT</span>";
			}
			//Description
			if (strlen($this->getDesc()) > $config->getNode('viewItem','homeChars')) {
				//Trim
				$string = substr($this->getDesc(),0,$config->getNode('viewItem','homeChars'))."...";
			} else {
				$string = $this->getDesc();
			}
			$reply .= "<p>".$string."</p>";
		}
		if ($type == "CATEGORY") {
			$reply = "<table><tr><td>";
			//Image
			$reply .= "<a href='".$this->getURL()."' class='ui-widget-content'><img src='".$config->getNode('paths','root')."/item/imageProvider.php?id=".$this->getID()."&image=0&size=thumb' style='margin-right: 1em; margin-top: 1em; max-height: ".(150*$config->getNode("viewItem","imageScale"))."px; max-width: ".(150*$config->getNode("viewItem","imageScale"))."px;' alt='".$this->getName()."' /></a>";
			$reply .= "</td>";//End Image TD
			if (strtolower($config->getNode('viewItem','catTextPos')) == "bottom") $reply .= "</tr><tr>";
			$reply .= "<td>";//Start Content TD
			$reply .= "<h3 style='font-size: 0.8em;'><a href='".$this->getURL()."' class='ui-widget-content'>".$this->getName()."</a></h3>"; //TITLE
			if ($config->getNode("site","shopEnabled")) {
				$reply .= "<em>&pound;".$this->itemPrice."</em><span class='ui-state-disabled'>".$config->getNode("messages","exVAT")."</span>"; //Price
			}
			if (strlen($this->getDesc()) > $config->getNode('viewItem','catChars')) $reply .= "<p style='font-size: 0.8em;'>".substr($this->getDesc(),0,$config->getNode('viewItem','catChars'))."...</p>";
			else $reply .= "<p style='font-size: 0.8em;'>".$this->getDesc()."</p>";
			$reply .= "</td></tr></table>"; //Close Container
		}
		if ($type == "SEARCH") {
			$reply = "<h3 style='margin-bottom: 0;'><a href='".$this->getURL()."' class='ui-widget-content'>".$this->getName($int)."</a></h3>";
			$reply .= "<span style='width: 100px; height: 100px;'><img src='".$config->getNode('paths','root')."/item/imageProvider.php?id=".$this->getID()."&image=0&size=thumb' style='float: left; margin-right: 1em; margin-top: 1em;' alt='".$this->getName()."' /></span>";
			if (strlen($this->getDesc()) > 250) $reply .= "<p style='overflow: hidden; height: 150px;'>".substr($this->getDesc($int),0,250)."...</p>";
			else $reply .= "<p style='overflow: hidden; height: 150px;'>".$this->getDesc()."</p>";
		}
		if ($type == "FULL") {
			$reply = "";
			if ($int === true && (isset($_SESSION['adminAuth']) && $_SESSION['adminAuth'] == true)) {
				//Image Upload
				$reply .= "<form action='".$config->getNode('paths','root')."/item/upload.php' method='post' enctype='multipart/form-data'><label for='file'>Upload Image: </label><input type='file' name='image' id='image' class='ui-state-default' /><input type='submit' class='ui-state-default' value='Upload' /><input type='hidden' name='id' id='id' value='".$this->getID()."' /></form>";
				
				//Change Category
				$reply .= "<form action='".$config->getNode('paths','root')."/item/update.php?pid=category' method='post' enctype='multipart/form-data' onsubmit='$(\"#notice\").html(loadMsg(\"Updating Category...\"));$(this).ajaxSubmit({target: \"#notice\"}); return false;'><select name='newCat' id='newCat' class='ui-widget-content'><option value='0'>Uncategorised</option>";
				$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
				while ($row = $dbConn->fetch($result)) {
					$parentCategory = new Category($row['id']);
					$selected = "";
					if ($this->getCategory(0) == $row['id']) $selected = " selected='selected'";
					$reply .= "<option value='".$parentCategory->getID()."'$selected>".$parentCategory->getFullName()."</option>";
				}
				$reply .= "</select><input type='submit' class='ui-state-default' value='Change Category' /><input type='hidden' name='id' id='id' value='".$this->getID()."' /></form>";
				
				//Disable
				$reply .= '<a href="javascript:void(0);" onclick="$(\'#notice\').html(loadMsg(\'Hiding Product...\')).load(\''.$config->getNode('paths','root').'/admin/endpoints/process/disableItem.php?id='.$this->getID().'\');">Hide Product</a>';
			}
			
			$reply .= "<div id='page_text'><h3 id='page_title'>".$this->getName()."</h3>";
			//Images
			$scale = 150*$config->getNode("viewItem","imageScale");
			$reply .= "<div id='item_image_container'><img src='".$config->getNode('paths','root')."/item/imageProvider.php?id=".$this->getID()."&amp;image=0&amp;size=thumb' onclick='openImageViewer(0);' style='cursor: pointer' alt='Image (Click to view full size)' />";
			
			$num = 0;
			while (file_exists($config->getNode('paths','offlineDir')."/images/item_".$this->getID()."/minithumb_$num.png")) {
				if (is_int(($num)/3)) $reply .= "<br />";
				$reply .= "<span style='width: 45px; height: 45px;'><img src='".$config->getNode('paths','root')."/item/imageProvider.php?id=".$this->getID()."&amp;image=$num&amp;size=minithumb' onclick='openImageViewer($num);' style='border: 1px solid #000; cursor: pointer;' alt='Image (Click to view full size)' /></span>";
				$num++;
			}
			
			$reply .= "</div>"; //Close Images Div
			
			/*Shop Mode (Removes Prices/Checkout)*/
			if ($config->getNode("site","shopMode")) {
				//Price
				if (time() > strtotime($this->itemReductionStart) && (time() < strtotime($this->itemReductionEnd) xor $this->itemReductionEnd == "1970-01-01 01:00:00")) {//Is there a special offer?
					$reply .= "<span style='text-decoration: line-through;'>&pound;".$this->itemPrice."</span>&nbsp;<strong>&pound;<span id='itemPrice'>".$this->itemReducedPrice."</span><span class='ui-state-disabled'>&nbsp;".$config->getNode("messages","exVAT")."</span></strong>";
					if ($this->itemReductionEnd != "1970-01-01 01:00:00") $reply .= "<em>".$config->getNode("messages","itemReductionUntil")." ".date("d-m-y",strtotime($this->itemReductionEnd))."</em>";
				} else {
					$reply .= "<em>&pound;<span id='itemPrice'>".$this->itemPrice."</span></em><span class='ui-state-disabled'>&nbsp;".$config->getNode("messages","exVAT")."</span>";
				}
				if ($int === true) {
					$reply .= "&nbsp;<a href='javascript:void(0);' onclick='reduceItem();'>Create Special Offer</a>";
					if (strtotime($this->itemReductionStart) > time()) {
						$reply .= " | A reduction is scheduled to start on ".date("d-m-y",strtotime($this->itemReductionStart)).".";
					}
				}
				//Delivery Price
				if ($this->itemDeliveryCost == -1) {
					$reply .= "&nbsp;".$config->getNode("messages","itemDeliveryUnavail");
				} else {
					$reply .= "&nbsp;".$config->getNode("messages","itemDelivery")." &pound;".$this->itemDeliveryCost;
				}
				//Stock
				if ($this->getStock() != 0) $reply .= "<div class='ui-state-default'><span id='itemStock'>".$this->getStock()."</span> Available. <a href='".$config->getNode('paths','root')."/basket.php?item=".$this->getID()."'>Add to Basket</a></div>";
				else $reply .= "<div class='ui-state-disabled'><span id='itemStock'>Out of Stock. </span><a href='javascript:void(0);'>Notify me when available</a></div>";
			}
			//Description
			if ($config->getNode("viewItem", "showID")) {
				$reply .= "<div class='ui-state-highlight'><strong>Product Code: </strong>".$this->getID()."</div><p id='itemDesc'>";
			} else {
				$reply .= '<p id="itemDesc">';
			}
			$reply .= $this->getDesc()."</p>";
			//Tracker
			//TODO: Unique only - Store Visited Array in session obj?
			$stats->incStat("item".$this->getID()."Hits");
			if ($int && (isset($_SESSION['adminAuth']) && $_SESSION['adminAuth'] == true)) $reply .= "This page has been viewed ".$stats->getStat("item".$this->getID()."Hits")." times.";
			$reply .= "</div>";
		}
		if ($type == "BASKET") {
			$reply = "<h4>".$this->getName()."</h4>";
			$reply .= "<div class='ui-widget-content ui-corner-bottom'>&pound;".$this->itemPrice." (x$int)";
			$reply .= "<div style='float: right;'>".$this->getStock()." Available</div>";
			$reply .= "<div class='ui-state-default'>";
			$reply .= "<span class='ui-icon ui-icon-trash' onclick='removeItem(".$this->getID().");' title='Remove' style='cursor: pointer;'>&nbsp;</span>";
			$reply .= "<span class='ui-icon ui-icon-plus' onclick='editQuantity(".$this->getID().",".$int.",".$this->getStock().");' title='Quantity' style='cursor: pointer;'>&nbsp;</span>";
			$reply .= "&nbsp;</div></div>";
		}
		if ($type == "ORDER") {
			$reply = "<a href='".$this->getURL()."'>".$this->getName()."</a> (x$int)";
		}
		return $reply;
	}
	
	//Getters/Setters
	function getID() {
		return $this->itemID;
	}
	
	function getName($highlight = NULL) {
		if ($highlight != NULL) {
			if (is_array($highlight)) {
				$return = $this->itemName;
				foreach ($highlight as $string) {
					$return = preg_replace("/($string)/i","<span class='ui-state-highlight'>$1</span>",$return);
				}
				return $return;
			} else {
				return preg_replace("/($highlight)/i","<span class='ui-state-highlight'>$1</span>",$this->itemName);
			}
		} else {
			return $this->itemName;
		}
	}
	
	function getPrice() {
		if (time() > strtotime($this->itemReductionStart) && (time() < strtotime($this->itemReductionEnd) xor $this->itemReductionEnd == "1970-01-01 01:00:00")) {return $this->itemReducedPrice;} else return $this->itemPrice;
	}
	
	function getFriendlyPrice() {
		return number_format($this->itemPrice,2);
	}
	
	function getStock() {
		return $this->itemStock;
	}
	
	function reduceStock($amount, $commit = true) {
		if (!$this->checkStock($amount)) return false;
		$this->itemStock -= $amount;
		if ($commit) return $dbConn->query("UPDATE `products` SET stock='".$this->getStock()."' WHERE id='".$this->getID()."' LIMIT 1");
		return true;
	}
	
	function getDesc($highlight = NULL) {
		if ($highlight != NULL) {
			if (is_array($highlight)) {
				$return = $this->itemDesc;
				foreach ($highlight as $string) {
					$return = preg_replace("/($string)/i","<span class='ui-state-highlight'>$1</span>",$return);
				}
				return $return;
			} else {
				return preg_replace("/($highlight)/i","<span class='ui-state-highlight'>$1</span>",$this->itemDesc);
			}
		} else {
			return $this->itemDesc;
		}
	}
	
	function getURL() {
		return $this->itemURL;
	}
	
	function getModifyURL() {
		return $this->itemModifyURL;
	}
	
	function getCategory($int = 0) {
		return $this->itemCategory[$int];
	}
	
	function getCategories() {
		return $this->itemCategory;
	}
	
	function getWeight() {
		return $this->itemWeight;
	}
	
	function getDeliveryCost() {
		return $this->itemDeliveryCost;
	}
	
	function setName($str) {
		$this->itemName = $str;
		$this->change = true;
	}
	
	function setPrice($str) {
		$this->itemPrice = $str;
		$this->change = true;
	}
	
	function setStock($str) {
		$this->itemStock = $str;
		$this->change = true;
	}
	
	function setDesc($str) {
		$this->itemDesc = $str;
		$this->change = true;
	}
	
	function setCategory($id,$index = 0) {
		//ID: ID of the category
		//Index: The index of the old category to update in the categories array
		$this->itemCategory[$index] = $id;
		$this->change = true;
	}
}
?>