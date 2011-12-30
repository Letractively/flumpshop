<?php
$requires_tier2 = true;
require_once dirname(__FILE__)."/../header.php";

if (!$config->getEditable()) die("conf.txt is not editable.");

$vars = array_keys($_POST);

$config->falseify();

foreach ($vars as $var) {
	$val = explode("|",$var);
	if (is_bool($config->getNode($val[0],$val[1]))) {
		if ($_POST[$var] == "on") {
			$config->setNode($val[0],$val[1],true);
		} else {
			$config->setNode($val[0],$val[1],false);
		}
	} else {
		$value = str_replace(array("\\\'","\\\""),array("'",'"'),$_POST[$var]);
		if (!$config->setNode($val[0],$val[1],$value)) {
			echo "Failed to save $val[0]|$val[1].";
		}
	}
}

include dirname(__FILE__).'/../advanced/clearCache.php';

?><div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Configuration Saved</div><?php
include dirname(__FILE__)."/../advanced/varMan.php";

