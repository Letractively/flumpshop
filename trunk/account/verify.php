<?php
$page_title = "Verification";
require_once dirname(__FILE__)."/../header.php";

$key = str_replace("'","''",$_GET['key']);
$result = $dbConn->query("SELECT uid FROM `keys` WHERE action=0 AND `key`='$key' LIMIT 1");

if ($dbConn->rows($result) == 0) {
	echo <<<EOT
    <h1 class="content">Verification Failed</h1>
    <p>Please check you entered the code correctly, and that your account has not already been activated.</p>
EOT;
} else {
	$result = $dbConn->fetch($result);
	$user = new User($result['uid']);
	$user->activate();
	$dbConn->query("DELETE FROM `keys` WHERE `key`='$key'");
	echo <<<EOT
    <h1 class="content">Verification Successful</h1>
    <p>Your new user account, {$user->getUname()}, has been verified and activated. You can now log in.</p>
EOT;
}

require_once dirname(__FILE__)."/../footer.php";
?>