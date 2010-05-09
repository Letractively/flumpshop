<?php
require_once "../header.php";
?><h1>Manage Categories</h1>
<p>Here you can do anything to do with categories, from creating and deleting, to prioritising and sorting. Follow the simple steps to get on your way.</p>
<p><b>I want to...</b> contains everything the standard users would need regarding setting up and configuring products.</p>
<p><b>Advanced Tools</b> contains additional features or condensed versions of wizards, for experienced or administrative staff use.</p>
<?php
if (isset($_GET['msg'])) echo $_GET['msg'];
?>
<div class='ui-widget-header'>I want to...</div>
<div class='ui-widget-content'>
	<ul>
		<?php if (acpusr_validate("can_add_categories")) {
			?><li><a href="../categories/createSimple.php" onclick="loader('Please wait...','Launching Wizard...');">Create a new category</a></li><?php
		}
		if (acpusr_validate("can_edit_categories")) {
			?><li><a href="../categories/editWizard.php" onclick="loader('Please wait...','Launching Wizard...');">Change a category</a></li><?php
		}
		if (acpusr_validate("can_delete_categories")) {
			?><li><a href="../categories/deleteWizard.php" onclick="loader('Please wait...','Launching Wizard...');">Delete a category</a></li><?php
		}?>
	</ul>
</div>
<div class="ui-widget-header">Advanced Tools</div>
<div class='ui-widget-content'>
	<ul>
		<?php 
		//Classic Add form
		if (acpusr_validate("can_add_categories")) {
			?><li><a href="../categories/createForm.php" onclick="loader('Please wait...','Loading Form');">Create a new category (Advanced)</a></li><?php
		}
		//Classic Edit form
		if (acpusr_validate("can_edit_categories")) {
			?><li><a href="../categories/categorySearch.php" onclick="loader('Please wait...','Loading Database');">Edit a category</a></li><?php
		}
		//Mothballed Items
		if (acpusr_validate("can_delete_categories")) {
			?><li><a href="../categories/mothballed.php" onclick="loader('Please wait...','Loading Database');">View Archived Categories</a></li><?php
		}
		//Item Report
		if (acpusr_validate("can_view_reports")) {
			?><li><a href="../categories/report.php" onclick="loader('I\'m generating the category report as I speak. This can take a few minutes, depending on the size of the category database. Please Wait...','Generating Report');">View Category Report</a></li><?php
		}?>
	</ul>
</div>
</body></html>