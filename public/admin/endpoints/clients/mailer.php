<?php
require_once "../header.php";
?>
<script type="text/javascript" src="../../tiny_mce/tiny_mce_gzip.js"></script><script type="text/javascript" src="../../tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	//TinyMCE For the newsletter editor
	$(document).ready(function() {
		$('#email').tinymce({
			// Location of TinyMCE script
			script_url : '../..tiny_mce/tiny_mce_gzip.js',

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
			media_external_list_url : "lists/media_list.js",

			//Custom Element Support
			extended_valid_elements : "iframe[src|width|height|name|align|style],span[class|id|style]",
			width : '95%',
			height : '250px'
		});
	});
</script>
<h1>Client Mailer</h1>
<p>If you have configured an SMTP server, you can use this page to send a message to all users who have given permission for you to contact them about special offers.</p>
<p>As part of this process, I, the Flumpnet robot, will automatically build an email based on your website theme. Alternatively, you can manually edit the source code to use a special theme just for emails.</p>
<form action="mailerPreview.php" method="post" target="preview">
<label for="title">Subject: </label><input type="text" name="title" id="title" /><br />
<textarea name="email" id="email">Type your email here, then click Preview to view it below.</textarea>
<input type="submit" value="Preview" onclick="$('#preview,#sendlink').show(); return true;" />
</form>
Preview:<br />
<iframe src="./mailerPreview.php" id="preview" style="width: 800px; height: 400px; display:none;"></iframe>
<br /><a href="../process/sendNewsletter.php" id="sendlink" style="display:none">Send Newsletter!</a>
<script type="text/javascript"></script> 
</body></html>