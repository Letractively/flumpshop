<?php
$ajaxProvider = true;
$_PRINTDATA = false;
require_once dirname(__FILE__)."/../../../preload.php";

$export = array();

$export['revision'] = 3;

$items = $dbConn->query("SELECT id FROM `products`");
$export['items'] = array();
while ($item = $dbConn->fetch($items)) {
	$export['items'][] = serialize(new Item($item['id']));
}

$categories = $dbConn->query("SELECT id FROM `category`");
$export['categories'] = array();
while ($category = $dbConn->fetch($categories)) {
	$export['categories'][] = serialize(new Category($category['id']));
}

$users = $dbConn->query("SELECT id FROM `login`");
$export['users'] = array();
while ($user = $dbConn->fetch($users)) {
	$export['users'][] = serialize(new User($user['id']));
}

$customers = $dbConn->query("SELECT id FROM `customers`");
$export['customers'] = array();
while ($customer = $dbConn->fetch($customers)) {
	$export['customers'][] = serialize(new Customer($customer['id']));
}

$baskets = $dbConn->query("SELECT id FROM `basket`");
$export['baskets'] = array();
while ($basket = $dbConn->fetch($baskets)) {
	$export['baskets'][] = serialize(new Basket($basket['id']));
}

$orders = $dbConn->query("SELECT id FROM `orders`");
$export['orders'] = array();
while ($order = $dbConn->fetch($orders)) {
	$export['orders'][] = serialize(new Order($order['id']));
}

$deliveries = $dbConn->query("SELECT id FROM `delivery`");
$export['deliveries'] = array();
while ($delivery = $dbConn->fetch($deliveries)) {
	$export['deliveries'][] = serialize(new Delivery($delivery['id']));
}

$newsPosts = $dbConn->query("SELECT id FROM `news`");
$export['news'] = array();
while ($news = $dbConn->fetch($newsPosts)) {
	$export['news'][] = serialize(new News($news['id']));
}

$reserves = $dbConn->query("SELECT id FROM `reserve`");
$export['reserves'] = array();
while ($reserve = $dbConn->fetch($reserves)) {
	$export['reserves'][] = serialize(new Reserve($reserve['id']));
}

$sessions = $dbConn->query("SELECT id FROM `session`");
$export['sessions'] = array();
while ($session = $dbConn->fetch($sessions)) {
	$export['sessions'][] = serialize(new Session($session['id']));
}

$techhelps = $dbConn->query("SELECT * FROM `techhelp`");
$export['techhelp'] = array();
while ($techhelp = $dbConn->fetch($techhelps)) {
	$export['techhelp'][] = serialize(new Techhelp($techhelp['id']));
}

$stats = new Stats();
$stats->cacheAll();
$export['stats'] = serialize($stats);

$countries = $dbConn->query("SELECT iso FROM `country` WHERE supported = 1");
$export['countries'] = array();
while ($country = $dbConn->fetch($countries)) {
	$export['countries'][] = $country['iso'];
}

$keycodes = new Keycodes();
$export['keycodes'] = serialize($keycodes);

//Config
$export['config'] = serialize($config);

//Images
$export['images'] == array();
$package = array();
//Upgrade Object Generated, now package files
$dirs = array($config->getNode('paths','offlineDir')."/images/");
while ($dir = array_pop($dirs)) {
	$handle = opendir($config->getNode('paths','offlineDir')."/$dir");
	while ($file = readdir($handle)) {
		if (is_dir($config->getNode('paths','offlineDir')."/$dir/$file") && $file != "." && $file != "..") {
			array_push($dirs,$dir."/$file");
		} elseif (preg_match("/(png|jpg|gif)$/i",$file)) {//Only include Image Files
			if (!is_dir($config->getNode('paths','offlineDir')."/$dir/$file")) {
				$export['images'][$dir][$file] = file_get_contents($config->getNode('paths','offlineDir')."/$dir/$file");
			}
		}
	}
}

//Check it isn't backup mode
if (!isset($storeExport)) {
	header("Content-Disposition: attachment; filename=exportdata.dat");

//Waitwhat? Do this AFTER spending years generating the file?
if (!isset($_SESSION['adminAuth']) || !$_SESSION['adminAuth']) die($config->getNode('messages','adminDenied'));

echo base64_encode(serialize($export));
} else {
	file_put_contents($storeExport,base64_encode(serialize($export)));
}
?>