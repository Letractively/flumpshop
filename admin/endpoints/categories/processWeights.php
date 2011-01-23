<?php
$USR_REQUIREMENT = "can_edit_pages";
require_once "../header.php";

foreach($_POST as $key => $value) {
	if (strstr($key,'_weight') !== false) {
		$id = str_replace('_weight','',$key);
		$dbConn->query('UPDATE `category` SET weight='.intval($value).' WHERE id='.intval($id).' LIMIT 1');
	}
}

header('Location: ../switchboard/categories.php?msg=<div class="ui-state-highlight"><span class="ui-icon ui-icon-circle-check"></span>Category weights have been successfully updated. Please note it may take up to an hour for the changes to take effect. You can force the changes by going to Advanced->Clear Cache.</div>');
?>