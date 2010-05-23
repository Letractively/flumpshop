<?php
require_once "../header.php";
?><h1>Manage News</h1>
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
<div class='ui-widget-header'>I want to...</div>
<div class='ui-widget-content'>
	<ul><?php
	if (acpusr_validate('can_post_news')) {
		if ($config->getNode('homePage','latestNews')) {
			?>
			<li><a href="../news/newNews.php" onclick="loader('Please wait...','Launching Wizard...');">Create a new <?php echo $config->getNode("messages","latestNewsHeader");?> post</a></li>
			<?php
		} if ($config->getNode('homePage','techTips')) {
			?>
			<li><a href="../news/newQuicktip.php" onclick="loader('Please wait...','Launching Wizard...');">Create a new <?php echo $config->getNode("messages","technicalHeader");?> post</a></li>
			<?php
		}
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
		<li><a href="../news/customise.php" onclick="loader('Please wait...','Loading Content...');">Choose which news feeds appear on the home page</a></li>
		<?php
	}
	?>
	</ul>
</div>
</body></html>