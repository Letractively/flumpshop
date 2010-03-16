<?php
/*
theme_rjccom Plugin for Flumpshop
Any code entered on this page is called in the <body> section of the page, just before the footer <div> tag.
Here you should place any code that outputs information onto the screen.
Feel free to call other includes to do this, using 
dirname(__FILE__).'/file.inc.php'
*/
?><script type="text/javascript">
//Shadow
$('#header').append('<h1 id="site_name_shadow"><?php echo $config->getNode('messages','name');?></h1>');
//Adds JQueryUI Templates
$('#content_container div h4').addClass('ui-widget-header');
//Adds JQueryUI templates
$('#featured_items_container, #popular_items_container').addClass('ui-widget-content');
//Add JQueryUI Templates
$('input').addClass('ui-state-default');

function theme_rjccom_init() {
//Forces navigation to extend to bottom of the page
$('#category_container').css('min-height',($('#content_container').height()+50)+"px");
//Forces footer to the bottom of the page
$('#footer').css('top',($(document).height()-$('#footer').height())+"px").css('position','absolute');
$('#footer').css('left',-(($(document).width()-$(document.body).width())/2)+"px");
$('#footer').css('width',$(document).width()+"px");
}
$(document).ready(function() {theme_rjccom_init();setInterval("theme_rjccom_init();",1000);});<?php
if (preg_match("/MSIE 6\.0/",$_SERVER['HTTP_USER_AGENT'])) {
	//IE6 haxx
	?>$('#header').css('width',$(document).width()+"px");<?php
}
?></script>