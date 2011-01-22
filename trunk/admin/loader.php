<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"
><head>
<style type="text/css">
body {background-color: #1e2b5b; color: #FFF; font: Arial, Helvetica, sans-serif;}
.container {width: 300px; margin: 150px auto 0 auto;}
.header {font: "Trebuchet MS", Arial, Helvetica, sans-serif; font-size: 23px;}
.header2 {color: #c43131;}
.title {background-color: #0e78ee; padding: 0 5px; line-height: 1.5; font-size: 17px;}
.content {background-color: #e7e7e7; padding: 0 5px; color: #000; font-size: 14px;}
.content label {color: #1e2b5b;}
table td {text-align: right;}
</style>
<link rel="stylesheet" href="jqueryui.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jqueryui.js"></script>
<script type="text/javascript" src="./tiny_mce/jquery.tinymce.js"></script>
<title>Flumpshop ACP | Loading</title></head><body>
<div class="container">

	<div class="header"><img src="images/logo.jpg" alt="Flumpshop Logo" />flump<span class='header2'>shop</span></div>
	
	<div class="title">loading...</div>
	
	<div class="content">
	Flumpshop is loading...<br /><br />
	<div id="progressBar"></div>
	&nbsp;
	</div>
	<div id="loaderContent" style="display:none"></div>
</div>
<script type="text/javascript">
$(function() {
	$('#progressBar').progressbar({value:1});
	
	//Initialise TinyMCE
	$('#loaderContent').append('<textarea id="tinymce_loader">Lorem ipsum</textarea>');
	$('#tinymce_loader').tinymce({
			// Location of TinyMCE script
			script_url : 'tiny_mce/tiny_mce.js',

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

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			media_external_list_url : "lists/media_list.js"
		});
	
	//Repeat until TinyMCE Loaded
	setTimeout("checkMCE()",200);
});

function checkMCE() {
	if (window.tinyMCE) {
		//TinyMCE is now loaded. Continue.
		$('#progressBar').progressbar('value',100);
		window.location="./";
		
	} else {
		//TinyMCE isn't loaded yet. Try again in 100ms
		setTimeout("checkMCE()",200);
		$('#progressBar').progressbar('value',$('#progressBar').progressbar('value')+1);
		if ($('#progressBar').progressbar('value') == 100) {
			$('#progressBar').progressbar('value',1);
		}
	}
}
</script>
</body></html><?php
if (!isset($_SESSION)) session_start(); $_SESSION['acpLoaded'] = true;
?>