<?php
require_once "../header.php";
?><h1>Manage Products</h1>
<p>Here you can do anything to do with products, from creating and deleting, to promoting and reducing. You'll only see the options on this page that your Administrator has given you permission to use, so it's possible that the page is empty.</p>
<p><b>I want to...</b> contains everything the standard users would need regarding setting up and configuring products.</p>
<p><b>Advanced Tools</b> contains additional features or condensed versions of wizards, for experienced or administrative staff use.</p>
<?php
if (isset($_GET['msg'])) echo $_GET['msg'];
?>
<div class='ui-widget-header'>I want to...</div>
<div class='ui-widget-content'>
	<ul>
		<?php if (acpusr_validate("can_add_products")) {
			?><li><a href="../products/createSimple.php" onclick="loader('Please wait...','Launching Wizard...');">Create a new product</a></li><?php
		}
		if (acpusr_validate("can_edit_products")) {
			?><li><a href="../products/editWizard.php" onclick="loader('Please wait...','Launching Wizard...');">Change a product</a></li><?php
		}
		if (acpusr_validate("can_delete_products")) {
			?><li><a href="../products/deleteWizard.php" onclick="loader('Please wait...','Launching Wizard...');">Delete a product</a></li><?php
		}?>
	</ul>
</div>
<div class="ui-widget-header">Advanced Tools</div>
<div class='ui-widget-content'>
	<ul>
		<?php 
		//Classic Add form
		if (acpusr_validate("can_add_products")) {
			?><li><a href="../products/createForm.php" onclick="loader('Please wait...','Loading Form');">Create one or more identical products</a></li><?php
		}
		//Classic Edit form
		if (acpusr_validate("can_edit_products")) {
			?><li><a href="../products/itemSearch.php" onclick="loader('Please wait...','Loading Database');">Edit a Product</a></li><?php
		}
		//Mothballed Items
		if (acpusr_validate("can_delete_products")) {
			?><li><a href="../products/mothballed.php" onclick="loader('Please wait...','Loading Database');">View Archived Products</a></li><?php
		}
		//Item Report
		if (acpusr_validate("can_view_reports")) {
			?><li><a href="../products/report.php" onclick="loader('I\'m generating the product report as I speak. This can take a few minutes, depending on the size of the product database. Please Wait...','Generating Report');">View Product Report</a></li><?php
		}
		//Featured Items
		if (acpusr_validate("can_edit_pages")) {
			?><li><a href="../products/featured.php" onclick="loader('Please wait...','Loading Content');">Featured Items</a></li><?php
		}?>
	</ul>
</div>
</body></html>