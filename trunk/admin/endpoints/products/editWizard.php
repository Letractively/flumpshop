<?php
$USR_REQUIREMENT = "can_edit_products";
require_once "../header.php";
?><h1>Change a Product</h1>
<p>Hey, if you're looking to change any information about a product, from adding a new image to putting it on special offer, this is the place to start. So, first off, I need to know what product you're going to edit.</p>
<div class="ui-widget-header">I know the Product Number</div>
<div class="ui-widget-content">
<p>If you know the unique number I assigned to the product when it was first created, then enter it below and I'll take you straight to the edit page. <b>Note:</b> This is NOT the SKU.</p>
<form action="editItem.php" method="get">
<label for="id">Product #<input type="text" class="required number" name="id" id="id" /><input type="submit" value="Go!" /></label>
</form>
</div>

<div class="ui-widget-header">I know the Product Name</div>
<div class="ui-widget-content">
<p>If you know the name, or part of the name, of the product, you can enter it here and I'll search for any products that match it. As you type I'll also provide suggestions of items that meet the criteria you've given me.</p>
<form action="itemSearch.php" method="get">
<label for="filter">Product Name<input type="text" class="required" name="filter" id="filter" /><input type="submit" value="Go!" /></label>
</form>
</div>

<div class="ui-widget-header">I don't know either of these</div>
<div class="ui-widget-content">
<p>If you're not sure what you want to edit, then click the button below to access the Product Database. This will provide you with a list or products of a number of pages, sorted alphabetically. If you then change your mind, the search box on the database page allows you to search for both Product Numbers and Names, so you don't have to come back to try it out.</p>
<button onclick="loader('Please wait...','Loading Product Database');window.location='itemSearch.php';">Access the Product Database</button>
</div>

<script>$('#filter').autocomplete({source: 'ajax_itemSearch.php'});</script>
</body></html>