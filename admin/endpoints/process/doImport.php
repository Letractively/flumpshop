<?php
$requires_tier2 = true;
require_once dirname(__FILE__)."/../header.php";

$import = unserialize(base64_decode(file_get_contents($_FILES['file']['tmp_name'])));

//Import Baskets
echo "Importing Baskets...<br />";
foreach ($import['baskets'] as $basket) {
	$basket = unserialize($basket);
	if (!$basket->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT BASKET #".$basket->getID()."</div>";
	} else {
		debug_message("Basket ".$basket->getID()." imported successfully.");
	}
}

//Import Categories
echo "Importing Categories...<br />";
foreach ($import['categories'] as $category) {
	$category = unserialize($category);
	if (!$category->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT CATEGORY #".$category->getID()."</div>";
	} else {
		debug_message("Category ".$category->getID()." imported successfully.");
	}
}

//Import Config
if (isset($import['revision']) and $import['revision'] >= 2 and isset($import['config'])) {
	if (isset($_POST['includeConf'])) {
		echo "Importing Config...<br />";
		$config = unserialize($import['config']);
		$config->import();
		debug_message("Configuration file imported successfully.");
	} else {
		echo "Configuration Object Import Skipped.<br />";
	}
} else {
	echo "Notice: Configuration not included in import file.<br />";
}

//Import Countries
echo "Importing Countries...<br />";
$dbConn->query("UPDATE `country` SET supported='0'");
foreach ($import['countries'] as $country) {
	if (!$dbConn->query("UPDATE `country` SET supported = 1 WHERE iso='$country' LIMIT 1")) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT COUNTRY [$country]</div>";
	} else {
		debug_message("Country [$country] imported successfully.");
	}
}

//Import Customers
echo "Importing Customers...<br />";
foreach ($import['customers'] as $customer) {
	$customer = unserialize($customer);
	if (!$customer->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT CUSTOMER #".$customer->getID()."</div>";
	} else {
		debug_message("Customer ".$customer->getID()." imported successfully.");
	}
}

//Import Delivery Rates
echo "Importing Delivery Rates...<br />";
foreach ($import['deliveries'] as $delivery) {
	$delivery = unserialize($delivery);
	if (!$delivery->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT DELIVERY RATE #".$delivery->getID()."</div>";
	} else {
		debug_message("Delivery Rate ".$delivery->getID()." imported successfully.");
	}
}

//Import Keycodes
echo "Importing Keycodes...<br />";
$keycodes = unserialize($import['keycodes']);
$keycodes->import();

//Import Images
if (isset($import['revision']) and $import['revision'] >= 3 and isset($import['config'])) {
	if (isset($_POST['includeImages'])) {
		echo "Importing Images...<br />";
		foreach ($export['images'] as $dirname => $dir) {
			if (!is_dir($config->getNode("paths","offlineDir")."/".$dirname)) mkdir($config->getNode("paths","offlineDir")."/".$dirname);
			foreach ($dir as $file => $contents) {
				debug_message($config->getNode("paths","offlineDir")."/".$dirname."/".$file);
				$fp = fopen($config->getNode("paths","offlineDir")."/".$dirname."/".$file,"w+");
				fwrite($fp, $contents);
				fclose($fp);
			}
		}
	} else {
		echo "Images import skipped.";
	}
} else {
	echo "Notice: Images not included in import file.<br />";
}

//Import Items
echo "Importing Items...<br />";
foreach ($import['items'] as $item) {
	$item = unserialize($item);
	if (!$item->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT ITEM #".$item->getID()."</div>";
	} else {
		debug_message("Item ".$item->getID()." imported successfully.");
	}
}

//Import News
echo "Importing News...<br />";
foreach ($import['news'] as $news) {
	$news = unserialize($news);
	if (!$news->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT NEWS #".$news->getID()."</div>";
	} else {
		debug_message("News ".$news->getID()." imported successfully.");
	}
}

//Import Orders
echo "Importing Orders...<br />";
foreach ($import['orders'] as $order) {
	$order = unserialize($order);
	if (!$order->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT ORDER #".$order->getID()."</div>";
	} else {
		debug_message("Order ".$order->getID()." imported successfully.");
	}
}

//Import Reserves
echo "Importing Reserves...<br />";
foreach ($import['reserves'] as $reserve) {
	$reserve = unserialize($reserve);
	if (!$reserve->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT RESERVE #".$reserve->getID()."</div>";
	} else {
		debug_message("Reserve ".$reserve->getID()." imported successfully.");
	}
}

//Import Stats
echo "Importing Statistics...<br />";
$stats = unserialize($import['stats']);
if (!$stats->import()) {
	echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT STATISTICS</div>";
} else {
	debug_message("Statistics imported successfully.");
}

//Import Sessions
echo "Importing Sessions...<br />";
foreach ($import['sessions'] as $session) {
	$session = unserialize($session);
	if (!$session->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT SESSION #".$session->getID()."</div>";
	} else {
		debug_message("Session ".$session->getID()." imported successfully.");
	}
}

//Import Techhelp
echo "Importing Techhelp...<br />";
foreach ($import['techhelp'] as $techhelp) {
	$techhelp = unserialize($techhelp);
	if (!$techhelp->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT TECHHELP #".$techhelp->getID()."</div>";
	} else {
		debug_message("Techhelp ".$techhelp->getID()." imported successfully.");
	}
}

//Import Users
echo "Importing Users...<br />";
foreach ($import['users'] as $user) {
	$user = unserialize($user);
	if (!$user->import()) {
		echo "<div class='ui-state-error'><span class='ui-icon ui-icon-alert'></span>FAILED TO IMPORT USER #".$user->getID()."</div>";
	} else {
		debug_message("User ".$user->getID()." imported successfully.");
	}
}

echo "<br />Import complete.";
?>