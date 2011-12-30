<?php
$USR_REQUIREMENT = "can_edit_categories";
require_once "../header.php";
?><h1>Change a Category</h1>
<p>Hey, if you're looking to change any information about a category, from changing the name or configuring advanced attributes, then you've come to the right place.</p>
<div class="ui-widget-header">I know the Category Number</div>
<div class="ui-widget-content">
<p>If you know the unique number I assigned to the category when it was first created, then enter it below and I'll take you straight to the edit page. You can also find this in the address bar of the category page when you view the main site e.g. example.com/category/<strong>123</strong></p>
<form action="../categories/editCategory.php" id="form2" method="get">
<label for="id">Category #<input type="text" class="required number" name="id" id="id" /><input type="submit" value="Go!" /></label>
</form>
</div>

<div class="ui-widget-header">I know the Category Name</div>
<div class="ui-widget-content">
<p>If you know the name, or part of the name, of the category, you can enter it here and I'll search for any categories that match it. As you type I'll also provide suggestions of items that meet the criteria you've given me.</p>
<form action="../categories/categorySearch.php" id="form1" method="get">
<label for="filter">Category Name<input type="text" class="required" name="filter" id="filter" /><input type="submit" value="Go!" /></label>
</form>
</div>

<div class="ui-widget-header">I don't know either of these</div>
<div class="ui-widget-content">
<p>If you're not sure what you want to edit, then click the button below to access the Category Database. This will provide you with a list or categories over a number of pages, sorted alphabetically. If you then change your mind, the search box on the database page allows you to search for both Category Numbers and Names, so you don't have to come back to try it out.</p>
<button onclick="loader('Please wait...','Loading Category Database');window.location='categorySearch.php';">Access the Category Database</button>
</div>

<script>$('#filter').autocomplete({source: 'ajax_categorySearch.php'});</script>
</body></html>