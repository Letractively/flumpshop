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
//'Email Club'
<?php
if (PAGE_TYPE == "index") {
	?>$('#content_container').append('<h4 class="ui-widget-header">Join our Email Club!</h4><p>Interested in finding out more about RJC\'s products, services and discounts on a regular basis? <br /><a href="account/signup.php">Sign up to our email newsletter today</a>.</p><br /><br />');<?php
}?>
//Adds JQueryUI Templates
$('#content_container div h4').addClass('ui-widget-header');
//Adds JQueryUI templates
$('#featured_items_container, #popular_items_container').addClass('ui-widget-content');
//Add JQueryUI Templates
$('input').addClass('ui-state-default');
<?php
//Don't magic footer in IE6
if (!preg_match("/MSIE 6\.0/",$_SERVER['HTTP_USER_AGENT'])) {
	?>function theme_rjccom_init(first) {
	//Forces navigation to extend to bottom of the page
	$('#category_container').css('min-height',($('#content_container').height()+50)+"px");
	//Forces footer to the bottom of the page
	$('#footer').css('top',(($("#container").height()-$('#footer').outerHeight())+150)+"px").css('position','absolute');
	if (first) {
		offset = $('#footer').offset();
		$('#footer').css('left',0-(offset.left));
	}
	$('#footer').css('width',($(document).width()-5)+"px");
	}
	$(document).ready(function() {theme_rjccom_init(true);setInterval("theme_rjccom_init(false);",1000);});<?php
} else {
	//IE6 haxx
	?>$('#header').css('width',$(document).width()+"px");<?php
}
?></script>