<?php
/**
* @todo add permission specifically for this
*/
$USR_REQUIREMENT = "can_edit_pages";
require_once "../header.php";
?>
<h1>Manage Messages</h1>
<p>Flumpshop is highly customisable, and as part of this, almost all messages that are displayed on the site can be customised. The most common ones are displayed under I want to..., and rarely changes ones under Advanced Tools.</p>
<p>Further messages can be changed in the configuration manager.</p>
<div class='ui-widget-header'>I want to...</div>
<div class='ui-widget-content'>
	<ul><?php
	if (acpusr_validate('can_edit_pages')) {
		?>
		<li><a href="../messages/edit.php?msgid=navAdvert" onclick="loader('Please wait...','Loading Content...');">Edit the Navigation Bar Advert</a></li>
		<li><a href="../messages/edit.php?msgid=maintenance" onclick="loader('Please wait...','Loading Content...');">Edit the Site Maintenance Message</a></li>
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
		<li>Generic Messages
			<ul>
				<li><a href="../messages/edit.php?msgid=footer" onclick="loader('Please wait...','Loading Content...');">Edit the Footer</a></li>
				<li><a href="../messages/edit.php?msgid=name" onclick="loader('Please wait...','Loading Content...');">Edit the Site Name</a></li>
				<li><a href="../messages/edit.php?msgid=tagline" onclick="loader('Please wait...','Loading Content...');">Edit the Tagline</a></li>
				<li><a href="../messages/edit.php?msgid=keywords" onclick="loader('Please wait...','Loading Content...');">Edit Site Keywords</a></li>
			</ul>
		</li>
		<li>Home Page Headers
			<ul>
				<li><a href="../messages/edit.php?msgid=featuredItemHeader" onclick="loader('Please wait...','Loading Content...');">Edit the Featured Items header</a></li>
				<li><a href="../messages/edit.php?msgid=popularItemHeader" onclick="loader('Please wait...','Loading Content...');">Edit the Popular Items header</a></li>
				<li><a href="../messages/edit.php?msgid=latestNewsHeader" onclick="loader('Please wait...','Loading Content...');">Edit the Latest News header</a></li>
				<li><a href="../messages/edit.php?msgid=technicalHeader" onclick="loader('Please wait...','Loading Content...');">Edit the Quick Tips header</a></li>
			</ul>
		</li>
		<li>Error Messages/Pages
			<ul>
				<li><a href="../messages/edit.php?msgid=ajax500" onclick="loader('Please wait...','Loading Content...');">ajax500</a></li>
				<li><a href="../messages/edit.php?msgid=ajax404" onclick="loader('Please wait...','Loading Content...');">ajax404</a></li>
				<li><a href="../messages/edit.php?msgid=ajax403" onclick="loader('Please wait...','Loading Content...');">ajax403</a></li>
				<li><a href="../messages/edit.php?msgid=ajaxError" onclick="loader('Please wait...','Loading Content...');">AJAX Generic Error</a></li>
				<li><a href="../messages/edit.php?msgid=adminDenied" onclick="loader('Please wait...','Loading Content...');">adminDenied</a></li>
				<li><a href="../messages/edit.php?msgid=transactionFailed" onclick="loader('Please wait...','Loading Content...');">transactionFailed</a></li>
				<li><a href="../messages/edit.php?msgid=transactionCancelled" onclick="loader('Please wait...','Loading Content...');">transactionCancelled</a></li>
				<li><a href="../messages/edit.php?msgid=noScript" onclick="loader('Please wait...','Loading Content...');">noScript</a></li>
				<li><a href="../messages/edit.php?msgid=insufficientStock" onclick="loader('Please wait...','Loading Content...');">insufficientStock</a></li>
				<li><a href="../messages/edit.php?msgid=formFieldRequired" onclick="loader('Please wait...','Loading Content...');">formFieldRequired</a></li>
			</ul>
		</li>
		<?php
	}
	?>
	</ul>
</div>
</body></html>