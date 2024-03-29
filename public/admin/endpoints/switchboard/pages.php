<?php
$USR_REQUIREMENT = "can_edit_pages";
require_once "../header.php";
?>
<h1>Manage Pages</h1>
<?php
if ($config->getNode('homePage','latestNews') and $config->getNode('homePage','techTips')) {
	?>
	<p>This page is your main point of managing the two news feeds that are used on Flumpshop - <?php echo $config->getNode('messages','latestNewsHeader')?>, and <?php echo $config->getNode('messages','technicalHeader')?>. The key difference is the way they are displayed on the home page, the former has the latest post shown in full, and the latter has links to several recent posts.
	<?php
} elseif ($config->getNode('homePage','latestNews')) {
	?>
	<p>This page is where you can post a message under the <?php echo $config->getNode('messages','latestNewsHeader');?> heading of the home page. Every time you create a new post, it replaces the last one.</p>
	<?php
} elseif ($config->getNode('homePage','techTips')) {
	?>
	<p>This page is where you can post a link under the <?php echo $config->getNode('messages','techTips');?> heading of the home page. Up to four links are displayed, which link to a full page which can be as long as you want.</p>
	<?php
} else {
	?>
	<p>When enabled, this page allows you to post and control news feeds on the home page. However, at the moment, both of these are turned off, so there is not much that you can do here.</p>
	<?php
}
if (isset($_GET['msg'])) echo $_GET['msg'];
?>
<p>New Feature: It is now potentially possible to use technologomy to gain a greater degree of control over the home page. Choose Modify Home Page Layout for more.</p>
<div class='ui-widget-header'>I want to...</div>
<div class='ui-widget-content'>
	<ul><?php
	if (acpusr_validate('can_edit_pages')) {
		?>
		<li><a href="../pages/edit.php?pageid=homePage" onclick="loader('Please wait...','Loading Content...');">Edit the Home Page</a></li>
		<li><a href="../pages/edit.php?pageid=aboutPage" onclick="loader('Please wait...','Loading Content...');">Edit the About Page</a></li>
		<li><a href="../pages/edit.php?pageid=contactPage" onclick="loader('Please wait...','Loading Content...');">Edit the Contact Page</a></li>
		<?php
	}
	?>
	</ul>
</div>
<div class="ui-widget-header">Advanced Tools</div>
<div class="ui-widget-content">
	<ul>
	<?php
	if (acpusr_validate('can_edit_pages')) {
		?>
		<li><a href="../pages/customise.php" onclick="loader('Please wait...','Loading Content...');">Choose which pages can be accessed on the site</a></li>
		<li><a href="../pages/edit.php?pageid=privacyPolicy" onclick="loader('Please wait...','Loading Content...');">Edit the Privacy Policy</a></li>
		<li><a href="../pages/edit.php?pageid=disclaimer" onclick="loader('Please wait...','Loading Content...');">Edit the Disclaimer</a></li>
		<li><a href="../pages/edit.php?pageid=termsConditions" onclick="loader('Please wait...','Loading Content...');">Edit the Terms and Conditions</a></li>
		<li><a href="../pages/home.php" onclick="loader('Please Wait...','This may take a few moments. This feature us currently experimental');">Edit the Home Page layout</a></li>
		<?php
	}
	?>
	</ul>
</div>
</body></html>