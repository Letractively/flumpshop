<?php
$USR_REQUIREMENT = 'can_edit_pages';
require_once "../header.php";

function getToggleText($bool) {
	if ($bool) return "enabled"; else return "disabled";
}

if (isset($_GET['toggle1'])) {
	$config->setNode('homePage','latestNews',!$config->getNode('homePage','latestNews'));
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>".$config->getNode('messages','latestNewsHeader')." is now ".getToggleText($config->getNode('homePage','latestNews')).".</div>";
}

if (isset($_GET['toggle2'])) {
	$config->setNode('homePage','techTips',!$config->getNode('homePage','techTips'));
	echo "<div class='ui-state-highlight'><span class='ui-icon ui-icon-circle-check'></span>".$config->getNode('messages','technicalHeader')." is now ".getToggleText($config->getNode('homePage','techTips')).".</div>";
}

?><h1>Customise News Feeds</h1>
<p>Here, you can choose which news information, if any is displayed on the site home page. When an item is disabled, it will also hide the option to post to that stream in the ACP.</p>
<?php
if ($config->getNode('homePage','latestNews')) {
	?>
	<p><?php echo $config->getNode('messages','latestNewsHeader');?> is currently displayed on the home page. <a href='customise.php?toggle1'>Turn off</a></p>
	<?php
} else {
	?>
	<p><?php echo $config->getNode('messages','latestNewsHeader');?> is currently not displayed on the home page. <a href='customise.php?toggle1'>Turn on</a></p>
	<?php
}

if ($config->getNode('homePage','techTips')) {
	?>
	<p><?php echo $config->getNode('messages','technicalHeader');?> is currently displayed on the home page. <a href='customise.php?toggle2'>Turn off</a></p>
	<?php
} else {
	?>
	<p><?php echo $config->getNode('messages','technicalHeader');?> is currently not displayed on the home page. <a href='customise.php?toggle2'>Turn on</a></p>
	<?php
}
?>