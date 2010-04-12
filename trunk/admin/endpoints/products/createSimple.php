<?php
$USR_REQUIREMENT = "can_add_products";
require_once dirname(__FILE__)."/../header.php";
?><div class="ui-widget"><h1>Create a New Product</h1></div>
<p>Hey, you've taken the first step toward creating a new product. I'll carefully guide you through the process of doing so.</p>
<strong>Before You Start</strong>
<p>To save some time later, it's best that you make sure you've made some categories to organise your products. If you haven't done so already, head over to the <a href="../switchboard/categories.php" onclick="loader('Please wait...','Loading Category Manager');">Category Management section</a> to add some now.</p>
<form action="processCreateSimple.php" method="post" class="ui-widget" enctype="multipart/form-data" onsubmit="if ($(this).valid()) loader('Saving data...','Creating Product');">
<div class="ui-widget-header">1. Name the Product</div>
<div class="ui-widget-content">
	<p>First, you need to type the name of the product. Try to make it different to any other product, but keep it brief. The maximum length of the name is 255 characters.</p>
	<label for="name">Enter the product name: <input type="text" maxlength="255" name="name" id="name" class="ui-widget-content ui-state-default required" /></label>
</div>

<div class="ui-widget-header">2. Describe the Product</div>
<div class="ui-widget-content">
	<p>Here you should type a detailed description of the product. Remember, you're trying to sell it, so make it appealing to users! You can leave this box blank if you want, and you can type as much as you want here.</p>
	<label for="description">Describe the product:<br /><textarea rows="10" cols="45" name="description" id="description" class="ui-widget-content ui-state-default"></textarea></label>
</div>

<div class="ui-widget-header">3. Categorise the Product</div>
<div class="ui-widget-content">
	<p>Here, you can place the product into one of your categories to help users find it. If you don't set a category, visitors to your site can only find the product by searching for it. A product can be in as many categories as you want it to be.</p>
	<p>Look out! I don't check if you've put a product in a category more than once, so make sure you double check.</p>
	<div id="categoryselect_container"></div>
	<a href='javascript:' onclick='addCategorySelector();'>Add another category...</a>
</div>

<div class="ui-widget-header">4. Customise the Product</div>
<div class="ui-widget-content">
	<p>If it's been set up, then each category you selected will have a series of <em style="cursor:pointer" onclick="alert('Special options for products that change depending on what categories a product belongs to.');">Features</em> that come with it. These help you to organise and describe your product, and more importantly, help your visitors find them. Any boxes shown below are optional.</p>
	<p>If you type anything in here, then change a category, any information in this section will be lost.</p>
	<div id="category_feature_fields"></div>
</div>

<div class="ui-widget-header">5. Price the Product</div>
<div class="ui-widget-content">
	<p>Now you can enter a price for the new product. I currently only work with GBP, so make sure that's what your using. Visitors to the site will only see the price is <em style="cursor:pointer" onclick="alert('You site administrator may have chosen whether or not the site works as an online catalogue, or a fully-featured online shop. If shop mode is off, then no prices, stock, or related information is shown to visitors.');">Shop Mode</em> is enabled. However, it helps to have all the information in one place.</p>
	<label for="price">Enter the Price: &pound;<input type="text" maxlength="11" value="0.00" name="price" id="price" class="ui-widget-content ui-state-default required number" /></label>
</div>

<div class="ui-widget-header">6. Stock the Product</div>
<div class="ui-widget-content">
	<p>Also optional, here you can enter the amount of stock you currently have for the product. If Shop Mode is enabled, then this product won't be sold if the stock runs out.</p>
	<label for="stock">Set the Stock:
	<input type="text" maxlength="10" value="0" name="stock" id="stock" class="ui-widget-content ui-state-default required number" /></label>
</div>

<div class="ui-widget-header">7. Weigh the Product</div>
<div class="ui-widget-content">
	<p>If Delivery Rates are set up, then this weight will be used to automatically calculate the cost of delivering the product to all supported countries. It's only necessary if Shop Mode is enabled.</p>
	<label for="weight">Set the Weight: <input type="text" maxlength="8" value="0" name="weight" id="weight" class="ui-widget-content ui-state-default required number" />Kg</label>
</div>

<div class="ui-widget-header">8. Display the Product</div>
<div class="ui-widget-content">
	<p>Finally, if you have a picture of the product, upload it here so the visitors can see it. You can add more pictures of it later if you want.</p>
    <label for="image">Choose an Image: <input type="file" name="image" id="image" class="ui-widget-content ui-state-default" /></label>
</div>

<div class="ui-widget-header">9. Create the Product</div>
<div class="ui-widget-content">
	<p>Great, once you're sure the product's ready to go, click the Create button below to create the product. You can always change it later if you don't like it.</p>
	<input type="submit" value="Create" name="submit" id="submit" style="font-size: 13px; padding: .2em .4em;" />
</div>
</form>
<script>
$('form').validate({
				   messages: {
					   name: "Please name the product",
					   price: "Please enter the price here, without units",
					   stock: "Please enter the stock here, as a number",
					   weight: "Please enter a weight here, without units",
				   }
				   });

document.categorySelectNo = 0;

function updateFeatures() {
	str = "";
	for (i=0;i<document.categorySelectNo;i++) str = str+$('#category_'+i).val()+",";
	$('#category_feature_fields').html("<img src='../../../images/loading.gif' />Updating feature list...");
	$('#category_feature_fields').load('featureFields.php?&id='+str);
}

function addCategorySelector() {
	$('#categoryselect_container').append("<div id='newField_"+document.categorySelectNo+"'><img src='../../../images/loading.gif' />Loading category list...</div>");
	$('#newField_'+document.categorySelectNo).load("categorySelect.php?id="+document.categorySelectNo);
	document.categorySelectNo++;
}

$.ajaxSetup({cache:true});

addCategorySelector();
</script>
</body></html>