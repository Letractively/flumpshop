<?php
$USR_REQUIREMENT = "can_add_categories";
require_once dirname(__FILE__)."/../header.php";
?><script type="text/javascript" src="../../tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('#description').tinymce({
			// Location of TinyMCE script
			script_url : '../../tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,advlink,iespell,inlinepopups,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,forecolor",
			theme_advanced_buttons2 : "search,|,bullist,numlist,|,blockquote,|,link,unlink,code,|,preview,|",
			theme_advanced_buttons3 : "hr,removeformat,|,sub,sup,|,charmap,iespell,media,cleanup",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "../../../style/cssProvider.php?theme=<?php echo $config->getNode("site","theme");?>&sub=main",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			media_external_list_url : "lists/media_list.js"
		});
	});
</script>
<h1>Create a New Category</h1>
<p>Hey, you've taken the first step toward creating a new category. I'll carefully guide you through the process of doing so.</p>
<form action="../categories/processCreateSimple.php" method="post" class="ui-widget" enctype="multipart/form-data" onsubmit="if ($(this).valid()) loader('Saving data...','Creating Category');" name="form">
<div class="ui-widget-header">1. Name the Product</div>
<div class="ui-widget-content">
	<p>First, you need to type the name of the category. Try to make it different to any other category, but keep it brief. The maximum length of the name is 75 characters.</p>
	<label for="name">Enter the category name: <input type="text" maxlength="75" name="name" id="name" class="ui-widget-content ui-state-default required" /></label>
</div>

<div class="ui-widget-header">2. Describe the Category</div>
<div class="ui-widget-content">
	<p>Here you should type a detailed description of the category. It appears when a user clicks on the category for more information, so make sure you help explain to them what is in it, and provide general information about all of the products within. You can also, if you want, keep it blank.</p>
	<label for="description">Describe the category:<br /><textarea rows="10" cols="45" name="description" id="description" class="ui-widget-content ui-state-default"></textarea></label>
</div>

<div class="ui-widget-header">3. Position the Category</div>
<div class="ui-widget-content">
	<p>A category can either be top level, that is, be displayed on the home page and have no other categories above it, or it can be a sub-category, which is a sub-section of another category, it's parent.</p>
	<label for="parent">Choose a parent:<br />
	<select name="parent" id="parent" class="ui-widget-content">
		<option value="0" selected="selected">No Parent (Top-level Category)</option>
		<?php
		$result = $dbConn->query("SELECT id FROM `category` ORDER BY `parent` ASC");
		while ($row = $dbConn->fetch($result)) {
			$category = new Category($row['id']);
			echo "<option value='".$category->getID()."'>".$category->getFullName()."</option>";
		}
		?>
	</select></label>
</div>

<div class="ui-widget-header">4. Create the Category</div>
<div class="ui-widget-content">
	<p>Great, once you're sure the category's ready to go, click the Create button below to create it. You can always change it later if you don't like it.</p>
	<input type="submit" value="Create" name="submit" id="submit" style="font-size: 13px; padding: .2em .4em;" onclick="$('#description').val(tinyMCE.get('description').getContent());return true" />
</div>
</form>
<script>
$('form').validate({
				   messages: {
					   name: "Please name the category",
				   }
				   });

$.ajaxSetup({cache:true});
</script>
</body></html>