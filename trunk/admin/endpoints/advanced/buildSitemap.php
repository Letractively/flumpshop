<?php
$auth = false;
require_once dirname(__FILE__).'/../../../preload.php';
error_reporting(E_ALL);
//FS Ignore Params
$ignore = array('sort','page=1');
//Blacklist pages
$blacklist = array('basket.php');

$urls = array($config->getNode('paths','root'));
$links = array();
function getLinksFromPage($url) {
	global $links,$config,$ignore,$urls,$blacklist;
	//Fetch the page using the CURL Library
	fwrite(STDOUT, 'Read: '.$url."\n");
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	$store = curl_exec ($ch);
	curl_close ($ch);
	//Strip all links from the page
	preg_match_all('/<a ([^>]*)href=[\"\'](.*)[\"\']/U',$store, $matches, PREG_SET_ORDER);
	//Return part [2] only
	foreach ($matches as $match) {
		$match[2] = html_entity_decode($match[2]);
		//Filter Ignore GET Terms
		foreach($ignore as $ig) {
			$match[2] = preg_replace('/(\?|&)'.$ig.'(.*)?(&|$)/i','',$match[2]);
		}
		//Filter Blacklist
		$out = false;
		foreach ($blacklist as $black) {
			if (strpos($match[2],$black) !== false) {
				$out = true;
				continue;
			}
		}
		if ($out) continue;
		//Calculate what the return URL would be
		if (strpos($match[2],$config->getNode('paths','root')) !== false) {
			//URL was already absolute
			$returnURL = $match[2];
		} else {
			//URL is relative. Ouch.
			$dir = $url;
			//Strip to last /
			$pieces = explode('/',$dir);
			$dir = '';
			$pieceCount = sizeof($pieces)-1;
			for ($i=0;$i<$pieceCount;$i++) {
				$dir .= $pieces[$i].'/';
			}
			//Append match to dir and return
			$returnURL = $dir.$match[2];
		}
		$match[2] = str_replace($config->getNode('paths','root').'/','',$match[2]);
		if (in_array($returnURL,$links) === false &&
			in_array($returnURL,$urls) === false &&
			strpos($match[2],'#') === false &&
			strpos($match[2],'javascript:') === false &&
			strpos($match[2],'mailto:') === false &&
			strpos($match[2],'ftp://') === false &&
			strpos($match[2],'http://') === false) {
				$return[] = $returnURL;
		}
	}
	$return = array_unique($return);
	return $return;
}

do {
	$next = array_shift($urls);
	$links[] = $next;
	$new = getLinksFromPage($next);
	if (!empty($new)) $urls = array_unique(array_merge($urls,$new));
	fwrite(STDOUT,'Remaining in queue: '.sizeof($urls)."\n");
} while (sizeof($urls) !== 0);
$date=date('Y-m-d');

//Print all results inside a XML FILE:
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
';

//Loop inside all links and add them to the sitemap using a default priority as 0.9 and a default changefreq as daily.
foreach ($links as $val)
{

$xml .= "<url>
<loc>$val</loc>
<changefreq>monthly</changefreq>
</url>
";
}

$xml .= '</urlset>';

file_put_contents('sitemap.xml',$xml);

fwrite(STDOUT,'Total Pages Indexed: '.sizeof($links));
?>