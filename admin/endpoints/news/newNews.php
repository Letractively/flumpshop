<?php
$USR_REQUIREMENT = "can_post_news";
require_once dirname(__FILE__)."/../header.php";
?>
<script type="text/javascript" src="../../tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('#postContent').tinymce({
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
<div class="ui-widget-header">New <?php echo $config->getNode("messages","latestNewsHeader");?> Post</div>
<form action="../news/processNews.php" method="post" onsubmit="if ($(this).valid()) {loader('Saving Content...'); return true;} else return false;" class="ui-widget-content">
<p>This post will appear on the home page, under the <?php echo $config->getNode("messages","latestNewsHeader");?> heading.</p>
    <label for="postTitle">Title: </label>
    <input type="text" name="postTitle" id="postTitle" class="required ui-widget-content" maxlength="250" style="width: 500px;" /><br />
    <textarea name="postContent" id="postContent" class="required ui-widget-content" style="width: 90%; height: 200px;"></textarea><br />
    <input type="submit" onclick="$('#postContent').val(tinyMCE.get('postContent').getContent());return true" value="Add Post" class="ui-widget-content" style="font-size: 13px;" />
</form>