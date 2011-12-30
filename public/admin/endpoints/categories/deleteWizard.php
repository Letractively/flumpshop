<?php
$USR_REQUIREMENT = "can_delete_categories";
require_once "../header.php";
?><h1>Delete a Category</h1>
<p>Decided to overhaul your store layout, or found a duplicate category? Here you can delete a category from the system.</p>
<div class="ui-widget-header">I know the Category Number</div>
<div class="ui-widget-content">
<p>If you know the unique number I assigned to the category when it was first created, then enter it below and I'll immediately remove the category.</p>
<form action="../categories/deleteCategory.php" method="get">
<label for="id">Category #<input type="text" class="required number" name="id" id="id" /><input type="submit" value="Go!" /></label>
</form>
</div>

<div class="ui-widget-header">I know the Category Name</div>
<div class="ui-widget-content">
<p>If you know the name, or part of the name, of the category, you can enter it here and I'll search for any categories that match it. As you type I'll also provide suggestions of items that meet the criteria you've given me.</p>
<p>Once you click Go!, you will be taken to a list of categories, which when clicked take them to the edit page. Simply click "Delete Category" at the top of this page to remove it.</p>
<form action="../categories/categorySearch.php" method="get">
<label for="filter">Category Name<input type="text" class="required" name="filter" id="filter" /><input type="submit" value="Go!" /></label>
</form>
</div>

<div class="ui-widget-header">I don't know either of these</div>
<div class="ui-widget-content">
<p>If you're not sure what you want to delete, then click the button below to access the Category Database. This will provide you with a list or categories over a number of pages, sorted alphabetically. If you then change your mind, the search box on the database page allows you to search for both Category Numbers and Names, so you don't have to come back to try it out.</p>
<p>The database will link to the edit category page, but there is a delete link there too.</p>
<button onclick="loader('Please wait...','Loading Category Database');window.location='categorySearch.php';">Access the Category Database</button>
</div>

<script>$('#filter').autocomplete({source: 'ajax_categorySearch.php'});</script>
</body></html>