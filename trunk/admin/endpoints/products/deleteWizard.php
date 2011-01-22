<?php
$USR_REQUIREMENT = "can_edit_products";
require_once "../header.php";
?><h1>Delete a Product</h1>
<p>Product not selling well? Supplier gone out of business? Doesn't matter, simply tell me the product you want to delete and I'll get rid of it right away.</p>
<div class="ui-widget-header">I know the Product Number</div>
<div class="ui-widget-content">
<p>If you know the unique number I assigned to the product when it was first created, then enter it below and I'll immediately remove the product. <b>Note:</b> This is NOT the SKU.</p>
<form action="deleteItem.php" method="get">
<label for="id">Product #<input type="text" class="required number" name="id" id="id" /><input type="submit" value="Go!" /></label>
</form>
</div>

<div class="ui-widget-header">I know the Product Name</div>
<div class="ui-widget-content">
<p>If you know the name, or part of the name, of the product, you can enter it here and I'll search for any products that match it. As you type I'll also provide suggestions of items that meet the criteria you've given me.</p>
<p>Once you click Go!, you will be taken to a list of items, which when clicked take them to the edit page. Simply click "Delete Product" at the top of this page to remove it.</p>
<form action="itemSearch.php" method="get">
<label for="filter">Product Name<input type="text" class="required" name="filter" id="filter" /><input type="submit" value="Go!" /></label>
</form>
</div>

<div class="ui-widget-header">I don't know either of these</div>
<div class="ui-widget-content">
<p>If you're not sure what you want to delete, then click the button below to access the Product Database. This will provide you with a list or products of a number of pages, sorted alphabetically. If you then change your mind, the search box on the database page allows you to search for both Product Numbers and Names, so you don't have to come back to try it out.</p>
<p>The database will link to the edit product page, but there is a delete link there too.</p>
<button onclick="loader('Please wait...','Loading Product Database');window.location='itemSearch.php';">Access the Product Database</button>
</div>

<script>$('#filter').autocomplete({source: 'ajax_itemSearch.php'});</script>
</body></html>