<?php
$USR_REQUIREMENT = "can_edit_pages";
require_once dirname(__FILE__)."/../header.php";
?><h1>Edit Home Page Layout</h1>
<p>The Home Page uses a unique templating mechanism, different to the rest of the site. This is for a very, very good reason. Flumpshop is able to allow you to completely customise the layout of the elements on the home page, without touching a single line of code. This is very, VERY experimental, so have no doubt that this will likely break elements of your site until it's stable.</p>
<p>Current Limitations:</p>
<ui>
<li>No Plugin Support</li>
<li>Doesn't affect actual home page</li>
<li>Does not restore saved layout in this view</li>
<li>Spews out a lot of information when you save it</li>
</ui>
<style type="text/css">
.column { width: 250px; float: left; padding-bottom: 100px; }
.portlet { margin: 0 1em 1em 0; }
.portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
.portlet-header .ui-icon { float: right; }
.portlet-content { padding: 0.4em; }
.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
.ui-sortable-placeholder * { visibility: hidden; }
</style>
<script type="text/javascript">
$(function() {
	$(".column").sortable({
		connectWith: '.column'
	});

	$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
		.find(".portlet-header")
			.addClass("ui-widget-header ui-corner-all")
			.prepend('<span class="ui-icon ui-icon-minusthick"></span>')
			.end()
		.find(".portlet-content");

	$(".portlet-header .ui-icon").click(function() {
		$(this).toggleClass("ui-icon-minusthick").toggleClass("ui-icon-plusthick");
		$(this).parents(".portlet:first").find(".portlet-content").toggle();
	});

	$(".column").disableSelection();
});
</script>
<div id="sort_container">
<div class="column">
<h3>Left Sidebar</h3>
	<div class="portlet" id="wid_1">
		<div class="portlet-header">Navigation Bar</div>
		<div class="portlet-content">This is the main site navigation bar.</div>
	</div>
	
	<div class="portlet" id="wid_2">
		<div class="portlet-header">Latest</div>
		<div class="portlet-content">This contains the value of the navAdvert message</div>
	</div>

</div>

<div class="column">
<h3>Main Content</h3>
	<div class="portlet" id="wid_3">
		<div class="portlet-header">Welcome</div>
		<div class="portlet-content">This is the title of the page, as well as the home page text content</div>
	</div>
	<div class="portlet" id="wid_4">
		<div class="portlet-header"><?php echo $config->getNode('messages','featuredItemHeader');?></div>
		<div class="portlet-content">The site's featured items</div>
	</div>
	<div class="portlet" id="wid_5">
		<div class="portlet-header"><?php echo $config->getNode('messages','popularItemHeader');?></div>
		<div class="portlet-content">The site's popular items</div>
	</div>
	<div class="portlet" id="wid_6">
		<div class="portlet-header"><?php echo $config->getNode('messages','latestNewsHeader');?></div>
		<div class="portlet-content">The site's latest news</div>
	</div>
	<div class="portlet" id="wid_7">
		<div class="portlet-header"><?php echo $config->getNode('messages','technicalHeader');?></div>
		<div class="portlet-content">The site's technical tips header</div>
	</div>

</div>

<div class="column">
<h3>Right Sidebar</h3>
	<div class="portlet" id="wid_8">
		<div class="portlet-header">Plugins</div>
		<div class="portlet-content">Later in the development cycle, this will be the default location for all home page plugin includes.</div>
	</div>
	

</div>
</div>
<button onclick='done();'>Done</button>
<script type="text/javascript">
function done() {
	data = '';
	$('.column').each(function(){
		data += $(this).sortable('serialize');
		data += '&wid[]=0&';
	});
	window.location='../pages/saveHome.php?'+data;
}
</script>

</body></html>